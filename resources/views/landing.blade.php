<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>InternHub - Selamat Datang</title>
    <link rel="icon" type="image/png" href="/logo-bps.png" />

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-slate-50 min-h-screen flex flex-col">
    <header class="w-full border-b border-slate-200 bg-white/95 backdrop-blur supports-[backdrop-filter]:bg-white/80 sticky top-0 z-30">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                    <img src="/logo-bps.png" alt="Logo InternHub" class="h-9 w-auto" loading="lazy" style="max-width:40px;object-fit:contain;" />
                    <span class="text-lg font-bold tracking-tight text-slate-900">InternHub</span>
                </a>
            </div>
        </div>
    </header>

    <main class="w-full py-12 sm:py-16 lg:py-20 flex-1">
        <section class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-14 items-center">
                <div>
                    <p class="inline-flex items-center rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-700 mb-4">
                        Program Magang BPS Provinsi Sulawesi Utara
                    </p>
                    <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight text-slate-900">
                        Presensi Anak Magang BPS Provinsi Sulawesi Utara
                    </h1>
                    <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed max-w-xl">
                        Halaman ini digunakan untuk mendukung kedisiplinan dan pelaporan kegiatan peserta magang selama menjalankan penugasan di Badan Pusat Statistik Provinsi Sulawesi Utara.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3 text-base font-semibold text-white hover:bg-blue-700 transition shadow-sm min-h-[48px]">
                            Masuk ke InternHub
                        </a>
                    </div>
                </div>

                <div>
                    <div class="mb-5 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                        <img src="/images/peta-sulawesi-utara.svg" alt="Peta Sulawesi Utara" class="h-auto w-full rounded-xl" loading="lazy" />
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xl">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="h-14 w-14 rounded-xl bg-blue-100 flex items-center justify-center">
                                <img src="/logo-bps.png" alt="Logo InternHub" class="h-9 w-auto" loading="lazy" style="max-width:38px;object-fit:contain;" />
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-900">InternHub</h2>
                                <p class="text-sm text-slate-500">Pendampingan kegiatan magang di lingkungan BPS Sulut</p>
                            </div>
                        </div>

                        <div class="space-y-3 text-sm sm:text-base text-slate-700">
                            <div class="flex items-start gap-3">
                                <span class="mt-1 h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                                <p>Memudahkan peserta magang mencatat kehadiran harian secara tertib.</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                <p>Membantu peserta menyusun laporan aktivitas magang dengan rapi.</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                                <p>Mendukung pembimbing dalam memantau perkembangan peserta magang.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="w-full border-t border-slate-200 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-5 text-sm text-slate-500">
            © {{ date('Y') }} InternHub - Badan Pusat Statistik Provinsi Sulawesi Utara.
        </div>
    </footer>

    @include('components.toast')
</body>
</html>