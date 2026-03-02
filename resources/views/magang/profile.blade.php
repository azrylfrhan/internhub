@extends('layouts.magang')

@section('title', 'Profil')

@section('content')
<div class="space-y-6">
    <!-- Profile Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center space-x-4">
            <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center">
                <span class="text-2xl font-bold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
            </div>
            <div class="flex-1">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400">{{ Auth::user()->email }}</p>
                <p class="text-sm text-blue-600 dark:text-blue-400 font-medium mt-1">Anak Magang BPS</p>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Informasi Profil</h2>

        <div class="space-y-4">
            <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Nama Lengkap</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nama yang terdaftar di sistem</p>
                </div>
                <p class="text-gray-900 dark:text-gray-200">{{ Auth::user()->name }}</p>
            </div>

            <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Email</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Alamat email untuk login</p>
                </div>
                <p class="text-gray-900 dark:text-gray-200">{{ Auth::user()->email }}</p>
            </div>

            <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Role</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Jenis akun Anda</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Anak Magang
                </span>
            </div>

            <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Bergabung Sejak</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal pendaftaran</p>
                </div>
                <p class="text-gray-900 dark:text-gray-200">{{ Auth::user()->created_at->locale('id')->isoFormat('D MMMM YYYY') }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Statistik Aktivitas</h2>

        <div class="grid grid-cols-2 gap-4">
            <div class="text-center p-4 bg-green-50 dark:bg-green-900/30 rounded-lg">
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ Auth::user()->presensis()->count() }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Kehadiran</p>
            </div>

            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ Auth::user()->logbooks()->count() }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Logbook</p>
            </div>
        </div>
    </div>

    <!-- Account Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Pengaturan Akun</h2>

        <div class="space-y-3">
            <button class="w-full flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    <span class="font-medium text-gray-900">Edit Profil</span>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <button class="w-full flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    <span class="font-medium text-gray-900">Ubah Password</span>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Logout Button -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form id="profileLogoutForm" method="POST" action="{{ route('logout') }}">
            @csrf
                <button type="button" onclick="confirmProfileLogout()"
                    class="w-full bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white font-medium py-3 px-6 rounded-lg transition-colors flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span>Keluar</span>
            </button>
        </form>

    <script>
    function confirmProfileLogout() {
        if (!confirm('Apakah Anda yakin ingin keluar?')) return;

        // Show the same loading overlay to signal logout is running
        const overlay = document.getElementById('pageLoadingOverlay');
        if (overlay) {
            const textEl = overlay.querySelector('p');
            if (textEl) textEl.textContent = 'Sedang logout...';
            overlay.classList.remove('hidden');
        }

        const form = document.getElementById('profileLogoutForm');
        const submitBtn = form?.querySelector('button[type="button"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
        }

        form?.submit();
    }
    </script>
    </div>
</div>
@endsection