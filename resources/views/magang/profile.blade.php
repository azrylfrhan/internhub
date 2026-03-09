@extends('layouts.magang')

@section('title', 'Profil')

@section('content')
<div
    x-data="{
        editModal: @js($errors->has('email')),
        passwordModal: @js($errors->has('current_password') || $errors->has('new_password')),
        showCurrentPassword: false,
        showNewPassword: false,
        showConfirmPassword: false
    }"
    class="space-y-6"
>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center space-x-4">
            <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center">
                <span class="text-2xl font-bold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
            </div>
            <div class="flex-1">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400">{{ Auth::user()->email }}</p>
                <p class="text-sm text-blue-600 dark:text-blue-400 font-medium mt-1">Anak InternHub</p>
            </div>
        </div>
    </div>

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
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
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

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Pengaturan Akun</h2>

        <div class="space-y-3">
            <button
                type="button"
                @click="editModal = true"
                class="w-full flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
            >
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    <span class="font-medium text-gray-900 dark:text-white">Edit Profil</span>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <button
                type="button"
                @click="passwordModal = true"
                class="w-full flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
            >
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    <span class="font-medium text-gray-900 dark:text-white">Ubah Password</span>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form id="profileLogoutForm" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="button" onclick="confirmProfileLogout()" class="w-full bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white font-medium py-3 px-6 rounded-lg transition-colors flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span>Keluar</span>
            </button>
        </form>
    </div>

    <div
        x-show="editModal"
        x-cloak
        @keydown.escape.window="editModal = false"
        @click.self="editModal = false"
        class="fixed inset-0 z-[80] bg-black/50 flex items-center justify-center px-4"
    >
        <div class="w-full sm:max-w-lg md:max-w-2xl bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Profil</h3>
                <button type="button" @click="editModal = false" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('magang.profile.update') }}" class="px-6 py-5 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                    <input type="text" value="{{ Auth::user()->name }}" readonly class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 cursor-not-allowed">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', Auth::user()->email) }}" required class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div
        x-show="passwordModal"
        x-cloak
        @keydown.escape.window="passwordModal = false"
        @click.self="passwordModal = false"
        class="fixed inset-0 z-[80] bg-black/50 flex items-center justify-center px-4"
    >
        <div class="w-full sm:max-w-lg md:max-w-2xl bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ubah Password</h3>
                <button type="button" @click="passwordModal = false" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="passwordUpdateForm" method="POST" action="{{ route('magang.profile.password.update') }}" class="px-6 py-5 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password Lama</label>
                    <div class="relative">
                        <input id="current_password" name="current_password" :type="showCurrentPassword ? 'text' : 'password'" required class="w-full px-4 py-3 pr-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button type="button" @click="showCurrentPassword = !showCurrentPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg x-show="!showCurrentPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <svg x-show="showCurrentPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9-4-9-7 0-1.598.872-3.048 2.318-4.23M6.223 6.223A9.953 9.953 0 0112 5c5 0 9 4 9 7 0 1.61-.886 3.07-2.349 4.255M9.88 9.88a3 3 0 104.24 4.24M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password Baru</label>
                    <div class="relative">
                        <input id="new_password" name="new_password" :type="showNewPassword ? 'text' : 'password'" required class="w-full px-4 py-3 pr-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button type="button" @click="showNewPassword = !showNewPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg x-show="!showNewPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <svg x-show="showNewPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9-4-9-7 0-1.598.872-3.048 2.318-4.23M6.223 6.223A9.953 9.953 0 0112 5c5 0 9 4 9 7 0 1.61-.886 3.07-2.349 4.255M9.88 9.88a3 3 0 104.24 4.24M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    @error('new_password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input id="new_password_confirmation" name="new_password_confirmation" :type="showConfirmPassword ? 'text' : 'password'" required class="w-full px-4 py-3 pr-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg x-show="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <svg x-show="showConfirmPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9-4-9-7 0-1.598.872-3.048 2.318-4.23M6.223 6.223A9.953 9.953 0 0112 5c5 0 9 4 9 7 0 1.61-.886 3.07-2.349 4.255M9.88 9.88a3 3 0 104.24 4.24M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <p id="passwordClientError" class="hidden text-sm text-red-600"></p>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="passwordModal = false" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium">
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmProfileLogout() {
    if (!confirm('Apakah Anda yakin ingin keluar?')) return;

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

document.addEventListener('DOMContentLoaded', function() {
    @if (session('success'))
    showToast('success', "{{ addslashes(session('success')) }}");
    @endif

    const passwordForm = document.getElementById('passwordUpdateForm');
    const currentPassword = document.getElementById('current_password');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');
    const clientError = document.getElementById('passwordClientError');

    if (passwordForm && currentPassword && newPassword && confirmPassword && clientError) {
        passwordForm.addEventListener('submit', function(e) {
            clientError.classList.add('hidden');
            clientError.textContent = '';

            if (newPassword.value !== confirmPassword.value) {
                e.preventDefault();
                clientError.textContent = 'Konfirmasi password baru tidak cocok.';
                clientError.classList.remove('hidden');
                showToast('error', 'Konfirmasi password baru tidak cocok.');
                confirmPassword.focus();
                return;
            }

            if (currentPassword.value && newPassword.value && currentPassword.value === newPassword.value) {
                e.preventDefault();
                clientError.textContent = 'Password baru harus berbeda dari password lama.';
                clientError.classList.remove('hidden');
                showToast('error', 'Password baru harus berbeda dari password lama.');
                newPassword.focus();
            }
        });
    }
});
</script>
@endsection
