@extends('layouts.magang')

@section('title', 'Absensi')

@section('content')
<div class="space-y-6" x-data="{ openModalIzin: {{ ($errors->has('permission_type') || $errors->has('reason') || $errors->has('medical_document')) ? 'true' : 'false' }}, permissionType: '{{ old('permission_type', 'sakit') }}' }" @keydown.escape.window="openModalIzin = false">
    <!-- Today's Attendance Status -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="text-center">
            <div class="w-20 h-20 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Absensi Hari Ini</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-4">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>

            <!-- Status Absensi -->
            <div id="attendance-status" class="mb-6">
                <!-- Status akan diupdate oleh JavaScript -->
            </div>

            <div id="permission-status-info" class="mb-6"></div>

            <!-- Tombol Absen -->
            <div id="attendance-buttons" class="space-y-4">
                <!-- Tombol akan diupdate oleh JavaScript -->
            </div>

            <div id="permission-submit-wrapper" class="mt-3">
                <button
                    id="btn-ajukan-izin"
                    type="button"
                    @click="openModalIzin = true"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-400"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"></path>
                    </svg>
                    Ajukan Izin
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Ajukan Izin -->
    <div
        x-show="openModalIzin"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak
        class="fixed inset-0 z-50 flex items-end justify-center sm:items-center sm:px-4 bg-black/60 backdrop-blur-sm"
        @click.self="openModalIzin = false"
    >
        <!-- Modal card: flex column, max height set HERE, not on form -->
        <div
            x-show="openModalIzin"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
            class="relative flex w-full flex-col sm:max-w-lg rounded-t-3xl sm:rounded-3xl bg-white dark:bg-gray-900 shadow-2xl border border-gray-200 dark:border-gray-700"
            style="max-height: 92dvh; max-height: 92vh;"
        >
            <!-- Accent gradient bar -->
            <div class="h-1.5 w-full flex-shrink-0 rounded-t-3xl sm:rounded-t-3xl bg-gradient-to-r from-amber-400 via-orange-400 to-amber-500"></div>

            <!-- Drag handle (mobile only) -->
            <div class="flex flex-shrink-0 justify-center pb-1 pt-2.5 sm:hidden">
                <div class="h-1 w-10 rounded-full bg-gray-300 dark:bg-gray-600"></div>
            </div>

            <!-- Header (flex-shrink-0 so it never gets compressed) -->
            <div class="flex flex-shrink-0 items-center justify-between gap-3 px-5 pt-3 pb-4 sm:px-6 sm:pt-5">
                <div class="flex min-w-0 items-center gap-3">
                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-800/60">
                        <svg class="h-5 w-5 text-amber-600 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Ajukan Izin</h3>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400">Akan diproses oleh mentor atau admin</p>
                    </div>
                </div>
                <button type="button" @click="openModalIzin = false"
                    class="flex-shrink-0 rounded-xl p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-500 dark:hover:bg-gray-800 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form: flex column, fills remaining card height -->
            <form method="POST" action="{{ route('magang.permissions.store') }}" enctype="multipart/form-data"
                class="flex min-h-0 flex-1 flex-col">
                @csrf

                <!-- Scrollable body -->
                <div class="flex-1 overflow-y-auto px-5 pb-2 sm:px-6">
                    <div class="space-y-4 pb-4">

                        <!-- Tanggal info card -->
                        <div class="flex items-center gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-700/50 dark:bg-amber-900/40">
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-amber-200/70 dark:bg-amber-800/70">
                                <svg class="h-5 w-5 text-amber-700 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold uppercase tracking-wider text-amber-600 dark:text-amber-400">Tanggal Pengajuan</p>
                                <p class="text-sm font-bold text-amber-900 dark:text-amber-100">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
                                <p class="text-xs text-amber-700 dark:text-amber-300">Tanggal izin otomatis sesuai hari ini</p>
                            </div>
                        </div>

                        <!-- Jenis Izin — Radio Cards -->
                        <div>
                            <p class="mb-3 text-sm font-semibold text-gray-800 dark:text-gray-100">
                                Jenis Izin <span class="text-rose-500">*</span>
                            </p>
                            <div class="grid grid-cols-2 gap-3">

                                <!-- Sakit -->
                                <label class="relative flex cursor-pointer flex-col items-center gap-2.5 rounded-2xl border-2 p-4 transition-all duration-200"
                                    :class="permissionType === 'sakit'
                                        ? 'border-amber-400 bg-amber-50 shadow-lg dark:border-amber-400 dark:bg-amber-800/40'
                                        : 'border-gray-200 bg-white hover:border-amber-300 hover:bg-amber-50/50 dark:border-gray-600 dark:bg-gray-800 dark:hover:border-amber-500/60 dark:hover:bg-amber-900/20'">
                                    <input type="radio" name="permission_type" value="sakit" x-model="permissionType" class="sr-only">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl transition-colors duration-200"
                                        :class="permissionType === 'sakit' ? 'bg-amber-200 dark:bg-amber-700/70' : 'bg-gray-100 dark:bg-gray-700'">
                                        <svg class="h-6 w-6 transition-colors duration-200"
                                            :class="permissionType === 'sakit' ? 'text-amber-700 dark:text-amber-200' : 'text-gray-400 dark:text-gray-400'"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-bold transition-colors duration-200"
                                        :class="permissionType === 'sakit' ? 'text-amber-800 dark:text-amber-200' : 'text-gray-600 dark:text-gray-300'">
                                        Sakit
                                    </span>
                                    <!-- Checkmark badge -->
                                    <div class="absolute right-2.5 top-2.5 transition-all duration-200"
                                        :class="permissionType === 'sakit' ? 'opacity-100 scale-100' : 'opacity-0 scale-75'">
                                        <div class="flex h-5 w-5 items-center justify-center rounded-full bg-amber-500 dark:bg-amber-400">
                                            <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </label>

                                <!-- Alasan Lain -->
                                <label class="relative flex cursor-pointer flex-col items-center gap-2.5 rounded-2xl border-2 p-4 transition-all duration-200"
                                    :class="permissionType === 'lainnya'
                                        ? 'border-blue-400 bg-blue-50 shadow-lg dark:border-blue-400 dark:bg-blue-800/40'
                                        : 'border-gray-200 bg-white hover:border-blue-300 hover:bg-blue-50/50 dark:border-gray-600 dark:bg-gray-800 dark:hover:border-blue-500/60 dark:hover:bg-blue-900/20'">
                                    <input type="radio" name="permission_type" value="lainnya" x-model="permissionType" class="sr-only">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl transition-colors duration-200"
                                        :class="permissionType === 'lainnya' ? 'bg-blue-200 dark:bg-blue-700/70' : 'bg-gray-100 dark:bg-gray-700'">
                                        <svg class="h-6 w-6 transition-colors duration-200"
                                            :class="permissionType === 'lainnya' ? 'text-blue-700 dark:text-blue-200' : 'text-gray-400 dark:text-gray-400'"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-bold transition-colors duration-200"
                                        :class="permissionType === 'lainnya' ? 'text-blue-800 dark:text-blue-200' : 'text-gray-600 dark:text-gray-300'">
                                        Alasan Lain
                                    </span>
                                    <!-- Checkmark badge -->
                                    <div class="absolute right-2.5 top-2.5 transition-all duration-200"
                                        :class="permissionType === 'lainnya' ? 'opacity-100 scale-100' : 'opacity-0 scale-75'">
                                        <div class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-500 dark:bg-blue-400">
                                            <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('permission_type')
                                <p class="mt-2 flex items-center gap-1 text-xs text-rose-600 dark:text-rose-400">
                                    <svg class="h-3.5 w-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Dokumen Sakit -->
                        <div x-show="permissionType === 'sakit'"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            x-cloak>
                            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-700/60 dark:bg-rose-900/30">
                                <div class="mb-3 flex items-center gap-2.5">
                                    <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-rose-200/80 dark:bg-rose-700/60">
                                        <svg class="h-4 w-4 text-rose-700 dark:text-rose-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-rose-800 dark:text-rose-200">Dokumen Izin Sakit</p>
                                        <p class="text-xs text-rose-600 dark:text-rose-300">Wajib dilampirkan untuk izin sakit</p>
                                    </div>
                                </div>
                                <!-- Upload area -->
                                <div x-data="{ fileName: null }" class="relative">
                                    <label for="medical_document"
                                        class="relative flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed px-4 py-6 transition-all"
                                        :class="fileName
                                            ? 'border-rose-400 bg-white dark:border-rose-500 dark:bg-gray-800'
                                            : 'border-rose-300 bg-white hover:border-rose-400 hover:bg-rose-50/80 dark:border-rose-600/70 dark:bg-gray-800/80 dark:hover:border-rose-500 dark:hover:bg-rose-900/25'"
                                        @dragover.prevent
                                        @drop.prevent="fileName = $event.dataTransfer.files[0]?.name; $refs.fileInput.files = $event.dataTransfer.files">
                                        <input type="file" id="medical_document" name="medical_document" accept=".pdf,.jpg,.jpeg,.png"
                                            class="absolute inset-0 cursor-pointer opacity-0"
                                            x-ref="fileInput"
                                            @change="fileName = $event.target.files[0]?.name">

                                        <template x-if="!fileName">
                                            <div class="flex flex-col items-center gap-2 text-center pointer-events-none">
                                                <svg class="h-10 w-10 text-rose-400 dark:text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">Klik untuk unggah <span class="text-rose-600 dark:text-rose-300">atau seret file</span></p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">PDF, JPG, PNG — maks. 3 MB</p>
                                                </div>
                                            </div>
                                        </template>

                                        <template x-if="fileName">
                                            <div class="flex items-center gap-3 pointer-events-none">
                                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-rose-100 dark:bg-rose-800/60">
                                                    <svg class="h-5 w-5 text-rose-600 dark:text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="truncate text-sm font-bold text-rose-800 dark:text-rose-200" x-text="fileName"></p>
                                                    <p class="text-xs font-medium text-emerald-700 dark:text-emerald-300">✓ Siap diunggah — klik untuk ganti</p>
                                                </div>
                                            </div>
                                        </template>
                                    </label>
                                </div>
                                @error('medical_document')
                                    <p class="mt-2 flex items-center gap-1 text-xs text-rose-700 dark:text-rose-300">
                                        <svg class="h-3.5 w-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Alasan Lainnya (textarea) -->
                        <div x-show="permissionType === 'lainnya'"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            x-cloak>
                            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 dark:border-blue-700/60 dark:bg-blue-900/30">
                                <div class="mb-3 flex items-center gap-2.5">
                                    <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-blue-200/80 dark:bg-blue-700/60">
                                        <svg class="h-4 w-4 text-blue-700 dark:text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-blue-800 dark:text-blue-200">Keterangan Alasan</p>
                                        <p class="text-xs text-blue-600 dark:text-blue-300">Jelaskan alasan tidak masuk dengan lengkap</p>
                                    </div>
                                </div>
                                <div x-data="{ charCount: {{ strlen(old('reason', '')) }} }">
                                    <textarea id="reason" name="reason" rows="4" maxlength="1500"
                                        placeholder="Contoh: Saya izin tidak masuk karena ada keperluan keluarga yang mendesak..."
                                        @input="charCount = $event.target.value.length"
                                        class="w-full resize-none rounded-xl border border-blue-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 transition-all focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300/50 dark:border-blue-600/60 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-500 dark:focus:border-blue-400 dark:focus:ring-blue-400/25">{{ old('reason') }}</textarea>
                                    <div class="mt-1.5 flex items-center justify-between">
                                        @error('reason')
                                            <p class="flex items-center gap-1 text-xs text-rose-600 dark:text-rose-400">
                                                <svg class="h-3.5 w-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                                {{ $message }}
                                            </p>
                                        @else
                                            <span></span>
                                        @enderror
                                        <p class="text-xs"
                                            :class="charCount > 1400 ? 'text-rose-500 dark:text-rose-400 font-semibold' : 'text-gray-500 dark:text-gray-400'">
                                            <span x-text="charCount"></span>/1500
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Footer tombol — flex-shrink-0 agar selalu kelihatan -->
                <div class="flex flex-shrink-0 flex-col-reverse gap-2.5 border-t border-gray-200 bg-white px-5 py-4 dark:border-gray-700 dark:bg-gray-900 sm:flex-row sm:justify-end sm:px-6">
                    <button type="button" @click="openModalIzin = false"
                        class="w-full rounded-xl border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800 sm:w-auto sm:py-2.5">
                        Batal
                    </button>
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-amber-600 bg-amber-600 bg-gradient-to-r from-amber-500 to-orange-500 px-5 py-3 text-sm font-bold text-white shadow-sm transition-all hover:border-amber-700 hover:bg-amber-700 hover:from-amber-600 hover:to-orange-600 hover:shadow-md active:scale-95 focus:outline-none focus:ring-2 focus:ring-amber-400 sm:w-auto sm:py-2.5">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->presensis()->count() }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">Total Absensi</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->presensis()->where('status', 'hadir')->count() }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">Hadir Tepat Waktu</p>
        </div>
    </div>

    <!-- Kalender Absensi -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 md:p-6 relative">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg md:text-xl font-semibold text-gray-900 dark:text-white">Kalender Absensi</h3>
            <div class="flex items-center space-x-3">
                <button type="button" onclick="previousMonth()" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <span id="calendar-month-year" class="text-sm md:text-base text-gray-700 dark:text-gray-300 font-semibold min-w-[140px] text-center"></span>
                <button type="button" onclick="nextMonth()" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Loading overlay untuk kalender -->
        <div id="calendar-loading" class="hidden absolute inset-0 bg-white dark:bg-gray-800 bg-opacity-75 dark:bg-opacity-75 items-center justify-center rounded-lg z-10">
            <div class="flex flex-col items-center">
                <svg class="w-10 h-10 text-blue-600 dark:text-blue-400 animate-spin mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <p class="text-sm text-gray-600 dark:text-gray-400">Memuat kalender...</p>
            </div>
        </div>
        
        <div class="grid grid-cols-7 gap-1 md:gap-2">
            <!-- Header hari -->
            <div class="text-center font-semibold text-xs md:text-sm text-gray-600 dark:text-gray-400 py-2 md:py-3">Min</div>
            <div class="text-center font-semibold text-xs md:text-sm text-gray-600 dark:text-gray-400 py-2 md:py-3">Sen</div>
            <div class="text-center font-semibold text-xs md:text-sm text-gray-600 dark:text-gray-400 py-2 md:py-3">Sel</div>
            <div class="text-center font-semibold text-xs md:text-sm text-gray-600 dark:text-gray-400 py-2 md:py-3">Rab</div>
            <div class="text-center font-semibold text-xs md:text-sm text-gray-600 dark:text-gray-400 py-2 md:py-3">Kam</div>
            <div class="text-center font-semibold text-xs md:text-sm text-gray-600 dark:text-gray-400 py-2 md:py-3">Jum</div>
            <div class="text-center font-semibold text-xs md:text-sm text-gray-600 dark:text-gray-400 py-2 md:py-3">Sab</div>
            
            <!-- Tanggal kalender -->
            <div id="calendar-container" class="contents">
                <!-- Diisi oleh JavaScript -->
            </div>
        </div>
        
        <!-- Legend -->
        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 grid grid-cols-2 md:grid-cols-4 gap-3 text-xs md:text-sm">
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-green-100 dark:bg-green-900/30 rounded"></div>
                <span class="text-gray-700 dark:text-gray-300">Hadir</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-orange-100 dark:bg-orange-900/30 rounded"></div>
                <span class="text-gray-700 dark:text-gray-300">Terlambat</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-indigo-100 dark:bg-indigo-900/30 rounded"></div>
                <span class="text-gray-700 dark:text-gray-300">Izin</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-gray-200 dark:bg-gray-700 rounded"></div>
                <span class="text-gray-700 dark:text-gray-300">Alpa / Belum Ada</span>
            </div>
        </div>
    </div>


    <!-- Recent Attendance -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Riwayat Absensi</h3>

        @php
            $recentAttendances = Auth::user()->presensis()->latest()->take(5)->get();
        @endphp

        @if($recentAttendances->count() > 0)
            <div class="space-y-3">
                @foreach($recentAttendances as $attendance)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    @php
                                        $tgl = $attendance->tanggal instanceof \Carbon\Carbon ? $attendance->tanggal : \Carbon\Carbon::parse($attendance->tanggal);
                                    @endphp
                                    {{ $tgl->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Masuk: {{ $attendance->jam_masuk ? $attendance->jam_masuk : '-' }}
                                    @if($attendance->jam_pulang)
                                        | Pulang: {{ $attendance->jam_pulang }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($attendance->status == 'hadir') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                @elseif($attendance->status == 'terlambat') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                {{ ucfirst($attendance->status) }}
                            </span>
                            @if($attendance->jam_pulang)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">✓ Pulang</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">Belum ada riwayat absensi</p>
            </div>
        @endif
    </div>

    <!-- Modal Detail Absensi -->
    <div id="modal-detail-absensi" class="fixed inset-0 px-4 bg-black bg-opacity-0 hidden items-center justify-center z-50 transition-all duration-300 ease-out">
        <div id="modal-detail-content" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-[90%] sm:w-full sm:max-w-lg md:max-w-2xl md:max-h-[80vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300 ease-out">
            <div class="sticky top-0 bg-white dark:bg-gray-800 p-4 md:p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center rounded-t-2xl">
                <h3 class="text-lg md:text-xl font-semibold text-gray-900 dark:text-white" id="modal-tanggal">Tanggal</h3>
                <button type="button" onclick="closeModalDetail()" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4 md:p-6 space-y-4" id="modal-content">
                <!-- Konten diisi oleh JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
// Untuk mode test: tombol isi koordinat kantor
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('btn-use-office-location');
    if (btn) {
        btn.addEventListener('click', function() {
            window.mockOfficeLocation = { latitude: 1.46759, longitude: 124.84542 };
            showMessage('Mode test: Koordinat kantor BPS diaktifkan', 'info');
        });
    }
});
// Bersihkan kode tombol testing/reset, hanya logika absensi utama yang berjalan

// CSRF token untuk AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Fungsi untuk mendapatkan lokasi GPS
function getCurrentLocation() {
    // Jika mode test, pakai koordinat kantor
    if (window.mockOfficeLocation) {
        return Promise.resolve(window.mockOfficeLocation);
    }
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            reject(new Error('Geolocation tidak didukung oleh browser ini'));
            return;
        }
        navigator.geolocation.getCurrentPosition(
            (position) => {
                resolve({
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                });
            },
            (error) => {
                reject(new Error('Tidak dapat mendapatkan lokasi: ' + error.message));
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 300000 // 5 menit
            }
        );
    });
}

