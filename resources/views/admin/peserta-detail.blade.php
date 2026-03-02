@extends('layouts.admin')

@section('title', 'Detail Peserta')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Dashboard</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Detail Peserta</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Peserta Magang</h1>
</div>

<!-- Tabel Peserta -->
@php
    $pesertaList = \App\Models\User::where('role', 'magang')->orderBy('name')->get();
@endphp

<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Peserta Magang</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Gunakan tombol aksi untuk detail atau nonaktifkan</p>
        </div>
        <span class="text-sm text-gray-500 dark:text-gray-400">Total: {{ $pesertaList->count() }} peserta</span>
    </div>

    <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm md:text-base">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                <tr>
                    <th class="px-3 md:px-4 py-2 text-left font-semibold">Nama</th>
                    <th class="hidden md:table-cell px-3 md:px-4 py-2 text-left font-semibold">Email</th>
                    <th class="px-3 md:px-4 py-2 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($pesertaList as $peserta)
                    <tr id="row-user-{{ $peserta->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-3 md:px-4 py-2 text-gray-900 dark:text-gray-200 font-medium">{{ $peserta->name }}<span class="block md:hidden text-xs text-gray-500 dark:text-gray-400">{{ $peserta->email }}</span></td>
                        <td class="hidden md:table-cell px-3 md:px-4 py-2 text-gray-600 dark:text-gray-400">{{ $peserta->email }}</td>
                        <td class="px-3 md:px-4 py-2 space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.peserta.kalender', $peserta->id) }}" class="px-2 md:px-3 py-1 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700 hover:bg-blue-100 dark:hover:bg-blue-900 text-xs font-medium">Detail</a>
                            <button type="button" onclick="nonaktifkanPeserta('{{ $peserta->id }}', this)" class="px-2 md:px-3 py-1 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-700 hover:bg-red-100 dark:hover:bg-red-900 text-xs font-medium">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-center text-gray-500">Belum ada peserta magang aktif</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

async function nonaktifkanPeserta(userId, btn) {
    const ok = confirm('Nonaktifkan peserta ini? Data tetap tersimpan, peserta tidak muncul lagi sebagai magang.');
    if (!ok) return;

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
        btn.textContent = 'Hapus / Nonaktifkan';
    }
}
</script>
@endsection
