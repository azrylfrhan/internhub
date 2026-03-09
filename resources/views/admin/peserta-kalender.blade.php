@extends('layouts.admin')

@section('title', 'Kalender Peserta')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <a href="{{ route('admin.peserta.detail') }}" class="hover:text-blue-600">Detail Peserta</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Kalender</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">Kalender Absensi Peserta</h1>
</div>

@php
    $totalHadirPeserta = \App\Models\Presensi::where('user_id', $user->id)
        ->whereMonth('tanggal', now()->month)
        ->whereYear('tanggal', now()->year)
        ->whereIn('status', ['hadir', 'terlambat'])
        ->count();

    $totalIzinAlpaPeserta = \App\Models\Presensi::where('user_id', $user->id)
        ->whereMonth('tanggal', now()->month)
        ->whereYear('tanggal', now()->year)
        ->whereIn('status', ['izin', 'alpa'])
        ->count();

    $totalLogbookPeserta = \App\Models\Logbook::where('user_id', $user->id)
        ->whereMonth('tanggal', now()->month)
        ->whereYear('tanggal', now()->year)
        ->count();
@endphp

<!-- Info Peserta -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
        <div class="flex items-center gap-4">
        <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center shrink-0">
            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h2>
            <p class="text-gray-600 dark:text-gray-300">{{ $user->email }}</p>
            <div class="mt-2 inline-flex items-center rounded-full bg-blue-50 dark:bg-blue-900/30 px-3 py-1 text-xs font-semibold text-blue-700 dark:text-blue-300">
                Peserta Magang InternHub
            </div>
        </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-sm w-full lg:max-w-3xl">
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-2">
                <p class="text-xs text-gray-500 dark:text-gray-400">Instansi / Kampus</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $user->instansi ?: '-' }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-2">
                <p class="text-xs text-gray-500 dark:text-gray-400">No. Telepon</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $user->nomor_telepon ?: '-' }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-2 sm:col-span-2 lg:col-span-1">
                <p class="text-xs text-gray-500 dark:text-gray-400">Durasi Magang</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">
                    @if($user->tanggal_mulai && $user->tanggal_selesai)
                        {{ \Carbon\Carbon::parse($user->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($user->tanggal_selesai)->format('d M Y') }}
                    @else
                        -
                    @endif
                </p>
            </div>
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-2 sm:col-span-2 lg:col-span-3">
                <p class="text-xs text-gray-500 dark:text-gray-400">Alamat</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $user->alamat ?: '-' }}</p>
            </div>
        </div>
    </div>

    <div class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="rounded-lg border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/20 px-4 py-3">
            <p class="text-xs text-blue-700 dark:text-blue-300">Hadir Bulan Ini</p>
            <p class="text-2xl font-bold text-blue-800 dark:text-blue-200">{{ $totalHadirPeserta }}</p>
        </div>
        <div class="rounded-lg border border-orange-200 dark:border-orange-800 bg-orange-50 dark:bg-orange-900/20 px-4 py-3">
            <p class="text-xs text-orange-700 dark:text-orange-300">Izin/Alpa Bulan Ini</p>
            <p class="text-2xl font-bold text-orange-800 dark:text-orange-200">{{ $totalIzinAlpaPeserta }}</p>
        </div>
        <div class="rounded-lg border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20 px-4 py-3">
            <p class="text-xs text-green-700 dark:text-green-300">Logbook Bulan Ini</p>
            <p class="text-2xl font-bold text-green-800 dark:text-green-200">{{ $totalLogbookPeserta }}</p>
        </div>
    </div>
</div>