// Fungsi untuk memuat status absensi hari ini
async function loadAttendanceStatus() {
    try {
        const response = await fetch(`/presensi/status-hari-ini?ts=${Date.now()}`, {
            method: 'GET',
            cache: 'no-store',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        updateAttendanceUI(data);
    } catch (error) {
        console.error('Error loading attendance status:', error);
        showMessage('Gagal memuat status absensi', 'error');
    }
}

// Fungsi untuk update UI berdasarkan status
function updateAttendanceUI(data) {
    const statusDiv = document.getElementById('attendance-status');
    const buttonsDiv = document.getElementById('attendance-buttons');
    const permissionInfoDiv = document.getElementById('permission-status-info');
    const permissionSubmitWrapper = document.getElementById('permission-submit-wrapper');

    if (data.can_attend === false) {
        const blockReason = data.attendance_block_reason || 'Akun tidak dapat melakukan absensi saat ini.';

        statusDiv.innerHTML = `
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 dark:bg-slate-800/70 dark:border-slate-700">
                <div class="text-center">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-500 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Absensi Tidak Tersedia</h3>
                    <p class="text-slate-700 dark:text-slate-300">${blockReason}</p>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Kalender absensi dan logbook tetap dapat diakses.</p>
                </div>
            </div>
        `;

        buttonsDiv.innerHTML = '';
        permissionInfoDiv.innerHTML = '';

        if (permissionSubmitWrapper) {
            permissionSubmitWrapper.classList.add('hidden');
        }

        return;
    }

    if (permissionSubmitWrapper) {
        // Tombol Ajukan Izin hanya muncul jika bisa submit dan belum absen masuk
        if (data.can_submit_permission && !data.sudah_absen_masuk && !data.sudah_hadir_hari_ini) {
            permissionSubmitWrapper.classList.remove('hidden');
        } else {
            permissionSubmitWrapper.classList.add('hidden');
        }
    }

    if (permissionInfoDiv) {
        const latestPermission = data.latest_permission;
        const todayStr = new Date().toISOString().slice(0, 10);
        // Hanya tampilkan notifikasi izin jika ada pengajuan dan masih relevan (end_date >= hari ini)
        if (!latestPermission || latestPermission.end_date < todayStr) {
            permissionInfoDiv.innerHTML = '';
        } else {
            const statusMap = {
                pending: {
                    title: 'Pengajuan izin sedang diproses',
                    badge: 'Pending',
                    wrapper: 'bg-amber-50 border-amber-200',
                    badgeClass: 'bg-amber-100 text-amber-700',
                    textClass: 'text-amber-800'
                },
                approved: {
                    title: 'Pengajuan izin disetujui',
                    badge: 'Approved',
                    wrapper: 'bg-green-50 border-green-200',
                    badgeClass: 'bg-green-100 text-green-700',
                    textClass: 'text-green-800'
                },
                rejected: {
                    title: 'Pengajuan izin ditolak',
                    badge: 'Rejected',
                    wrapper: 'bg-rose-50 border-rose-200',
                    badgeClass: 'bg-rose-100 text-rose-700',
                    textClass: 'text-rose-800'
                }
            };

            const meta = statusMap[latestPermission.status] || statusMap.pending;
            const typeText = latestPermission.permission_type_label || 'Izin';
            const detailText = latestPermission.permission_type === 'sakit'
                ? 'Dokumen izin sakit telah diunggah.'
                : (latestPermission.reason || '-');
            const attachmentLink = latestPermission.attachment_url
                ? `<a href="${latestPermission.attachment_url}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex text-xs font-semibold text-blue-700 underline dark:text-blue-300">Lihat dokumen</a>`
                : '';

            permissionInfoDiv.innerHTML = `
                <div class="rounded-xl border p-4 ${meta.wrapper}">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold ${meta.textClass}">${meta.title}</p>
                            <p class="mt-1 text-xs ${meta.textClass}">Jenis izin: ${typeText}</p>
                            <p class="mt-1 text-xs ${meta.textClass}">Keterangan: ${detailText}</p>
                            ${attachmentLink}
                        </div>
                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ${meta.badgeClass}">${meta.badge}</span>
                    </div>
                </div>
            `;

            if (latestPermission.status === 'approved' || latestPermission.status === 'rejected') {
                const statusNoticeKey = `permission-status-seen-${latestPermission.id}-${latestPermission.status}-${latestPermission.updated_at}`;
                if (!localStorage.getItem(statusNoticeKey)) {
                    showMessage(
                        latestPermission.status === 'approved'
                            ? 'Pengajuan izin kamu sudah disetujui.'
                            : 'Pengajuan izin kamu ditolak. Silakan cek kembali detailnya.',
                        latestPermission.status === 'approved' ? 'success' : 'error'
                    );
                    localStorage.setItem(statusNoticeKey, '1');
                }
            }
        }
    }

    if (data.has_approved_permission_today) {
        const extraInfo = data.sudah_absen_masuk
            ? '<p class="mt-2 text-xs text-amber-700">Catatan: absensi masuk sudah tercatat sebelumnya.</p>'
            : '';

        statusDiv.innerHTML = `
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-amber-900 mb-2">Izin Disetujui Untuk Hari Ini</h3>
                    <p class="text-amber-700">Absen masuk disembunyikan karena kamu sedang izin.</p>
                    ${extraInfo}
                </div>
            </div>
        `;
        buttonsDiv.innerHTML = '';
    } else if (data.sudah_hadir_hari_ini) {
        // Sudah absen masuk dan pulang, tidak bisa absen lagi hari ini
        statusDiv.innerHTML = `
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-blue-900 mb-2">Kamu sudah absen hari ini</h3>
                <p class="text-blue-700">Terima kasih, absensi kamu sudah tercatat sebagai hadir 1 hari.</p>
            </div>
        `;
        buttonsDiv.innerHTML = '';
    } else if (data.sudah_absen_masuk) {
        // Sudah absen masuk, belum pulang
        let statusHtml = `
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-emerald-900">Absen Masuk Berhasil</h3>
                        <p class="text-emerald-700">
                            Waktu masuk: <span class="font-medium">${data.data.jam_masuk}</span>
                            ${data.data.status === 'terlambat' ? '<span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Terlambat</span>' : '<span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Tepat Waktu</span>'}
                        </p>
                    </div>
                </div>
            </div>
        `;
        let buttonsHtml = `
            <button id="absen-pulang-btn" class="w-full inline-flex items-center justify-center px-6 py-4 border border-transparent text-base font-medium rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors duration-200 shadow-sm">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Absen Pulang
            </button>
        `;
        statusDiv.innerHTML = statusHtml;
        buttonsDiv.innerHTML = buttonsHtml;
        // Attach event listener untuk absen pulang
        const absenPulangBtn = document.getElementById('absen-pulang-btn');
        if (absenPulangBtn) {
            absenPulangBtn.addEventListener('click', handleAbsenPulang);
        }
    } else {
        // Belum absen masuk
        statusDiv.innerHTML = `
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Absen Hari Ini</h3>
                    <p class="text-gray-600">Silakan lakukan absen masuk untuk memulai kegiatan magang hari ini.</p>
                </div>
            </div>
        `;
        buttonsDiv.innerHTML = `
            <button id="absen-masuk-btn" class="w-full inline-flex items-center justify-center px-6 py-4 border border-transparent text-base font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 shadow-sm">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Absen Masuk
            </button>
        `;
        // Attach event listener untuk absen masuk
        document.getElementById('absen-masuk-btn').addEventListener('click', handleAbsenMasuk);
    }
}

// Fungsi untuk handle absen masuk
async function handleAbsenMasuk() {
    const button = document.getElementById('absen-masuk-btn');
    const originalHtml = button.innerHTML;

    try {
        // Disable button dan tampilkan loading
        button.disabled = true;
        button.innerHTML = `
            <svg class="w-6 h-6 mr-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Meminta Izin Lokasi...
        `;

        // Secara otomatis minta izin lokasi saat tombol diklik
        const location = await getCurrentLocation();

        button.innerHTML = `
            <svg class="w-6 h-6 mr-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Menyimpan Absensi...
        `;

        // Kirim request absen masuk
        const response = await fetch('/presensi/masuk', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                latitude: location.latitude,
                longitude: location.longitude
            })
        });

        const data = await response.json();

        if (data.success) {
            showMessage(data.message, 'success');

            // Tampilkan informasi jarak jika tersedia
            if (data.data && data.data.jarak) {
                setTimeout(() => {
                    showMessage(`Jarak dari kantor: ${data.data.jarak}`, 'info');
                }, 1000);
            }

            loadAttendanceStatus(); // Reload status
            await renderCalendar(currentYear, currentMonth); // Refresh calendar without page reload
        } else {
            showMessage(data.message, 'error');
        }

    } catch (error) {
        console.error('Error:', error);
        showMessage(error.message, 'error');
    } finally {
        button.disabled = false;
        button.innerHTML = originalHtml;
    }
}

