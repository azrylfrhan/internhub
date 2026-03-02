@extends('layouts.admin')

@section('title', 'Dashboard Magang')

@section('content')
<!-- Welcome Section -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Selamat Datang, {{ Auth::user()->name }}</h1>
    <p class="text-gray-600 dark:text-gray-400">Pantau aktivitas magang dan kelola presensi Anda</p>
</div>

<!-- Personal Stats -->
<div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4 mb-8">
    <!-- Total Presensi -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Presensi Saya</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->presensis()->count() }}</p>
                <p class="text-xs text-green-600 dark:text-green-400 mt-1">Hadir</p>
            </div>
            <div class="p-3 bg-green-50 rounded-xl">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Logbook -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Logbook Saya</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->logbooks()->count() }}</p>
                <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Aktivitas</p>
            </div>
            <div class="p-3 bg-blue-50 rounded-xl">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Presensi Hari Ini -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Status Hari Ini</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">
                    @php
                        $todayPresensi = Auth::user()->presensis()->whereDate('created_at', today())->exists();
                    @endphp
                    @if($todayPresensi)
                        <span class="text-green-600">✓</span>
                    @else
                        <span class="text-red-600">✗</span>
                    @endif
                </p>
                <p class="text-xs {{ $todayPresensi ? 'text-green-600' : 'text-red-600' }} mt-1">
                    {{ $todayPresensi ? 'Sudah presensi' : 'Belum presensi' }}
                </p>
            </div>
            <div class="p-3 {{ $todayPresensi ? 'bg-green-50' : 'bg-red-50' }} rounded-xl">
                <svg class="w-8 h-8 {{ $todayPresensi ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Logbook Minggu Ini -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Logbook Minggu Ini</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ Auth::user()->logbooks()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count() }}
                </p>
                <p class="text-xs text-purple-600 mt-1">Dalam seminggu</p>
            </div>
            <div class="p-3 bg-purple-50 rounded-xl">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Presensi -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Presensi</h3>
            <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium">Lihat Semua</a>
        </div>
        <div class="space-y-4">
            @foreach(Auth::user()->presensis()->latest()->take(5)->get() as $presensi)
            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Presensi</p>
                        <p class="text-sm text-gray-500">{{ $presensi->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Hadir
                </span>
            </div>
            @endforeach
            @if(Auth::user()->presensis()->count() == 0)
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-500">Belum ada data presensi</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Logbook -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Logbook Terbaru</h3>
            <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua</a>
        </div>
        <div class="space-y-4">
            @foreach(Auth::user()->logbooks()->latest()->take(5)->get() as $logbook)
            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Logbook Entry</p>
                        <p class="text-sm text-gray-500">{{ $logbook->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Entry
                </span>
            </div>
            @endforeach
            @if(Auth::user()->logbooks()->count() == 0)
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <p class="text-gray-500">Belum ada data logbook</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Aksi Cepat</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="#" class="flex flex-col items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-blue-300 transition-all group">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <div class="text-center">
                <p class="font-medium text-gray-900">Presensi Hari Ini</p>
                <p class="text-xs text-gray-500">Catat kehadiran</p>
            </div>
        </a>

        <a href="#" class="flex flex-col items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-green-300 transition-all group">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <div class="text-center">
                <p class="font-medium text-gray-900">Tambah Logbook</p>
                <p class="text-xs text-gray-500">Catat aktivitas harian</p>
            </div>
        </a>

        <a href="#" class="flex flex-col items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-purple-300 transition-all group">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div class="text-center">
                <p class="font-medium text-gray-900">Profil Saya</p>
                <p class="text-xs text-gray-500">Kelola informasi pribadi</p>
            </div>
        </a>
    </div>
</div>
@endsection