@extends('layouts.index')

@php
    $role = 'admin';
    $classes = [
        ['id' => 1, 'kelas' => 'I A', 'wali' => 'Siti Rahayu, S.Pd'],
        ['id' => 2, 'kelas' => 'I B', 'wali' => 'Ahmad Fauzi, S.Pd'],
        ['id' => 3, 'kelas' => 'II A', 'wali' => 'Dewi Lestari, S.Pd'],
        ['id' => 4, 'kelas' => 'II B', 'wali' => 'Rizal Hidayat, S.Pd'],
        ['id' => 5, 'kelas' => 'III A', 'wali' => 'Budi Santoso, S.Pd'],
        ['id' => 6, 'kelas' => 'III B', 'wali' => 'Nurul Aini, S.Pd'],
        ['id' => 7, 'kelas' => 'IV A', 'wali' => 'Hendra Putra, S.Pd'],
        ['id' => 8, 'kelas' => 'IV B', 'wali' => 'Fitriana, S.Pd'],
        ['id' => 9, 'kelas' => 'V A', 'wali' => 'Rina Marlina, S.Pd'],
        ['id' => 10, 'kelas' => 'V B', 'wali' => 'Yusuf Hakim, S.Pd'],
    ];
@endphp

@section('content')
    <div class="max-w-[1400px] mx-auto space-y-6">
        
        <div class="space-y-1">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Absensi</h1>
            <p class="text-sm text-gray-500">Pilih Kelas</p>
        </div>

        <div class="bg-white rounded-[24px] shadow-sm border border-slate-200">
            <!-- Header Section -->
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-xl font-bold text-[#1e2567]">Daftar Kelas</h2>
                <p class="text-sm text-slate-500 mt-1 mb-4">Tahun Pelajaran</p>
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Dropdown Tahun -->
                        <div class="relative">
                            <button type="button" class="w-full sm:w-[280px] flex items-center justify-between border border-slate-200 rounded-xl px-4 py-2.5 bg-white text-sm focus:outline-none focus:border-[#1e2567] focus:ring-1 focus:ring-[#1e2567]">
                                <div class="flex items-center gap-2">
                                    <span class="bg-blue-50 text-blue-600 text-xs px-2.5 py-1 rounded-md font-medium">Terbaru</span>
                                    <span class="text-slate-700">2024/2025 - Semester 2</span>
                                </div>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                        </div>

                        <!-- Dropdown Entries -->
                        <div class="relative">
                            <select class="appearance-none border border-slate-200 rounded-xl px-4 py-2.5 pr-8 bg-white text-sm text-slate-700 focus:outline-none focus:border-[#1e2567] focus:ring-1 focus:ring-[#1e2567]">
                                <option>10</option>
                                <option>25</option>
                                <option>50</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="relative w-full md:w-[320px]">
                        <input type="text" placeholder="Cari nama kelas..." class="w-full border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-sm focus:outline-none focus:border-[#1e2567] focus:ring-1 focus:ring-[#1e2567] transition-all">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead>
                        <tr class="bg-[#1e2567] text-white">
                            <th class="px-6 py-4 font-semibold text-center rounded-l-xl w-16">#</th>
                            <th class="px-6 py-4 font-semibold text-center w-1/4">Kelas</th>
                            <th class="px-6 py-4 font-semibold text-center w-1/3">Wali Kelas</th>
                            <th class="px-6 py-4 font-semibold text-center rounded-r-xl w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-transparent">
                        @foreach($classes as $index => $class)
                        <tr class="hover:bg-slate-50 transition-colors {{ $index % 2 == 0 ? 'bg-white' : 'bg-slate-50/50' }}">
                            <td class="px-6 py-4 text-center text-slate-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-center font-medium">{{ $class['kelas'] }}</td>
                            <td class="px-6 py-4 text-center">{{ $class['wali'] }}</td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.absensi.pilih-bulan', $class['id']) }}" class="inline-flex items-center justify-center gap-1.5 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Section -->
            <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-slate-500">Menampilkan 1–10 dari 10 kelas</p>
                <div class="flex items-center gap-2">
                    <button class="w-9 h-9 flex items-center justify-center border border-slate-200 rounded-lg text-slate-500 hover:bg-slate-50 bg-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button class="w-9 h-9 flex items-center justify-center border border-transparent rounded-lg bg-[#1e2567] text-white font-medium">1</button>
                    <button class="w-9 h-9 flex items-center justify-center border border-slate-200 rounded-lg text-slate-500 hover:bg-slate-50 bg-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection