<header id="main-header" class="sticky top-0 z-50 w-full border-b border-gray-200 bg-white/95 shadow-sm backdrop-blur dark:border-gray-700 dark:bg-gray-800/95">
    <div class="flex items-center justify-between px-4 py-3 md:px-6 lg:px-8">
        <div class="flex items-center gap-3 md:gap-4">
            <button @click="sidebarExpanded = !sidebarExpanded" class="rounded-lg p-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 md:hidden" aria-label="Toggle sidebar">
                <svg x-show="!sidebarExpanded" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="sidebarExpanded" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="flex items-center gap-3">
                <img src="/logo-bps.png" alt="Logo BPS" class="h-9 w-9 rounded-lg border border-gray-200 bg-white p-1 dark:border-gray-600" />
                <div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">InternHub Admin</p>
                    <h1 class="text-xs text-gray-600 dark:text-gray-300 md:text-sm">@yield('title', 'Dashboard')</h1>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 md:gap-3">
            <div class="relative">
                <button onclick="toggleThemeMenu()" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200" aria-label="Theme menu">
                    <svg id="theme-icon-sun" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <svg id="theme-icon-moon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <svg id="theme-icon-system" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </button>
                <div id="theme-menu" class="absolute right-0 z-50 mt-2 hidden w-48 rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                    <button onclick="setTheme('light')" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-t-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Light
                    </button>
                    <button onclick="setTheme('dark')" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        Dark
                    </button>
                    <button onclick="setTheme('system')" class="flex w-full items-center gap-3 rounded-b-lg px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        System
                    </button>
                </div>
            </div>

            <div class="relative">
                <button onclick="toggleUserMenu()" class="flex items-center gap-3 rounded-lg border border-transparent p-2 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700" aria-label="Admin menu">
                    <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" alt="Admin" class="h-9 w-9 rounded-full border border-gray-200 dark:border-gray-600">
                    <div class="hidden text-left md:block">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Administrator</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="user-menu" class="absolute right-0 z-50 mt-2 hidden w-56 rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                    </div>
                    <form id="logoutForm" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="button" onclick="showLogoutConfirm()" class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 opacity-0 transition-all duration-300 ease-out">
    <div id="logoutModalContent" class="w-[90%] transform rounded-2xl bg-white opacity-0 shadow-xl transition-all duration-300 ease-out sm:w-full sm:max-w-lg md:max-w-2xl dark:bg-gray-800">
        <div class="px-6 py-4">
                <div class="flex justify-center mb-4">
                    <svg class="w-12 h-12 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M7.08 6.47a7 7 0 1 1 9.84 0"></path>
                    </svg>
                </div>
                <h3 class="mb-2 text-center text-lg font-semibold text-gray-900 dark:text-white">Logout Confirmation</h3>
                <p class="mb-6 text-center text-gray-600 dark:text-gray-300">Apakah Anda yakin ingin keluar dari sistem?</p>
                <div class="flex gap-3">
                    <button onclick="closeLogoutModal()" class="flex-1 rounded-xl border border-gray-300 px-4 py-2 font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        Batal
                    </button>
                    <button onclick="confirmLogout()" class="flex-1 rounded-xl bg-red-600 px-4 py-2 font-medium text-white transition hover:bg-red-700">
                        Logout
                    </button>
                </div>
        </div>
    </div>
</div>

<script>
    function toggleUserMenu() {
        const menu = document.getElementById('user-menu');
        menu.classList.toggle('hidden');
    }

    function showLogoutConfirm() {
        const modal = document.getElementById('logoutModal');
        const modalContent = document.getElementById('logoutModalContent');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            });
        });
        document.getElementById('user-menu').classList.add('hidden');
    }

    function closeLogoutModal() {
        const modal = document.getElementById('logoutModal');
        const modalContent = document.getElementById('logoutModalContent');
        
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    }

    async function confirmLogout() {
        // Show loading overlay and disable buttons to indicate progress
        const overlay = document.getElementById('adminLoadingOverlay');
        const overlayText = document.getElementById('adminLoadingText');
        if (overlay) {
            if (overlayText) overlayText.textContent = 'Sedang logout...';
            overlay.classList.remove('hidden');
        }
        document.querySelectorAll('#logoutModal button').forEach(btn => {
            btn.disabled = true;
            btn.classList.add('opacity-60', 'cursor-not-allowed');
        });

        try {
            // Refresh CSRF token sebelum submit untuk mencegah 419 error
            await fetch('{{ route("login") }}', { method: 'HEAD' });
        } catch (e) {
            console.log('Failed to refresh session, continuing anyway');
        }
        document.getElementById('logoutForm').submit();
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('user-menu');
        const button = event.target.closest('button[onclick="toggleUserMenu()"]');
        if (!button && menu && !menu.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('logoutModal');
        if (event.target === modal) {
            closeLogoutModal();
        }
    });
</script>