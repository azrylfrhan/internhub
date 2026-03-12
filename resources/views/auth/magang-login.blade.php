<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Magang - InternHub</title>
    <link rel="icon" type="image/png" href="/logo-bps.png" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-emerald-50 via-white to-blue-50 text-gray-900 min-h-screen">
    <main class="min-h-screen w-full flex items-center py-10">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                <section class="hidden lg:block">
                    <div class="rounded-3xl border border-emerald-100 bg-white/80 backdrop-blur p-10 shadow-xl">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="h-14 w-14 rounded-2xl bg-emerald-100 flex items-center justify-center">
                                <img src="/logo-bps.png" alt="Logo InternHub" class="h-9 w-auto" loading="lazy" style="max-width:38px;object-fit:contain;" />
                            </div>
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">InternHub</p>
                                <h1 class="text-3xl font-bold text-gray-900">Portal Peserta Magang</h1>
                            </div>
                        </div>
                        <p class="text-gray-600 leading-relaxed mb-6">Akses absensi harian, catatan logbook, dan ringkasan aktivitas secara terpusat untuk mendukung proses magang yang terstruktur.</p>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start gap-3"><span class="mt-2 h-2.5 w-2.5 rounded-full bg-emerald-500"></span><span>Presensi berbasis lokasi yang akurat.</span></li>
                            <li class="flex items-start gap-3"><span class="mt-2 h-2.5 w-2.5 rounded-full bg-blue-500"></span><span>Logbook aktivitas harian yang terdokumentasi.</span></li>
                            <li class="flex items-start gap-3"><span class="mt-2 h-2.5 w-2.5 rounded-full bg-amber-500"></span><span>Riwayat progres magang yang transparan.</span></li>
                        </ul>
                    </div>
                </section>

                <section>
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 sm:p-8 max-w-xl w-full mx-auto">
                        <div class="text-center mb-6">
                            <div class="mx-auto h-14 w-14 bg-emerald-600 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Login Peserta Magang</h2>
                            <p class="text-gray-600 mt-1">Masuk ke sistem absensi InternHub</p>
                        </div>

                        <form method="POST" action="{{ route('magang.login') }}" class="space-y-5" id="magangLoginForm">
                            @csrf
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                <input id="username" name="username" type="text" autocomplete="username" value="{{ old('username') }}" required class="w-full px-4 py-3 border border-gray-300 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="username">
                                @error('username')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <div class="relative">
                                    <input id="password" name="password" type="password" autocomplete="current-password" required class="w-full px-4 py-3 pr-11 border border-gray-300 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Masukkan password">
                                    <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-700">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                Ingat saya
                            </label>

                            <button id="loginBtn" type="submit" class="w-full min-h-[48px] rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition flex items-center justify-center gap-2">
                                <span id="loginBtnText">Masuk ke Dashboard</span>
                                <svg id="loginSpinner" class="hidden animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                </svg>
                            </button>
                        </form>

                        <p class="text-sm text-gray-600 mt-5 text-center">
                            Anda admin atau mentor?
                            <a href="{{ route('admin.login') }}" class="font-semibold text-emerald-700 hover:text-emerald-600">Login sebagai Admin</a>
                        </p>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('svg path');
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('d', 'M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9-4-9-7s4-7 9-7 9 4 9 7c0 1.306-.835 2.417-2.125 3.825M15 12a3 3 0 11-6 0 3 3 0 016 0zm-2.25 6.825L21 21M3 3l18 18');
            } else {
                input.type = 'password';
                icon.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('magangLoginForm');
            if (!form) return;
            form.addEventListener('submit', function() {
                const btn = document.getElementById('loginBtn');
                const text = document.getElementById('loginBtnText');
                const spinner = document.getElementById('loginSpinner');
                btn.disabled = true;
                btn.classList.add('opacity-50', 'pointer-events-none');
                text.textContent = 'Loading...';
                spinner.classList.remove('hidden');
            });
        });
    </script>

    @include('components.toast')
</body>
</html>
