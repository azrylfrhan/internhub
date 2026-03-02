<div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-gray-800 shadow-lg transform -translate-x-full lg:relative lg:translate-x-0 lg:w-72 transition-all duration-300 ease-in-out">
    <!-- Logo Section -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white">
                <img src="/logo-bps.png" alt="Logo BPS" class="h-8 w-auto" loading="lazy" style="max-width:32px;max-height:32px;object-fit:contain;" />
            </div>
            <span class="sidebar-logo-text text-xl font-bold text-gray-800">BPS Magang</span>
        </div>
        <!-- Toggle button for collapsed sidebar (desktop only) -->
        <button onclick="toggleSidebar()" class="sidebar-toggle-button hidden lg:flex lg:w-20 lg:justify-center p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        <!-- Close button for mobile -->
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 px-3">
        <ul class="space-y-2">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}" class="menu-item group {{ request()->routeIs('dashboard') ? 'menu-item-active' : 'menu-item-inactive' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    <span class="menu-item-text">Dashboard</span>
                </a>
            </li>

            <!-- Detail Peserta -->
            <li>
                <a href="{{ route('admin.peserta.detail') }}" class="menu-item group {{ request()->routeIs('admin.peserta.detail') ? 'menu-item-active' : 'menu-item-inactive' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.peserta.detail') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11c1.654 0 3-1.346 3-3s-1.346-3-3-3-3 1.346-3 3 1.346 3 3 3zM6 11c1.654 0 3-1.346 3-3S7.654 5 6 5 3 6.346 3 8s1.346 3 3 3zm0 2c-2.206 0-4 1.794-4 4v1h8v-1c0-2.206-1.794-4-4-4zm10 0c-1.195 0-2.27.524-3.012 1.353A4.97 4.97 0 0115 18v1h8v-1c0-2.206-1.794-4-4-4z"></path>
                    </svg>
                    <span class="menu-item-text">Detail Peserta</span>
                </a>
            </li>

            <!-- Laporan Presensi -->
            <li>
                <a href="{{ route('admin.laporan.presensi') }}" class="menu-item group {{ request()->routeIs('admin.laporan.presensi') ? 'menu-item-active' : 'menu-item-inactive' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.laporan.presensi') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm6 0V7a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="menu-item-text">Laporan Presensi</span>
                </a>
            </li>

            <!-- Logbook -->
            <li>
                <a href="{{ route('admin.logbook') }}" class="menu-item group {{ request()->routeIs('admin.logbook*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.logbook*') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="menu-item-text">Manajemen Logbook</span>
                </a>
            </li>

            <!-- Profile -->
            <li>
                <a href="#" class="menu-item group {{ request()->routeIs('profile.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('profile.*') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="menu-item-text">Profile</span>
                </a>
            </li>
        </ul>
    </nav>
</div>