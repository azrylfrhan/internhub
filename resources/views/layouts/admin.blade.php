<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'InternHub')</title>
    <link rel="icon" type="image/png" href="/logo-bps.png" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300">
    <div
        class="min-h-screen w-full flex"
        x-data="{ sidebarExpanded: false, isMobile: false }"
        x-init="
            const syncScreen = () => {
                isMobile = window.innerWidth < 768;
                if (isMobile) {
                    sidebarExpanded = false;
                }
            };
            syncScreen();
            window.addEventListener('resize', syncScreen);
        "
    >
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col transition-all duration-300" id="admin-main" :class="isMobile ? 'ml-0' : (sidebarExpanded ? 'ml-64' : 'ml-20')">
            <!-- Header -->
            @include('layouts.partials.header')

                <!-- Page Content -->
            <main id="main-content" class="flex-1 transition-all duration-300 ease-in-out">
                <div class="p-4 md:p-6 lg:p-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Backdrop for mobile -->
    <div x-show="isMobile && sidebarExpanded" x-transition.opacity class="fixed inset-0 bg-black/50 z-30 md:hidden" @click="sidebarExpanded = false"></div>

    <!-- Global Content Loading Overlay -->
    <div id="adminLoadingOverlay" class="hidden fixed inset-0 bg-white/90 dark:bg-gray-900/90 z-50 backdrop-blur-sm">
        <div class="flex items-center justify-center h-full gap-3 text-gray-700 dark:text-gray-200">
            <svg class="w-10 h-10 text-blue-600 dark:text-blue-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v4m0 8v4m-8-8h4m8 0h4m-2.5-5.5l-2.5 2.5m0 5l2.5 2.5m-11-10l-2.5-2.5m0 10l2.5-2.5"></path>
            </svg>
            <span id="adminLoadingText" class="text-lg font-medium">Memuat konten...</span>
        </div>
    </div>

    <script>
        // Theme Management
        function toggleThemeMenu() {
            const menu = document.getElementById('theme-menu');
            menu.classList.toggle('hidden');
        }

        function setTheme(theme) {
            localStorage.setItem('theme', theme);
            applyTheme(theme);
            const menu = document.getElementById('theme-menu');
            menu.classList.add('hidden');
            updateThemeIcon(theme);
        }

        function applyTheme(theme) {
            if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        function updateThemeIcon(theme) {
            const sunIcon = document.getElementById('theme-icon-sun');
            const moonIcon = document.getElementById('theme-icon-moon');
            const systemIcon = document.getElementById('theme-icon-system');
            
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
            const button = event.target.closest('button[onclick="toggleThemeMenu()"]');
            if (!button && menu && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize theme
            const theme = localStorage.getItem('theme') || 'system';
            updateThemeIcon(theme);
            applyTheme(theme);
        });

        // Show loading overlay on navigation clicks
        document.addEventListener('click', function(event) {
            const link = event.target.closest('a');
            if (!link) return;
            if (link.target === '_blank' || link.hasAttribute('download')) return;
            if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
            const href = link.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
            if (link.dataset.noLoader !== undefined) return;
            
            showAdminLoadingOverlay();
        });

        // Show loading overlay on form submissions (skip when disabled)
        document.addEventListener('submit', function(event) {
            const form = event.target;
            if (form.dataset.noLoader !== undefined) return;
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && submitBtn.disabled) return;
            showAdminLoadingOverlay();
        });

        function showAdminLoadingOverlay() {
            const overlay = document.getElementById('adminLoadingOverlay');
            if (overlay) overlay.classList.remove('hidden');
        }
    </script>

    @include('components.toast')
</body>
</html>
