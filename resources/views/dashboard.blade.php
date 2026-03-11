@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
@php
    $today = now()->toDateString();

    $pesertaList = \App\Models\User::where('role', 'magang')
        ->orderBy('name')
        ->get();

    $totalPeserta = $pesertaList->count();

    $presensiHariIni = \App\Models\Presensi::whereDate('tanggal', $today)
        ->get()
        ->keyBy('user_id');

    $izinHariIni = \App\Models\Permission::where('status', 'approved')
        ->whereDate('start_date', '<=', $today)
        ->whereDate('end_date', '>=', $today)
        ->get()
        ->keyBy('user_id');

    $statusHariIniPerPeserta = $pesertaList->map(function ($peserta) use ($presensiHariIni, $izinHariIni) {
        $presensi = $presensiHariIni->get($peserta->id);
        $izin = $izinHariIni->get($peserta->id);

        if ($izin) {
            return 'izin';
        }

        return $presensi->status ?? 'belum_absen';
    });

    $hadirHariIni = $statusHariIniPerPeserta
        ->filter(fn ($status) => in_array($status, ['hadir', 'terlambat'], true))
        ->count();

    $izinAlpaHariIni = $statusHariIniPerPeserta
        ->filter(fn ($status) => in_array($status, ['izin', 'alpa'], true))
        ->count();

    $logbookMasukHariIni = \App\Models\Logbook::whereDate('tanggal', $today)->count();

    $recentPresensis = \App\Models\Presensi::with('user')
        ->latest()
        ->take(5)
        ->get();
@endphp