// Fungsi untuk handle absen pulang
async function handleAbsenPulang() {
    const button = document.getElementById('absen-pulang-btn');
    const originalHtml = button.innerHTML;

    try {
        // Disable button dan tampilkan loading
        button.disabled = true;
        button.innerHTML = `
            <svg class="w-6 h-6 mr-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Meminta Izin Lokasi...
        `;

        // Secara otomatis minta izin lokasi saat tombol diklik
        const location = await getCurrentLocation();

        button.innerHTML = `
            <svg class="w-6 h-6 mr-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Menyimpan Absensi...
        `;

        // Kirim request absen pulang
        const response = await fetch('/presensi/pulang', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                latitude: location.latitude,
                longitude: location.longitude
            })
        });

        const data = await response.json();

        if (data.success) {
            showMessage(data.message, 'success');

            // Tampilkan informasi jarak jika tersedia
            if (data.data && data.data.jarak) {
                setTimeout(() => {
                    showMessage(`Jarak dari kantor: ${data.data.jarak}`, 'info');
                }, 1000);
            }

            loadAttendanceStatus(); // Reload status
            await renderCalendar(currentYear, currentMonth); // Refresh calendar without page reload
        } else {
            showMessage(data.message, 'error');
        }

    } catch (error) {
        console.error('Error:', error);
        showMessage(error.message, 'error');
    } finally {
        button.disabled = false;
        button.innerHTML = originalHtml;
    }
}

