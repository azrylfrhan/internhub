@extends('layouts.admin')

@section('title', 'Manajemen Logbook')

@section('content')
<!-- Header -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Manajemen Logbook</h1>
    <p class="text-gray-600 dark:text-gray-300">Lihat dan kelola logbook peserta magang</p>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <!-- Peserta Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Peserta</label>
            <select id="filterPeserta" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Peserta</option>
                @foreach(\App\Models\User::where('role', 'magang')->get() as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tanggal Mulai -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai</label>
            <input type="date" id="filterTanggalMulai" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <!-- Tanggal Akhir -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Akhir</label>
            <input type="date" id="filterTanggalAkhir" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <!-- Search -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari</label>
            <input type="text" id="filterSearch" placeholder="Cari aktivitas..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
    </div>

    <!-- Buttons -->
    <div class="flex items-center gap-2">
        <button onclick="applyLogbookFilter()" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
            Terapkan Filter
        </button>
        <button onclick="resetLogbookFilter()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg text-sm font-medium hover:bg-gray-300 dark:hover:bg-gray-600">
            Reset
        </button>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">Total Logbook</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="stat-total">-</p>
    </div>
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">Logbook Bulan Ini</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Logbook::whereDate('created_at', '>=', now()->startOfMonth())->count() }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">Peserta Aktif</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\User::where('role', 'magang')->whereHas('logbooks')->distinct()->count() }}</p>
    </div>
</div>

<!-- Table -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto pb-2" style="-webkit-overflow-scrolling: touch;">
        <table class="min-w-full text-xs md:text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                <tr>
                    <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Peserta</th>
                    <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                    <th class="hidden md:table-cell px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Aktivitas</th>
                    <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Waktu</th>
                    <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="logbookTableBody" class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td colspan="5" class="px-3 md:px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 animate-spin mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>

    <div class="px-4 pb-4 md:px-6">
        <div class="mt-2 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <p id="logbook-pagination-info" class="text-xs text-gray-500 dark:text-gray-400">Menampilkan 0 data</p>
            <div class="flex items-center gap-2">
                <label for="logbook-page-size" class="text-xs text-gray-600 dark:text-gray-300">Per halaman</label>
                <select id="logbook-page-size" onchange="changeLogbookPageSize(this.value)" class="rounded-lg border border-gray-300 px-2 py-1 text-xs text-gray-700 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <button id="logbook-page-prev" onclick="changeLogbookPage(-1)" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700" disabled>Sebelumnya</button>
                <span id="logbook-page-indicator" class="text-xs text-gray-600 dark:text-gray-300">Hal. 1 / 1</span>
                <button id="logbook-page-next" onclick="changeLogbookPage(1)" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700" disabled>Berikutnya</button>
            </div>
        </div>
    </div>

<!-- Detail Modal -->
<div id="detailModal" class="hidden fixed inset-0 z-50 bg-black/50 p-4 items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-[90%] sm:w-full sm:max-w-lg md:max-w-2xl max-h-[85vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between sticky top-0 bg-white dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Logbook</h3>
                <button onclick="closeDetailModal()" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="modalContent" class="px-6 py-4 space-y-4 text-gray-900 dark:text-gray-200">
                <!-- Loaded dynamically -->
            </div>
        </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let logbookRows = [];
let logbookCurrentPage = 1;
let logbookPageSize = 10;

// Load logbook on page load
document.addEventListener('DOMContentLoaded', function() {
    applyLogbookFilter();
});