<div class="w-full space-y-6">
    <div>
        <h1 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">Dashboard Admin</h1>
        <p class="text-sm text-gray-600 dark:text-gray-300">Pantau kehadiran peserta, tren presensi, dan aktivitas terbaru dalam satu halaman.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-blue-200 bg-white p-5 shadow-sm dark:border-blue-800 dark:bg-gray-800">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-700 dark:text-blue-300">Total Peserta Magang</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalPeserta }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Peserta aktif terdaftar</p>
                </div>
                <div class="rounded-lg bg-blue-50 p-2.5 dark:bg-blue-900/30">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5m10 0v-2a3 3 0 00-3-3H10a3 3 0 00-3 3v2m10 0H7m10-11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-blue-200 bg-white p-5 shadow-sm dark:border-blue-800 dark:bg-gray-800">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-700 dark:text-blue-300">Hadir Hari Ini</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $hadirHariIni }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Status hadir dan terlambat</p>
                </div>
                <div class="rounded-lg bg-blue-50 p-2.5 dark:bg-blue-900/30">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-blue-200 bg-white p-5 shadow-sm dark:border-blue-800 dark:bg-gray-800">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-700 dark:text-blue-300">Izin / Alpa</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $izinAlpaHariIni }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Akumulasi hari ini</p>
                </div>
                <div class="rounded-lg bg-blue-50 p-2.5 dark:bg-blue-900/30">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M6.938 4h10.124c1.727 0 2.808 1.87 1.94 3.376L13.94 16.5c-.866 1.5-3.013 1.5-3.88 0L4.998 7.376C4.13 5.87 5.21 4 6.938 4z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-blue-200 bg-white p-5 shadow-sm dark:border-blue-800 dark:bg-gray-800">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-700 dark:text-blue-300">Logbook Masuk</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $logbookMasukHariIni }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Entri logbook hari ini</p>
                </div>
                <div class="rounded-lg bg-blue-50 p-2.5 dark:bg-blue-900/30">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="xl:col-span-2 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Grafik Tren Kehadiran</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Data kehadiran berdasarkan status presensi.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="trend-btn rounded-lg border border-blue-200 px-3 py-1.5 text-xs font-semibold text-blue-700 dark:border-blue-700 dark:text-blue-300" data-range="7" onclick="loadTrend('7', this)">7 Hari</button>
                    <button type="button" class="trend-btn rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:border-gray-600 dark:text-gray-200" data-range="30" onclick="loadTrend('30', this)">30 Hari</button>
                    <button type="button" class="trend-btn rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:border-gray-600 dark:text-gray-200" data-range="month" onclick="loadTrend('month', this)">Bulan Ini</button>
                </div>
            </div>

            <div id="trendLoading" class="hidden items-center justify-center py-10">
                <svg class="h-8 w-8 animate-spin text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </div>
            <div class="h-72">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Rekap Hari Ini</h2>
                    <p id="rekapTanggal" class="text-xs text-gray-500 dark:text-gray-400">Memuat data...</p>
                </div>
                <button type="button" onclick="loadRekap()" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Refresh</button>
            </div>

            <div class="mb-4 grid grid-cols-3 gap-2 text-center">
                <div class="rounded-lg bg-blue-50 px-2 py-3 dark:bg-blue-900/30">
                    <p class="text-[11px] font-semibold text-blue-700 dark:text-blue-300">Hadir</p>
                    <p id="rekapTotalHadir" class="text-xl font-bold text-blue-700 dark:text-blue-200">-</p>
                </div>
                <div class="rounded-lg bg-rose-50 px-2 py-3 dark:bg-rose-900/30">
                    <p class="text-[11px] font-semibold text-rose-700 dark:text-rose-300">Belum</p>
                    <p id="rekapTotalBelum" class="text-xl font-bold text-rose-700 dark:text-rose-200">-</p>
                </div>
                <div class="rounded-lg bg-gray-100 px-2 py-3 dark:bg-gray-700">
                    <p class="text-[11px] font-semibold text-gray-700 dark:text-gray-300">Persen</p>
                    <p id="rekapPersen" class="text-xl font-bold text-gray-800 dark:text-gray-100">-</p>
                </div>
            </div>

            <div class="mb-3 flex items-center gap-2">
                <button type="button" id="btnTabHadir" onclick="switchRekapTab('hadir')" class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white">Hadir</button>
                <button type="button" id="btnTabBelum" onclick="switchRekapTab('belum')" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:border-gray-600 dark:text-gray-200">Belum Hadir</button>
                <input id="rekapSearch" type="text" placeholder="Cari nama..." class="ml-auto w-36 rounded-lg border border-gray-300 px-2 py-1.5 text-xs text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-500/30" />
            </div>

            <div id="rekapList" class="max-h-72 space-y-2 overflow-y-auto pr-1"></div>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4 dark:border-gray-700">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Ringkasan Kehadiran Hari Ini</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal {{ \Carbon\Carbon::parse($today)->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/60">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Nama Peserta</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Jam Masuk</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Jam Pulang</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($pesertaList as $peserta)
                        @php
                            $presensi = $presensiHariIni->get($peserta->id);
                            $izin = $izinHariIni->get($peserta->id);
                            $status = $izin ? 'izin' : ($presensi->status ?? 'belum_absen');
                            $keterangan = $izin ? $izin->reason : ($presensi->keterangan ?? '-');
                        @endphp
                        <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700/30">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $peserta->name }}</td>
                            <td class="px-4 py-3">
                                @if($status === 'hadir')
                                    <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">Hadir</span>
                                @elseif($status === 'terlambat')
                                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">Terlambat</span>
                                @elseif($status === 'izin')
                                    <span class="inline-flex rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-semibold text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">Izin</span>
                                @elseif($status === 'alpa')
                                    <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">Alpa</span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">Belum Absen</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $presensi->jam_masuk ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $presensi->jam_pulang ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $keterangan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">Belum ada peserta magang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="xl:col-span-2 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Aktivitas Presensi Terbaru</h2>
                <span class="text-xs text-gray-500 dark:text-gray-400">5 terbaru · refresh untuk update</span>
            </div>

            <div class="max-h-72 overflow-y-auto space-y-3 pr-1">
                @forelse($recentPresensis as $presensi)
                    <div class="flex items-start justify-between gap-3 rounded-lg border border-gray-200 px-3 py-3 dark:border-gray-700">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $presensi->user->name ?? 'Peserta' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($presensi->tanggal)->format('d M Y') }} | Masuk: {{ $presensi->jam_masuk ?? '-' }} | Pulang: {{ $presensi->jam_pulang ?? '-' }}</p>
                        </div>
                        <span class="inline-flex shrink-0 rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">{{ ucfirst($presensi->status) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada aktivitas presensi.</p>
                @endforelse
            </div>
        </div>

        <div class="flex flex-col rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Menu Admin</h2>
            <div class="flex flex-col gap-3">
                <a href="{{ route('admin.peserta.detail') }}" class="flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700">Kelola Peserta <span>&rsaquo;</span></a>
                <a href="{{ route('admin.laporan.presensi') }}" class="flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700">Laporan Presensi <span>&rsaquo;</span></a>
                <a href="{{ route('admin.logbook') }}" class="flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700">Manajemen Logbook <span>&rsaquo;</span></a>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700">Pengaturan <span>&rsaquo;</span></a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
let trendChartInstance = null;
let currentRekapTab = 'hadir';
let rekapCache = { hadir: [], belum_hadir: [] };

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text || '';
    return div.innerHTML;
}

function statusBadge(status) {
    const map = {
        hadir: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
        terlambat: 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
        izin: 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300',
        alpa: 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300',
    };
    const color = map[status] || 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300';
    return `<span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ${color}">${escapeHtml((status || 'belum').toString())}</span>`;
}

function renderRekapList() {
    const container = document.getElementById('rekapList');
    const search = (document.getElementById('rekapSearch').value || '').toLowerCase();
    const source = currentRekapTab === 'hadir' ? rekapCache.hadir : rekapCache.belum_hadir;

    const rows = source.filter(item => (item.name || '').toLowerCase().includes(search));

    if (!rows.length) {
        container.innerHTML = '<p class="rounded-lg border border-dashed border-gray-300 p-3 text-xs text-gray-500 dark:border-gray-600 dark:text-gray-400">Tidak ada data untuk ditampilkan.</p>';
        return;
    }

    container.innerHTML = rows.map(item => {
        if (currentRekapTab === 'hadir') {
            const detailText = item.status === 'izin'
                ? `Keterangan: ${escapeHtml(item.keterangan || '-')}`
                : `Masuk: ${escapeHtml(item.jam_masuk || '-')} | Pulang: ${escapeHtml(item.jam_pulang || '-')}`;

            return `
                <div class="rounded-lg border border-gray-200 px-3 py-2.5 dark:border-gray-700">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">${escapeHtml(item.name)}</p>
                        ${statusBadge(item.status)}
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">${detailText}</p>
                </div>
            `;
        }

        return `
            <div class="rounded-lg border border-gray-200 px-3 py-2.5 dark:border-gray-700">
                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">${escapeHtml(item.name)}</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">${escapeHtml(item.email || '-')}</p>
            </div>
        `;
    }).join('');
}

function switchRekapTab(tab) {
    currentRekapTab = tab;

    const hadirBtn = document.getElementById('btnTabHadir');
    const belumBtn = document.getElementById('btnTabBelum');

    if (tab === 'hadir') {
        hadirBtn.className = 'rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white';
        belumBtn.className = 'rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:border-gray-600 dark:text-gray-200';
    } else {
        belumBtn.className = 'rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white';
        hadirBtn.className = 'rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:border-gray-600 dark:text-gray-200';
    }

    renderRekapList();
}

async function loadRekap() {
    const url = @js(route('admin.presensi.rekap'));

    try {
        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            }
        });
        const data = await response.json();

        if (!data.success) throw new Error(data.message || 'Gagal memuat rekap');

        rekapCache.hadir = data.hadir || [];
        rekapCache.belum_hadir = data.belum_hadir || [];

        document.getElementById('rekapTanggal').textContent = data.tanggal || 'Hari ini';
        document.getElementById('rekapTotalHadir').textContent = data.statistik?.total_hadir ?? 0;
        document.getElementById('rekapTotalBelum').textContent = data.statistik?.total_belum_hadir ?? 0;
        document.getElementById('rekapPersen').textContent = `${data.statistik?.persentase_kehadiran ?? 0}%`;

        renderRekapList();
    } catch (error) {
        document.getElementById('rekapList').innerHTML = `<p class="rounded-lg border border-red-200 bg-red-50 p-3 text-xs text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-200">${escapeHtml(error.message || 'Terjadi kesalahan saat memuat rekap.')}</p>`;
    }
}