<!-- Kalender Absensi -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6 relative">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg md:text-xl font-semibold text-gray-900">Kalender Absensi</h3>
        <div class="flex items-center space-x-3">
            <button type="button" onclick="previousMonth()" class="p-1 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <span id="calendar-month-year" class="text-sm md:text-base text-gray-700 font-semibold min-w-[140px] text-center"></span>
            <button type="button" onclick="nextMonth()" class="p-1 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
    
    <!-- Loading overlay -->
    <div id="calendar-loading" class="hidden absolute inset-0 bg-white bg-opacity-75 items-center justify-center rounded-lg z-10">
        <div class="flex flex-col items-center">
            <svg class="w-10 h-10 text-blue-600 animate-spin mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <p class="text-sm text-gray-600">Memuat kalender...</p>
        </div>
    </div>
    
    <div class="grid grid-cols-7 gap-1 md:gap-2">
        <div class="text-center font-semibold text-xs md:text-sm text-gray-600 py-2 md:py-3">Min</div>
        <div class="text-center font-semibold text-xs md:text-sm text-gray-600 py-2 md:py-3">Sen</div>
        <div class="text-center font-semibold text-xs md:text-sm text-gray-600 py-2 md:py-3">Sel</div>
        <div class="text-center font-semibold text-xs md:text-sm text-gray-600 py-2 md:py-3">Rab</div>
        <div class="text-center font-semibold text-xs md:text-sm text-gray-600 py-2 md:py-3">Kam</div>
        <div class="text-center font-semibold text-xs md:text-sm text-gray-600 py-2 md:py-3">Jum</div>
        <div class="text-center font-semibold text-xs md:text-sm text-gray-600 py-2 md:py-3">Sab</div>
        
        <div id="calendar-container" class="contents"></div>
    </div>
    
    <div class="mt-6 pt-4 border-t border-gray-200 grid grid-cols-2 md:grid-cols-4 gap-3 text-xs md:text-sm">
        <div class="flex items-center space-x-2">
            <div class="w-4 h-4 bg-green-100 rounded"></div>
            <span class="text-gray-700">Hadir</span>
        </div>
        <div class="flex items-center space-x-2">
            <div class="w-4 h-4 bg-orange-100 rounded"></div>
            <span class="text-gray-700">Terlambat</span>
        </div>
        <div class="flex items-center space-x-2">
            <div class="w-4 h-4 bg-gray-200 rounded"></div>
            <span class="text-gray-700">Tidak Hadir</span>
        </div>
        <div class="flex items-center space-x-2">
            <div class="w-4 h-4 bg-gray-100 rounded"></div>
            <span class="text-gray-700">Belum Ada</span>
        </div>
    </div>
</div>

<!-- Modal Detail Absensi -->
<div id="modal-detail-absensi" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-[90%] sm:w-full sm:max-w-lg md:max-w-2xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-white dark:bg-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center rounded-t-2xl">
            <h3 class="text-lg md:text-xl font-semibold text-gray-900 dark:text-white" id="modal-tanggal">Tanggal</h3>
            <button type="button" onclick="closeModalDetail()" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="px-6 py-4 space-y-4" id="modal-content"></div>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const currentUserId = {{ $user->id }};
let currentYear = new Date().getFullYear();
let currentMonth = new Date().getMonth() + 1;

renderCalendar(currentYear, currentMonth);