async function applyLogbookFilter() {
    const pesertaId = document.getElementById('filterPeserta').value;
    const tanggalMulai = document.getElementById('filterTanggalMulai').value;
    const tanggalAkhir = document.getElementById('filterTanggalAkhir').value;
    const search = document.getElementById('filterSearch').value;

    const params = new URLSearchParams();
    if (pesertaId) params.append('user_id', pesertaId);
    if (tanggalMulai) params.append('tanggal_mulai', tanggalMulai);
    if (tanggalAkhir) params.append('tanggal_akhir', tanggalAkhir);
    if (search) params.append('search', search);

    try {
        const response = await fetch(`/admin/logbook/data?${params.toString()}`, {
            headers: { 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            renderLogbookTable(data.rows);
            document.getElementById('stat-total').textContent = data.count;
        } else {
            showToast('error', 'Gagal memuat data: ' + (data.message || 'Unknown error'));
            console.error('Server response:', data);
        }
    } catch (error) {
        console.error('Error loading logbook:', error);
        showToast('error', 'Terjadi kesalahan saat memuat data logbook. Silakan refresh halaman.');
    }
}

function resetLogbookFilter() {
    document.getElementById('filterPeserta').value = '';
    document.getElementById('filterTanggalMulai').value = '';
    document.getElementById('filterTanggalAkhir').value = '';
    document.getElementById('filterSearch').value = '';
    applyLogbookFilter();
}

function renderLogbookTable(rows) {
    logbookRows = rows || [];
    logbookCurrentPage = 1;

    renderLogbookPage();
}

function renderLogbookPage() {
    const tbody = document.getElementById('logbookTableBody');

    if (logbookRows.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="px-3 md:px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                    Tidak ada data logbook yang ditemukan
                </td>
            </tr>
        `;
        updateLogbookPaginationInfo();
        return;
    }

    const totalPages = Math.max(1, Math.ceil(logbookRows.length / logbookPageSize));
    if (logbookCurrentPage > totalPages) logbookCurrentPage = totalPages;

    const start = (logbookCurrentPage - 1) * logbookPageSize;
    const end = start + logbookPageSize;
    const rows = logbookRows.slice(start, end);

    let html = '';
    rows.forEach(row => {
        const tanggal = new Date(row.tanggal).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
        const durasi = row.jam_mulai && row.jam_selesai ? `${row.jam_mulai} - ${row.jam_selesai}` : '-';
        
        html += `
            <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700/40 hover:bg-blue-50/60 dark:hover:bg-blue-900/20 transition-colors">
                <td class="px-3 md:px-6 py-3 md:py-4 whitespace-normal md:whitespace-nowrap">
                    <div class="text-xs md:text-sm font-medium text-gray-900 dark:text-gray-200">${row.peserta}</div>
                </td>
                <td class="px-3 md:px-6 py-3 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-600 dark:text-gray-400">
                    ${tanggal}
                </td>
                <td class="hidden md:table-cell px-3 md:px-6 py-3 md:py-4">
                    <div class="text-xs md:text-sm font-medium text-gray-900 dark:text-gray-200 truncate" title="${row.aktivitas}">${row.aktivitas}</div>
                </td>
                <td class="px-3 md:px-6 py-3 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-600 dark:text-gray-400">
                    ${durasi}
                </td>
                <td class="px-3 md:px-6 py-3 md:py-4 whitespace-nowrap text-xs md:text-sm">
                    <button onclick="openDetailModal(${row.id})" class="px-2 md:px-3 py-1 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded font-medium">
                        Detail
                    </button>
                </td>
            </tr>
        `;
    });

    tbody.innerHTML = html;
    updateLogbookPaginationInfo();
}

function updateLogbookPaginationInfo() {
    const total = logbookRows.length;
    const totalPages = Math.max(1, Math.ceil(total / logbookPageSize));
    const start = total === 0 ? 0 : ((logbookCurrentPage - 1) * logbookPageSize) + 1;
    const end = Math.min(logbookCurrentPage * logbookPageSize, total);

    document.getElementById('logbook-pagination-info').textContent = total === 0
        ? 'Menampilkan 0 data'
        : `Menampilkan ${start}-${end} dari ${total} data`;

    document.getElementById('logbook-page-indicator').textContent = `Hal. ${logbookCurrentPage} / ${totalPages}`;
    document.getElementById('logbook-page-prev').disabled = logbookCurrentPage <= 1 || total === 0;
    document.getElementById('logbook-page-next').disabled = logbookCurrentPage >= totalPages || total === 0;
}

function changeLogbookPage(delta) {
    const totalPages = Math.max(1, Math.ceil(logbookRows.length / logbookPageSize));
    const nextPage = logbookCurrentPage + delta;
    if (nextPage < 1 || nextPage > totalPages) return;
    logbookCurrentPage = nextPage;
    renderLogbookPage();
}

function changeLogbookPageSize(value) {
    const nextSize = Number(value);
    if (![10, 25, 50].includes(nextSize)) return;
    logbookPageSize = nextSize;
    logbookCurrentPage = 1;
    renderLogbookPage();
}

async function openDetailModal(logbookId) {
    const modalContent = document.getElementById('modalContent');
    
    try {
        const response = await fetch(`/api/logbook/${logbookId}`, {
            headers: { 'Accept': 'application/json' }
        });
        const data = await response.json();
        
        if (data.success) {
            const lb = data.logbook;
            const tanggal = new Date(lb.tanggal).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
            
            modalContent.innerHTML = `
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Peserta</label>
                        <p class="text-gray-900 dark:text-gray-100">${lb.user?.name || 'N/A'}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</label>
                        <p class="text-gray-900 dark:text-gray-100">${tanggal}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Aktivitas</label>
                        <p class="text-gray-900 dark:text-gray-100">${lb.aktivitas}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                        <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">${lb.deskripsi}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Jam Mulai</label>
                            <p class="text-gray-900 dark:text-gray-100">${lb.jam_mulai || '-'}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Jam Selesai</label>
                            <p class="text-gray-900 dark:text-gray-100">${lb.jam_selesai || '-'}</p>
                        </div>
                    </div>
                </div>
            `;
            
            const detailModal = document.getElementById('detailModal');
            detailModal.classList.remove('hidden');
            detailModal.classList.add('flex');
        } else {
            alert('Gagal memuat detail logbook');
        }
    } catch (error) {
        console.error('Error loading logbook detail:', error);
        alert('Terjadi kesalahan saat memuat detail logbook');
    }
}

function closeDetailModal() {
    const detailModal = document.getElementById('detailModal');
    detailModal.classList.remove('flex');
    detailModal.classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('detailModal');
    if (event.target === modal) {
        closeDetailModal();
    }
});
</script>
@endsection
