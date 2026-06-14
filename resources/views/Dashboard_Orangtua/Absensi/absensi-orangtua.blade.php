@extends('layouts.index')

@php $role = 'orangtua'; @endphp

@section('title', 'Absensi Anak')

@section('content')
@include('components.navbar', ['role' => $role])
@php
    $children = [
        ['initials' => 'YA', 'name' => 'Yusuf Ahmad', 'kelas' => 'Kelas III A', 'active' => true],
        ['initials' => 'SA', 'name' => 'Siti Aisyah',  'kelas' => 'Kelas V B',   'active' => false],
        ['initials' => 'MR', 'name' => 'M. Rafi',      'kelas' => 'Kelas I C',   'active' => false],
    ];

    $summary = [
        'hadir' => 12,
        'sakit' => 3,
        'izin'  => 2,
        'alpha' => 1,
        'total' => 18,
    ];
    $persen = round(($summary['hadir'] / $summary['total']) * 100);

    $records = [
        ['no' => 1,  'tanggal' => '01 April 2026', 'hari' => 'Senin',  'status' => 'Hadir', 'keterangan' => ''],
        ['no' => 2,  'tanggal' => '02 April 2026', 'hari' => 'Selasa', 'status' => 'Hadir', 'keterangan' => ''],
        ['no' => 3,  'tanggal' => '03 April 2026', 'hari' => 'Rabu',   'status' => 'Sakit', 'keterangan' => 'Demam, surat dokter'],
        ['no' => 4,  'tanggal' => '04 April 2026', 'hari' => 'Kamis',  'status' => 'Hadir', 'keterangan' => ''],
        ['no' => 5,  'tanggal' => '07 April 2026', 'hari' => 'Senin',  'status' => 'Izin',  'keterangan' => 'Keperluan keluarga'],
        ['no' => 6,  'tanggal' => '08 April 2026', 'hari' => 'Selasa', 'status' => 'Hadir', 'keterangan' => ''],
        ['no' => 7,  'tanggal' => '09 April 2026', 'hari' => 'Rabu',   'status' => 'Alpha', 'keterangan' => 'Tidak ada keterangan'],
        ['no' => 8,  'tanggal' => '10 April 2026', 'hari' => 'Kamis',  'status' => 'Hadir', 'keterangan' => ''],
        ['no' => 9,  'tanggal' => '11 April 2026', 'hari' => 'Jumat',  'status' => 'Hadir', 'keterangan' => ''],
        ['no' => 10, 'tanggal' => '14 April 2026', 'hari' => 'Senin',  'status' => 'Sakit', 'keterangan' => 'Flu, izin orang tua'],
    ];

    $statusConfig = [
        'Hadir' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
        'Sakit' => ['bg' => 'bg-blue-100',    'text' => 'text-blue-600'],
        'Izin'  => ['bg' => 'bg-amber-100',   'text' => 'text-amber-600'],
        'Alpha' => ['bg' => 'bg-red-100',     'text' => 'text-red-600'],
    ];
@endphp

<div class="max-w-[1200px] mx-auto space-y-6 pb-10">

    {{-- Page Title --}}
    <div class="space-y-1">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Absensi Anak</h1>
        <p class="text-sm text-gray-500">Pantau kehadiran anak secara lengkap</p>
    </div>

    {{-- Child Selector --}}
    <div class="flex flex-wrap gap-3">
        @foreach ($children as $child)
            <button type="button"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl border transition-all duration-200
                    {{ $child['active']
                        ? 'bg-[#1e2567] text-white border-[#1e2567] shadow-md'
                        : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300 hover:shadow-sm' }}">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                    {{ $child['active'] ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">
                    {{ $child['initials'] }}
                </div>
                <div class="text-left">
                    <p class="text-sm font-semibold leading-tight">{{ $child['name'] }}</p>
                    <p class="text-xs {{ $child['active'] ? 'text-blue-200' : 'text-slate-400' }} leading-tight">{{ $child['kelas'] }}</p>
                </div>
            </button>
        @endforeach
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="w-full h-1 rounded-full bg-emerald-500 mb-4"></div>
            <p class="text-sm text-slate-500 mb-1">Hadir</p>
            <p class="text-3xl font-bold text-emerald-600">{{ $summary['hadir'] }}
                <span class="text-sm font-normal text-slate-400">hari</span>
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="w-full h-1 rounded-full bg-blue-500 mb-4"></div>
            <p class="text-sm text-slate-500 mb-1">Sakit</p>
            <p class="text-3xl font-bold text-blue-600">{{ $summary['sakit'] }}
                <span class="text-sm font-normal text-slate-400">hari</span>
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="w-full h-1 rounded-full bg-amber-400 mb-4"></div>
            <p class="text-sm text-slate-500 mb-1">Izin</p>
            <p class="text-3xl font-bold text-amber-500">{{ $summary['izin'] }}
                <span class="text-sm font-normal text-slate-400">hari</span>
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="w-full h-1 rounded-full bg-red-500 mb-4"></div>
            <p class="text-sm text-slate-500 mb-1">Alpha</p>
            <p class="text-3xl font-bold text-red-600">{{ $summary['alpha'] }}
                <span class="text-sm font-normal text-slate-400">hari</span>
            </p>
        </div>

        <div class="bg-[#1e2567] rounded-2xl p-5 shadow-sm col-span-2 sm:col-span-1">
            <p class="text-sm text-blue-200 mb-1">Kehadiran</p>
            <p class="text-3xl font-bold text-white">{{ $persen }}%
                <span class="text-xs font-normal text-blue-300">dari {{ $summary['total'] }} hari</span>
            </p>
            <div class="mt-3 w-full bg-white/20 rounded-full h-2">
                <div class="bg-amber-400 h-2 rounded-full" style="width: {{ $persen }}%"></div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        {{-- Filter Bar --}}
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-500">Bulan:</span>
                <button type="button" class="flex items-center gap-1.5 border border-slate-200 rounded-lg px-3 py-1.5 text-sm font-medium text-slate-700 hover:border-slate-300 bg-white transition-colors">
                    April 2026
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-500">Status:</span>
                <div class="flex gap-1.5 flex-wrap">
                    @foreach (['Semua', 'Hadir', 'Sakit', 'Izin', 'Alpha'] as $filter)
                        <button type="button"
                            class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors
                                {{ $filter === 'Semua'
                                    ? 'bg-[#1e2567] text-white'
                                    : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            {{ $filter }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-slate-700">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/50">
                        <th class="px-6 py-3 text-left font-semibold text-slate-600 w-14">No</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-600">Tanggal</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-600">Hari</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-600">Status</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-600">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($records as $record)
                        @php $cfg = $statusConfig[$record['status']]; @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors {{ $record['status'] === 'Alpha' ? 'bg-red-50/40' : '' }}">
                            <td class="px-6 py-4 text-slate-400">{{ $record['no'] }}</td>
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $record['tanggal'] }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $record['hari'] }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $cfg['bg'] }} {{ $cfg['text'] }}">
                                    {{ $record['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 {{ $record['status'] === 'Alpha' ? 'text-red-500 font-medium' : 'text-slate-500' }}">
                                {{ $record['keterangan'] ?: '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-slate-500">Menampilkan 10 dari 18 data</p>
            <div class="flex items-center gap-1.5">
                <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#1e2567] text-white text-sm font-semibold">1</button>
                <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm transition-colors">2</button>
                <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

</div>
@endsection