@extends('layouts.admin')

@section('title', 'Input & Manajemen Izin')

@section('content')
<div class="space-y-6" x-data="{ openInputModal: {{ ($errors->has('user_id') || $errors->has('start_date') || $errors->has('end_date') || $errors->has('permission_type') || $errors->has('reason') || $errors->has('medical_document')) ? 'true' : 'false' }}, permissionType: '{{ old('permission_type', 'sakit') }}' }" @keydown.escape.window="openInputModal = false">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Input & Manajemen Izin</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Input izin dilakukan oleh Admin/Mentor berdasarkan laporan peserta.</p>
        </div>

        <button
            type="button"
            @click="openInputModal = true"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Input Izin Baru
        </button>
    </div>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Total</p>
            <p class="mt-1 text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $summary['total'] }}</p>
        </div>
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 shadow-sm dark:border-amber-800/60 dark:bg-amber-900/30">
            <p class="text-xs font-semibold uppercase tracking-wide text-amber-700 dark:text-amber-300">Izin Sakit</p>
            <p class="mt-1 text-2xl font-bold text-amber-800 dark:text-amber-200">{{ $summary['sakit'] }}</p>
        </div>
        <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 shadow-sm dark:border-blue-800/60 dark:bg-blue-900/30">
            <p class="text-xs font-semibold uppercase tracking-wide text-blue-700 dark:text-blue-300">Alasan Lain</p>
            <p class="mt-1 text-2xl font-bold text-blue-800 dark:text-blue-200">{{ $summary['lainnya'] }}</p>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.permissions.index') }}" class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
            <div class="md:col-span-2">
                <label for="q" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Cari Peserta</label>
                <input id="q" name="q" type="text" value="{{ request('q') }}" placeholder="Nama, username, atau email"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:focus:ring-blue-500/30">
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
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Rentang Izin</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Alasan</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Diinput</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($permissions as $permission)
                        <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700/30">
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                <p class="font-semibold">{{ $permission->user->name ?? 'Peserta' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ '@' . ($permission->user->username ?? '-') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $permission->user->email ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                <p class="font-medium">{{ $permission->start_date?->format('d M Y') ?? '-' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">s.d. {{ $permission->end_date?->format('d M Y') ?? '-' }}</p>
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
                                            Lihat dokumen PDF
                                        </a>
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
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                <p class="font-medium">{{ $permission->created_at?->format('d M Y') ?? '-' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $permission->created_at?->format('H:i') ?? '-' }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada data izin.</td>
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

    <div
        x-show="openInputModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        @click.self="openInputModal = false"
    >
        <div class="w-full max-w-2xl rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4 dark:border-gray-700">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Input Izin Manual</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Data akan tersimpan otomatis dengan status approved.</p>
                </div>
                <button type="button" @click="openInputModal = false" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.permissions.store') }}" enctype="multipart/form-data" class="space-y-4 px-5 py-5">
                @csrf

                <div>
                    <label for="user_id" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Nama Anak Magang</label>
                    <select id="user_id" name="user_id" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:focus:ring-blue-500/30">
                        <option value="">Pilih peserta magang</option>
                        @foreach($participants as $participant)
                            <option value="{{ $participant->id }}" @selected((string) old('user_id') === (string) $participant->id)>
                                {{ $participant->name }} ({{ '@' . ($participant->username ?? '-') }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="start_date" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Tanggal Mulai</label>
                        <input id="start_date" name="start_date" type="date" value="{{ old('start_date') }}" required
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:focus:ring-blue-500/30">
                        @error('start_date')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Tanggal Akhir</label>
                        <input id="end_date" name="end_date" type="date" value="{{ old('end_date') }}" required
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:focus:ring-blue-500/30">
                        @error('end_date')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="permission_type" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Jenis Izin</label>
                    <select id="permission_type" name="permission_type" x-model="permissionType" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:focus:ring-blue-500/30">
                        <option value="sakit">Sakit</option>
                        <option value="lainnya">Alasan Lain</option>
                    </select>
                    @error('permission_type')
                        <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="permissionType === 'sakit'" x-cloak>
                    <label for="medical_document" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Dokumen Sakit (PDF)</label>
                    <input id="medical_document" name="medical_document" type="file" accept="application/pdf,.pdf" :required="permissionType === 'sakit'"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 file:mr-3 file:rounded-md file:border-0 file:bg-blue-600 file:px-3 file:py-1.5 file:text-white hover:file:bg-blue-700 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format wajib PDF, maksimal 3 MB.</p>
                    @error('medical_document')
                        <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="permissionType === 'lainnya'" x-cloak>
                    <label for="reason" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Alasan Izin</label>
                    <textarea id="reason" name="reason" rows="4" maxlength="1500" :required="permissionType === 'lainnya'"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-500 dark:focus:ring-blue-500/30"
                        placeholder="Contoh: Izin tidak masuk karena ada keperluan keluarga.">{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col-reverse gap-2 border-t border-gray-200 pt-4 dark:border-gray-700 sm:flex-row sm:justify-end">
                    <button type="button" @click="openInputModal = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        Batal
                    </button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Simpan Izin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
