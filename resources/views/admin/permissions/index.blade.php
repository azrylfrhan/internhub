@extends('layouts.admin')

@section('title', 'Daftar Izin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Izin</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Tinjau dan proses pengajuan izin peserta magang.</p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/60">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Peserta</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Periode Izin</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Alasan</th>
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
                                {{ $permission->start_date->format('d M Y') }} - {{ $permission->end_date->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                <div class="max-w-xl whitespace-pre-line break-words">{{ $permission->reason }}</div>
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
                                            <button type="submit" class="inline-flex items-center rounded-lg bg-green-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-green-700">Setujui</button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.permissions.update-status', $permission) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="inline-flex items-center rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">Tolak</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Sudah diproses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada pengajuan izin.</td>
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
