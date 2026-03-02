<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="theme-transition">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BPS Magang')</title>
    <link rel="icon" type="image/png" href="/logo-bps.png" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .theme-transition {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
    </style>
    <script>
        // Initialize theme before page load to prevent flash
        (function() {
            const theme = localStorage.getItem('theme') || 'system';
            const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (isDark) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <!-- Main Content -->
    <div class="min-h-screen pb-20">
        <!-- Header -->
        <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 px-4 py-3 transition-colors">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-white dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <img src="/logo-bps.png" alt="Logo BPS" class="h-7 w-auto" loading="lazy" style="max-width:28px;max-height:28px;object-fit:contain;" />
                    </div>
                    <div>
                        <h1 class="text-lg font-semibold text-gray-900 dark:text-white">BPS Magang</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Selamat datang, {{ Auth::user()->name }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <!-- Theme Toggle -->
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
                            <button onclick="event.stopPropagation(); setTheme('light');" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-t-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Light
                            </button>
                            <button onclick="event.stopPropagation(); setTheme('dark');" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                                Dark
                            </button>
                            <button onclick="event.stopPropagation(); setTheme('system');" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-b-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                System
                            </button>
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
        </header>

        <!-- Page Content -->
        <main class="p-4 bg-gray-50 dark:bg-gray-900 transition-colors">
            @yield('content')
        </main>

        <!-- Global Page Loading Overlay -->
        <div id="pageLoadingOverlay" class="hidden fixed top-0 left-0 right-0 bg-white dark:bg-gray-900 bg-opacity-95 dark:bg-opacity-95 z-[70] transition-colors" style="bottom: 72px;">
            <div class="flex flex-col items-center justify-center h-full">
                <svg class="w-12 h-12 text-blue-600 dark:text-blue-400 animate-spin mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <p class="text-gray-600 dark:text-gray-300 font-medium">Memuat halaman...</p>
            </div>
        </div>
        </div>

        <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 px-4 py-2 transition-colors z-50">
        <div class="flex justify-around items-center">
            <!-- Attendance -->
            <a href="{{ route('magang.attendance') }}"
               class="flex flex-col items-center space-y-1 p-2 rounded-lg transition-colors {{ request()->routeIs('magang.attendance') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-xs font-medium">Absen</span>
            </a>

            <!-- Logbook -->
            <a href="{{ route('magang.logbook') }}"
               class="flex flex-col items-center space-y-1 p-2 rounded-lg transition-colors {{ request()->routeIs('magang.logbook') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span class="text-xs font-medium">Logbook</span>
            </a>

            <!-- Profile -->
            <a href="{{ route('magang.profile') }}"
               class="flex flex-col items-center space-y-1 p-2 rounded-lg transition-colors {{ request()->routeIs('magang.profile') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-xs font-medium">Profil</span>
            </a>
        </div>
    </nav>

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="hidden fixed inset-0 bg-black bg-opacity-0 z-[60] transition-all duration-300 ease-out">
        <div class="flex items-center justify-center h-full">
            <div id="logoutModalContent" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-sm w-full mx-4 transition-colors transform scale-95 opacity-0 transition-all duration-300 ease-out">
                <div class="p-6">
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 text-yellow-500 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M7.08 6.47a7 7 0 1 1 9.84 0"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-2">Logout Confirmation</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-center mb-6">Apakah Anda yakin ingin keluar dari sistem?</p>
                    <div class="flex gap-3">
                        <button onclick="closeLogoutModal()" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Batal
                        </button>
                        <button onclick="confirmLogout()" class="flex-1 px-4 py-2 bg-red-600 dark:bg-red-700 text-white rounded-lg font-medium hover:bg-red-700 dark:hover:bg-red-800 transition">
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Theme Management
        function toggleThemeMenu() {
            const menu = document.getElementById('theme-menu');
            menu.classList.toggle('hidden');
        }

        function setTheme(theme) {
            console.log('Setting theme to:', theme);
            localStorage.setItem('theme', theme);
            applyTheme(theme);
            const menu = document.getElementById('theme-menu');
            if (menu) menu.classList.add('hidden');
            updateThemeIcon(theme);
        }

        function applyTheme(theme) {
            const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            console.log('Applying theme:', theme, 'isDark:', isDark);
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            // Make sure theme-transition class is always present
            if (!document.documentElement.classList.contains('theme-transition')) {
                document.documentElement.classList.add('theme-transition');
            }
        }

        function updateThemeIcon(theme) {
            const sunIcon = document.getElementById('theme-icon-sun');
            const moonIcon = document.getElementById('theme-icon-moon');
            const systemIcon = document.getElementById('theme-icon-system');
            
            if (!sunIcon || !moonIcon || !systemIcon) {
                console.error('Theme icons not found');
                return;
            }
            
            // Hide all icons first
            sunIcon.classList.add('hidden');
            moonIcon.classList.add('hidden');
            systemIcon.classList.add('hidden');
            
            // Show the appropriate icon
            if (theme === 'light') {
                sunIcon.classList.remove('hidden');
            } else if (theme === 'dark') {
                moonIcon.classList.remove('hidden');
            } else {
                systemIcon.classList.remove('hidden');
            }
        }

        // Close theme menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('theme-menu');
            if (!menu) return;
            
            const button = event.target.closest('button[onclick="toggleThemeMenu()"]');
            if (!button && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        // Logout functions
        function showLogoutConfirm() {
            const modal = document.getElementById('logoutModal');
            const modalContent = document.getElementById('logoutModalContent');
            
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
            
            modal.classList.remove('bg-opacity-50');
            modal.classList.add('bg-opacity-0');
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        async function confirmLogout() {
            // Show loading feedback so users know logout is in progress
            const overlay = document.getElementById('pageLoadingOverlay');
            if (overlay) {
                const textEl = overlay.querySelector('p');
                if (textEl) textEl.textContent = 'Sedang logout...';
                overlay.classList.remove('hidden');
            }

            // Prevent double submits while logging out
            document.querySelectorAll('#logoutModal button').forEach(btn => {
                btn.disabled = true;
                btn.classList.add('opacity-60', 'cursor-not-allowed');
            });

            try {
                await fetch('{{ route('login') }}', { method: 'HEAD' });
            } catch (e) {
                console.log('Failed to refresh session, continuing logout');
            }

            document.getElementById('logoutForm').submit();
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('logoutModal');
            if (event.target === modal) {
                closeLogoutModal();
            }
        });

        // Initialize everything on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize theme
            const theme = localStorage.getItem('theme') || 'system';
            console.log('Initializing theme:', theme);
            updateThemeIcon(theme);
            applyTheme(theme);

            // Ensure logout modal opens on touch/click (mobile-safe)
            const logoutIconButton = document.getElementById('logoutIconButton');
            if (logoutIconButton) {
                const openLogout = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    showLogoutConfirm();
                };
                ['click', 'touchend'].forEach(evt => logoutIconButton.addEventListener(evt, openLogout, { passive: false }));
            }

            // Global page loading functionality
            const pageLoadingOverlay = document.getElementById('pageLoadingOverlay');
            const bottomNavLinks = document.querySelectorAll('nav.fixed.bottom-0 a[href]');

            console.log('Bottom nav links found:', bottomNavLinks.length);

            // Show loading on bottom navigation clicks
            bottomNavLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    const currentUrl = window.location.pathname;
                    
                    console.log('Link clicked:', href);
                    console.log('Current URL:', currentUrl);
                    
                    // Check if it's a different page
                    if (href && href !== currentUrl && !href.startsWith('#')) {
                        console.log('Showing loading overlay');
                        pageLoadingOverlay.classList.remove('hidden');
                    }
                });
            });

            // Hide loading when page loads
            pageLoadingOverlay.classList.add('hidden');
        });

        // Hide loading on page show (back/forward navigation)
        window.addEventListener('pageshow', function(event) {
            const pageLoadingOverlay = document.getElementById('pageLoadingOverlay');
            if (pageLoadingOverlay) {
                pageLoadingOverlay.classList.add('hidden');
            }
        });

        // Show loading on form submissions (except logout)
        document.addEventListener('submit', function(e) {
            if (e.target.id !== 'logoutForm') {
                const submitBtn = e.target.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    const pageLoadingOverlay = document.getElementById('pageLoadingOverlay');
                    if (pageLoadingOverlay) {
                        pageLoadingOverlay.classList.remove('hidden');
                    }
                }
            }
        });
    </script>

    @include('components.toast')
</body>
</html>