// KALENDER FUNCTIONS
let currentYear = new Date().getFullYear();
let currentMonth = new Date().getMonth() + 1; // JavaScript months are 0-indexed

async function renderCalendar(year = currentYear, month = currentMonth) {
    // Show loading overlay
    const calendarLoading = document.getElementById('calendar-loading');
    calendarLoading.classList.remove('hidden');
    calendarLoading.classList.add('flex');
    
    try {
        const response = await fetch(`/presensi/bulan-ini?year=${year}&month=${month}`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        
        if (!data.success) return;
        
        // Update current tracking
        currentYear = data.year;
        currentMonth = data.month_num;
        
        // Set bulan/tahun di header
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                           'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        document.getElementById('calendar-month-year').textContent = `${monthNames[currentMonth - 1]} ${currentYear}`;
        
        // Dapatkan hari pertama dan jumlah hari di bulan ini
        const firstDay = new Date(currentYear, currentMonth - 1, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();
        
        let html = '';
        
        // Tambah empty cells untuk hari sebelum tanggal 1
        for (let i = 0; i < firstDay; i++) {
            html += '<div class="text-center py-1 md:py-2"></div>';
        }
        
        // Tambah hari-hari dalam bulan
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${currentYear}-${String(currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const presensi = data.calendar[dateStr];
            
            let bgColor = 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300';
            let statusText = '';
            let hoverClass = 'hover:shadow-md cursor-pointer';
            
            if (presensi) {
                hoverClass = 'hover:shadow-lg cursor-pointer';
                if (presensi.status === 'hadir') {
                    bgColor = 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300';
                    statusText = '✓';
                } else if (presensi.status === 'terlambat') {
                    bgColor = 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300';
                    statusText = '⏱';
                } else if (presensi.status === 'izin') {
                    bgColor = 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300';
                    statusText = 'I';
                } else if (presensi.status === 'alpa') {
                    bgColor = 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200';
                    statusText = 'A';
                }
            }
            
            html += `
                <button type="button" 
                    class="calendar-day w-full aspect-square md:aspect-auto md:h-14 flex flex-col items-center justify-center p-1 md:p-2 rounded-lg ${bgColor} ${hoverClass} transition font-semibold text-xs md:text-sm"
                    data-date="${dateStr}"
                    onclick="openModalDetail('${dateStr}')">
                    <div class="text-sm md:text-base">${day}</div>
                    ${presensi ? `<div class="text-xs md:text-xs">${statusText}</div>` : ''}
                </button>
            `;
        }
        
        document.getElementById('calendar-container').innerHTML = html;
    } catch (error) {
        console.error('Error rendering calendar:', error);
    } finally {
        // Hide loading overlay
        calendarLoading.classList.remove('flex');
        calendarLoading.classList.add('hidden');
    }
}

async function openModalDetail(dateStr) {
    const modal = document.getElementById('modal-detail-absensi');
    const modalContent = document.getElementById('modal-detail-content');
    
    // Show modal instantly with loading state
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Trigger animation
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            modal.classList.remove('bg-opacity-0');
            modal.classList.add('bg-opacity-50');
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        });
    });
    
    document.getElementById('modal-tanggal').textContent = 'Memuat...';
    document.getElementById('modal-content').innerHTML = `
        <div class="flex flex-col items-center justify-center py-8">
            <svg class="w-10 h-10 text-blue-600 animate-spin mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <p class="text-gray-500 text-sm">Memuat detail absensi...</p>
        </div>
    `;
    
    try {
        const response = await fetch(`/presensi/detail/${dateStr}`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        
        if (!data.success) {
            closeModalDetail();
            return;
        }
        
        // Set tanggal di modal
        document.getElementById('modal-tanggal').textContent = data.tanggal;
        
        // Build content (absensi + logbook)
        const presensi = data.presensi || null;
        const logbook = data.logbook || null;
        const status = presensi?.status || 'belum ada';

        let statusBgClass = 'bg-gray-100 dark:bg-gray-700';
        let statusTextClass = 'text-gray-800 dark:text-gray-200';
        if (status === 'hadir') {
            statusBgClass = 'bg-green-100 dark:bg-green-900/30';
            statusTextClass = 'text-green-800 dark:text-green-300';
        } else if (status === 'terlambat') {
            statusBgClass = 'bg-orange-100 dark:bg-orange-900/30';
            statusTextClass = 'text-orange-800 dark:text-orange-300';
        } else if (status === 'izin') {
            statusBgClass = 'bg-indigo-100 dark:bg-indigo-900/30';
            statusTextClass = 'text-indigo-800 dark:text-indigo-300';
        } else if (status === 'alpa') {
            statusBgClass = 'bg-gray-200 dark:bg-gray-700';
            statusTextClass = 'text-gray-800 dark:text-gray-200';
        }

        let content = `
            <div class="space-y-4">
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40">
                    <h4 class="mb-2 text-sm font-semibold text-gray-900 dark:text-white">Informasi Kehadiran</h4>
                    <div class="mb-2">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium ${statusBgClass} ${statusTextClass}">
                            ${status.charAt(0).toUpperCase() + status.slice(1)}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 gap-2 text-sm text-gray-700 dark:text-gray-300 sm:grid-cols-2">
                        <p>Jam Datang: <span class="font-semibold text-gray-900 dark:text-white">${presensi?.jam_masuk || '—'}</span></p>
                        <p>Jam Pulang: <span class="font-semibold text-gray-900 dark:text-white">${presensi?.jam_pulang || '—'}</span></p>
                        <p class="sm:col-span-2">Keterangan: <span class="font-semibold text-gray-900 dark:text-white">${presensi?.keterangan || '—'}</span></p>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900/40">
                    <h4 class="mb-2 text-sm font-semibold text-gray-900 dark:text-white">Catatan Logbook</h4>
                    ${logbook ? `
                        <p class="mb-1 text-sm font-medium text-gray-900 dark:text-white">Aktivitas: ${logbook.aktivitas || '-'}</p>
                        <p class="mb-2 text-sm text-gray-700 dark:text-gray-300">${logbook.deskripsi || '-'}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Waktu: <span class="font-medium">${(logbook.jam_mulai || '—')} - ${(logbook.jam_selesai || '—')}</span></p>
                    ` : `
                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada catatan logbook pada tanggal ini.</p>
                    `}
                </div>
            </div>
        `;
        
        document.getElementById('modal-content').innerHTML = content;
        
    } catch (error) {
        console.error('Error opening modal:', error);
        closeModalDetail();
        showMessage('Gagal memuat detail absensi', 'error');
    }
}

function closeModalDetail() {
    const modal = document.getElementById('modal-detail-absensi');
    const modalContent = document.getElementById('modal-detail-content');
    
    modal.classList.remove('bg-opacity-50');
    modal.classList.add('bg-opacity-0');
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }, 300);
}

// Navigation bulan
function previousMonth() {
    currentMonth--;
    if (currentMonth < 1) {
        currentMonth = 12;
        currentYear--;
    }
    renderCalendar(currentYear, currentMonth);
}

function nextMonth() {
    currentMonth++;
    if (currentMonth > 12) {
        currentMonth = 1;
        currentYear++;
    }
    renderCalendar(currentYear, currentMonth);
}

// Close modal saat klik outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('modal-detail-absensi');
    if (e.target === modal) {
        closeModalDetail();
    }
});

// Load status saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    loadAttendanceStatus();
    renderCalendar();
});
</script>
@endsection
