@extends('layouts.admin')

@section('title', 'Detail Peserta')

@section('content')
<div x-data="{ openAddPeserta: @js($errors->addPeserta->any()) }" class="w-full space-y-6">
<div class="mb-8">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Peserta Magang</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola data peserta, kontak, dan durasi magang dari satu halaman.</p>
        </div>
        <button
            type="button"
            @click="openAddPeserta = true"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Peserta
        </button>
    </div>
</div>

@php
    $arsipTahun = request('arsip_tahun');
    $arsipBulan = request('arsip_bulan');
    $allowedPerPage = [10, 25, 50];

    $aktifPerPage = (int) request('aktif_per_page', 10);
    if (!in_array($aktifPerPage, $allowedPerPage, true)) {
        $aktifPerPage = 10;
    }

    $arsipPerPage = (int) request('arsip_per_page', 10);
    if (!in_array($arsipPerPage, $allowedPerPage, true)) {
        $arsipPerPage = 10;
    }

    $pesertaList = \App\Models\User::where('role', 'magang')
        ->orderBy('name')
        ->paginate($aktifPerPage, ['*'], 'aktif_page');

    $arsipQuery = \App\Models\User::where(function ($q) {
        $q->where('role', 'alumni')
          ->orWhere(function ($q2) {
              $q2->where('role', 'magang')
                 ->whereNotNull('tanggal_selesai')
                 ->whereDate('tanggal_selesai', '<', now()->toDateString());
          });
    });

    if ($arsipTahun) {
        $arsipQuery->whereYear('tanggal_selesai', $arsipTahun);
    }

    if ($arsipBulan) {
        $arsipQuery->whereMonth('tanggal_selesai', $arsipBulan);
    }

    $arsipPesertaList = $arsipQuery
        ->orderByDesc('tanggal_selesai')
        ->orderBy('name')
        ->paginate($arsipPerPage, ['*'], 'arsip_page');

    $arsipTahunList = \App\Models\User::where(function ($q) {
            $q->where('role', 'alumni')
              ->orWhere(function ($q2) {
                  $q2->where('role', 'magang')
                     ->whereNotNull('tanggal_selesai')
                     ->whereDate('tanggal_selesai', '<', now()->toDateString());
              });
        })
        ->pluck('tanggal_selesai')
        ->filter()
        ->map(fn ($tanggal) => \Carbon\Carbon::parse($tanggal)->format('Y'))
        ->unique()
        ->sortDesc()
        ->values();
@endphp

