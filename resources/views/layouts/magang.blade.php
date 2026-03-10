<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="theme-transition">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'InternHub')</title>
    <link rel="icon" type="image/png" href="/logo-bps.png" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        [x-cloak] { display: none !important; }
        @keyframes loading-bar-sweep {
            0% { transform: translateX(-120%); }
            100% { transform: translateX(420%); }
        }
        .loading-progress-bar {
            animation: loading-bar-sweep 1.05s linear infinite;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .theme-transition {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
    </style>
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'system';
            const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (isDark) document.documentElement.classList.add('dark');
        })();
    </script>
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 transition-colors duration-300 min-h-screen flex flex-col"
    x-data="{ loading: true }"
    x-init="window.onload = () => { setTimeout(() => loading = false, 500) }"
    x-on:page-loading.window="loading = true"
    x-on:page-loaded.window="loading = false"
>
    <x-loading-screen
        x-show="loading"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    />
    <header class="sticky top-0 z-40 w-full border-b border-gray-200 dark:border-gray-700 bg-white/95 dark:bg-gray-800/95 backdrop-blur">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 bg-white dark:bg-gray-700 rounded-lg flex items-center justify-center shrink-0">
                        <img src="/logo-bps.png" alt="Logo InternHub" class="h-7 w-auto" loading="lazy" style="max-width:28px;max-height:28px;object-fit:contain;" />
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-lg font-semibold text-gray-900 dark:text-white truncate">InternHub</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Selamat datang, {{ Auth::user()->name }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <div class="relative">
                        <button onclick="event.stopPropagation(); toggleThemeMenu();" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg id="theme-icon-sun" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <svg id="theme-icon-moon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                            <svg id="theme-icon-system" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                        <div id="theme-menu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50">
                            <button onclick="event.stopPropagation(); setTheme('light');" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-t-lg transition">Light</button>
                            <button onclick="event.stopPropagation(); setTheme('dark');" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">Dark</button>
                            <button onclick="event.stopPropagation(); setTheme('system');" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-b-lg transition">System</button>
                        </div>
                    </div>
                    <form id="logoutForm" method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button id="logoutIconButton" type="button" onclick="showLogoutConfirm()" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <nav class="hidden lg:block w-full border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-14 flex items-center gap-2">
                <a href="{{ route('magang.attendance') }}" class="js-nav-link px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('magang.attendance') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Absen</a>
                <a href="{{ route('magang.logbook') }}" class="js-nav-link px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('magang.logbook') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Logbook</a>
                <a href="{{ route('magang.profile') }}" class="js-nav-link px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('magang.profile') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Profil</a>
            </div>
        </div>
    </nav>

    <main class="flex-1 w-full bg-gray-100 dark:bg-gray-900 transition-colors">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8 pb-24 lg:pb-8">
            @yield('content')
        </div>
    </main>

    <nav class="lg:hidden fixed bottom-0 inset-x-0 w-full bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 px-4 py-2 transition-colors z-40">
        <div class="flex justify-around items-center">
            <a href="{{ route('magang.attendance') }}" class="js-nav-link flex flex-col items-center space-y-1 p-2 rounded-lg transition-colors {{ request()->routeIs('magang.attendance') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-xs font-medium">Absen</span>
            </a>
            <a href="{{ route('magang.logbook') }}" class="js-nav-link flex flex-col items-center space-y-1 p-2 rounded-lg transition-colors {{ request()->routeIs('magang.logbook') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span class="text-xs font-medium">Logbook</span>
            </a>
            <a href="{{ route('magang.profile') }}" class="js-nav-link flex flex-col items-center space-y-1 p-2 rounded-lg transition-colors {{ request()->routeIs('magang.profile') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span class="text-xs font-medium">Profil</span>
            </a>
        </div>
    </nav>

    <div id="logoutModal" class="hidden fixed inset-0 bg-black bg-opacity-0 z-[99] transition-all duration-300 ease-out">
        <div class="flex items-center justify-center h-full px-4">
            <div id="logoutModalContent" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-sm w-full transform transition-all duration-300 ease-out scale-95 opacity-0">
                <div class="p-6">
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 text-yellow-500 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M7.08 6.47a7 7 0 1 1 9.84 0"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-2">Logout Confirmation</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-center mb-6">Apakah Anda yakin ingin keluar dari sistem?</p>
                    <div class="flex gap-3">
                        <button onclick="closeLogoutModal()" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">Batal</button>
                        <button onclick="confirmLogout()" class="flex-1 px-4 py-2 bg-red-600 dark:bg-red-700 text-white rounded-lg font-medium hover:bg-red-700 dark:hover:bg-red-800 transition">Logout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let logoutFormTarget = 'logoutForm';

        function toggleThemeMenu() {
            const menu = document.getElementById('theme-menu');
            if (menu) menu.classList.toggle('hidden');
        }

        function setTheme(theme) {
            localStorage.setItem('theme', theme);
            applyTheme(theme);
            const menu = document.getElementById('theme-menu');
            if (menu) menu.classList.add('hidden');
            updateThemeIcon(theme);
        }

        function applyTheme(theme) {
            const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', isDark);
            if (!document.documentElement.classList.contains('theme-transition')) {
                document.documentElement.classList.add('theme-transition');
            }
        }

        function updateThemeIcon(theme) {
            const sunIcon = document.getElementById('theme-icon-sun');
            const moonIcon = document.getElementById('theme-icon-moon');
            const systemIcon = document.getElementById('theme-icon-system');
            if (!sunIcon || !moonIcon || !systemIcon) return;

            sunIcon.classList.add('hidden');
            moonIcon.classList.add('hidden');
            systemIcon.classList.add('hidden');

            if (theme === 'light') sunIcon.classList.remove('hidden');
            else if (theme === 'dark') moonIcon.classList.remove('hidden');
            else systemIcon.classList.remove('hidden');
        }

        function showLogoutConfirm(formId = 'logoutForm') {
            logoutFormTarget = formId;
            const modal = document.getElementById('logoutModal');
            const modalContent = document.getElementById('logoutModalContent');
            if (!modal || !modalContent) return;

            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    modal.classList.remove('bg-opacity-0');
                    modal.classList.add('bg-opacity-50');
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                });
            });
        }

        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            const modalContent = document.getElementById('logoutModalContent');
            if (!modal || !modalContent) return;

            modal.classList.remove('bg-opacity-50');
            modal.classList.add('bg-opacity-0');
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        function confirmLogout() {
            const targetForm = document.getElementById(logoutFormTarget);
            if (!targetForm) return;

            window.dispatchEvent(new CustomEvent('page-loading'));
            document.querySelectorAll('#logoutModal button').forEach(btn => {
                btn.disabled = true;
                btn.classList.add('opacity-60', 'cursor-not-allowed');
            });

            targetForm.submit();
        }

        document.addEventListener('click', function(event) {
            const menu = document.getElementById('theme-menu');
            const button = event.target.closest('button[onclick="event.stopPropagation(); toggleThemeMenu();"]');
            if (menu && !button && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }

            const modal = document.getElementById('logoutModal');
            if (event.target === modal) closeLogoutModal();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const theme = localStorage.getItem('theme') || 'system';
            applyTheme(theme);
            updateThemeIcon(theme);

            const logoutIconButton = document.getElementById('logoutIconButton');
            if (logoutIconButton) {
                ['click', 'touchend'].forEach(evt => {
                    logoutIconButton.addEventListener(evt, function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        showLogoutConfirm('logoutForm');
                    }, { passive: false });
                });
            }

            const navLinks = document.querySelectorAll('.js-nav-link[href]');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    const href = this.getAttribute('href');
                    if (href && href !== window.location.pathname && !href.startsWith('#')) {
                        window.dispatchEvent(new CustomEvent('page-loading'));
                    }
                });
            });
        });

        window.addEventListener('pageshow', function() {
            window.dispatchEvent(new CustomEvent('page-loaded'));
        });

        function applyInlineSubmitState(button) {
            if (!button || button.disabled || button.dataset.skipInlineLoader !== undefined) return;

            const label = (button.innerText || '').toLowerCase();
            if (!label.includes('simpan') && !label.includes('login') && !label.includes('masuk')) return;

            if (!button.querySelector('[data-inline-spinner]')) {
                const spinner = document.createElement('span');
                spinner.setAttribute('data-inline-spinner', 'true');
                spinner.className = 'inline-block h-4 w-4 rounded-full border-2 border-current border-t-transparent animate-spin';
                button.appendChild(spinner);
            }

            button.classList.add('opacity-50', 'pointer-events-none');
            button.disabled = true;
        }

        document.addEventListener('submit', function(e) {
            const form = e.target;
            const submitBtn = e.submitter || form.querySelector('button[type="submit"]');

            applyInlineSubmitState(submitBtn);

            if (form.id !== 'logoutForm' && form.id !== 'profileLogoutForm') {
                if (submitBtn && submitBtn.disabled && !submitBtn.querySelector('[data-inline-spinner]')) return;
                window.dispatchEvent(new CustomEvent('page-loading'));
            }
        });
    </script>

    <script src="{{ asset('js/notifications.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                showMessage(@js(session('success')), 'success');
            @endif
            @if(session('error'))
                showMessage(@js(session('error')), 'error');
            @endif
            @if(session('status'))
                showMessage(@js(session('status')), 'info');
            @endif
            @if($errors->any())
                showMessage(@js($errors->first()), 'error');
            @endif
        });
    </script>
</body>
</html>
