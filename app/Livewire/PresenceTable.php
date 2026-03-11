<?php

namespace App\Livewire;

use App\Models\Presensi;
use App\Models\Permission;
use App\Models\User;
use Livewire\Component;

class PresenceTable extends Component
{
    /**
     * Fetch today's attendance and permission data for all interns
     */
    public function render()
    {
        $today = now()->toDateString();

        // Get all magang users
        $pesertaList = User::where('role', 'magang')
            ->orderBy('name')
            ->get();

        // Get presensi records for today
        $presensiHariIni = Presensi::whereDate('tanggal', $today)
            ->get()
            ->keyBy('user_id');

        // Get approved permissions for today
        $izinHariIni = Permission::where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->get()
            ->keyBy('user_id');

        // Combine data for display
        $attendanceData = $pesertaList->map(function ($peserta) use ($presensiHariIni, $izinHariIni) {
            $presensi = $presensiHariIni->get($peserta->id);
            $izin = $izinHariIni->get($peserta->id);

            // Determine status (izin takes priority over presensi)
            $status = $izin ? 'izin' : ($presensi->status ?? 'belum_absen');
            $keterangan = $izin ? $izin->reason : ($presensi->keterangan ?? '-');

            return [
                'id' => $peserta->id,
                'name' => $peserta->name,
                'email' => $peserta->email,
                'status' => $status,
                'jam_masuk' => $presensi->jam_masuk ?? null,
                'jam_pulang' => $presensi->jam_pulang ?? null,
                'keterangan' => $keterangan,
            ];
        });

        return view('livewire.presence-table', [
            'attendanceData' => $attendanceData,
            'today' => $today,
        ]);
    }
}
