@extends('layouts.guest')

@section('title', 'BPS Magang - Selamat Datang')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <!-- Logo Atas -->
    <div class="mb-8 flex justify-center">
        <img src="/logo-bps.png" alt="Logo BPS" class="h-16 w-auto md:h-20" loading="lazy" style="max-width:90px;object-fit:contain;" />
    </div>
    <div class="w-full max-w-2xl bg-white rounded-xl shadow p-6 md:p-10 flex flex-col md:flex-row items-center gap-8">
        <!-- Kiri: Card Login -->
        <div class="flex-1 w-full">
            <h1 class="text-2xl font-bold mb-3 text-gray-900">Selamat datang di BPS Magang</h1>
            <p class="text-gray-600 mb-6">Silakan pilih peran Anda untuk melanjutkan ke halaman masuk yang sesuai.</p>
            <div class="flex flex-col gap-4">
                <a href="{{ route('admin.login') }}" class="block px-6 py-3 bg-blue-600 text-white rounded-lg text-center font-semibold hover:bg-blue-700 transition">Login sebagai Admin / Mentor</a>
                <a href="{{ route('magang.login') }}" class="block px-6 py-3 bg-green-600 text-white rounded-lg text-center font-semibold hover:bg-green-700 transition">Login sebagai Peserta Magang</a>
            </div>
        </div>
        
    </div>
</div>
@endsection