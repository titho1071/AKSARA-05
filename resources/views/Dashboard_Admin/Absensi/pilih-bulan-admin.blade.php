@extends('layouts.index')

@php
    $role = 'admin';
    $months = [
        ['id' => 1, 'name' => 'Januari 2025', 'icon_bg' => 'bg-orange-400', 'icon_border' => 'border-orange-200', 'card_hover' => 'hover:border-orange-300 hover:shadow-orange-100'],
        ['id' => 2, 'name' => 'Februari 2025', 'icon_bg' => 'bg-yellow-400', 'icon_border' => 'border-yellow-200', 'card_hover' => 'hover:border-yellow-300 hover:shadow-yellow-100'],
        ['id' => 3, 'name' => 'Maret 2025', 'icon_bg' => 'bg-green-500', 'icon_border' => 'border-green-200', 'card_hover' => 'hover:border-green-300 hover:shadow-green-100'],
        ['id' => 4, 'name' => 'April 2025', 'icon_bg' => 'bg-emerald-400', 'icon_border' => 'border-emerald-200', 'card_hover' => 'hover:border-emerald-300 hover:shadow-emerald-100'],
        ['id' => 5, 'name' => 'Mei 2025', 'icon_bg' => 'bg-cyan-400', 'icon_border' => 'border-cyan-200', 'card_hover' => 'hover:border-cyan-300 hover:shadow-cyan-100'],
        ['id' => 6, 'name' => 'Juni 2025', 'icon_bg' => 'bg-blue-600', 'icon_border' => 'border-blue-200', 'card_hover' => 'hover:border-blue-300 hover:shadow-blue-100'],
    ];
@endphp

@section('content')
    <div class="max-w-[1400px] mx-auto space-y-6">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Absensi</h1>
                    <p class="text-gray-600 mt-1">Pilih Bulan</p>
                </div>
                <a href="{{ route('admin.absensi') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Pilih Kelas
                </a>
            </div>
        </div>

        <!-- Info Card -->
        <div class="bg-white rounded-[20px] shadow-sm border border-slate-200">
            <div class="flex">
                <div class="w-1.5 bg-amber-400 rounded-l-[20px] flex-shrink-0"></div>
                <div class="p-6 space-y-1">
                    <p class="text-sm text-slate-800">
                        <span class="font-bold">Kelas</span> : {{ $class['kelas'] ?? 'III A' }}
                    </p>
                    <p class="text-sm text-slate-800">
                        <span class="font-bold">Wali Kelas</span> : {{ $class['wali'] ?? 'Nama Wali Kelas, S.Pd' }}
                    </p>
                    <p class="text-sm text-slate-800">
                        <span class="font-bold">Tahun Pelajaran</span> : 2024/2025 - Semester 2
                    </p>
                </div>
            </div>
        </div>

        <!-- Month Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($months as $month)
                <a href="{{ route('admin.absensi.detail', ['id' => $id ?? 1, 'month' => $month['id']]) }}"
                   class="group bg-white rounded-[16px] border border-slate-200 p-5 flex items-center gap-4 transition-all duration-200 shadow-sm hover:shadow-md {{ $month['card_hover'] }}">
                    <!-- Calendar Icon -->
                    <div class="w-12 h-12 rounded-xl {{ $month['icon_bg'] }} flex items-center justify-center flex-shrink-0 transition-transform duration-200 group-hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <!-- Month Label -->
                    <span class="text-sm font-semibold text-slate-800 group-hover:text-slate-950 transition-colors">{{ $month['name'] }}</span>
                </a>
            @endforeach
        </div>

    </div>
@endsection