async function renderCalendar(year, month) {
    const calendarLoading = document.getElementById('calendar-loading');
    calendarLoading.classList.remove('hidden');
    calendarLoading.classList.add('flex');

    try {
        const response = await fetch(`/admin/presensi/peserta/${currentUserId}?year=${year}&month=${month}`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const data = await response.json();

        if (!data.success) return;

        currentYear = data.year;
        currentMonth = data.month_num;

        const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        document.getElementById('calendar-month-year').textContent = `${monthNames[currentMonth - 1]} ${currentYear}`;

        const firstDay = new Date(currentYear, currentMonth - 1, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();

        let html = '';
        for (let i = 0; i < firstDay; i++) {
            html += '<div class="text-center py-1 md:py-2"></div>';
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${currentYear}-${String(currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const presensi = data.calendar[dateStr];

            let bgColor = 'bg-gray-100 text-gray-600';
            let statusText = '';
            let hoverClass = 'hover:shadow-md';

            if (presensi) {
                hoverClass = 'hover:shadow-lg cursor-pointer';
                if (presensi.status === 'hadir') {
                    bgColor = 'bg-green-100 text-green-700';
                    statusText = '✓';
                } else if (presensi.status === 'terlambat') {
                    bgColor = 'bg-orange-100 text-orange-700';
                    statusText = '⏱';
                } else if (presensi.status === 'izin' || presensi.status === 'alpa') {
                    bgColor = 'bg-gray-200 text-gray-700';
                    statusText = '—';
                }
            }

            html += `
                <button type="button"
                    class="calendar-day w-full aspect-square md:aspect-auto md:h-14 flex flex-col items-center justify-center p-1 md:p-2 rounded-lg ${bgColor} ${hoverClass} transition font-semibold text-xs md:text-sm"
                    onclick="openModalDetail('${dateStr}')"
                    ${presensi ? '' : 'disabled'}>
                    <div class="text-sm md:text-base">${day}</div>
                    ${presensi ? `<div class="text-xs md:text-xs">${statusText}</div>` : ''}
                </button>
            `;
        }

        document.getElementById('calendar-container').innerHTML = html;
    } catch (error) {
        console.error('Error rendering calendar:', error);
    } finally {
        calendarLoading.classList.remove('flex');
        calendarLoading.classList.add('hidden');
    }
}

async function openModalDetail(dateStr) {
    const detailModal = document.getElementById('modal-detail-absensi');
    detailModal.classList.remove('hidden');
    detailModal.classList.add('flex');
    document.getElementById('modal-tanggal').textContent = 'Memuat...';
    document.getElementById('modal-content').innerHTML = `
        <div class="flex flex-col items-center justify-center py-8">
            <svg class="w-10 h-10 text-blue-600 animate-spin mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <p class="text-gray-500 text-sm">Memuat detail absensi...</p>
        </div>
    `;

    try {
        const response = await fetch(`/admin/presensi/peserta/${currentUserId}/tanggal/${dateStr}`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const data = await response.json();

        if (!data.success) {
            closeModalDetail();
            return;
        }

        document.getElementById('modal-tanggal').textContent = data.tanggal;

        let content = '';
        if (data.presensi) {
            const status = data.presensi.status;
            let statusBgClass = status === 'hadir' ? 'bg-green-100' : status === 'terlambat' ? 'bg-orange-100' : 'bg-gray-100';
            let statusTextClass = status === 'hadir' ? 'text-green-800' : status === 'terlambat' ? 'text-orange-800' : 'text-gray-800';

            content += `
                <div class="space-y-4 md:space-y-3">
                    <div class="border-b pb-3 md:pb-3">
                        <h4 class="font-semibold text-gray-900 mb-2 text-sm md:text-base">Status Absensi</h4>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs md:text-sm font-medium ${statusBgClass} ${statusTextClass}">
                            ${status.charAt(0).toUpperCase() + status.slice(1)}
                        </span>
                    </div>
                    <div class="border-b pb-3 md:pb-3">
                        <h4 class="font-semibold text-gray-900 mb-2 text-sm md:text-base">Waktu Absensi</h4>
                        <p class="text-xs md:text-sm text-gray-600 mb-1">Masuk: <span class="font-medium">${data.presensi.jam_masuk || '—'}</span></p>
                        <p class="text-xs md:text-sm text-gray-600">Pulang: <span class="font-medium">${data.presensi.jam_pulang || '—'}</span></p>
                    </div>
                </div>
            `;
        } else {
            content += '<p class="text-gray-600 text-xs md:text-sm text-center py-4">Belum ada absensi pada hari ini</p>';
        }

        if (data.logbook) {
            content += `
                <div class="border-t pt-4 md:pt-3">
                    <h4 class="font-semibold text-gray-900 mb-2 text-sm md:text-base">Catatan Logbook</h4>
                    <p class="text-xs md:text-sm text-gray-600 leading-relaxed">${data.logbook.kegiatan}</p>
                </div>
            `;
        } else if (data.presensi) {
            content += '<p class="text-gray-500 text-xs md:text-sm text-center py-4 border-t">Belum ada catatan logbook</p>';
        }

        document.getElementById('modal-content').innerHTML = content;

    } catch (error) {
        console.error('Error opening modal:', error);
        closeModalDetail();
    }
}

function closeModalDetail() {
    const detailModal = document.getElementById('modal-detail-absensi');
    detailModal.classList.remove('flex');
    detailModal.classList.add('hidden');
}

function previousMonth() {
    currentMonth--;
    if (currentMonth < 1) {
        currentMonth = 12;
        currentYear--;
    }
    renderCalendar(currentYear, currentMonth);
}

function nextMonth() {
    currentMonth++;
    if (currentMonth > 12) {
        currentMonth = 1;
        currentYear++;
    }
    renderCalendar(currentYear, currentMonth);
}

document.addEventListener('click', function(e) {
    const modal = document.getElementById('modal-detail-absensi');
    if (e.target === modal) {
        closeModalDetail();
    }
});
</script>
@endsection
