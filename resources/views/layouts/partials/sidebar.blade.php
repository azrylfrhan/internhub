<aside
    id="sidebar"
    class="fixed inset-y-0 left-0 top-0 z-40 bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-all duration-300 ease-in-out border-r border-gray-200 dark:border-gray-800"
    :class="(sidebarExpanded ? 'w-64' : 'w-20') + (isMobile ? (sidebarExpanded ? ' translate-x-0' : ' -translate-x-full') : ' translate-x-0')"
    @mouseenter="sidebarExpanded = true"
    @mouseleave="sidebarExpanded = false"
    @click="if (!isMobile) sidebarExpanded = true"
>
    <!-- Logo Section -->
    <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 p-4">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/95">
                <img src="/logo-bps.png" alt="Logo InternHub" class="h-8 w-auto" loading="lazy" style="max-width:32px;max-height:32px;object-fit:contain;" />
            </div>
            <span x-show="sidebarExpanded" x-transition.opacity.duration.200ms class="sidebar-logo-text text-xl font-bold text-gray-900 dark:text-white">InternHub</span>
        </div>
        <button @click="sidebarExpanded = false" class="p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white md:hidden">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 px-3">
        <ul class="space-y-2">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}" class="menu-item group {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-200 dark:bg-blue-500/20 dark:text-blue-200 dark:ring-blue-400/40' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}" :class="(sidebarExpanded || isMobile) ? '' : 'justify-center px-2'">
                    <svg class="h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-blue-700 dark:text-blue-300' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span x-show="sidebarExpanded" x-transition.opacity.duration.150ms class="menu-item-text">Dashboard</span>
                </a>
            </li>

            <!-- Detail Peserta -->
            <li>
                <a href="{{ route('admin.peserta.detail') }}" class="menu-item group {{ request()->routeIs('admin.peserta.detail') || request()->routeIs('admin.peserta.kalender') ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-200 dark:bg-blue-500/20 dark:text-blue-200 dark:ring-blue-400/40' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}" :class="(sidebarExpanded || isMobile) ? '' : 'justify-center px-2'">
                    <svg class="h-5 w-5 {{ request()->routeIs('admin.peserta.detail') || request()->routeIs('admin.peserta.kalender') ? 'text-blue-700 dark:text-blue-300' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11c1.654 0 3-1.346 3-3s-1.346-3-3-3-3 1.346-3 3 1.346 3 3 3zM6 11c1.654 0 3-1.346 3-3S7.654 5 6 5 3 6.346 3 8s1.346 3 3 3zm0 2c-2.206 0-4 1.794-4 4v1h8v-1c0-2.206-1.794-4-4-4zm10 0c-1.195 0-2.27.524-3.012 1.353A4.97 4.97 0 0115 18v1h8v-1c0-2.206-1.794-4-4-4z"></path>
                    </svg>
                    <span x-show="sidebarExpanded" x-transition.opacity.duration.150ms class="menu-item-text">Peserta Magang</span>
                </a>
            </li>

            <!-- Laporan Presensi -->
            <li>
                <a href="{{ route('admin.laporan.presensi') }}" class="menu-item group {{ request()->routeIs('admin.laporan.presensi') ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-200 dark:bg-blue-500/20 dark:text-blue-200 dark:ring-blue-400/40' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}" :class="(sidebarExpanded || isMobile) ? '' : 'justify-center px-2'">
                    <svg class="h-5 w-5 {{ request()->routeIs('admin.laporan.presensi') ? 'text-blue-700 dark:text-blue-300' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm6 0V5a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span x-show="sidebarExpanded" x-transition.opacity.duration.150ms class="menu-item-text">Laporan Presensi</span>
                </a>
            </li>

            <!-- Logbook -->
            <li>
                <a href="{{ route('admin.logbook') }}" class="menu-item group {{ request()->routeIs('admin.logbook*') ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-200 dark:bg-blue-500/20 dark:text-blue-200 dark:ring-blue-400/40' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}" :class="(sidebarExpanded || isMobile) ? '' : 'justify-center px-2'">
                    <svg class="h-5 w-5 {{ request()->routeIs('admin.logbook*') ? 'text-blue-700 dark:text-blue-300' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span x-show="sidebarExpanded" x-transition.opacity.duration.150ms class="menu-item-text">Manajemen Logbook</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.permissions.index') }}" class="menu-item group {{ request()->routeIs('admin.permissions.*') ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-200 dark:bg-blue-500/20 dark:text-blue-200 dark:ring-blue-400/40' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}" :class="(sidebarExpanded || isMobile) ? '' : 'justify-center px-2'">
                    <svg class="h-5 w-5 {{ request()->routeIs('admin.permissions.*') ? 'text-blue-700 dark:text-blue-300' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"></path>
                    </svg>
                    <span x-show="sidebarExpanded" x-transition.opacity.duration.150ms class="menu-item-text">Input & Manajemen Izin</span>
                </a>
            </li>

            @if(Auth::user()?->role === 'admin')
            <li>
                <a href="{{ route('admin.management.index') }}" class="menu-item group {{ request()->routeIs('admin.management.*') ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-200 dark:bg-blue-500/20 dark:text-blue-200 dark:ring-blue-400/40' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}" :class="(sidebarExpanded || isMobile) ? '' : 'justify-center px-2'">
                    <svg class="h-5 w-5 {{ request()->routeIs('admin.management.*') ? 'text-blue-700 dark:text-blue-300' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5m10 0v-5a3 3 0 00-3-3h-4a3 3 0 00-3 3v5m10 0H7m5-12a3 3 0 110 6 3 3 0 010-6z"></path>
                    </svg>
                    <span x-show="sidebarExpanded" x-transition.opacity.duration.150ms class="menu-item-text">Manajemen Admin/Mentor</span>
                </a>
            </li>
            @endif

            @if(Auth::user()?->role === 'admin')
            <!-- Pengaturan -->
            <li>
                <a href="{{ route('admin.settings.index') }}" class="menu-item group {{ request()->routeIs('admin.settings.*') ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-200 dark:bg-blue-500/20 dark:text-blue-200 dark:ring-blue-400/40' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}" :class="(sidebarExpanded || isMobile) ? '' : 'justify-center px-2'">
                    <svg class="h-5 w-5 {{ request()->routeIs('admin.settings.*') ? 'text-blue-700 dark:text-blue-300' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.983 5.5c.573-1.91 3.27-1.91 3.843 0a1.99 1.99 0 002.985 1.132c1.73-1.006 3.636.9 2.63 2.63a1.99 1.99 0 001.132 2.985c1.91.573 1.91 3.27 0 3.843a1.99 1.99 0 00-1.132 2.985c1.006 1.73-.9 3.636-2.63 2.63a1.99 1.99 0 00-2.985 1.132c-.573 1.91-3.27 1.91-3.843 0a1.99 1.99 0 00-2.985-1.132c-1.73 1.006-3.636-.9-2.63-2.63a1.99 1.99 0 00-1.132-2.985c-1.91-.573-1.91-3.27 0-3.843a1.99 1.99 0 001.132-2.985c-1.006-1.73.9-3.636 2.63-2.63A1.99 1.99 0 0011.983 5.5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 14a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span x-show="sidebarExpanded" x-transition.opacity.duration.150ms class="menu-item-text">Pengaturan</span>
                </a>
            </li>
            @endif
        </ul>
    </nav>
</aside>