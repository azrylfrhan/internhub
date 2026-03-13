<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - InternHub</title>
    <link rel="icon" type="image/png" href="/logo-bps.png" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .login-hero-card {
            background-image: linear-gradient(160deg, rgba(255,255,255,0.92), rgba(239,246,255,0.82));
            box-shadow: 0 20px 50px rgba(30, 64, 175, 0.14);
        }

        .logo-badge {
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.22);
        }

        .feature-item {
            transition: transform 180ms ease, background-color 180ms ease;
        }

        .feature-item:hover {
            transform: translateX(4px);
            background-color: rgba(239, 246, 255, 0.7);
        }

        .login-card {
            box-shadow: 0 22px 60px rgba(15, 23, 42, 0.1);
        }

        .field-input {
            transition: border-color 160ms ease, box-shadow 160ms ease, background-color 160ms ease;
        }

        .field-input:hover {
            background-color: #f8fafc;
        }

        .login-btn {
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.28);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-blue-50 via-white to-slate-100 text-gray-900 min-h-screen">
    <main class="min-h-screen w-full flex items-center py-10">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">

                <section class="hidden lg:block">
                    <div class="login-hero-card rounded-3xl border border-blue-100 backdrop-blur p-10 shadow-xl">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="logo-badge h-14 w-14 rounded-2xl bg-blue-100 flex items-center justify-center">
                                <img src="/logo-bps.png" alt="Logo BPS" class="h-9 w-auto" loading="lazy" style="max-width:38px;object-fit:contain;" />
                            </div>
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">InternHub</p>
                                <h1 class="text-3xl font-bold text-gray-900">Portal Magang BPS Sulut</h1>
                            </div>
                        </div>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            Sistem terpadu untuk mengelola presensi, logbook, dan pemantauan kegiatan magang di lingkungan Badan Pusat Statistik Provinsi Sulawesi Utara.
                        </p>
                        <ul class="space-y-3 text-gray-700">
                            <li class="feature-item rounded-xl px-2 py-1.5 -mx-2 flex items-start gap-3">
                                <span class="mt-2 h-2.5 w-2.5 rounded-full bg-blue-500 shrink-0"></span>
                                <span>Peserta magang - catat absensi dan logbook harian.</span>
                            </li>
                            <li class="feature-item rounded-xl px-2 py-1.5 -mx-2 flex items-start gap-3">
                                <span class="mt-2 h-2.5 w-2.5 rounded-full bg-indigo-500 shrink-0"></span>
                                <span>Admin dan Mentor - kelola data, pantau progres peserta.</span>
                            </li>
                            <li class="feature-item rounded-xl px-2 py-1.5 -mx-2 flex items-start gap-3">
                                <span class="mt-2 h-2.5 w-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                                <span>Sistem otomatis mengarahkan Anda sesuai peran setelah login.</span>
                            </li>
                        </ul>
                    </div>
                </section>

                <section>
                    <div class="login-card bg-white rounded-2xl shadow-lg border border-gray-200 p-6 sm:p-8 max-w-xl w-full mx-auto">

                        <div class="text-center mb-6">
                            <div class="logo-badge mx-auto h-14 w-14 bg-blue-600 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2m-2-2a2 2 0 00-2 2m-4-6a2 2 0 012 2m0 0a2 2 0 012 2m-2-2a2 2 0 00-2 2M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3m6 0a1 1 0 001-1V10m-9 3h4m-4 4h4" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Masuk ke InternHub</h2>
                            <p class="text-gray-500 mt-1 text-sm">Gunakan akun yang telah diberikan oleh administrator</p>
                        </div>

                        @if (session('status'))
                            <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="space-y-5" id="loginForm">
                            @csrf

                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                <input
                                    id="username"
                                    name="username"
                                    type="text"
                                    autocomplete="username"
                                    value="{{ old('username') }}"
                                    required
                                    autofocus
                                    class="field-input w-full px-4 py-3 border {{ $errors->has('username') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Masukkan username"
                                />
                                @error('username')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <div class="relative">
                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        autocomplete="current-password"
                                        required
                                        class="field-input w-full px-4 py-3 pr-11 border {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Masukkan password"
                                    />
                                    <button
                                        type="button"
                                        onclick="togglePassword('password', this)"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-700"
                                        tabindex="-1"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                    <input
                                        id="remember"
                                        name="remember"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    />
                                    Ingat saya
                                </label>
                            </div>

                            <button
                                id="loginBtn"
                                type="submit"
                                class="login-btn w-full min-h-[48px] rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition flex items-center justify-center gap-2"
                            >
                                <span id="loginBtnText">Masuk</span>
                                <svg id="loginSpinner" class="hidden animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                </svg>
                            </button>
                        </form>

                        <p class="text-xs text-gray-400 text-center mt-5">
                            Sistem akan mengarahkan Anda ke halaman yang sesuai berdasarkan peran akun Anda.
                        </p>
                    </div>
                </section>

            </div>
        </div>
    </main>

    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const path = btn.querySelector('svg path');
            if (input.type === 'password') {
                input.type = 'text';
                path.setAttribute('d', 'M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-7s4.477-7 10-7c1.306 0 2.57.25 3.75.688M15 12a3 3 0 11-6 0 3 3 0 016 0zm3.536-5.536L21 3m0 0l-2.464 2.464M21 3l-18 18');
            } else {
                input.type = 'password';
                path.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('loginForm');
            if (!form) return;
            form.addEventListener('submit', function () {
                const btn = document.getElementById('loginBtn');
                const text = document.getElementById('loginBtnText');
                const spinner = document.getElementById('loginSpinner');
                btn.disabled = true;
                btn.classList.add('opacity-60', 'pointer-events-none');
                text.textContent = 'Memproses...';
                spinner.classList.remove('hidden');
            });
        });
    </script>
</body>
</html>
