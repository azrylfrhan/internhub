@extends('layouts.magang')

@section('title', 'Logbook')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Logbook Aktivitas</h1>
            <p class="text-gray-600 dark:text-gray-400">Catatan harian aktivitas magang Anda</p>
        </div>
        <button onclick="showAddLogbookForm()" class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Logbook</span>
        </button>
    </div>

    <!-- Stats -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="grid grid-cols-3 gap-4 text-center">
            <div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white" id="stat-total">0</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Entri</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="stat-month">0</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Bulan Ini</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400" id="stat-week">0</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Minggu Ini</p>
            </div>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <input type="date" id="filterDate" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="filterLogbookByDate()">
    </div>

    <!-- Logbook Entries -->
    <div class="space-y-4">
        <div id="logbookList">
            <div class="text-center py-8">
                <svg class="w-8 h-8 text-blue-600 animate-spin mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Logbook Modal -->
<div id="logbookModal" class="hidden fixed inset-0 px-4 bg-black bg-opacity-0 z-50 transition-all duration-300 ease-out items-center justify-center">
    <div id="logbookModalContent" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-[90%] sm:w-full sm:max-w-lg md:max-w-2xl max-h-[80vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300 ease-out">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Logbook</h3>
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

// Load logbook on page load
document.addEventListener('DOMContentLoaded', function() {
    setTodayDate();
    loadLogbook();
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

async function loadLogbook(tanggal = null) {
    try {
        let url = '/magang/logbook/data';
        if (tanggal) {
            url += '?tanggal=' + encodeURIComponent(tanggal);
        }
        
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
            renderLogbookList(data.rows);
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
    }
}

function renderLogbookList(rows) {
    const container = document.getElementById('logbookList');
    
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
        return;
    }

    let html = '';
    rows.forEach(row => {
        const tanggal = new Date(row.tanggal).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        const durasi = row.jam_mulai && row.jam_selesai ? `${row.jam_mulai} - ${row.jam_selesai}` : '-';
        
        html += `
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">${row.aktivitas}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">${tanggal}</p>
                    </div>
                    <button onclick="deleteLogbook(${row.id})" class="text-red-500 hover:text-red-700 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-gray-600 mb-2">${row.deskripsi}</p>
                <p class="text-xs text-gray-500 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    ${durasi}
                </p>
            </div>
        `;
    });

    container.innerHTML = html;
}

function filterLogbookByDate() {
    const tanggal = document.getElementById('filterDate').value;
    loadLogbook(tanggal);
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

    try {
        const response = await fetch('/magang/logbook/store', {
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
            loadLogbook();
            updateStats();
            showToast('success', 'Logbook berhasil disimpan');
        } else {
            const errorMsg = data.message || 'Gagal menyimpan logbook';
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
            loadLogbook();
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