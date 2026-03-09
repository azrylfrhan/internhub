@extends('layouts.admin')

@section('title', 'Pengaturan Jam Kerja')

@section('content')
<div class="mb-8">
    <h1 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">Pengaturan</h1>
    <p class="text-sm text-gray-600 dark:text-gray-300">Kelola jam kerja rutin, jam kerja khusus per tanggal, serta koordinat lokasi kantor.</p>
</div>

<div x-data="{ tab: 'rutin' }" class="space-y-6">
    <div class="rounded-2xl border border-gray-200 bg-white p-2 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="grid grid-cols-1 gap-2 md:grid-cols-3">
            <button @click="tab = 'rutin'" :class="tab === 'rutin' ? 'bg-blue-600 text-white' : 'bg-transparent text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700'" class="rounded-xl px-4 py-2.5 text-sm font-semibold transition">Jam Kerja Rutin</button>
            <button @click="tab = 'khusus'" :class="tab === 'khusus' ? 'bg-blue-600 text-white' : 'bg-transparent text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700'" class="rounded-xl px-4 py-2.5 text-sm font-semibold transition">Jam Kerja Khusus</button>
            <button @click="tab = 'lokasi'" :class="tab === 'lokasi' ? 'bg-blue-600 text-white' : 'bg-transparent text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700'" class="rounded-xl px-4 py-2.5 text-sm font-semibold transition">Lokasi Kantor</button>
        </div>
    </div>

    <form x-show="tab === 'rutin'" x-cloak action="{{ route('admin.settings.update') }}" method="POST" class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        @csrf
        @method('PUT')
        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Jam Kerja Rutin</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Atur jam kerja default untuk Senin-Kamis dan Jumat.</p>
        </div>

        <div class="space-y-6 px-6 py-6">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="jam_masuk_senin_kamis" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Masuk Senin-Kamis</label>
                    <input id="jam_masuk_senin_kamis" name="jam_masuk_senin_kamis" type="text" inputmode="numeric" placeholder="07.30" value="{{ old('jam_masuk_senin_kamis', str_replace(':', '.', $settings['jam_masuk_senin_kamis'])) }}" pattern="^([01]\d|2[0-3])[\.:]([0-5]\d)$" title="Gunakan format 24 jam: 00.00 - 23.59" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-500/30" required>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format 24 jam: 00.00 - 23.59</p>
                </div>

                <div>
                    <label for="jam_pulang_senin_kamis" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Pulang Senin-Kamis</label>
                    <input id="jam_pulang_senin_kamis" name="jam_pulang_senin_kamis" type="text" inputmode="numeric" placeholder="16.00" value="{{ old('jam_pulang_senin_kamis', str_replace(':', '.', $settings['jam_pulang_senin_kamis'])) }}" pattern="^([01]\d|2[0-3])[\.:]([0-5]\d)$" title="Gunakan format 24 jam: 00.00 - 23.59" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-500/30" required>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format 24 jam: 00.00 - 23.59</p>
                </div>

                <div>
                    <label for="jam_masuk_jumat" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Masuk Jumat</label>
                    <input id="jam_masuk_jumat" name="jam_masuk_jumat" type="text" inputmode="numeric" placeholder="07.30" value="{{ old('jam_masuk_jumat', str_replace(':', '.', $settings['jam_masuk_jumat'])) }}" pattern="^([01]\d|2[0-3])[\.:]([0-5]\d)$" title="Gunakan format 24 jam: 00.00 - 23.59" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-500/30" required>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format 24 jam: 00.00 - 23.59</p>
                </div>

                <div>
                    <label for="jam_pulang_jumat" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Pulang Jumat</label>
                    <input id="jam_pulang_jumat" name="jam_pulang_jumat" type="text" inputmode="numeric" placeholder="16.00" value="{{ old('jam_pulang_jumat', str_replace(':', '.', $settings['jam_pulang_jumat'])) }}" pattern="^([01]\d|2[0-3])[\.:]([0-5]\d)$" title="Gunakan format 24 jam: 00.00 - 23.59" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-500/30" required>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format 24 jam: 00.00 - 23.59</p>
                </div>
            </div>

            <input type="hidden" name="office_latitude" value="{{ old('office_latitude', $settings['office_latitude']) }}">
            <input type="hidden" name="office_longitude" value="{{ old('office_longitude', $settings['office_longitude']) }}">
            <input type="hidden" name="max_distance_meters" value="{{ old('max_distance_meters', $settings['max_distance_meters']) }}">

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500/40">Simpan Jam Rutin</button>
            </div>
        </div>
    </form>

    <div x-show="tab === 'khusus'" x-cloak class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Jam Kerja Khusus</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Buat rentang tanggal khusus (misalnya Ramadhan atau kebijakan lembur).</p>
        </div>

        <div class="space-y-6 px-6 py-6">
            <form action="{{ route('admin.settings.custom-working-days.store') }}" method="POST" class="grid grid-cols-1 gap-4 rounded-xl border border-gray-200 p-4 dark:border-gray-700 md:grid-cols-2">
                @csrf
                <div>
                    <label for="tanggal_mulai" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                    <input id="tanggal_mulai" name="tanggal_mulai" type="date" value="{{ old('tanggal_mulai') }}" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-500/30" required>
                </div>
                <div>
                    <label for="tanggal_selesai" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Selesai</label>
                    <input id="tanggal_selesai" name="tanggal_selesai" type="date" value="{{ old('tanggal_selesai') }}" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-500/30" required>
                </div>
                <div>
                    <label for="jam_masuk" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Masuk</label>
                    <input id="jam_masuk" name="jam_masuk" type="text" inputmode="numeric" placeholder="07.30" value="{{ old('jam_masuk') }}" pattern="^([01]\d|2[0-3])[\.:]([0-5]\d)$" title="Gunakan format 24 jam: 00.00 - 23.59" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-500/30" required>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format 24 jam: 00.00 - 23.59</p>
                </div>
                <div>
                    <label for="jam_pulang" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Pulang</label>
                    <input id="jam_pulang" name="jam_pulang" type="text" inputmode="numeric" placeholder="16.00" value="{{ old('jam_pulang') }}" pattern="^([01]\d|2[0-3])[\.:]([0-5]\d)$" title="Gunakan format 24 jam: 00.00 - 23.59" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-500/30" required>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format 24 jam: 00.00 - 23.59</p>
                </div>
                <div class="md:col-span-2">
                    <label for="keterangan" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Keterangan</label>
                    <input id="keterangan" name="keterangan" type="text" value="{{ old('keterangan') }}" placeholder="Contoh: Jam kerja Ramadhan" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-500/30">
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500/40">Tambah Jam Khusus</button>
                </div>
            </form>

            <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/70">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Rentang Tanggal</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Jam Kerja</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Keterangan</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($customWorkingDays as $custom)
                            <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700/30">
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $custom->tanggal_mulai->format('d M Y') }} - {{ $custom->tanggal_selesai->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ \Illuminate\Support\Str::substr($custom->jam_masuk, 0, 5) }} - {{ \Illuminate\Support\Str::substr($custom->jam_pulang, 0, 5) }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $custom->keterangan ?: '-' }}</td>
                                <td class="px-4 py-3">
                                    <form action="{{ route('admin.settings.custom-working-days.destroy', $custom) }}" method="POST" onsubmit="return confirm('Hapus jam kerja khusus ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">Belum ada jam kerja khusus.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form x-show="tab === 'lokasi'" x-cloak action="{{ route('admin.settings.update') }}" method="POST" class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        @csrf
        @method('PUT')
        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Lokasi Kantor</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Atur koordinat kantor dan radius maksimal absensi.</p>
        </div>

        <div class="space-y-6 px-6 py-6">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div>
                    <label for="office_latitude" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Latitude</label>
                    <input id="office_latitude" name="office_latitude" type="number" step="0.000001" value="{{ old('office_latitude', $settings['office_latitude']) }}" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-500/30" required>
                </div>

                <div>
                    <label for="office_longitude" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Longitude</label>
                    <input id="office_longitude" name="office_longitude" type="number" step="0.000001" value="{{ old('office_longitude', $settings['office_longitude']) }}" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-500/30" required>
                </div>

                <div>
                    <label for="max_distance_meters" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Radius Maksimal (meter)</label>
                    <input id="max_distance_meters" name="max_distance_meters" type="number" min="1" value="{{ old('max_distance_meters', $settings['max_distance_meters']) }}" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-500/30" required>
                </div>
            </div>

            <input type="hidden" name="jam_masuk_senin_kamis" value="{{ old('jam_masuk_senin_kamis', $settings['jam_masuk_senin_kamis']) }}">
            <input type="hidden" name="jam_pulang_senin_kamis" value="{{ old('jam_pulang_senin_kamis', $settings['jam_pulang_senin_kamis']) }}">
            <input type="hidden" name="jam_masuk_jumat" value="{{ old('jam_masuk_jumat', $settings['jam_masuk_jumat']) }}">
            <input type="hidden" name="jam_pulang_jumat" value="{{ old('jam_pulang_jumat', $settings['jam_pulang_jumat']) }}">

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500/40">Simpan Lokasi Kantor</button>
            </div>
        </div>
    </form>
</div>
@endsection