async function loadTrend(range, buttonEl = null) {
    const loading = document.getElementById('trendLoading');
    const trendBtns = document.querySelectorAll('.trend-btn');
    const url = @js(route('admin.dashboard.trend'));

    trendBtns.forEach(btn => {
        btn.className = 'trend-btn rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:border-gray-600 dark:text-gray-200';
    });

    if (buttonEl) {
        buttonEl.className = 'trend-btn rounded-lg border border-blue-200 px-3 py-1.5 text-xs font-semibold text-blue-700 dark:border-blue-700 dark:text-blue-300';
    }

    loading.classList.remove('hidden');
    loading.classList.add('flex');

    try {
        const response = await fetch(`${url}?range=${encodeURIComponent(range)}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            }
        });

        const data = await response.json();
        if (!data.success) throw new Error('Gagal memuat tren');

        const ctx = document.getElementById('trendChart').getContext('2d');

        if (trendChartInstance) {
            trendChartInstance.destroy();
        }

        trendChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels || [],
                datasets: [
                    {
                        label: 'Hadir',
                        data: data.series?.hadir || [],
                        borderColor: '#2563EB',
                        backgroundColor: 'rgba(37,99,235,0.15)',
                        tension: 0.3,
                        fill: true,
                    },
                    {
                        label: 'Terlambat',
                        data: data.series?.terlambat || [],
                        borderColor: '#F59E0B',
                        backgroundColor: 'rgba(245,158,11,0.15)',
                        tension: 0.3,
                        fill: true,
                    },
                    {
                        label: 'Izin',
                        data: data.series?.izin || [],
                        borderColor: '#4F46E5',
                        backgroundColor: 'rgba(79,70,229,0.15)',
                        tension: 0.3,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        }
                    }
                }
            }
        });
    } catch (error) {
        if (trendChartInstance) {
            trendChartInstance.destroy();
            trendChartInstance = null;
        }

        const ctx = document.getElementById('trendChart').getContext('2d');
        trendChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Gagal memuat data'],
                datasets: [{
                    label: 'Tren Kehadiran',
                    data: [0],
                    backgroundColor: 'rgba(239,68,68,0.2)',
                    borderColor: '#EF4444',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    } finally {
        loading.classList.add('hidden');
        loading.classList.remove('flex');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('rekapSearch');
    if (search) {
        search.addEventListener('input', renderRekapList);
    }

    loadRekap();

    const defaultTrendBtn = document.querySelector('.trend-btn[data-range="7"]');
    loadTrend('7', defaultTrendBtn);
});
</script>
@endsection
