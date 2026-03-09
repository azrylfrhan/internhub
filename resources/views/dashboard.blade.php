@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Selamat Datang, {{ Auth::user()->name }}</h1>
    <p class="text-gray-600 dark:text-gray-400">Pantau aktivitas magang dan kelola sistem InternHub</p>
</div>

<!-- Key Metrics -->
<div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4 mb-8">
    <!-- Total Users -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Peserta Magang</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\User::where('role', 'magang')->count() }}</p>
                <p class="text-xs text-green-600 dark:text-green-400 mt-1">Aktif saat ini</p>
            </div>
            <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-xl">
                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Presensi -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Presensi</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Presensi::count() }}</p>
                <p class="text-xs text-green-600 dark:text-green-400 mt-1">+{{ \App\Models\Presensi::whereDate('created_at', '>=', now()->startOfMonth())->count() }} bulan ini</p>
            </div>
            <div class="p-3 bg-green-50 dark:bg-green-900/30 rounded-xl">
                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Logbook -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Logbook</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Logbook::count() }}</p>
                <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">+{{ \App\Models\Logbook::whereDate('created_at', '>=', now()->startOfWeek())->count() }} minggu ini</p>
            </div>
            <div class="p-3 bg-purple-50 dark:bg-purple-900/30 rounded-xl">
                <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Presensi Rate -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Tingkat Kehadiran</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">
                    @php
                        $totalUsers = \App\Models\User::where('role', 'magang')->count();
                        $presensiToday = \App\Models\Presensi::whereDate('created_at', today())->count();
                        $rate = $totalUsers > 0 ? round(($presensiToday / $totalUsers) * 100) : 0;
                    @endphp
                    {{ $rate }}%
                </p>
                <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">{{ $presensiToday }}/{{ $totalUsers }} hari ini</p>
            </div>
            <div class="p-3 bg-orange-50 dark:bg-orange-900/30 rounded-xl">
                <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Main Dashboard Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Recent Activities -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Rekap Absensi Hari Ini -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Rekap Absensi Hari Ini</h3>
                <button onclick="refreshRekap()" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
            </div>
            
            <!-- Stats Summary -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-green-50 dark:bg-green-900/30 p-4 rounded-lg">
                    <p class="text-sm text-green-600 dark:text-green-400 font-medium mb-1">Hadir</p>
                    <p class="text-2xl font-bold text-green-700 dark:text-green-300" id="stat-hadir">-</p>
                </div>
                <div class="bg-red-50 dark:bg-red-900/30 p-4 rounded-lg">
                    <p class="text-sm text-red-600 dark:text-red-400 font-medium mb-1">Belum Hadir</p>
                    <p class="text-2xl font-bold text-red-700 dark:text-red-300" id="stat-belum">-</p>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg">
                    <p class="text-sm text-blue-600 dark:text-blue-400 font-medium mb-1">Persentase</p>
                    <p class="text-2xl font-bold text-blue-700 dark:text-blue-300" id="stat-persen">-</p>
                </div>
            </div>
            
            <!-- Tabs -->
            <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
                <nav class="-mb-px flex space-x-6">
                    <button onclick="switchTab('hadir')" id="tab-hadir" class="tab-button py-2 px-1 border-b-2 border-blue-600 dark:border-blue-400 font-medium text-sm text-blue-600 dark:text-blue-400">
                        Sudah Hadir (<span id="count-hadir">0</span>)
                    </button>
                    <button onclick="switchTab('belum')" id="tab-belum" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600">
                        Belum Hadir (<span id="count-belum">0</span>)
                    </button>
                </nav>
            </div>
            
            <!-- Content -->
            <div id="rekap-content" class="space-y-3 max-h-96 overflow-y-auto">
                <div class="flex items-center justify-center py-8">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Tren Kehadiran -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tren Kehadiran</h3>
                <div class="flex items-center gap-2">
                    <button class="px-3 py-1 text-sm rounded border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300" onclick="loadTrend('7')">7 Hari</button>
                    <button class="px-3 py-1 text-sm rounded border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300" onclick="loadTrend('30')">30 Hari</button>
                    <button class="px-3 py-1 text-sm rounded border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300" onclick="loadTrend('month')">Bulan Ini</button>
                </div>
            </div>
            <div id="trend-loading" class="hidden items-center justify-center py-12">
                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </div>
            <div class="h-72">
                <canvas id="trendChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- Recent Presensi -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Aktivitas Presensi Terbaru</h3>
                <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                @foreach(\App\Models\Presensi::with('user')->latest()->take(8)->get() as $presensi)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $presensi->user->name ?? 'User' }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $presensi->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                        Hadir
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        
    </div>

    <!-- Sidebar Panel -->
    <div class="space-y-6">
        <!-- System Alerts -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Notifikasi Sistem</h3>
            <div class="space-y-3">
                @php
                    $presensiToday = \App\Models\Presensi::whereDate('created_at', today())->count();
                    $totalUsers = \App\Models\User::where('role', 'magang')->count();
                    $missingPresensi = $totalUsers - $presensiToday;
                @endphp

                @if($missingPresensi > 0)
                <div class="flex items-start gap-3 p-3 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Presensi Belum Lengkap</p>
                        <p class="text-xs text-yellow-700 dark:text-yellow-400">{{ $missingPresensi }} peserta belum presensi hari ini</p>
                    </div>
                </div>
                @endif

                <div class="flex items-start gap-3 p-3 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Sistem Aktif</p>
                        <p class="text-xs text-blue-700 dark:text-blue-400">Semua layanan berjalan normal</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistik Cepat</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Presensi Bulan Ini</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ \App\Models\Presensi::whereDate('created_at', '>=', now()->startOfMonth())->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Logbook Bulan Ini</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ \App\Models\Logbook::whereDate('created_at', '>=', now()->startOfMonth())->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Rata-rata Presensi</span>
                    <span class="text-sm font-semibold text-gray-900">
                        @php
                            $daysInMonth = now()->daysInMonth;
                            $monthlyPresensi = \App\Models\Presensi::whereDate('created_at', '>=', now()->startOfMonth())->count();
                            $avgPresensi = $daysInMonth > 0 ? round($monthlyPresensi / $daysInMonth) : 0;
                        @endphp
                        {{ $avgPresensi }}/hari
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admin Quick Actions -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Menu Admin</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('admin.peserta.detail') }}" class="flex flex-col items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-blue-300 transition-all group">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
            <div class="text-center">
                <p class="font-medium text-gray-900">Kelola Peserta</p>
                <p class="text-xs text-gray-500">Tambah/Edit peserta magang</p>
            </div>
        </a>

        <a href="#" class="flex flex-col items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-green-300 transition-all group">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div class="text-center">
                <p class="font-medium text-gray-900">Laporan</p>
                <p class="text-xs text-gray-500">Generate laporan presensi</p>
            </div>
        </a>

        <a href="#" class="flex flex-col items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-purple-300 transition-all group">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div class="text-center">
                <p class="font-medium text-gray-900">Pengaturan Sistem</p>
                <p class="text-xs text-gray-500">Konfigurasi aplikasi</p>
            </div>
        </a>

        <a href="#" class="flex flex-col items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-orange-300 transition-all group">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div class="text-center">
                <p class="font-medium text-gray-900">Profil Admin</p>
                <p class="text-xs text-gray-500">Kelola akun Anda</p>
            </div>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let currentTab = 'hadir';
