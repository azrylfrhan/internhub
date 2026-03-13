
<?php

use App\Http\Controllers\Auth\UnifiedLoginController;
use App\Http\Controllers\AdminManagementController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\PesertaManagementController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        if (in_array($role, ['admin', 'mentor'])) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('magang.attendance');
    }
    return view('auth.login');
})->name('home');

// Graceful fallback for GET /logout (avoid 405 Method Not Allowed)
Route::get('/logout', function (Request $request) {
    if (Auth::check()) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
    return redirect()->route('login')->with('status', 'Anda telah keluar. Silakan login kembali.');
})->name('logout.get');

// Unified login — single entry point for all roles
Route::middleware('guest')->group(function () {
    Route::get('/login', [UnifiedLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UnifiedLoginController::class, 'login']);

    // Keep old URL aliases so any existing bookmarks still work
    Route::get('/admin/login', [UnifiedLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::get('/magang/login', [UnifiedLoginController::class, 'showLoginForm'])->name('magang.login');
});

// Admin/Mentor Dashboard - Protected by role middleware
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:admin,mentor'])->name('dashboard');

// Magang routes with separate layout - Protected by role middleware
Route::middleware(['auth', 'verified', 'role:magang,alumni'])->group(function () {
    Route::get('/magang/attendance', function () {
        return view('magang.attendance');
    })->name('magang.attendance');

    Route::get('/magang/logbook', function () {
        return view('magang.logbook');
    })->name('magang.logbook');
    Route::get('/magang/logbook/data', [LogbookController::class, 'getData'])->name('magang.logbook.data');
    Route::get('/magang/logbook/stats', [LogbookController::class, 'getStats'])->name('magang.logbook.stats');
    Route::post('/magang/logbook/store', [LogbookController::class, 'store'])->name('magang.logbook.store');
    Route::put('/magang/logbook/{id}', [LogbookController::class, 'update'])->name('magang.logbook.update');
    Route::delete('/magang/logbook/{id}', [LogbookController::class, 'destroy'])->name('magang.logbook.destroy');

    Route::get('/magang/profile', function () {
        return view('magang.profile');
    })->name('magang.profile');
    Route::patch('/magang/profile', [ProfileController::class, 'updateMagang'])->name('magang.profile.update');
    Route::put('/magang/profile/password', [ProfileController::class, 'updatePassword'])->name('magang.profile.password.update');
});

// Presensi routes - Available to authenticated users
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/presensi/masuk', [PresensiController::class, 'absenMasuk'])->name('presensi.masuk');
    Route::post('/presensi/pulang', [PresensiController::class, 'absenPulang'])->name('presensi.pulang');
    Route::get('/presensi/status-hari-ini', [PresensiController::class, 'getStatusHariIni'])->name('presensi.status');
    Route::get('/presensi/bulan-ini', [PresensiController::class, 'getPresensiBulanIni'])->name('presensi.bulanini');
    Route::get('/presensi/detail/{tanggal}', [PresensiController::class, 'getDetailTanggal'])->name('presensi.detail');

    // Route reset absensi hari ini (khusus local/dev)
    if (app()->environment('local')) {
        Route::post('/presensi/reset-hari-ini', [PresensiController::class, 'resetHariIni'])->name('presensi.reset');
    }
});

// Admin/Mentor routes - Protected by role middleware
Route::middleware(['auth', 'verified', 'role:admin,mentor'])->group(function () {
    Route::get('/admin/presensi/rekap-hari-ini', [PresensiController::class, 'getRekapHariIni'])->name('admin.presensi.rekap');
    Route::get('/admin/presensi/peserta/{userId}', [PresensiController::class, 'getDataPeserta'])->name('admin.presensi.peserta');
    Route::get('/admin/presensi/peserta/{userId}/tanggal/{tanggal}', [PresensiController::class, 'getDetailPesertaTanggal'])->name('admin.presensi.peserta.tanggal');
    Route::post('/admin/peserta/{userId}/nonaktif', [PresensiController::class, 'nonaktifkanPeserta'])->name('admin.peserta.nonaktif');
    Route::get('/admin/peserta/{userId}/kalender', [PresensiController::class, 'showPesertaCalendar'])->name('admin.peserta.kalender');
    Route::get('/admin/peserta/detail', function () {
        return view('admin.peserta-detail');
    })->name('admin.peserta.detail');
    Route::post('/admin/peserta', [PesertaManagementController::class, 'store'])->name('admin.peserta.store');
    Route::put('/admin/peserta/{peserta}', [PesertaManagementController::class, 'update'])->name('admin.peserta.update');
    Route::delete('/admin/peserta/{peserta}/permanent', [PesertaManagementController::class, 'destroyPermanent'])->name('admin.peserta.destroy-permanent');

    // Laporan Presensi
    Route::get('/admin/laporan/presensi', function () {
        return view('admin.laporan-presensi');
    })->name('admin.laporan.presensi');
    Route::get('/admin/laporan/presensi/data', [PresensiController::class, 'getLaporanPresensiData'])->name('admin.laporan.presensi.data');
    Route::get('/admin/laporan/presensi/export/csv', [PresensiController::class, 'exportLaporanPresensiCsv'])->name('admin.laporan.presensi.export.csv');
    Route::get('/admin/laporan/presensi/export/print', [PresensiController::class, 'printLaporanPresensi'])->name('admin.laporan.presensi.export.print');

    // Dashboard trend data (Chart)
    Route::get('/admin/dashboard/trend', [PresensiController::class, 'getTrendPresensi'])->name('admin.dashboard.trend');

    // Logbook Management
    Route::get('/admin/logbook', function () {
        return view('admin.logbook-management');
    })->name('admin.logbook');
    Route::get('/admin/logbook/data', [PresensiController::class, 'getLogbookData'])->name('admin.logbook.data');
    Route::get('/api/logbook/{id}', [PresensiController::class, 'getLogbookDetail'])->name('api.logbook.detail');

    Route::get('/admin/permissions', [PermissionController::class, 'index'])->name('admin.permissions.index');
    Route::post('/admin/permissions', [PermissionController::class, 'store'])->name('admin.permissions.store');

});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // Settings Jam Kerja
    Route::get('/admin/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/admin/settings', [SettingController::class, 'update'])->name('admin.settings.update');
    Route::post('/admin/settings/custom-working-days', [SettingController::class, 'storeCustomWorkingDay'])->name('admin.settings.custom-working-days.store');
    Route::delete('/admin/settings/custom-working-days/{customWorkingDay}', [SettingController::class, 'destroyCustomWorkingDay'])->name('admin.settings.custom-working-days.destroy');

    Route::resource('/admin/management', AdminManagementController::class)
        ->except(['show', 'create', 'edit'])
        ->names('admin.management');
});

// Auth scaffolding routes (login, logout, register, password reset, etc)
require __DIR__ . '/auth.php';

