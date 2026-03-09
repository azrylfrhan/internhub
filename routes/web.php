
<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\MagangLoginController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
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

// Separate login routes for different roles
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminLoginController::class, 'login']);

    Route::get('/magang/login', [MagangLoginController::class, 'showLoginForm'])->name('magang.login');
    Route::post('/magang/login', [MagangLoginController::class, 'login']);

    // Universal fallback login route for auth redirects
    Route::get('/login', function () {
        // Redirect ke login sesuai preferensi, default ke admin
        return redirect()->route('admin.login');
    })->name('login');
});

// Admin/Mentor Dashboard - Protected by role middleware
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:admin,mentor'])->name('dashboard');

// Magang routes with separate layout - Protected by role middleware
Route::middleware(['auth', 'verified', 'role:magang'])->group(function () {
    Route::get('/magang/attendance', function () {
        return view('magang.attendance');
    })->name('magang.attendance');

    Route::get('/magang/logbook', function () {
        return view('magang.logbook');
    })->name('magang.logbook');
    Route::get('/magang/logbook/data', [LogbookController::class, 'getData'])->name('magang.logbook.data');
    Route::get('/magang/logbook/stats', [LogbookController::class, 'getStats'])->name('magang.logbook.stats');
    Route::post('/magang/logbook/store', [LogbookController::class, 'store'])->name('magang.logbook.store');
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
});

// Auth scaffolding routes (login, logout, register, password reset, etc)
require __DIR__ . '/auth.php';

