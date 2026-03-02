<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen w-screen flex">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            @include('layouts.partials.header')

                <!-- Page Content -->
            <main id="main-content" class="flex-1 p-4 md:p-6 lg:p-8 transition-all duration-300 ease-in-out">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Backdrop for mobile -->
    <div id="backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden transition-opacity" onclick="toggleSidebar()"></div>

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
        let sidebarCollapsed = false;

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

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('backdrop');
            const menuTexts = document.querySelectorAll('.menu-item-text');
            const logoText = document.querySelector('.sidebar-logo-text');
            const toggleButton = document.querySelector('.sidebar-toggle-button');

            if (window.innerWidth < 1024) {
                // Mobile: toggle show/hide sidebar
                const isOpen = !sidebar.classList.contains('-translate-x-full');
                if (isOpen) {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                } else {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                }
            } else {
                // Desktop: toggle collapse/expand
                sidebarCollapsed = !sidebarCollapsed;

                if (sidebarCollapsed) {
                    sidebar.classList.remove('lg:w-72');
                    sidebar.classList.add('lg:w-20');
                    menuTexts.forEach(text => text.classList.add('hidden'));
                    if (logoText) logoText.classList.add('hidden');
                    if (toggleButton) toggleButton.classList.remove('hidden');
                } else {
                    sidebar.classList.remove('lg:w-20');
                    sidebar.classList.add('lg:w-72');
                    menuTexts.forEach(text => text.classList.remove('hidden'));
                    if (logoText) logoText.classList.remove('hidden');
                    if (toggleButton) toggleButton.classList.add('hidden');
                }
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize theme
            const theme = localStorage.getItem('theme') || 'system';
            updateThemeIcon(theme);
            applyTheme(theme);

            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('backdrop');
            // Mobile: sidebar hidden by default
            if (window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
                if (backdrop) backdrop.classList.add('hidden');
            } else {
                // Desktop: sidebar expanded by default
                sidebar.classList.add('lg:w-72');
                sidebar.classList.remove('lg:w-20');
                const menuTexts = document.querySelectorAll('.menu-item-text');
                const logoText = document.querySelector('.sidebar-logo-text');
                const toggleButton = document.querySelector('.sidebar-toggle-button');
                menuTexts.forEach(text => text.classList.remove('hidden'));
                if (logoText) logoText.classList.remove('hidden');
                if (toggleButton) toggleButton.classList.add('hidden');
            }
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
            
            // Close sidebar on mobile when menu item clicked
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth < 1024 && sidebar && !sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.add('-translate-x-full');
                const backdrop = document.getElementById('backdrop');
                if (backdrop) backdrop.classList.add('hidden');
            }
            
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
