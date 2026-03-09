@extends('layouts.admin')

@section('title', 'Manajemen Admin/Mentor')

@section('content')
<div x-data="{ openAddModal: false }" class="mx-auto w-full space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Admin/Mentor</h1>
            <p class="text-sm text-gray-600 dark:text-gray-300">Kelola akun pengguna dengan role admin atau mentor.</p>
        </div>
        <button type="button" @click="openAddModal = true" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Admin/Mentor
        </button>
    </div>

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

    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Admin/Mentor</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/70">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Nama</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Email</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Role</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $user)
                        <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700/30">
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->role === 'admin' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300' }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <button
                                        type="button"
                                        onclick="openEditManagementFromButton(this)"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}"
                                        data-role="{{ $user->role }}"
                                        class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700 hover:bg-amber-100 dark:border-amber-700 dark:bg-amber-900/30 dark:text-amber-300 dark:hover:bg-amber-900/50"
                                    >
                                        Edit
                                    </button>

                                    <button
                                        type="button"
                                        onclick="openDeleteManagementModal(this)"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        class="rounded-lg border border-red-200 bg-red-50 px-3 py-1 text-xs font-medium text-red-700 hover:bg-red-100 dark:border-red-700 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">Belum ada akun admin/mentor.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div
        x-cloak
        x-show="openAddModal"
        @keydown.escape.window="openAddModal = false"
        class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4 sm:p-6"
    >
        <div x-show="openAddModal" x-transition.opacity class="absolute inset-0 bg-gray-900/60" @click="openAddModal = false"></div>

        <div x-show="openAddModal" x-transition class="relative my-8 w-full max-w-2xl rounded-2xl border border-gray-200 bg-white p-5 shadow-2xl dark:border-gray-700 dark:bg-gray-800 sm:p-6">
            <div class="mb-5 flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Tambah Admin/Mentor</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Buat akun admin atau mentor baru.</p>
                </div>
                <button type="button" @click="openAddModal = false" class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.management.store') }}" class="space-y-4" data-no-loader>
                @csrf
                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                    <input id="name" name="name" type="text" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" />
                </div>

                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input id="email" name="email" type="email" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400 dark:[color-scheme:dark]" />
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="role" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                        <select id="role" name="role" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400">
                            <option value="admin">Admin</option>
                            <option value="mentor">Mentor</option>
                        </select>
                    </div>
                    <div>
                        <label for="password" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                        <input id="password" name="password" type="password" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400 dark:[color-scheme:dark]" />
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400 dark:[color-scheme:dark]" />
                </div>

                <div class="flex flex-col-reverse gap-2 pt-1 sm:flex-row sm:justify-end">
                    <button type="button" @click="openAddModal = false" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Batal</button>
                    <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editManagementModal" class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto p-4 sm:p-6" onkeydown="if(event.key==='Escape'){closeEditManagementModal();}">
    <div class="absolute inset-0 bg-gray-900/60" onclick="closeEditManagementModal()"></div>

    <div class="relative my-8 w-full max-w-2xl rounded-2xl border border-gray-200 bg-white p-5 shadow-2xl dark:border-gray-700 dark:bg-gray-800 sm:p-6">
        <div class="mb-5 flex items-start justify-between gap-4">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Admin/Mentor</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Perbarui data akun admin atau mentor.</p>
            </div>
            <button type="button" onclick="closeEditManagementModal()" class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="editManagementForm" method="POST" class="space-y-4" data-no-loader>
            @csrf
            @method('PUT')

            <div>
                <label for="edit_name" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                <input id="edit_name" name="name" type="text" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" />
            </div>

            <div>
                <label for="edit_email" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input id="edit_email" name="email" type="email" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" />
            </div>

            <div>
                <label for="edit_role" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                <select id="edit_role" name="role" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400">
                    <option value="admin">Admin</option>
                    <option value="mentor">Mentor</option>
                </select>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="edit_password" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Password Baru (opsional)</label>
                    <input id="edit_password" name="password" type="password" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" />
                </div>
                <div>
                    <label for="edit_password_confirmation" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password Baru</label>
                    <input id="edit_password_confirmation" name="password_confirmation" type="password" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400" />
                </div>
            </div>

            <div class="flex flex-col-reverse gap-2 pt-1 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeEditManagementModal()" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Batal</button>
                <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteManagementModal" class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto p-4 sm:p-6" onkeydown="if(event.key==='Escape'){closeDeleteManagementModal();}">
    <div class="absolute inset-0 bg-gray-900/60" onclick="closeDeleteManagementModal()"></div>

    <div class="relative my-8 w-full max-w-md rounded-2xl border border-gray-200 bg-white p-5 shadow-2xl dark:border-gray-700 dark:bg-gray-800 sm:p-6">
        <div class="mb-4 flex items-start justify-between gap-4">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Konfirmasi Hapus</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <button type="button" onclick="closeDeleteManagementModal()" class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <p class="mb-6 text-sm text-gray-700 dark:text-gray-300">
            Yakin ingin menghapus akun <span id="deleteManagementName" class="font-semibold text-gray-900 dark:text-white"></span>?
        </p>

        <form id="deleteManagementForm" method="POST" data-no-loader>
            @csrf
            @method('DELETE')

            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeDeleteManagementModal()" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Batal</button>
                <button type="submit" class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700">Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditManagementFromButton(button) {
    const userId = button.dataset.id;
    const form = document.getElementById('editManagementForm');
    form.action = `/admin/management/${userId}`;

    document.getElementById('edit_name').value = button.dataset.name || '';
    document.getElementById('edit_email').value = button.dataset.email || '';
    document.getElementById('edit_role').value = button.dataset.role || 'mentor';
    document.getElementById('edit_password').value = '';
    document.getElementById('edit_password_confirmation').value = '';

    const modal = document.getElementById('editManagementModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeEditManagementModal() {
    const modal = document.getElementById('editManagementModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

function openDeleteManagementModal(button) {
    const userId = button.dataset.id;
    const userName = button.dataset.name || 'akun ini';

    const form = document.getElementById('deleteManagementForm');
    form.action = `/admin/management/${userId}`;

    document.getElementById('deleteManagementName').textContent = userName;

    const modal = document.getElementById('deleteManagementModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteManagementModal() {
    const modal = document.getElementById('deleteManagementModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}
</script>
@endsection
