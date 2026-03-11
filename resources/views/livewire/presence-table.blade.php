<div wire:poll.5s class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4 dark:border-gray-700">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Ringkasan Kehadiran Hari Ini</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal {{ \Carbon\Carbon::parse($today)->translatedFormat('d F Y') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                <span class="inline-block h-2 w-2 bg-green-500 rounded-full animate-pulse"></span>
                Live Update
            </span>
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
                @forelse($attendanceData as $data)
                    <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700/30">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $data['name'] }}</td>
                        <td class="px-4 py-3">
                            @if($data['status'] === 'hadir')
                                <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">Hadir</span>
                            @elseif($data['status'] === 'terlambat')
                                <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">Terlambat</span>
                            @elseif($data['status'] === 'izin')
                                <span class="inline-flex rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-semibold text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">Izin</span>
                            @elseif($data['status'] === 'alpa')
                                <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">Alpa</span>
                            @else
                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">Belum Absen</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $data['jam_masuk'] ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $data['jam_pulang'] ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $data['keterangan'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">Belum ada peserta magang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Loading state indicator -->
    <div wire:loading.flex class="absolute inset-0 bg-white/50 dark:bg-gray-800/50 flex items-center justify-center rounded-b-lg">
        <div class="flex flex-col items-center gap-2">
            <div class="h-6 w-6 border-3 border-blue-300 border-t-blue-600 rounded-full animate-spin"></div>
            <p class="text-xs text-gray-600 dark:text-gray-400">Memperbarui data...</p>
        </div>
    </div>
</div>
