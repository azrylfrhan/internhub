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
    <style>
        html, body {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }
        [x-cloak] { display: none !important; }
        @keyframes loading-bar-sweep {
            0% { transform: translateX(-120%); }
            100% { transform: translateX(420%); }
        }
        .loading-progress-bar {
            animation: loading-bar-sweep 1.05s linear infinite;
        }
    </style>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
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
<body class="font-sans antialiased overflow-x-hidden bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300">
    <div
        class="relative min-h-screen w-full max-w-full overflow-x-hidden flex"
        x-data="{ sidebarExpanded: false, isMobile: false, loading: true }"
        x-init="
            window.onload = () => { setTimeout(() => loading = false, 500) };
            const syncScreen = () => {
                isMobile = window.innerWidth < 768;
                if (isMobile) {
                    sidebarExpanded = false;
                }
            };
            syncScreen();
            window.addEventListener('resize', syncScreen);

            $watch('sidebarExpanded', value => {
                document.body.classList.toggle('overflow-hidden', isMobile && value);
            });
            $watch('isMobile', value => {
                if (!value) {
                    document.body.classList.remove('overflow-hidden');
                }
            });
        "
        x-on:page-loading.window="loading = true"
        x-on:page-loaded.window="loading = false"
    >
        <x-loading-screen
            x-show="loading"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        />

        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex max-w-full flex-col overflow-x-hidden transition-all duration-300" id="admin-main" :class="isMobile ? 'ml-0' : (sidebarExpanded ? 'ml-64' : 'ml-20')">
            <!-- Header -->
            @include('layouts.partials.header')

                <!-- Page Content -->
            <main id="main-content" class="flex-1 w-full max-w-full overflow-x-hidden transition-all duration-300 ease-in-out">
                <div class="p-4 md:p-6 lg:p-8">
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- Backdrop for mobile -->
        <div
            x-cloak
            x-show="isMobile && sidebarExpanded"
            x-transition.opacity
            class="fixed inset-0 z-30 bg-black/40 md:hidden"
            @click="sidebarExpanded = false"
        ></div>
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
            
            window.dispatchEvent(new CustomEvent('page-loading'));
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

        // Show loading overlay on form submissions (skip when disabled)
        document.addEventListener('submit', function(event) {
            const form = event.target;
            const submitBtn = event.submitter || form.querySelector('button[type="submit"]');

            applyInlineSubmitState(submitBtn);

            if (form.dataset.noLoader !== undefined) return;
            if (submitBtn && submitBtn.disabled && !submitBtn.querySelector('[data-inline-spinner]')) return;

            window.dispatchEvent(new CustomEvent('page-loading'));
        });

        window.addEventListener('pageshow', function() {
            window.dispatchEvent(new CustomEvent('page-loaded'));
            document.body.classList.remove('overflow-hidden');
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
    @livewireScripts
</body>
</html>
