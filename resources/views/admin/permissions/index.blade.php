@extends('layouts.admin')

@section('title', 'Daftar Izin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Izin</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Tinjau dan proses pengajuan izin peserta magang.</p>
    </div>

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Total</p>
            <p class="mt-1 text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $summary['total'] }}</p>
        </div>
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 shadow-sm dark:border-amber-800/60 dark:bg-amber-900/30">
            <p class="text-xs font-semibold uppercase tracking-wide text-amber-700 dark:text-amber-300">Pending</p>
            <p class="mt-1 text-2xl font-bold text-amber-800 dark:text-amber-200">{{ $summary['pending'] }}</p>
        </div>
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 shadow-sm dark:border-emerald-800/60 dark:bg-emerald-900/30">
            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700 dark:text-emerald-300">Approved</p>
            <p class="mt-1 text-2xl font-bold text-emerald-800 dark:text-emerald-200">{{ $summary['approved'] }}</p>
        </div>
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 shadow-sm dark:border-rose-800/60 dark:bg-rose-900/30">
            <p class="text-xs font-semibold uppercase tracking-wide text-rose-700 dark:text-rose-300">Rejected</p>
            <p class="mt-1 text-2xl font-bold text-rose-800 dark:text-rose-200">{{ $summary['rejected'] }}</p>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.permissions.index') }}" class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
            <div class="md:col-span-2">
                <label for="q" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Cari Peserta</label>
                <input id="q" name="q" type="text" value="{{ request('q') }}" placeholder="Nama atau email"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:focus:ring-blue-500/30">
            </div>
            <div>
                <label for="status" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Status</label>
                <select id="status" name="status" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:focus:ring-blue-500/30">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                </select>
            </div>
            <div>
                <label for="permission_type" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Jenis Izin</label>
                <select id="permission_type" name="permission_type" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:focus:ring-blue-500/30">
                    <option value="">Semua Jenis</option>
                    <option value="sakit" @selected(request('permission_type') === 'sakit')>Sakit</option>
                    <option value="lainnya" @selected(request('permission_type') === 'lainnya')>Alasan Lain</option>
                </select>
            </div>
        </div>
        <div class="mt-3 flex flex-col gap-2 sm:flex-row sm:justify-end">
            <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Reset</a>
            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Terapkan Filter</button>
        </div>
    </form>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/60">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Peserta</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Diajukan</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Tanggal Izin</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Detail Izin</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($permissions as $permission)
                        <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700/30">
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                <p class="font-semibold">{{ $permission->user->name ?? 'Peserta' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $permission->user->email ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                <p class="font-medium">{{ $permission->created_at?->format('d M Y') ?? '-' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $permission->created_at?->format('H:i') ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                {{ $permission->start_date->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                <div class="max-w-xl space-y-2">
                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-700 dark:text-slate-200">{{ $permission->permission_type_label }}</span>
                                    <div class="whitespace-pre-line break-words">{{ $permission->reason }}</div>
                                    @if($permission->attachment_url)
                                        <a href="{{ $permission->attachment_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-300 dark:hover:text-blue-200">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16v-8m0 8l-3-3m3 3l3-3M5 20h14"></path>
                                            </svg>
                                            Lihat dokumen
                                        </a>
                                    @elseif($permission->permission_type === 'sakit')
                                        <span class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">Dokumen belum tersedia</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($permission->status === 'pending')
                                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">Pending</span>
                                @elseif($permission->status === 'approved')
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700 dark:bg-green-900/30 dark:text-green-300">Approved</span>
                                @else
                                    <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">Rejected</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($permission->status === 'pending')
                                    <div class="flex items-center gap-2">
                                        <form method="POST" action="{{ route('admin.permissions.update-status', $permission) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" onclick="return confirm('Setujui pengajuan izin ini?')" class="inline-flex items-center rounded-lg bg-green-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-green-700">Setujui</button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.permissions.update-status', $permission) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" onclick="return confirm('Tolak pengajuan izin ini?')" class="inline-flex items-center rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">Tolak</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Sudah diproses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada pengajuan izin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($permissions->hasPages())
            <div class="border-t border-gray-200 px-4 py-3 dark:border-gray-700">
                {{ $permissions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