@if(session('success'))
    <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/30 dark:text-green-200">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-200">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 md:p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Peserta Magang Aktif</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Gunakan tombol aksi untuk edit profil, lihat detail, atau nonaktifkan peserta.</p>
        </div>
        <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">Total: {{ $pesertaList->total() }} peserta</span>
    </div>

    <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 whitespace-nowrap">
                <tr>
                    <th class="px-3 md:px-4 py-2 text-left font-semibold">Nama</th>
                    <th class="hidden md:table-cell px-3 md:px-4 py-2 text-left font-semibold">Email</th>
                    <th class="hidden lg:table-cell px-3 md:px-4 py-2 text-left font-semibold">Instansi</th>
                    <th class="hidden lg:table-cell px-3 md:px-4 py-2 text-left font-semibold">Kontak</th>
                    <th class="hidden xl:table-cell px-3 md:px-4 py-2 text-left font-semibold">Durasi Magang</th>
                    <th class="hidden xl:table-cell px-3 md:px-4 py-2 text-left font-semibold">Alamat</th>
                    <th class="px-3 md:px-4 py-2 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($pesertaList as $peserta)
                    <tr id="row-user-{{ $peserta->id }}" class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700/40 hover:bg-blue-50/60 dark:hover:bg-blue-900/20 transition-colors">
                        <td class="px-3 md:px-4 py-3 text-gray-900 dark:text-gray-200 font-medium align-top min-w-[220px]">
                            <div class="font-semibold">{{ $peserta->name }}</div>
                            <span class="block md:hidden text-xs text-gray-500 dark:text-gray-400">{{ $peserta->email }}</span>
                            <span class="mt-1 block lg:hidden text-xs text-gray-500 dark:text-gray-400">{{ $peserta->instansi ?: '-' }}</span>
                            <span class="mt-1 block lg:hidden text-xs text-gray-500 dark:text-gray-400">{{ $peserta->nomor_telepon ?: '-' }}</span>
                            <span class="mt-1 block xl:hidden text-xs text-gray-500 dark:text-gray-400">{{ $peserta->alamat ?: '-' }}</span>
                        </td>
                        <td class="hidden md:table-cell px-3 md:px-4 py-3 text-gray-600 dark:text-gray-400 align-top">{{ $peserta->email }}</td>
                        <td class="hidden lg:table-cell px-3 md:px-4 py-3 text-gray-600 dark:text-gray-400 align-top">{{ $peserta->instansi ?: '-' }}</td>
                        <td class="hidden lg:table-cell px-3 md:px-4 py-3 text-gray-600 dark:text-gray-400 align-top">{{ $peserta->nomor_telepon ?: '-' }}</td>
                        <td class="hidden xl:table-cell px-3 md:px-4 py-3 text-gray-600 dark:text-gray-400 align-top whitespace-nowrap">
                            @if($peserta->tanggal_mulai && $peserta->tanggal_selesai)
                                {{ \Carbon\Carbon::parse($peserta->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($peserta->tanggal_selesai)->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="hidden xl:table-cell px-3 md:px-4 py-3 text-gray-600 dark:text-gray-400 align-top max-w-[260px] truncate">{{ $peserta->alamat ?: '-' }}</td>
                        <td class="px-3 md:px-4 py-3 align-top">
                            <div class="flex flex-wrap gap-2">
                            <button
                                type="button"
                                onclick="openEditPesertaFromButton(this)"
                                data-id="{{ $peserta->id }}"
                                data-name="{{ $peserta->name }}"
                                data-email="{{ $peserta->email }}"
                                data-instansi="{{ $peserta->instansi }}"
                                data-nomor-telepon="{{ $peserta->nomor_telepon }}"
                                data-tanggal-mulai="{{ optional($peserta->tanggal_mulai)->format('Y-m-d') }}"
                                data-tanggal-selesai="{{ optional($peserta->tanggal_selesai)->format('Y-m-d') }}"
                                data-alamat="{{ $peserta->alamat }}"
                                class="px-2 md:px-3 py-1 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-700 hover:bg-amber-100 dark:hover:bg-amber-900 text-xs font-medium"
                            >
                                Edit
                            </button>
                            <a href="{{ route('admin.peserta.kalender', $peserta->id) }}" class="px-2 md:px-3 py-1 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700 hover:bg-blue-100 dark:hover:bg-blue-900 text-xs font-medium">Detail</a>
                            <button type="button" onclick="openDeleteConfirmModal('{{ $peserta->id }}', this, '{{ $peserta->name }}')" class="px-2 md:px-3 py-1 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-700 hover:bg-red-100 dark:hover:bg-red-900 text-xs font-medium">Hapus</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-center text-gray-500">Belum ada peserta magang aktif</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-4">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Menampilkan {{ $pesertaList->firstItem() ?? 0 }}-{{ $pesertaList->lastItem() ?? 0 }} dari {{ $pesertaList->total() }} peserta aktif
            </p>
            <form method="GET" action="{{ route('admin.peserta.detail') }}" class="flex items-center gap-2">
                <input type="hidden" name="arsip_tahun" value="{{ request('arsip_tahun') }}">
                <input type="hidden" name="arsip_bulan" value="{{ request('arsip_bulan') }}">
                <input type="hidden" name="arsip_page" value="{{ request('arsip_page', 1) }}">
                <input type="hidden" name="arsip_per_page" value="{{ $arsipPerPage }}">
                <label for="aktifPerPage" class="text-xs text-gray-600 dark:text-gray-300">Per halaman</label>
                <select id="aktifPerPage" name="aktif_per_page" onchange="this.form.submit()" class="rounded-lg border border-gray-300 px-2.5 py-1 text-xs text-gray-700 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200">
                    @foreach($allowedPerPage as $size)
                        <option value="{{ $size }}" {{ $aktifPerPage === $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="flex items-center gap-2">
            @if($pesertaList->onFirstPage())
                <span class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-400 dark:border-gray-600 dark:text-gray-500">Sebelumnya</span>
            @else
                <a href="{{ $pesertaList->appends(request()->except('aktif_page'))->url($pesertaList->currentPage() - 1) }}" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Sebelumnya</a>
            @endif

            <span class="text-xs text-gray-600 dark:text-gray-300">Hal. {{ $pesertaList->currentPage() }} / {{ $pesertaList->lastPage() }}</span>

            @if($pesertaList->hasMorePages())
                <a href="{{ $pesertaList->appends(request()->except('aktif_page'))->url($pesertaList->currentPage() + 1) }}" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Berikutnya</a>
            @else
                <span class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-400 dark:border-gray-600 dark:text-gray-500">Berikutnya</span>
            @endif
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 md:p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Arsip Peserta Magang</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Daftar peserta yang sudah selesai magang atau tidak aktif.</p>
        </div>
        <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-200">Total Arsip: {{ $arsipPesertaList->total() }}</span>
    </div>

    <form method="GET" action="{{ route('admin.peserta.detail') }}" class="mb-4 flex flex-wrap items-end gap-3">
        <input type="hidden" name="aktif_page" value="{{ request('aktif_page', 1) }}">
        <input type="hidden" name="aktif_per_page" value="{{ $aktifPerPage }}">

        <div>
            <label for="arsipFilterTahun" class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">Filter Tahun</label>
            <select id="arsipFilterTahun" name="arsip_tahun" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200">
                <option value="">Semua Tahun</option>
                @foreach($arsipTahunList as $tahun)
                    <option value="{{ $tahun }}" {{ (string) $arsipTahun === (string) $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="arsipFilterBulan" class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">Filter Bulan</label>
            <select id="arsipFilterBulan" name="arsip_bulan" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200">
                <option value="">Semua Bulan</option>
                <option value="01" {{ $arsipBulan === '01' ? 'selected' : '' }}>Januari</option>
                <option value="02" {{ $arsipBulan === '02' ? 'selected' : '' }}>Februari</option>
                <option value="03" {{ $arsipBulan === '03' ? 'selected' : '' }}>Maret</option>
                <option value="04" {{ $arsipBulan === '04' ? 'selected' : '' }}>April</option>
                <option value="05" {{ $arsipBulan === '05' ? 'selected' : '' }}>Mei</option>
                <option value="06" {{ $arsipBulan === '06' ? 'selected' : '' }}>Juni</option>
                <option value="07" {{ $arsipBulan === '07' ? 'selected' : '' }}>Juli</option>
                <option value="08" {{ $arsipBulan === '08' ? 'selected' : '' }}>Agustus</option>
                <option value="09" {{ $arsipBulan === '09' ? 'selected' : '' }}>September</option>
                <option value="10" {{ $arsipBulan === '10' ? 'selected' : '' }}>Oktober</option>
                <option value="11" {{ $arsipBulan === '11' ? 'selected' : '' }}>November</option>
                <option value="12" {{ $arsipBulan === '12' ? 'selected' : '' }}>Desember</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">Terapkan</button>
            <a href="{{ route('admin.peserta.detail') }}" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Reset</a>
        </div>

        <div>
            <label for="arsipPerPage" class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">Per Halaman</label>
            <select id="arsipPerPage" name="arsip_per_page" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200">
                @foreach($allowedPerPage as $size)
                    <option value="{{ $size }}" {{ $arsipPerPage === $size ? 'selected' : '' }}>{{ $size }}</option>
                @endforeach
            </select>
        </div>
    </form>

    <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 whitespace-nowrap">
                    <tr>
                        <th class="px-3 md:px-4 py-2 text-left font-semibold">Nama</th>
                        <th class="hidden md:table-cell px-3 md:px-4 py-2 text-left font-semibold">Email</th>
                        <th class="hidden lg:table-cell px-3 md:px-4 py-2 text-left font-semibold">Instansi</th>
                        <th class="hidden lg:table-cell px-3 md:px-4 py-2 text-left font-semibold">Kontak</th>
                        <th class="px-3 md:px-4 py-2 text-left font-semibold">Status Arsip</th>
                        <th class="hidden xl:table-cell px-3 md:px-4 py-2 text-left font-semibold">Durasi Magang</th>
                        <th class="px-3 md:px-4 py-2 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($arsipPesertaList as $arsip)
                        @php
                            $statusArsip = $arsip->role === 'alumni' ? 'Nonaktif' : 'Selesai Magang';
                            $arsipTahun = $arsip->tanggal_selesai ? \Carbon\Carbon::parse($arsip->tanggal_selesai)->format('Y') : '';
                            $arsipBulan = $arsip->tanggal_selesai ? \Carbon\Carbon::parse($arsip->tanggal_selesai)->format('m') : '';
                        @endphp
                        <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700/30" data-archive-year="{{ $arsipTahun }}" data-archive-month="{{ $arsipBulan }}">
                            <td class="px-3 md:px-4 py-3 text-gray-900 dark:text-gray-200 font-medium min-w-[220px]">
                                <div class="font-semibold">{{ $arsip->name }}</div>
                                <span class="block md:hidden text-xs text-gray-500 dark:text-gray-400">{{ $arsip->email }}</span>
                                <span class="mt-1 block lg:hidden text-xs text-gray-500 dark:text-gray-400">{{ $arsip->instansi ?: '-' }}</span>
                            </td>
                            <td class="hidden md:table-cell px-3 md:px-4 py-3 text-gray-600 dark:text-gray-400">{{ $arsip->email }}</td>
                            <td class="hidden lg:table-cell px-3 md:px-4 py-3 text-gray-600 dark:text-gray-400">{{ $arsip->instansi ?: '-' }}</td>
                            <td class="hidden lg:table-cell px-3 md:px-4 py-3 text-gray-600 dark:text-gray-400">{{ $arsip->nomor_telepon ?: '-' }}</td>
                            <td class="px-3 md:px-4 py-3">
                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-200">{{ $statusArsip }}</span>
                            </td>
                            <td class="hidden xl:table-cell px-3 md:px-4 py-3 text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                @if($arsip->tanggal_mulai && $arsip->tanggal_selesai)
                                    {{ \Carbon\Carbon::parse($arsip->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($arsip->tanggal_selesai)->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-3 md:px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.peserta.kalender', $arsip->id) }}" class="inline-flex px-2 md:px-3 py-1 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700 hover:bg-blue-100 dark:hover:bg-blue-900 text-xs font-medium">Detail</a>
                                    <button
                                        type="button"
                                        onclick="openPermanentDeleteModal(this)"
                                        data-id="{{ $arsip->id }}"
                                        data-name="{{ $arsip->name }}"
                                        class="inline-flex px-2 md:px-3 py-1 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-700 hover:bg-red-100 dark:hover:bg-red-900 text-xs font-medium"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-3 text-center text-gray-500">Belum ada data arsip peserta.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-xs text-gray-500 dark:text-gray-400">
            Menampilkan {{ $arsipPesertaList->firstItem() ?? 0 }}-{{ $arsipPesertaList->lastItem() ?? 0 }} dari {{ $arsipPesertaList->total() }} data arsip
        </p>
        <div class="flex items-center gap-2">
            @if($arsipPesertaList->onFirstPage())
                <span class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-400 dark:border-gray-600 dark:text-gray-500">Sebelumnya</span>
            @else
                <a href="{{ $arsipPesertaList->appends(request()->except('arsip_page'))->url($arsipPesertaList->currentPage() - 1) }}" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Sebelumnya</a>
            @endif

            <span class="text-xs text-gray-600 dark:text-gray-300">Hal. {{ $arsipPesertaList->currentPage() }} / {{ $arsipPesertaList->lastPage() }}</span>

            @if($arsipPesertaList->hasMorePages())
                <a href="{{ $arsipPesertaList->appends(request()->except('arsip_page'))->url($arsipPesertaList->currentPage() + 1) }}" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Berikutnya</a>
            @else
                <span class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-400 dark:border-gray-600 dark:text-gray-500">Berikutnya</span>
            @endif
        </div>
    </div>
</div>

<div id="permanentDeleteModal" class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto p-4 sm:p-6" onkeydown="if(event.key==='Escape'){closePermanentDeleteModal();}">
    <div class="absolute inset-0 bg-gray-900/60" onclick="closePermanentDeleteModal()"></div>

    <div class="relative my-8 w-full max-w-md rounded-2xl border border-gray-200 bg-white p-5 shadow-2xl dark:border-gray-700 dark:bg-gray-800 sm:p-6">
        <div class="mb-4 flex items-start justify-between gap-4">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Hapus</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Data peserta, presensi, dan logbook akan terhapus dari database.</p>
            </div>
            <button type="button" onclick="closePermanentDeleteModal()" class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-200">
            <p>Anda yakin ingin menghapus permanen <span id="permanentDeleteName" class="font-semibold"></span>?</p>
        </div>

        <form id="permanentDeleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <button type="button" onclick="closePermanentDeleteModal()" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Batal</button>
                <button type="submit" class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700">Ya, Hapus Permanen</button>
            </div>
        </form>
    </div>
</div>

<div
    x-cloak
    x-show="openAddPeserta"
    @keydown.escape.window="openAddPeserta = false"
    class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4 sm:p-6"
>
    <div x-show="openAddPeserta" x-transition.opacity class="absolute inset-0 bg-gray-900/60" @click="openAddPeserta = false"></div>

    <div x-show="openAddPeserta" x-transition class="relative my-8 w-full max-w-3xl rounded-2xl border border-gray-200 bg-white p-5 shadow-2xl dark:border-gray-700 dark:bg-gray-800 sm:p-6">
        <div class="mb-5 flex items-start justify-between gap-4">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Tambah Peserta</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Isi data peserta magang baru beserta durasi dan kontak.</p>
            </div>
            <button type="button" @click="openAddPeserta = false" class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        @if($errors->addPeserta->any())
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-200">
                <ul class="space-y-1">
                    @foreach($errors->addPeserta->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.peserta.store') }}" class="space-y-4" data-no-loader>
            @csrf

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" />
                </div>

                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" />
                </div>

                <div>
                    <label for="instansi" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Instansi (Kampus)</label>
                    <input id="instansi" name="instansi" type="text" value="{{ old('instansi') }}" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" />
                </div>

                <div>
                    <label for="nomor_telepon" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">No. Telepon</label>
                    <input id="nomor_telepon" name="nomor_telepon" type="text" value="{{ old('nomor_telepon') }}" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" />
                </div>

                <div>
                    <label for="tanggal_mulai" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                    <input id="tanggal_mulai" name="tanggal_mulai" type="date" value="{{ old('tanggal_mulai') }}" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" />
                </div>

                <div>
                    <label for="tanggal_selesai" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Selesai Magang</label>
                    <input id="tanggal_selesai" name="tanggal_selesai" type="date" value="{{ old('tanggal_selesai') }}" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" />
                </div>
            </div>

            <div>
                <label for="alamat" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>
                <textarea id="alamat" name="alamat" rows="3" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" placeholder="Opsional">{{ old('alamat') }}</textarea>
            </div>

            <div class="rounded-xl border border-blue-100 bg-blue-50 px-3 py-2 text-xs text-blue-700 dark:border-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                Password default peserta: <span class="font-semibold">password123</span>
            </div>

            <div class="flex flex-col-reverse gap-2 pt-1 sm:flex-row sm:justify-end">
                <button type="button" @click="openAddPeserta = false" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Batal</button>
                <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Simpan Peserta</button>
            </div>
        </form>
    </div>
</div>
</div>

<div id="editPesertaModal" class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto p-4 sm:p-6" onkeydown="if(event.key==='Escape'){closeEditPesertaModal();}">
    <div class="absolute inset-0 bg-gray-900/60" onclick="closeEditPesertaModal()"></div>

    <div class="relative my-8 w-full max-w-3xl rounded-2xl border border-gray-200 bg-white p-5 shadow-2xl dark:border-gray-700 dark:bg-gray-800 sm:p-6">
        <div class="mb-5 flex items-start justify-between gap-4">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Peserta</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Perbarui data peserta magang dengan detail terbaru.</p>
            </div>
            <button type="button" onclick="closeEditPesertaModal()" class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        @if($errors->editPeserta->any())
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-200">
                <ul class="space-y-1">
                    @foreach($errors->editPeserta->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="editPesertaForm" method="POST" action="" class="space-y-4" data-no-loader>
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_user_id" name="edit_user_id" value="{{ old('edit_user_id') }}">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="edit_name" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                    <input id="edit_name" name="edit_name" type="text" value="{{ old('edit_name') }}" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" />
                </div>

                <div>
                    <label for="edit_email" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input id="edit_email" name="edit_email" type="email" value="{{ old('edit_email') }}" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" />
                </div>

                <div>
                    <label for="edit_instansi" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Instansi (Kampus)</label>
                    <input id="edit_instansi" name="edit_instansi" type="text" value="{{ old('edit_instansi') }}" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" />
                </div>

                <div>
                    <label for="edit_nomor_telepon" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">No. Telepon</label>
                    <input id="edit_nomor_telepon" name="edit_nomor_telepon" type="text" value="{{ old('edit_nomor_telepon') }}" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" />
                </div>

                <div>
                    <label for="edit_tanggal_mulai" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                    <input id="edit_tanggal_mulai" name="edit_tanggal_mulai" type="date" value="{{ old('edit_tanggal_mulai') }}" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" />
                </div>

                <div>
                    <label for="edit_tanggal_selesai" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Selesai Magang</label>
                    <input id="edit_tanggal_selesai" name="edit_tanggal_selesai" type="date" value="{{ old('edit_tanggal_selesai') }}" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" />
                </div>
            </div>

            <div>
                <label for="edit_alamat" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>
                <textarea id="edit_alamat" name="edit_alamat" rows="3" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" placeholder="Opsional">{{ old('edit_alamat') }}</textarea>
            </div>

            <div class="flex flex-col-reverse gap-2 pt-1 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeEditPesertaModal()" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Batal</button>
                <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteConfirmModal" class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto p-4 sm:p-6" onkeydown="if(event.key==='Escape'){closeDeleteConfirmModal();}">
    <div class="absolute inset-0 bg-gray-900/60" onclick="closeDeleteConfirmModal()"></div>

    <div class="relative my-8 w-full max-w-md rounded-2xl border border-gray-200 bg-white p-5 shadow-2xl dark:border-gray-700 dark:bg-gray-800 sm:p-6">
        <div class="mb-4 flex items-start justify-between gap-4">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Konfirmasi Hapus Peserta</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tindakan ini akan menonaktifkan peserta dari daftar aktif.</p>
            </div>
            <button type="button" onclick="closeDeleteConfirmModal()" class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-200">
            <p id="deleteConfirmText">Apakah Anda yakin ingin menonaktifkan peserta ini?</p>
        </div>

        <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <button type="button" onclick="closeDeleteConfirmModal()" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Batal</button>
            <button type="button" id="confirmDeleteBtn" onclick="confirmDeletePeserta()" class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700">Ya, Hapus</button>
        </div>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const pesertaUpdateBaseUrl = @js(url('/admin/peserta'));
const hasEditPesertaErrors = @js($errors->editPeserta->any());
let deleteUserId = null;
let deleteUserName = '';
let deleteBtnRef = null;
let deleteBtnOriginalText = 'Hapus';

function openEditPesertaFromButton(btn) {
    openEditPesertaModal({
        id: btn.dataset.id || '',
        name: btn.dataset.name || '',
        email: btn.dataset.email || '',
        instansi: btn.dataset.instansi || '',
        nomorTelepon: btn.dataset.nomorTelepon || '',
        tanggalMulai: btn.dataset.tanggalMulai || '',
        tanggalSelesai: btn.dataset.tanggalSelesai || '',
        alamat: btn.dataset.alamat || '',
    });
}

function openEditPesertaModal(payload) {
    const modal = document.getElementById('editPesertaModal');
    const form = document.getElementById('editPesertaForm');

    document.getElementById('edit_user_id').value = payload.id || '';
    document.getElementById('edit_name').value = payload.name || '';
    document.getElementById('edit_email').value = payload.email || '';
    document.getElementById('edit_instansi').value = payload.instansi || '';
    document.getElementById('edit_nomor_telepon').value = payload.nomorTelepon || '';
    document.getElementById('edit_tanggal_mulai').value = payload.tanggalMulai || '';
    document.getElementById('edit_tanggal_selesai').value = payload.tanggalSelesai || '';
    document.getElementById('edit_alamat').value = payload.alamat || '';

    form.action = `${pesertaUpdateBaseUrl}/${payload.id}`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeEditPesertaModal() {
    const modal = document.getElementById('editPesertaModal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function openDeleteConfirmModal(userId, btn, userName) {
    deleteUserId = userId;
    deleteUserName = userName || 'peserta ini';
    deleteBtnRef = btn;
    deleteBtnOriginalText = btn?.textContent || 'Hapus';

    const modal = document.getElementById('deleteConfirmModal');
    const text = document.getElementById('deleteConfirmText');
    text.textContent = `Apakah Anda yakin ingin menonaktifkan ${deleteUserName}? Data tetap tersimpan, namun peserta tidak akan muncul lagi sebagai magang aktif.`;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteConfirmModal() {
    const modal = document.getElementById('deleteConfirmModal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function openPermanentDeleteModal(button) {
    const userId = button.dataset.id;
    const userName = button.dataset.name || 'peserta ini';

    const form = document.getElementById('permanentDeleteForm');
    form.action = `${pesertaUpdateBaseUrl}/${userId}/permanent`;

    document.getElementById('permanentDeleteName').textContent = userName;

    const modal = document.getElementById('permanentDeleteModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closePermanentDeleteModal() {
    const modal = document.getElementById('permanentDeleteModal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

if (hasEditPesertaErrors) {
    openEditPesertaModal({
        id: @js(old('edit_user_id')),
        name: @js(old('edit_name')),
        email: @js(old('edit_email')),
        instansi: @js(old('edit_instansi')),
        nomorTelepon: @js(old('edit_nomor_telepon')),
        tanggalMulai: @js(old('edit_tanggal_mulai')),
        tanggalSelesai: @js(old('edit_tanggal_selesai')),
        alamat: @js(old('edit_alamat')),
    });
}

async function nonaktifkanPeserta(userId, btn) {
    if (!userId || !btn) return;
    btn.disabled = true;
    btn.textContent = 'Memproses...';

    try {
        const response = await fetch(`/admin/peserta/${userId}/nonaktif`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        if (!data.success) throw new Error(data.message || 'Gagal menonaktifkan');

        const row = document.getElementById(`row-user-${userId}`);
        if (row) row.remove();

        alert('Peserta dinonaktifkan.');
    } catch (err) {
        alert(err.message || 'Terjadi kesalahan');
    } finally {
        btn.disabled = false;
        btn.textContent = deleteBtnOriginalText || 'Hapus';
    }
}

async function confirmDeletePeserta() {
    if (!deleteUserId || !deleteBtnRef) return;

    const confirmBtn = document.getElementById('confirmDeleteBtn');
    confirmBtn.disabled = true;
    confirmBtn.textContent = 'Memproses...';

    closeDeleteConfirmModal();
    await nonaktifkanPeserta(deleteUserId, deleteBtnRef);

    confirmBtn.disabled = false;
    confirmBtn.textContent = 'Ya, Hapus';
    deleteUserId = null;
    deleteUserName = '';
    deleteBtnRef = null;
}
</script>
@endsection
