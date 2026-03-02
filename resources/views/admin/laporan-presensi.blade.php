@extends('layouts.admin')

@section('title', 'Laporan Presensi')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span>Laporan Presensi</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">Laporan Presensi</h1>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="text-sm text-gray-700">Tanggal Mulai</label>
            <input type="date" id="start_date" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg" />
        </div>
        <div>
            <label class="text-sm text-gray-700">Tanggal Selesai</label>
            <input type="date" id="end_date" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg" />
        </div>
        <div>
            <label class="text-sm text-gray-700">Status</label>
            <select id="status" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg">
                <option value="">Semua</option>
                <option value="hadir">Hadir</option>
                <option value="terlambat">Terlambat</option>
                <option value="izin">Izin</option>
                <option value="alpa">Alpa</option>
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-700">Peserta</label>
            <select id="user_id" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg">
                <option value="">Semua</option>
                @foreach(\App\Models\User::where('role', 'magang')->orderBy('name')->get() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="mt-4 flex items-center gap-3">
        <button id="btn-apply" onclick="applyFilter()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Terapkan</button>
        <button id="btn-csv" onclick="exportCsv()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Export CSV</button>
        <button id="btn-print" onclick="exportPrint()" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">Export PDF</button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-4 rounded-xl border border-gray-200">
        <p class="text-sm text-gray-600">Total</p>
        <p id="stat-total" class="text-2xl font-semibold">-</p>
    </div>
    <div class="bg-white p-4 rounded-xl border border-gray-200">
        <p class="text-sm text-gray-600">Hadir</p>
        <p id="stat-hadir" class="text-2xl font-semibold text-green-700">-</p>
    </div>
    <div class="bg-white p-4 rounded-xl border border-gray-200">
        <p class="text-sm text-gray-600">Terlambat</p>
        <p id="stat-terlambat" class="text-2xl font-semibold text-orange-700">-</p>
    </div>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Data Presensi</h3>
        <p id="range-label" class="text-sm text-gray-500"></p>
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

    tbody.innerHTML = '';
    if (!data.rows.length) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Tidak ada data</td></tr>';
        btnApply.disabled = false; btnCsv.disabled = false; btnPrint.disabled = false; btnApply.textContent = prevText;
        return;
    }
    for (const r of data.rows) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="px-4 py-2">${r.tanggal}</td>
            <td class="px-4 py-2">${r.nama}</td>
            <td class="px-4 py-2 text-gray-600">${r.email}</td>
            <td class="px-4 py-2">${r.status}</td>
            <td class="px-4 py-2">${r.jam_masuk ?? ''}</td>
            <td class="px-4 py-2">${r.jam_pulang ?? ''}</td>
        `;
        tbody.appendChild(tr);
    }

    btnApply.disabled = false; btnCsv.disabled = false; btnPrint.disabled = false; btnApply.textContent = prevText;
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
@endsection
