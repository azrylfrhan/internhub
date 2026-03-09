@extends('layouts.admin')

@section('title', 'Laporan Presensi')

@section('content')
<div class="mx-auto w-full  space-y-6">
<div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Presensi</h1>
    <p class="text-gray-600 dark:text-gray-300">Lihat dan cetak laporan presensi peserta magang.</p>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
    <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Filter</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="text-sm text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
            <input type="date" id="start_date" class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-500/30" />
        </div>
        <div>
            <label class="text-sm text-gray-700 dark:text-gray-300">Tanggal Selesai</label>
            <input type="date" id="end_date" class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-500/30" />
        </div>
        <div>
            <label class="text-sm text-gray-700 dark:text-gray-300">Status</label>
            <select id="status" class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-500/30">
                <option value="">Semua</option>
                <option value="hadir">Hadir</option>
                <option value="terlambat">Terlambat</option>
                <option value="izin">Izin</option>
                <option value="alpa">Alpa</option>
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-700 dark:text-gray-300">Peserta</label>
            <select id="user_id" class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-500/30">
                <option value="">Semua</option>
                @foreach(\App\Models\User::where('role', 'magang')->orderBy('name')->get() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="mt-4 flex items-center gap-3">
        <button id="btn-apply" onclick="applyFilter()" class="rounded-lg bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700">Terapkan</button>
        <button id="btn-csv" onclick="exportCsv()" class="rounded-lg bg-green-600 px-4 py-2 text-white transition hover:bg-green-700">Export CSV</button>
        <button id="btn-print" onclick="exportPrint()" class="rounded-lg bg-orange-600 px-4 py-2 text-white transition hover:bg-orange-700">Export PDF</button>
    </div>
</div>

<div class="grid grid-cols-1 gap-4 md:grid-cols-3">
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
        <p class="text-sm text-gray-600 dark:text-gray-300">Total</p>
        <p id="stat-total" class="text-2xl font-semibold text-gray-900 dark:text-white">-</p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
        <p class="text-sm text-gray-600 dark:text-gray-300">Hadir</p>
        <p id="stat-hadir" class="text-2xl font-semibold text-green-700">-</p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
        <p class="text-sm text-gray-600 dark:text-gray-300">Terlambat</p>
        <p id="stat-terlambat" class="text-2xl font-semibold text-orange-700">-</p>
    </div>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Presensi</h3>
        <p id="range-label" class="text-sm text-gray-500 dark:text-gray-400"></p>
    </div>
    <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs md:text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                <tr>
                    <th class="px-3 py-2 text-left font-semibold">Tanggal</th>
                    <th class="px-3 py-2 text-left font-semibold">Nama</th>
                    <th class="hidden md:table-cell px-3 py-2 text-left font-semibold">Email</th>
                    <th class="px-3 py-2 text-left font-semibold">Status</th>
                    <th class="px-3 py-2 text-left font-semibold">Masuk</th>
                    <th class="hidden lg:table-cell px-3 py-2 text-left font-semibold">Pulang</th>
                </tr>
            </thead>
            <tbody id="data-body" class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr>
                    <td colspan="6" class="px-3 md:px-4 py-6 text-center text-gray-500 dark:text-gray-400">Silakan terapkan filter untuk melihat data</td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <p id="pagination-info" class="text-xs text-gray-500 dark:text-gray-400">Menampilkan 0 data</p>
        <div class="flex items-center gap-2">
            <label for="page-size" class="text-xs text-gray-600 dark:text-gray-300">Per halaman</label>
            <select id="page-size" onchange="changePageSize(this.value)" class="rounded-lg border border-gray-300 px-2 py-1 text-xs text-gray-700 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            <button id="page-prev" onclick="changePage(-1)" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700" disabled>Sebelumnya</button>
            <span id="page-indicator" class="text-xs text-gray-600 dark:text-gray-300">Hal. 1 / 1</span>
            <button id="page-next" onclick="changePage(1)" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700" disabled>Berikutnya</button>
        </div>
    </div>
</div>

<!-- Global Loading Overlay -->
<div id="global-loading" class="fixed inset-0 bg-white dark:bg-gray-900 bg-opacity-70 dark:bg-opacity-70 backdrop-blur-sm z-50 hidden">
    <div class="h-full w-full flex items-center justify-center">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <span id="global-loading-text" class="text-sm text-gray-700 dark:text-gray-300">Memuat...</span>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let pageSize = 10;
let currentRows = [];

function showGlobalLoading(text = 'Memuat...') {
    document.getElementById('global-loading-text').textContent = text;
    document.getElementById('global-loading').classList.remove('hidden');
}
function hideGlobalLoading() {
    document.getElementById('global-loading').classList.add('hidden');
}

function buildQuery() {
    const params = new URLSearchParams();
    const s = document.getElementById('start_date').value;
    const e = document.getElementById('end_date').value;
    const st = document.getElementById('status').value;
    const uid = document.getElementById('user_id').value;
    if (s) params.set('start_date', s);
    if (e) params.set('end_date', e);
    if (st) params.set('status', st);
    if (uid) params.set('user_id', uid);
    return params.toString();
}

async function applyFilter() {
    const btnApply = document.getElementById('btn-apply');
    const btnCsv = document.getElementById('btn-csv');
    const btnPrint = document.getElementById('btn-print');
    btnApply.disabled = true; btnCsv.disabled = true; btnPrint.disabled = true;
    const prevText = btnApply.textContent; btnApply.textContent = 'Memuat...';

    const qs = buildQuery();
    const tbody = document.getElementById('data-body');
    tbody.innerHTML = `<tr>
        <td colspan="6" class="px-4 py-8 text-center">
            <div class='flex items-center justify-center gap-2 text-gray-600'>
                <svg class='w-5 h-5 text-blue-600 animate-spin' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'></path>
                </svg>
                Memuat data...
            </div>
        </td>
    </tr>`;

    let data;
    try {
        const res = await fetch(`/admin/laporan/presensi/data?${qs}`, { headers: { 'Accept': 'application/json' }});
        data = await res.json();
    } catch (e) {
        alert('Gagal mengambil data. Periksa koneksi internet Anda.');
        btnApply.disabled = false; btnCsv.disabled = false; btnPrint.disabled = false; btnApply.textContent = prevText;
        return;
    }
    if (!data.success) {
        alert(data.message || 'Gagal memuat data');
        btnApply.disabled = false; btnCsv.disabled = false; btnPrint.disabled = false; btnApply.textContent = prevText;
        return;
    }
    document.getElementById('stat-total').textContent = data.stat.total;
    document.getElementById('stat-hadir').textContent = data.stat.hadir;
    document.getElementById('stat-terlambat').textContent = data.stat.terlambat;
    document.getElementById('range-label').textContent = `${data.range.start} s/d ${data.range.end}`;

    currentRows = data.rows || [];
    currentPage = 1;
    renderCurrentPage();

    btnApply.disabled = false; btnCsv.disabled = false; btnPrint.disabled = false; btnApply.textContent = prevText;
}

function renderCurrentPage() {
    const tbody = document.getElementById('data-body');

    if (!currentRows.length) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Tidak ada data</td></tr>';
        updatePaginationInfo();
        return;
    }

    const totalPages = Math.max(1, Math.ceil(currentRows.length / pageSize));
    if (currentPage > totalPages) currentPage = totalPages;

    const start = (currentPage - 1) * pageSize;
    const end = start + pageSize;
    const rows = currentRows.slice(start, end);

    tbody.innerHTML = '';
    for (const r of rows) {
        const tr = document.createElement('tr');
        tr.className = 'odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700/40 hover:bg-blue-50/60 dark:hover:bg-blue-900/20 transition-colors';
        tr.innerHTML = `
            <td class="px-3 py-2 text-gray-900 dark:text-gray-100 md:px-4">${r.tanggal}</td>
            <td class="px-3 py-2 text-gray-900 dark:text-gray-100 md:px-4">${r.nama}</td>
            <td class="hidden px-3 py-2 text-gray-600 dark:text-gray-300 md:table-cell md:px-4">${r.email}</td>
            <td class="px-3 py-2 text-gray-900 dark:text-gray-100 md:px-4">${r.status}</td>
            <td class="px-3 py-2 text-gray-900 dark:text-gray-100 md:px-4">${r.jam_masuk ?? ''}</td>
            <td class="hidden px-3 py-2 text-gray-900 dark:text-gray-100 lg:table-cell md:px-4">${r.jam_pulang ?? ''}</td>
        `;
        tbody.appendChild(tr);
    }

    updatePaginationInfo();
}

