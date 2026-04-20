@extends('layouts.index')

@php
    $role = 'admin';
@endphp

@section('content')
@include('components.navbar', ['role' => $role])
<div class="max-w-[1400px] mx-auto space-y-6">

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Absensi Siswa</h1>
                <p class="text-gray-600 mt-1">Semua Detail Absensi Siswa</p>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="flex flex-col md:flex-row gap-4">
        <div class="flex items-center w-full md:w-1/3 border rounded-xl px-4 py-2 bg-white">
            <input type="text" placeholder="Cari nama kelas..." class="w-full outline-none text-sm">
        </div>

        <select class="border rounded-xl px-4 py-2 bg-white text-sm w-full md:w-1/4">
            <option>2023/2024 Semester 1</option>
        </select>
    </div>

    <!-- Daftar Kelas -->
    <div>
        <h2 class="text-lg font-semibold text-slate-800">Daftar Kelas</h2>
        <p class="text-sm text-slate-500 mb-4">Tahun Pelajaran 2023/2024 · Semester 1</p>

        @php
            $classes = [
                ['name'=>'Kelas I A','teacher'=>'Siti Rahayu, S.Pd','students'=>28,'status'=>'Sudah diisi hari ini','filled'=>true],
                ['name'=>'Kelas I B','teacher'=>'Ahmad Fauzi, S.Pd','students'=>30,'status'=>'Belum diisi hari ini','filled'=>false],
                ['name'=>'Kelas II A','teacher'=>'Dewi Lestari, S.Pd','students'=>27,'status'=>'Sudah diisi hari ini','filled'=>true],
                ['name'=>'Kelas II B','teacher'=>'Rizal Hidayat, S.Pd','students'=>29,'status'=>'Belum diisi hari ini','filled'=>false],
                ['name'=>'Kelas III A','teacher'=>'Budi Santoso, S.Pd','students'=>10,'status'=>'Sudah diisi hari ini','filled'=>true],
                ['name'=>'Kelas III B','teacher'=>'Nurul Aini, S.Pd','students'=>31,'status'=>'Belum diisi hari ini','filled'=>false],
                ['name'=>'Kelas IV A','teacher'=>'Hendra Putra, S.Pd','students'=>26,'status'=>'Sudah diisi hari ini','filled'=>true],
                ['name'=>'Kelas IV B','teacher'=>'Fitriana, S.Pd','students'=>32,'status'=>'Belum diisi hari ini','filled'=>false],
            ];
        @endphp

        <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-6">
            @foreach ($classes as $class)
                <div class="bg-white border rounded-2xl p-5 shadow-sm">
                    
                    <div class="mb-3">
                        <h3 class="font-semibold text-slate-900">{{ $class['name'] }}</h3>
                        <p class="text-sm text-slate-500">Wali: {{ $class['teacher'] }}</p>
                        <p class="text-sm text-slate-500">Siswa: {{ $class['students'] }} terdaftar</p>
                    </div>

                    <!-- Button -->
                    <a href="{{ route('admin.absensi.detail') }}"
                        class="block text-center border rounded-xl py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50">
                        Detail
                    </a>

                    <!-- Status -->
                    <div class="flex items-center gap-2 mt-4 text-sm">
                        <span class="w-2.5 h-2.5 rounded-full {{ $class['filled'] ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                        <span class="text-slate-600">{{ $class['status'] }}</span>
                    </div>

                </div>
            @endforeach
        </div>
    </div>

    <!-- Rekap -->
    <div class="bg-white border rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-6">

        <div class="flex gap-10 text-center">
            <div>
                <p class="text-sm text-slate-500">Total kelas</p>
                <p class="text-2xl font-semibold">8</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Sudah absen</p>
                <p class="text-2xl font-semibold text-emerald-600">4</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Belum absen</p>
                <p class="text-2xl font-semibold text-amber-500">3</p>
            </div>
        </div>

        <a href="#"
            class="bg-[#1E2567] text-white px-8 py-3 rounded-xl text-sm font-semibold hover:bg-indigo-800">
            Rekap Absensi
        </a>
    </div>

    <!-- Keterangan -->
    <div class="flex items-center gap-6 text-sm text-slate-600">
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 bg-emerald-500 rounded-full"></span>
            Sudah diisi
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 bg-amber-500 rounded-full"></span>
            Belum diisi
        </div>
    </div>

</div>
@endsection