@extends('layouts.magang')

@section('title', 'Logbook')

@section('content')
<div class="mx-auto w-full space-y-6">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-2xl border border-blue-100 bg-gradient-to-r from-blue-50 via-sky-50 to-cyan-50 p-6 shadow-sm dark:border-blue-900/40 dark:from-gray-800 dark:via-gray-800 dark:to-blue-950/30">
        <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full bg-blue-200/50 blur-3xl dark:bg-blue-700/20"></div>
        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="mb-2 inline-flex items-center rounded-full border border-blue-200 bg-white/80 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-700 dark:border-blue-700/60 dark:bg-blue-900/30 dark:text-blue-300">Jurnal Magang Harian</p>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Logbook Aktivitas</h1>
                <p class="text-gray-600 dark:text-gray-300">Catatan kegiatan magang Anda per hari secara terstruktur.</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="showAddLogbookForm()" class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-4 py-2.5 rounded-xl font-medium transition-all shadow-sm hover:shadow-md active:scale-[0.98] flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Tambah Logbook</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="grid grid-cols-3 gap-4 text-center">
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 transition hover:-translate-y-0.5 hover:shadow-sm dark:border-gray-700 dark:bg-gray-900/40">
                <p class="text-2xl font-bold text-gray-900 dark:text-white" id="stat-total">0</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Entri</p>
            </div>
            <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 transition hover:-translate-y-0.5 hover:shadow-sm dark:border-blue-900/40 dark:bg-blue-900/20">
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="stat-month">0</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Bulan Ini</p>
            </div>
            <div class="rounded-xl border border-green-100 bg-green-50 p-4 transition hover:-translate-y-0.5 hover:shadow-sm dark:border-green-900/40 dark:bg-green-900/20">
                <p class="text-2xl font-bold text-green-600 dark:text-green-400" id="stat-week">0</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Minggu Ini</p>
            </div>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter tanggal</div>
            <input type="date" id="filterDate" class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="filterLogbookByDate()">
            <button type="button" onclick="document.getElementById('filterDate').value=''; loadLogbook();" class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Reset</button>
        </div>
    </div>

    <!-- Logbook Entries -->
    <div class="space-y-4">
        <div id="logbookList">
            <div class="text-center py-8 rounded-2xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                <svg class="w-8 h-8 text-blue-600 animate-spin mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </div>
        </div>
        <div id="logbookPagination" class="hidden flex-col gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between"></div>
    </div>
</div>

<!-- Add/Edit Logbook Modal -->
<div id="logbookModal" class="hidden fixed inset-0 px-4 bg-black bg-opacity-0 z-50 transition-all duration-300 ease-out items-center justify-center">
    <div id="logbookModalContent" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-[90%] sm:w-full sm:max-w-lg md:max-w-2xl max-h-[80vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300 ease-out">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <h3 id="logbookModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Logbook</h3>
                    <button onclick="closeLogbookModal()" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <form id="logbookForm" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                    <input type="date" id="formTanggal" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Aktivitas</label>
                    <input type="text" id="formAktivitas" placeholder="Contoh: Meeting dengan mentor" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                    <textarea id="formDeskripsi" placeholder="Jelaskan aktivitas secara detail..." rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jam Mulai</label>
                        <input type="time" id="formJamMulai" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jam Selesai</label>
                        <input type="time" id="formJamSelesai" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeLogbookModal()" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn" class="flex-1 px-4 py-2 bg-blue-600 dark:bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-700 dark:hover:bg-blue-600 transition flex items-center justify-center gap-2">
                        <span id="submitText">Simpan</span>
                        <svg id="submitLoader" class="w-4 h-4 animate-spin hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let currentPage = 1;
let editingLogbookId = null;
const logbookRowsMap = {};

// Load logbook on page load
document.addEventListener('DOMContentLoaded', function() {
    setTodayDate();
    loadLogbook(null, 1);
    updateStats();
});

function setTodayDate() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('formTanggal').value = today;
    document.getElementById('filterDate').value = today;
}

function showAddLogbookForm() {
    const modal = document.getElementById('logbookModal');
    const modalContent = document.getElementById('logbookModalContent');
    
    document.getElementById('logbookForm').reset();
    editingLogbookId = null;
    document.getElementById('logbookModalTitle').textContent = 'Tambah Logbook';
    document.getElementById('submitText').textContent = 'Simpan';
    setTodayDate();
    
    // Show modal with animation
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Trigger animation after a brief delay to ensure CSS transition works
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            modal.classList.remove('bg-opacity-0');
            modal.classList.add('bg-opacity-50');
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        });
    });
}

function closeLogbookModal() {
    const modal = document.getElementById('logbookModal');
    const modalContent = document.getElementById('logbookModalContent');
    
    // Animate out
    modal.classList.remove('bg-opacity-50');
    modal.classList.add('bg-opacity-0');
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    // Hide after animation completes
    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }, 300);
}