let rekapData = null;
let trendChartInstance = null;

// Load rekap saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    loadRekap();
    loadTrend('30');
});

async function loadRekap() {
    try {
        const response = await fetch('/admin/presensi/rekap-hari-ini', {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        
        if (data.success) {
            rekapData = data;
            updateStats(data.statistik);
            updateCounts(data.hadir.length, data.belum_hadir.length);
            renderContent();
        }
    } catch (error) {
        console.error('Error loading rekap:', error);
        document.getElementById('rekap-content').innerHTML = '<p class="text-center text-red-600 py-4">Gagal memuat data</p>';
    }
}

function updateStats(stats) {
    document.getElementById('stat-hadir').textContent = stats.total_hadir;
    document.getElementById('stat-belum').textContent = stats.total_belum_hadir;
    document.getElementById('stat-persen').textContent = stats.persentase_kehadiran + '%';
}

function updateCounts(hadir, belum) {
    document.getElementById('count-hadir').textContent = hadir;
    document.getElementById('count-belum').textContent = belum;
}

function switchTab(tab) {
    currentTab = tab;
    
    // Update tab styling
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('border-blue-600', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    const activeTab = document.getElementById(`tab-${tab}`);
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-blue-600', 'text-blue-600');
    
    renderContent();
}

function renderContent() {
    if (!rekapData) return;
    
    const container = document.getElementById('rekap-content');
    const data = currentTab === 'hadir' ? rekapData.hadir : rekapData.belum_hadir;
    
    if (data.length === 0) {
        container.innerHTML = '<p class="text-center text-gray-500 py-8">Tidak ada data</p>';
        return;
    }
    
    let html = '';
    
    if (currentTab === 'hadir') {
        data.forEach(peserta => {
            const statusColor = peserta.status === 'hadir' ? 'green' : 'orange';
            const statusText = peserta.status === 'hadir' ? 'Tepat Waktu' : 'Terlambat';
            const pulangStatus = peserta.sudah_pulang 
                ? '<span class="text-xs text-green-600">✓ Sudah pulang</span>' 
                : '<span class="text-xs text-orange-600">Belum pulang</span>';
            
            html += `
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="w-10 h-10 bg-${statusColor}-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-${statusColor}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 truncate">${peserta.name}</p>
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <span>Masuk: ${peserta.jam_masuk || '-'}</span>
                                <span>•</span>
                                <span>Pulang: ${peserta.jam_pulang || '-'}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-${statusColor}-100 text-${statusColor}-800">
                            ${statusText}
                        </span>
                        ${pulangStatus}
                    </div>
                </div>
            `;
        });
    } else {
        data.forEach(peserta => {
            html += `
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">${peserta.name}</p>
                            <p class="text-sm text-gray-500">${peserta.email}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Belum Absen
                    </span>
                </div>
            `;
        });
    }
    
    container.innerHTML = html;
}

function refreshRekap() {
    document.getElementById('rekap-content').innerHTML = `
        <div class="flex items-center justify-center py-8">
            <svg class="w-8 h-8 text-blue-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
        </div>
    `;
    loadRekap();
}

async function loadTrend(range) {
    const loading = document.getElementById('trend-loading');
    loading.classList.remove('hidden');
    try {
        const resp = await fetch(`/admin/dashboard/trend?range=${encodeURIComponent(range)}`, {
            headers: { 'Accept': 'application/json' }
        });
        const data = await resp.json();
        loading.classList.add('hidden');
        if (data.success) {
            renderTrendChart(data.labels, data.series);
        }
    } catch (e) {
        loading.classList.add('hidden');
        console.error('Error loading trend:', e);
    }
}

function renderTrendChart(labels, series) {
    const ctx = document.getElementById('trendChart').getContext('2d');
    const datasets = [
        { label: 'Hadir', data: series.hadir, borderColor: '#16a34a', backgroundColor: 'rgba(22,163,74,0.15)', tension: 0.3 },
        { label: 'Terlambat', data: series.terlambat, borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,0.15)', tension: 0.3 },
        { label: 'Izin', data: series.izin, borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.15)', tension: 0.3 },
        { label: 'Alpa', data: series.alpa, borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,0.15)', tension: 0.3 },
    ];
    if (trendChartInstance) {
        trendChartInstance.data.labels = labels;
        trendChartInstance.data.datasets.forEach((ds, i) => ds.data = datasets[i].data);
        trendChartInstance.update();
        return;
    }
    trendChartInstance = new Chart(ctx, {
        type: 'line',
        data: { labels, datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'bottom' } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }
            }
        }
    });
}
</script>
@endsection
