@extends('Dashboard_Guru.layout')

@section('content')
    <div class="max-w-[1180px] mx-auto space-y-8">
        <div class="space-y-2">
            <p class="text-sm font-semibold text-slate-600">Dashboard</p>
            <h1 class="text-3xl font-semibold text-slate-950">Selamat datang, Guru</h1>
            <p class="text-sm text-slate-500">Pilih menu di sidebar untuk melihat fitur lain.</p>
        </div>

        <div class="rounded-[32px] bg-white p-8 shadow-[0_24px_60px_rgba(15,23,42,0.08)]">
            <p class="text-base text-slate-700">Gunakan menu di kiri untuk mengakses Absensi, Dokumentasi, Pengumuman, Jadwal, dan data Biodata.</p>
            <div class="mt-6 inline-flex items-center gap-3 rounded-[16px] bg-amber-500 px-5 py-4 text-sm font-semibold text-slate-950 shadow-[0_10px_30px_rgba(245,158,11,0.2)]">
                <span>Menu Favorit</span>
                <a href="{{ route('dashboard.absensi') }}" class="underline">Absensi</a>
            </div>
        </div>
    </div>
@endsection
