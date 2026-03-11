<?php
namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Logbook;
use App\Models\Permission;
use App\Models\CustomWorkingDay;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PresensiController extends Controller
{
    // Fallback default jika setting tidak tersedia atau tidak valid
    private const DEFAULT_OFFICE_LAT = 1.46759;
    private const DEFAULT_OFFICE_LNG = 124.84542;
    private const DEFAULT_MAX_DISTANCE_METERS = 500;

    /**
     * Cek apakah user boleh melakukan absen hari ini.
     */
    private function getAttendanceBlockReason($user, Carbon $today): ?string
    {
        if ($user->role === 'alumni') {
            return 'Akun kamu berstatus nonaktif sehingga tidak dapat melakukan absen.';
        }

        if (!empty($user->tanggal_selesai) && Carbon::parse($user->tanggal_selesai)->startOfDay()->lt($today->copy()->startOfDay())) {
            return 'Masa magang kamu sudah selesai, sehingga fitur absen dinonaktifkan.';
        }

        return null;
    }

    /**
     * Handle absen masuk
     */
    public function absenMasuk(Request $request)
    {
        $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $user = Auth::user();
        $today = Carbon::today('Asia/Makassar');
        $now = Carbon::now('Asia/Makassar');
        $todayWita = $now->copy()->startOfDay();

        $attendanceBlockReason = $this->getAttendanceBlockReason($user, $today);
        if ($attendanceBlockReason) {
            return response()->json([
                'success' => false,
                'message' => $attendanceBlockReason,
            ], 403);
        }

        $approvedPermissionToday = Permission::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->first();

        if ($approvedPermissionToday) {
            return response()->json([
                'success' => false,
                'message' => 'Anda memiliki izin yang sudah disetujui untuk hari ini, absen masuk tidak diperlukan.'
            ], 400);
        }

        // Cek apakah sudah absen hari ini
        $existingPresensi = Presensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if ($existingPresensi) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absen masuk hari ini.'
            ], 400);
        }

        // Validasi lokasi - cek apakah user berada di dekat kantor
        $userLat = $request->latitude;
        $userLng = $request->longitude;
        $skipDistance = (app()->environment('local') && (empty($userLat) || empty($userLng)));
        $officeConfig = $this->getOfficeConfig();
        $distance = $skipDistance ? 0 : $this->calculateDistance($userLat, $userLng, $officeConfig['lat'], $officeConfig['lng']);

        if (!$skipDistance && $distance > $officeConfig['max_distance']) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus berada di dekat kantor BPS untuk melakukan absen. Jarak Anda: ' . round($distance) . ' meter dari kantor.'
            ], 400);
        }

        // Validasi jam masuk berdasarkan prioritas: custom date > Jumat > Senin-Kamis
        $jamMasuk = $now->format('H:i');
        $schedule = $this->resolveScheduleForDate($todayWita);
        $batasMasukSetting = $schedule['jam_masuk'];
        [$batasMasukJam, $batasMasukMenit] = explode(':', $batasMasukSetting);
        $batasMasuk = Carbon::today('Asia/Makassar')->setTime((int) $batasMasukJam, (int) $batasMasukMenit, 0);
        
        // Tentukan status berdasarkan jam
        if ($now->lessThanOrEqualTo($batasMasuk)) {
            $status = 'hadir';
        } else {
            $status = 'terlambat';
        }
        $lokasi = $userLat . ',' . $userLng;
        $presensi = Presensi::create([
            'user_id' => $user->id,
            'tanggal' => $today,
            'jam_masuk' => $jamMasuk,
            'lokasi_masuk' => $lokasi,
            'status' => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absen masuk berhasil dicatat.',
            'data' => [
                'jam_masuk' => $jamMasuk,
                'status' => $status,
                'lokasi' => $lokasi,
                'jarak' => round($distance) . ' meter',
            ]
        ]);
    }

    /**
     * Handle absen pulang
     */
    public function absenPulang(Request $request)
    {
        $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $user = Auth::user();
        $today = Carbon::today('Asia/Makassar');
        $now = Carbon::now('Asia/Makassar');

        $attendanceBlockReason = $this->getAttendanceBlockReason($user, $today);
        if ($attendanceBlockReason) {
            return response()->json([
                'success' => false,
                'message' => $attendanceBlockReason,
            ], 403);
        }

        $approvedPermissionToday = Permission::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->first();
        $todayWita = $now->copy()->startOfDay();

        // Cari presensi hari ini
        $presensi = Presensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if (!$presensi) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum melakukan absen masuk hari ini.'
            ], 400);
        }

        $isAutoCheckout = $presensi->jam_pulang === '23:59' && $presensi->keterangan === 'Auto checkout by system';
        if ($presensi->jam_pulang && !$isAutoCheckout) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absen pulang hari ini.'
            ], 400);
        }

        // Validasi lokasi untuk absen pulang juga
        $userLat = $request->latitude;
        $userLng = $request->longitude;
        $skipDistance = (app()->environment('local') && (empty($userLat) || empty($userLng)));
        $officeConfig = $this->getOfficeConfig();
        $distance = $skipDistance ? 0 : $this->calculateDistance($userLat, $userLng, $officeConfig['lat'], $officeConfig['lng']);

        if (!$skipDistance && $distance > $officeConfig['max_distance']) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus berada di dekat kantor BPS untuk melakukan absen pulang. Jarak Anda: ' . round($distance) . ' meter dari kantor.'
            ], 400);
        }

        // Validasi jam pulang berdasarkan prioritas: custom date > Jumat > Senin-Kamis
        $jamPulang = $now->format('H:i');
        $schedule = $this->resolveScheduleForDate($todayWita);
        $batasPulangSetting = $schedule['jam_pulang'];
        [$batasPulangJam, $batasPulangMenit] = explode(':', $batasPulangSetting);
        $batasPulang = Carbon::today('Asia/Makassar')->setTime((int) $batasPulangJam, (int) $batasPulangMenit, 0);
        if ($now->lessThan($batasPulang)) {
            return response()->json([
                'success' => false,
                'message' => 'Absen pulang hanya bisa dilakukan setelah jam ' . $batasPulangSetting . ' WITA.'
            ], 400);
        }

        $lokasiPulang = $userLat . ',' . $userLng;
        $presensi->update([
            'jam_pulang' => $jamPulang,
            'lokasi_pulang' => $lokasiPulang,
            'keterangan' => $isAutoCheckout ? null : $presensi->keterangan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absen pulang berhasil dicatat.',
            'data' => [
                'jam_pulang' => $jamPulang,
                'lokasi_pulang' => $lokasiPulang,
                'jarak' => round($distance) . ' meter',
            ]
        ]);
    }

    /**
     * Tentukan status berdasarkan waktu
     */
    private function determineStatus(Carbon $waktuAbsen)
    {
        // Jam 8 pagi sebagai batas
        $batasWaktu = Carbon::today()->setHour(8)->setMinute(0)->setSecond(0);

        return $waktuAbsen->greaterThan($batasWaktu) ? 'terlambat' : 'hadir';
    }

    /**
     * Ambil setting waktu format HH:ii dengan fallback aman.
     */
    private function getSettingTime(string $key, string $fallback): string
    {
        $value = Setting::where('key', $key)->value('value');
        if (!is_string($value)) {
            return $fallback;
        }

        $value = trim($value);
        return preg_match('/^([01]\d|2[0-3]):([0-5]\d)$/', $value) ? $value : $fallback;
    }

    /**
     * Ambil konfigurasi jadwal berdasarkan prioritas custom date > Jumat > Senin-Kamis.
     */
    private function resolveScheduleForDate(Carbon $date): array
    {
        $dateStr = $date->toDateString();

        $customDay = CustomWorkingDay::whereDate('tanggal_mulai', '<=', $dateStr)
            ->whereDate('tanggal_selesai', '>=', $dateStr)
            ->orderByDesc('tanggal_mulai')
            ->first();

        if ($customDay) {
            return [
                'jam_masuk' => Carbon::parse($customDay->jam_masuk)->format('H:i'),
                'jam_pulang' => Carbon::parse($customDay->jam_pulang)->format('H:i'),
                'source' => 'custom_working_days',
            ];
        }

        // Carbon: Friday = 5
        if ((int) $date->dayOfWeek === Carbon::FRIDAY) {
            return [
                'jam_masuk' => $this->getSettingTime('jam_masuk_jumat', '07:30'),
                'jam_pulang' => $this->getSettingTime('jam_pulang_jumat', '16:00'),
                'source' => 'jumat',
            ];
        }

        // Default untuk Senin-Kamis (dan fallback hari lain)
        return [
            'jam_masuk' => $this->getSettingTime('jam_masuk_senin_kamis', '07:30'),
            'jam_pulang' => $this->getSettingTime('jam_pulang_senin_kamis', '16:00'),
            'source' => 'senin_kamis',
        ];
    }

    /**
     * Ambil konfigurasi lokasi kantor dari settings dengan fallback aman.
     */
    private function getOfficeConfig(): array
    {
        return [
            'lat' => $this->getSettingFloat('office_latitude', self::DEFAULT_OFFICE_LAT),
            'lng' => $this->getSettingFloat('office_longitude', self::DEFAULT_OFFICE_LNG),
            'max_distance' => $this->getSettingInt('max_distance_meters', self::DEFAULT_MAX_DISTANCE_METERS),
        ];
    }

    private function getSettingFloat(string $key, float $fallback): float
    {
        $value = Setting::where('key', $key)->value('value');
        if (!is_numeric($value)) {
            return $fallback;
        }

        return (float) $value;
    }

    private function getSettingInt(string $key, int $fallback): int
    {
        $value = Setting::where('key', $key)->value('value');
        if (!is_numeric($value)) {
            return $fallback;
        }

        $int = (int) $value;
        return $int > 0 ? $int : $fallback;
    }

    /**
     * Hitung jarak antara dua koordinat GPS menggunakan formula Haversine
     */
    public function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lngDelta / 2) * sin($lngDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Jarak dalam meter
    }

    /**
     * Get status presensi hari ini untuk user
     */
    public function getStatusHariIni()
    {
        $user = Auth::user();
        $today = Carbon::today('Asia/Makassar');
        $now = Carbon::now('Asia/Makassar');
        $attendanceBlockReason = $this->getAttendanceBlockReason($user, $today);
        $canAttend = $attendanceBlockReason === null;

        $approvedPermissionToday = Permission::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->latest('updated_at')
            ->first();

        $latestPermission = Permission::where('user_id', $user->id)
            ->latest('updated_at')
            ->first();

        $pendingPermissionActive = Permission::where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereDate('end_date', '>=', $today)
            ->exists();

        $presensi = Presensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        // Jika presensi ada, belum pulang, dan sudah lewat 23:59, auto checkout
        if ($presensi && !$presensi->jam_pulang && $now->format('H:i') >= '23:59') {
            $presensi->update([
                'jam_pulang' => '23:59',
                'lokasi_pulang' => $presensi->lokasi_masuk,
                'keterangan' => 'Auto checkout by system'
            ]);
        }

        $sudah_absen_masuk = $presensi ? true : false;
        $sudah_absen_pulang = $presensi && $presensi->jam_pulang ? true : false;
        $sudah_hadir_hari_ini = $presensi && $presensi->jam_masuk && $presensi->jam_pulang;
        $has_approved_permission_today = (bool) $approvedPermissionToday;
        
        return response()->json([
            'can_attend' => $canAttend,
            'attendance_block_reason' => $attendanceBlockReason,
            'sudah_absen_masuk' => $sudah_absen_masuk,
            'sudah_absen_pulang' => $sudah_absen_pulang,
            'sudah_hadir_hari_ini' => $sudah_hadir_hari_ini,
            'has_approved_permission_today' => $has_approved_permission_today,
            'has_pending_permission_active' => $pendingPermissionActive,
            'can_submit_permission' => $canAttend && !$has_approved_permission_today && !$pendingPermissionActive,
            'permission' => $approvedPermissionToday ? [
                'start_date' => $approvedPermissionToday->start_date->toDateString(),
                'end_date' => $approvedPermissionToday->end_date->toDateString(),
                'permission_type' => $approvedPermissionToday->permission_type,
                'permission_type_label' => $approvedPermissionToday->permission_type_label,
                'reason' => $approvedPermissionToday->reason,
                'attachment_url' => $approvedPermissionToday->attachment_url,
            ] : null,
            'latest_permission' => $latestPermission ? [
                'id' => $latestPermission->id,
                'start_date' => $latestPermission->start_date->toDateString(),
                'end_date' => $latestPermission->end_date->toDateString(),
                'permission_type' => $latestPermission->permission_type,
                'permission_type_label' => $latestPermission->permission_type_label,
                'reason' => $latestPermission->reason,
                'attachment_url' => $latestPermission->attachment_url,
                'status' => $latestPermission->status,
                'updated_at' => optional($latestPermission->updated_at)->toIso8601String(),
            ] : null,
            'data' => $presensi ? [
                'jam_masuk' => $presensi->jam_masuk,
                'jam_pulang' => $presensi->jam_pulang,
                'status' => $presensi->status,
                'lokasi_masuk' => $presensi->lokasi_masuk,
                'lokasi_pulang' => $presensi->lokasi_pulang,
            ] : null
        ]);
    }

    /**
     * Reset presensi hari ini (khusus mode local/development)
     */
    public function resetHariIni()
    {
        if (!app()->environment('local')) {
            return response()->json(['success' => false, 'message' => 'Fitur hanya tersedia di mode local/development'], 403);
        }
        $user = Auth::user();
        $today = Carbon::today('Asia/Makassar');
        $deleted = Presensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->delete();
        return response()->json([
            'success' => true,
            'message' => 'Presensi hari ini berhasil direset',
            'deleted' => $deleted
        ]);
    }

    /**
     * Get semua presensi bulan tertentu untuk kalender
     */
    public function getPresensiBulanIni(Request $request)
    {
        $user = Auth::user();
        
        // Ambil parameter month & year dari query string, default ke bulan ini
        $year = $request->query('year', Carbon::now('Asia/Makassar')->year);
        $month = $request->query('month', Carbon::now('Asia/Makassar')->month);
        
        try {
            $date = Carbon::createFromDate($year, $month, 1);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter bulan/tahun tidak valid'
            ], 400);
        }
        
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $presensis = Presensi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->get();

        $permissions = Permission::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $endOfMonth->toDateString())
            ->whereDate('end_date', '>=', $startOfMonth->toDateString())
            ->get();

        $calendar = [];
        foreach ($presensis as $presensi) {
            $calendar[$presensi->tanggal->format('Y-m-d')] = [
                'status' => $presensi->status,
                'jam_masuk' => $presensi->jam_masuk,
                'jam_pulang' => $presensi->jam_pulang,
            ];
        }

        foreach ($permissions as $permission) {
            $cursor = Carbon::parse($permission->start_date)->startOfDay();
            $rangeEnd = Carbon::parse($permission->end_date)->endOfDay();

            while ($cursor->lessThanOrEqualTo($rangeEnd)) {
                if ($cursor->betweenIncluded($startOfMonth, $endOfMonth)) {
                    $dateStr = $cursor->format('Y-m-d');
                    $calendar[$dateStr] = [
                        'status' => 'izin',
                        'jam_masuk' => null,
                        'jam_pulang' => null,
                    ];
                }
                $cursor->addDay();
            }
        }

        $hadirCount = $presensis->whereIn('status', ['hadir', 'terlambat'])->count();
        $alpaCount = $presensis->where('status', 'alpa')->count();
        $izinCount = collect($calendar)->where('status', 'izin')->count();
        $izinAlpaCount = $izinCount + $alpaCount;
        $logbookCount = Logbook::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->count();

        return response()->json([
            'success' => true,
            'month' => $date->format('Y-m'),
            'year' => (int)$year,
            'month_num' => (int)$month,
            'calendar' => $calendar
        ]);
    }

    /**
     * Get detail presensi dan logbook untuk tanggal tertentu
     */
    public function getDetailTanggal($tanggal)
    {
        $user = Auth::user();

        try {
            $date = Carbon::createFromFormat('Y-m-d', $tanggal);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Format tanggal tidak valid'
            ], 400);
        }

        $presensi = Presensi::where('user_id', $user->id)
            ->whereDate('tanggal', $date->toDateString())
            ->first();

        $permission = Permission::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $date->toDateString())
            ->whereDate('end_date', '>=', $date->toDateString())
            ->latest('updated_at')
            ->first();

        if ($permission) {
            $presensi = new Presensi([
                'status' => 'izin',
                'keterangan' => $permission->reason,
                'jam_masuk' => null,
                'jam_pulang' => null,
            ]);
        }

        $logbook = \App\Models\Logbook::where('user_id', $user->id)
            ->whereDate('tanggal', $date->toDateString())
            ->first();

        return response()->json([
            'success' => true,
            'tanggal' => $date->locale('id')->isoFormat('dddd, D MMMM YYYY'),
            'presensi' => $presensi ? [
                'status' => $presensi->status,
                'jam_masuk' => $presensi->jam_masuk,
                'jam_pulang' => $presensi->jam_pulang,
                'keterangan' => $presensi->keterangan,
            ] : null,
            'logbook' => $logbook ? [
                'aktivitas' => $logbook->aktivitas,
                'deskripsi' => $logbook->deskripsi,
                'jam_mulai' => $logbook->jam_mulai,
                'jam_selesai' => $logbook->jam_selesai,
            ] : null
        ]);
    }

    /**
     * Get detail presensi dan logbook untuk peserta tertentu (admin)
     */
    public function getDetailPesertaTanggal($userId, $tanggal)
    {
        $user = \App\Models\User::find($userId);

        if (!$user || !in_array($user->role, ['magang', 'alumni'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Peserta tidak ditemukan'
            ], 404);
        }

        try {
            $date = Carbon::createFromFormat('Y-m-d', $tanggal);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Format tanggal tidak valid'
            ], 400);
        }

        $presensi = Presensi::where('user_id', $user->id)
            ->whereDate('tanggal', $date->toDateString())
            ->first();

        $permission = Permission::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $date->toDateString())
            ->whereDate('end_date', '>=', $date->toDateString())
            ->latest('updated_at')
            ->first();

        if ($permission) {
            $presensi = new Presensi([
                'status' => 'izin',
                'keterangan' => $permission->reason,
                'jam_masuk' => null,
                'jam_pulang' => null,
            ]);
        }

        $logbook = \App\Models\Logbook::where('user_id', $user->id)
            ->whereDate('tanggal', $date->toDateString())
            ->first();

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'tanggal' => $date->locale('id')->isoFormat('dddd, D MMMM YYYY'),
            'presensi' => $presensi ? [
                'status' => $presensi->status,
                'jam_masuk' => $presensi->jam_masuk,
                'jam_pulang' => $presensi->jam_pulang,
                'keterangan' => $presensi->keterangan,
            ] : null,
            'logbook' => $logbook ? [
                'aktivitas' => $logbook->aktivitas,
                'deskripsi' => $logbook->deskripsi,
                'jam_mulai' => $logbook->jam_mulai,
                'jam_selesai' => $logbook->jam_selesai,
            ] : null
        ]);
    }

    /**
     * Tampilkan halaman kalender peserta tertentu (admin)
     */
    public function showPesertaCalendar($userId)
    {
        $user = \App\Models\User::whereIn('role', ['magang', 'alumni'])->find($userId);

        if (!$user) {
            abort(404, 'Peserta tidak ditemukan');
        }

        return view('admin.peserta-kalender', [
            'user' => $user,
        ]);
    }

    /**
     * Laporan Presensi - Data API
     */
    public function getLaporanPresensiData(Request $request)
    {
        $start = $request->query('start_date');
        $end = $request->query('end_date');
        $status = $request->query('status'); // hadir|terlambat|izin|alpa|null
        $userId = $request->query('user_id');

        // Validasi tanggal sederhana
        try {
            $startDate = $start ? Carbon::createFromFormat('Y-m-d', $start)->startOfDay() : Carbon::now()->startOfMonth();
            $endDate = $end ? Carbon::createFromFormat('Y-m-d', $end)->endOfDay() : Carbon::now()->endOfMonth();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Rentang tanggal tidak valid'], 400);
        }

        $query = Presensi::with('user')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $permissionsQuery = Permission::with('user')
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $endDate->toDateString())
            ->whereDate('end_date', '>=', $startDate->toDateString());

        if ($userId) {
            $permissionsQuery->where('user_id', $userId);
        }

        $permissions = $permissionsQuery->get();
        $permissionMap = [];

        foreach ($permissions as $permission) {
            $cursor = Carbon::parse($permission->start_date)->startOfDay();
            $rangeEnd = Carbon::parse($permission->end_date)->endOfDay();

            while ($cursor->lessThanOrEqualTo($rangeEnd)) {
                if ($cursor->betweenIncluded($startDate, $endDate)) {
                    $key = $permission->user_id . '|' . $cursor->format('Y-m-d');
                    $permissionMap[$key] = [
                        'user' => $permission->user,
                        'reason' => $permission->reason,
                    ];
                }
                $cursor->addDay();
            }
        }

        $rows = $query->get()->map(function ($p) use ($permissionMap) {
            $key = $p->user_id . '|' . $p->tanggal->format('Y-m-d');
            $permission = $permissionMap[$key] ?? null;

            return [
                'tanggal' => $p->tanggal->format('Y-m-d'),
                'nama' => $p->user->name ?? '-',
                'email' => $p->user->email ?? '-',
                'status' => $permission ? 'izin' : $p->status,
                'jam_masuk' => $permission ? null : $p->jam_masuk,
                'jam_pulang' => $permission ? null : $p->jam_pulang,
                'keterangan' => $permission['reason'] ?? $p->keterangan,
            ];
        })->values();

        foreach ($permissionMap as $key => $meta) {
            [$permissionUserId, $dateStr] = explode('|', $key);
            $alreadyExists = $rows->contains(function ($row) use ($dateStr, $meta) {
                return ($row['tanggal'] ?? null) === $dateStr
                    && ($row['email'] ?? null) === ($meta['user']->email ?? null);
            });

            if (!$alreadyExists) {
                $rows->push([
                    'tanggal' => $dateStr,
                    'nama' => $meta['user']->name ?? '-',
                    'email' => $meta['user']->email ?? '-',
                    'status' => 'izin',
                    'jam_masuk' => null,
                    'jam_pulang' => null,
                    'keterangan' => $meta['reason'],
                ]);
            }
        }

        if ($status) {
            $rows = $rows->where('status', $status)->values();
        }

        $rows = $rows
            ->sortBy([
                ['tanggal', 'asc'],
                ['nama', 'asc'],
            ])
            ->values();

        $stat = [
            'total' => $rows->count(),
            'hadir' => $rows->where('status', 'hadir')->count(),
            'terlambat' => $rows->where('status', 'terlambat')->count(),
            'izin' => $rows->where('status', 'izin')->count(),
            'alpa' => $rows->where('status', 'alpa')->count(),
        ];

        return response()->json([
            'success' => true,
            'rows' => $rows,
            'stat' => $stat,
            'range' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ]
        ]);
    }

    /**
     * Laporan Presensi - Export CSV (Excel-friendly)
     */
    public function exportLaporanPresensiCsv(Request $request)
    {
        $request->merge(['format' => 'csv']);
        // Reuse data method
        $dataResponse = $this->getLaporanPresensiData($request);
        $payload = $dataResponse->getData(true);
        if (!($payload['success'] ?? false)) {
            return $dataResponse;
        }

        $rows = $payload['rows'];
        $filename = 'laporan-presensi_' . ($payload['range']['start'] ?? '') . '_to_' . ($payload['range']['end'] ?? '') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $output = implode(',', ['Tanggal', 'Nama', 'Email', 'Status', 'Jam Masuk', 'Jam Pulang']) . "\n";
        foreach ($rows as $r) {
            $line = [
                $r['tanggal'],
                str_replace(',', ' ', $r['nama']),
                $r['email'],
                $r['status'],
                $r['jam_masuk'] ?? '',
                $r['jam_pulang'] ?? '',
            ];
            $output .= implode(',', $line) . "\n";
        }

        return response($output, 200, $headers);
    }

    /**
     * Laporan Presensi - Print (for save as PDF via browser)
     */
    public function printLaporanPresensi(Request $request)
    {
        // Pull data from API method
        $dataResponse = $this->getLaporanPresensiData($request);
        $payload = $dataResponse->getData(true);
        if (!($payload['success'] ?? false)) {
            abort(400, $payload['message'] ?? 'Invalid request');
        }

        return view('admin.laporan-presensi-print', [
            'rows' => $payload['rows'],
            'stat' => $payload['stat'],
            'range' => $payload['range'],
        ]);
    }

    /**
     * Statistik tren presensi untuk dashboard (daily aggregation)
     */
    public function getTrendPresensi(Request $request)
    {
        $range = $request->query('range', '30'); // 7|30|month

        if ($range === 'month') {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        } else {
            $days = is_numeric($range) ? (int)$range : 30;
            $end = Carbon::today();
            $start = Carbon::today()->subDays($days - 1);
        }

        // Fetch presensi in range
        $presensis = Presensi::whereBetween('tanggal', [$start, $end])->get(['tanggal','status']);

        // Build day labels
        $period = new \Carbon\CarbonPeriod($start, '1 day', $end);
        $labels = [];
        $series = [
            'hadir' => [],
            'terlambat' => [],
            'izin' => [],
            'alpa' => [],
        ];

        $grouped = [];
        foreach ($presensis as $p) {
            $key = Carbon::parse($p->tanggal)->format('Y-m-d');
            $grouped[$key] = $grouped[$key] ?? ['hadir'=>0,'terlambat'=>0,'izin'=>0,'alpa'=>0];
            if (isset($grouped[$key][$p->status])) {
                $grouped[$key][$p->status]++;
            }
        }

        foreach ($period as $date) {
            $key = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
            $day = $grouped[$key] ?? ['hadir'=>0,'terlambat'=>0,'izin'=>0,'alpa'=>0];
            foreach ($series as $k => $_) {
                $series[$k][] = $day[$k];
            }
        }

        return response()->json([
            'success' => true,
            'labels' => $labels,
            'series' => $series,
            'range' => [
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
            ],
        ]);
    }

    /**
     * View page for logbook management
     */
    public function viewLogbookManagement()
    {
        return view('admin.logbook-management');
    }

    /**
     * API: Get filtered logbook data
     */
    public function getLogbookData(Request $request)
    {
        $query = \App\Models\Logbook::with('user');

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }

        // Search by aktivitas or deskripsi
        if ($request->has('search') && $request->search) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('aktivitas', 'like', $search)
                  ->orWhere('deskripsi', 'like', $search);
            });
        }

        // Order by date descending
        $rows = $query->orderBy('tanggal', 'desc')->get()->map(function ($row) {
            return [
                'id' => $row->id,
                'peserta' => $row->user->name ?? 'N/A',
                'peserta_id' => $row->user_id,
                'tanggal' => $row->tanggal,
                'aktivitas' => $row->aktivitas,
                'deskripsi' => $row->deskripsi,
                'jam_mulai' => $row->jam_mulai,
                'jam_selesai' => $row->jam_selesai,
                'created_at' => $row->created_at->format('d M Y H:i'),
            ];
        });

        return response()->json([
            'success' => true,
            'rows' => $rows,
            'count' => $rows->count(),
        ]);
    }

    /**
     * API: Get single logbook detail
     */
    public function getLogbookDetail($id)
    {
        $logbook = \App\Models\Logbook::with('user')->find($id);

        if (!$logbook) {
            return response()->json([
                'success' => false,
                'message' => 'Logbook not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'logbook' => [
                'id' => $logbook->id,
                'user' => ['name' => $logbook->user->name ?? 'N/A'],
                'tanggal' => $logbook->tanggal,
                'aktivitas' => $logbook->aktivitas,
                'deskripsi' => $logbook->deskripsi,
                'jam_mulai' => $logbook->jam_mulai,
                'jam_selesai' => $logbook->jam_selesai,
            ],
        ]);
    }

    /**
     * Nonaktifkan peserta (set role to alumni)
     */
    public function nonaktifkanPeserta(Request $request, $userId)
    {
        $user = \App\Models\User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Peserta tidak ditemukan'
            ], 404);
        }

        $user->role = 'alumni';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Peserta dinonaktifkan',
        ]);
    }

    /**
     * Get rekap absensi hari ini untuk admin (semua peserta)
     */
    public function getRekapHariIni()
    {
        $today = Carbon::today('Asia/Makassar');
        
        // Get semua peserta magang
        $pesertaMagang = \App\Models\User::where('role', 'magang')->get();
        
        $hadir = [];
        $belumHadir = [];
        
        foreach ($pesertaMagang as $peserta) {
            $presensi = Presensi::where('user_id', $peserta->id)
                ->where('tanggal', $today)
                ->first();

            $permissionToday = Permission::where('user_id', $peserta->id)
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->latest('updated_at')
                ->first();
            
            if ($permissionToday) {
                $hadir[] = [
                    'id' => $peserta->id,
                    'name' => $peserta->name,
                    'email' => $peserta->email,
                    'jam_masuk' => '-',
                    'jam_pulang' => '-',
                    'status' => 'izin',
                    'keterangan' => $permissionToday->reason,
                    'sudah_pulang' => true,
                ];
            } elseif ($presensi) {
                $hadir[] = [
                    'id' => $peserta->id,
                    'name' => $peserta->name,
                    'email' => $peserta->email,
                    'jam_masuk' => $presensi->jam_masuk,
                    'jam_pulang' => $presensi->jam_pulang,
                    'status' => $presensi->status,
                    'keterangan' => $presensi->keterangan,
                    'sudah_pulang' => $presensi->jam_pulang ? true : false,
                ];
            } else {
                $belumHadir[] = [
                    'id' => $peserta->id,
                    'name' => $peserta->name,
                    'email' => $peserta->email,
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'tanggal' => $today->locale('id')->isoFormat('dddd, D MMMM YYYY'),
            'hadir' => $hadir,
            'belum_hadir' => $belumHadir,
            'statistik' => [
                'total_peserta' => $pesertaMagang->count(),
                'total_hadir' => count($hadir),
                'total_belum_hadir' => count($belumHadir),
                'persentase_kehadiran' => $pesertaMagang->count() > 0 ? round((count($hadir) / $pesertaMagang->count()) * 100, 1) : 0,
            ]
        ]);
    }

    /**
     * Get data absensi peserta tertentu untuk periode tertentu
     */
    public function getDataPeserta(Request $request, $userId)
    {
        $user = \App\Models\User::find($userId);
        
        if (!$user || !in_array($user->role, ['magang', 'alumni'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Peserta tidak ditemukan'
            ], 404);
        }
        
        $year = $request->query('year', Carbon::now()->year);
        $month = $request->query('month', Carbon::now()->month);
        
        try {
            $date = Carbon::createFromDate($year, $month, 1);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter bulan/tahun tidak valid'
            ], 400);
        }
        
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $presensis = Presensi::where('user_id', $userId)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->get();

        $permissions = Permission::where('user_id', $userId)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $endOfMonth->toDateString())
            ->whereDate('end_date', '>=', $startOfMonth->toDateString())
            ->get();

        $calendar = [];
        foreach ($presensis as $presensi) {
            $calendar[$presensi->tanggal->format('Y-m-d')] = [
                'status' => $presensi->status,
                'jam_masuk' => $presensi->jam_masuk,
                'jam_pulang' => $presensi->jam_pulang,
            ];
        }

        foreach ($permissions as $permission) {
            $cursor = Carbon::parse($permission->start_date)->startOfDay();
            $rangeEnd = Carbon::parse($permission->end_date)->endOfDay();

            while ($cursor->lessThanOrEqualTo($rangeEnd)) {
                if ($cursor->betweenIncluded($startOfMonth, $endOfMonth)) {
                    $dateStr = $cursor->format('Y-m-d');
                    $calendar[$dateStr] = [
                        'status' => 'izin',
                        'jam_masuk' => null,
                        'jam_pulang' => null,
                    ];
                }
                $cursor->addDay();
            }
        }

        $hadirCount = $presensis->whereIn('status', ['hadir', 'terlambat'])->count();
        $alpaCount = $presensis->where('status', 'alpa')->count();
        $izinCount = collect($calendar)->where('status', 'izin')->count();
        $izinAlpaCount = $izinCount + $alpaCount;
        $logbookCount = Logbook::where('user_id', $userId)
            ->whereBetween('tanggal', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->count();

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'month' => $date->format('Y-m'),
            'year' => (int)$year,
            'month_num' => (int)$month,
            'calendar' => $calendar,
            'summary' => [
                'hadir' => $hadirCount,
                'izin_alpa' => $izinAlpaCount,
                'logbook' => $logbookCount,
            ],
        ]);
    }
}