function showEditLogbookForm(logbookId) {
    const modal = document.getElementById('logbookModal');
    const modalContent = document.getElementById('logbookModalContent');
    const row = logbookRowsMap[logbookId];

    if (!row) {
        showToast('error', 'Data logbook tidak ditemukan. Muat ulang halaman lalu coba lagi.');
        return;
    }

    editingLogbookId = row.id;
    document.getElementById('logbookModalTitle').textContent = 'Edit Logbook';
    document.getElementById('submitText').textContent = 'Perbarui';
    document.getElementById('formTanggal').value = row.tanggal || '';
    document.getElementById('formAktivitas').value = row.aktivitas || '';
    document.getElementById('formDeskripsi').value = row.deskripsi || '';
    document.getElementById('formJamMulai').value = row.jam_mulai || '';
    document.getElementById('formJamSelesai').value = row.jam_selesai || '';

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            modal.classList.remove('bg-opacity-0');
            modal.classList.add('bg-opacity-50');
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        });
    });
}

async function loadLogbook(tanggal = null, page = 1) {
    try {
        currentPage = page;
        const params = new URLSearchParams();

        if (tanggal) {
            params.append('tanggal', tanggal);
        }

        params.append('page', page);
        const url = `/magang/logbook/data?${params.toString()}`;
        
        console.log('Fetching logbook data from:', url);
        const response = await fetch(url, {
            headers: { 
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Logbook data received:', data);
        
        if (data.success) {
            renderLogbookList(data.rows, data.pagination, tanggal);
        } else {
            showToast('error', 'Gagal memuat logbook: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error loading logbook:', error);
        const errorMsg = error.message || 'Terjadi kesalahan saat memuat logbook';
        if (errorMsg.includes('Failed to fetch') || errorMsg.includes('NetworkError')) {
            showToast('error', 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
        } else {
            showToast('error', 'Tidak dapat mengambil data logbook. Silakan coba lagi.');
        }
        // Show empty state
        document.getElementById('logbookList').innerHTML = `
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Gagal memuat data</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Terjadi kesalahan saat mengambil data logbook</p>
                <button onclick="loadLogbook()" class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    Coba Lagi
                </button>
            </div>
        `;
        document.getElementById('logbookPagination').classList.add('hidden');
        document.getElementById('logbookPagination').classList.remove('flex');
    }
}

function renderLogbookList(rows, pagination, tanggalFilter = null) {
    const container = document.getElementById('logbookList');
    Object.keys(logbookRowsMap).forEach(key => delete logbookRowsMap[key]);
    
    if (rows.length === 0) {
        container.innerHTML = `
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada logbook</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Mulai catat aktivitas magang Anda setiap hari</p>
                <button onclick="showAddLogbookForm()" class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    Buat Logbook Pertama
                </button>
            </div>
        `;
        document.getElementById('logbookPagination').classList.add('hidden');
        document.getElementById('logbookPagination').classList.remove('flex');
        return;
    }

    let html = '<div class="space-y-3">';
    rows.forEach(row => {
        logbookRowsMap[row.id] = row;
        const tanggal = new Date(row.tanggal).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        const durasi = row.jam_mulai && row.jam_selesai ? `${row.jam_mulai} - ${row.jam_selesai}` : '-';
        
        html += `
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 transition hover:shadow-md">
                <div class="mb-2 flex items-start justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <h3 class="break-words font-semibold text-gray-900 dark:text-white">${row.aktivitas}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">${tanggal}</p>
                    </div>
                    <div class="flex items-center gap-1">
                        <button onclick="showEditLogbookForm(${row.id})" class="rounded-lg p-1.5 text-blue-500 transition hover:bg-blue-50 hover:text-blue-700 dark:hover:bg-blue-900/30 dark:hover:text-blue-300" title="Edit logbook">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button onclick="deleteLogbook(${row.id})" class="rounded-lg p-1.5 text-red-500 transition hover:bg-red-50 hover:text-red-700 dark:hover:bg-red-900/30 dark:hover:text-red-300" title="Hapus logbook">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <p class="mb-2 break-words text-sm text-gray-600 dark:text-gray-300">${row.deskripsi || '-'}</p>
                <p class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs text-gray-600 dark:bg-gray-700 dark:text-gray-300 gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    ${durasi}
                </p>
            </div>
        `;
    });
    html += '</div>';

    container.innerHTML = html;
    renderLogbookPagination(pagination, tanggalFilter);
}

function renderLogbookPagination(pagination, tanggalFilter = null) {
    const paginationContainer = document.getElementById('logbookPagination');

    if (!pagination) {
        paginationContainer.classList.add('hidden');
        paginationContainer.classList.remove('flex');
        return;
    }

    paginationContainer.classList.remove('hidden');
    paginationContainer.classList.add('flex');

    const prevDisabled = pagination.current_page <= 1;
    const nextDisabled = pagination.current_page >= pagination.last_page;

    paginationContainer.innerHTML = `
        <p class="text-xs text-gray-600 dark:text-gray-400">
            Menampilkan ${pagination.from ?? 0}-${pagination.to ?? 0} dari ${pagination.total} logbook
            (Halaman ${pagination.current_page} dari ${pagination.last_page})
        </p>
        <div class="flex flex-wrap items-center gap-2">
            <button
                type="button"
                ${prevDisabled ? 'disabled' : ''}
                onclick="changeLogbookPage(${pagination.current_page - 1}, '${tanggalFilter ?? ''}')"
                class="rounded-lg border border-gray-300 px-3 py-1.5 font-medium transition ${prevDisabled ? 'cursor-not-allowed opacity-50 dark:border-gray-700 dark:text-gray-500' : 'hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700'}"
            >
                Sebelumnya
            </button>
            <span class="px-2 text-gray-700 dark:text-gray-300">${pagination.current_page} / ${pagination.last_page}</span>
            <button
                type="button"
                ${nextDisabled ? 'disabled' : ''}
                onclick="changeLogbookPage(${pagination.current_page + 1}, '${tanggalFilter ?? ''}')"
                class="rounded-lg border border-gray-300 px-3 py-1.5 font-medium transition ${nextDisabled ? 'cursor-not-allowed opacity-50 dark:border-gray-700 dark:text-gray-500' : 'hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700'}"
            >
                Berikutnya
            </button>
        </div>
    `;
}

function changeLogbookPage(page, tanggalFilter = '') {
    if (!page || page < 1) return;
    loadLogbook(tanggalFilter || null, page);
}

function filterLogbookByDate() {
    const tanggal = document.getElementById('filterDate').value;
    loadLogbook(tanggal, 1);
}

async function submitLogbook(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitLoader = document.getElementById('submitLoader');
    
    // Disable button and show loader
    submitBtn.disabled = true;
    submitText.textContent = 'Menyimpan...';
    submitLoader.classList.remove('hidden');
    
    const formData = new FormData();
    formData.append('tanggal', document.getElementById('formTanggal').value);
    formData.append('aktivitas', document.getElementById('formAktivitas').value);
    formData.append('deskripsi', document.getElementById('formDeskripsi').value);
    formData.append('jam_mulai', document.getElementById('formJamMulai').value);
    formData.append('jam_selesai', document.getElementById('formJamSelesai').value);

    const isEditMode = editingLogbookId !== null;
    const requestUrl = isEditMode ? `/magang/logbook/${editingLogbookId}` : '/magang/logbook/store';
    const successMessage = isEditMode ? 'Logbook berhasil diperbarui' : 'Logbook berhasil disimpan';

    if (isEditMode) {
        formData.append('_method', 'PUT');
    }

    try {
        const response = await fetch(requestUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            body: formData
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Submit response:', data);
        
        if (data.success) {
            closeLogbookModal();
            const activeFilter = document.getElementById('filterDate').value || null;
            loadLogbook(activeFilter, currentPage);
            updateStats();
            showToast('success', successMessage);
        } else {
            const errorMsg = data.message || (isEditMode ? 'Gagal memperbarui logbook' : 'Gagal menyimpan logbook');
            showToast('error', errorMsg);
            console.error('Server error:', data);
        }
    } catch (error) {
        console.error('Error saving logbook:', error);
        showToast('error', 'Terjadi kesalahan saat menyimpan logbook: ' + error.message);
    } finally {
        // Re-enable button and hide loader
        submitBtn.disabled = false;
        submitText.textContent = 'Simpan';
        submitLoader.classList.add('hidden');
    }
}

async function deleteLogbook(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus logbook ini?')) return;
    
    try {
        const response = await fetch(`/magang/logbook/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken }
        });
        const data = await response.json();
        
        if (data.success) {
            const activeFilter = document.getElementById('filterDate').value || null;
            loadLogbook(activeFilter, currentPage);
            updateStats();
            showToast('success', 'Logbook berhasil dihapus');
        } else {
            showToast('error', data.message || 'Gagal menghapus logbook');
        }
    } catch (error) {
        console.error('Error deleting logbook:', error);
        showToast('error', 'Terjadi kesalahan saat menghapus logbook.');
    }
}

async function updateStats() {
    try {
        const response = await fetch('/magang/logbook/stats', {
            headers: { 
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        if (!response.ok) {
            console.warn('Failed to fetch stats, using defaults');
            return;
        }
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('stat-total').textContent = data.total;
            document.getElementById('stat-month').textContent = data.month;
            document.getElementById('stat-week').textContent = data.week;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
        // Silently fail, keep showing 0
    }
}

// Handle form submission
document.getElementById('logbookForm').addEventListener('submit', submitLogbook);

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('logbookModal');
    if (event.target === modal) {
        closeLogbookModal();
    }
});
</script>
@endsection