function updatePaginationInfo() {
    const total = currentRows.length;
    const totalPages = Math.max(1, Math.ceil(total / pageSize));
    const start = total === 0 ? 0 : ((currentPage - 1) * pageSize) + 1;
    const end = Math.min(currentPage * pageSize, total);

    document.getElementById('pagination-info').textContent = total === 0
        ? 'Menampilkan 0 data'
        : `Menampilkan ${start}-${end} dari ${total} data`;
    document.getElementById('page-indicator').textContent = `Hal. ${currentPage} / ${totalPages}`;
    document.getElementById('page-prev').disabled = currentPage <= 1 || total === 0;
    document.getElementById('page-next').disabled = currentPage >= totalPages || total === 0;
}

function changePage(delta) {
    const totalPages = Math.max(1, Math.ceil(currentRows.length / pageSize));
    const nextPage = currentPage + delta;
    if (nextPage < 1 || nextPage > totalPages) return;
    currentPage = nextPage;
    renderCurrentPage();
}

function changePageSize(value) {
    const nextSize = Number(value);
    if (![10, 25, 50].includes(nextSize)) return;
    pageSize = nextSize;
    currentPage = 1;
    renderCurrentPage();
}

function exportCsv() {
    const qs = buildQuery();
    showGlobalLoading('Menyiapkan CSV...');
    setTimeout(hideGlobalLoading, 800);
    window.open(`/admin/laporan/presensi/export/csv?${qs}`, '_blank');
}

function exportPrint() {
    const qs = buildQuery();
    showGlobalLoading('Menyiapkan tampilan cetak...');
    setTimeout(hideGlobalLoading, 800);
    window.open(`/admin/laporan/presensi/export/print?${qs}`, '_blank');
}
</script>
</div>
@endsection
