@extends('layouts.index')

@php
    $role = 'guru';
@endphp

@section('content')
    <div class="max-w-[1400px] mx-auto space-y-6">

        <!-- Header -->
        <div class="space-y-1">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Absensi</h1>
            <p class="text-sm text-gray-500">Kelola Absensi</p>
        </div>

        <!-- Back Link -->
        <a href="{{ route('guru.absensi.detail') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#1e2567] hover:text-blue-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Detail Absensi
        </a>

        <!-- Info Card -->
        <div class="bg-white rounded-[20px] shadow-sm border border-slate-200">
            <div class="flex">
                <div class="w-1.5 bg-amber-400 rounded-l-[20px] flex-shrink-0"></div>
                <div class="p-6 w-full">
                    <div class="space-y-1">
                        <p class="text-sm text-slate-800">
                            <span class="font-bold">Kelas</span> : III A
                        </p>
                        <p class="text-sm text-slate-800">
                            <span class="font-bold">Wali Kelas</span> : Nama Wali Kelas, S.Pd
                        </p>
                        <p class="text-sm text-slate-800">
                            <span class="font-bold">Tahun Pelajaran</span> : 2024/2025 - Semester 2
                        </p>
                        <p class="text-sm text-slate-800">
                            <span class="font-bold">Hari, Tanggal</span> : Kamis, 7 Februari 2025
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-[20px] shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                @php
                    $students = [
                        ['nis' => '024342412', 'name' => 'ELFAN', 'gender' => 'L', 'status' => 'H'],
                        ['nis' => '024342121', 'name' => 'BUNGA', 'gender' => 'P', 'status' => 'I'],
                        ['nis' => '024342401', 'name' => 'ANDRE', 'gender' => 'L', 'status' => 'H'],
                        ['nis' => '024342402', 'name' => 'RENAL', 'gender' => 'L', 'status' => 'I'],
                        ['nis' => '024342404', 'name' => 'DIMAS', 'gender' => 'L', 'status' => 'H'],
                        ['nis' => '024342406', 'name' => 'RAFLI', 'gender' => 'L', 'status' => 'S'],
                        ['nis' => '024342407', 'name' => 'KHIKMAL', 'gender' => 'L', 'status' => 'I'],
                        ['nis' => '024342408', 'name' => 'TRIO', 'gender' => 'L', 'status' => 'I'],
                        ['nis' => '024342409', 'name' => 'DWI', 'gender' => 'P', 'status' => 'H'],
                        ['nis' => '024112410', 'name' => 'RIFAUL', 'gender' => 'P', 'status' => 'S'],
                    ];
                    $statusOptions = ['H', 'S', 'I', 'A'];
                    $statusLabels = [
                        'H' => 'Hadir',
                        'S' => 'Sakit',
                        'I' => 'Izin',
                        'A' => 'Alpha',
                    ];
                @endphp

                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[#1e2567] text-white">
                            <th class="px-4 py-4 font-semibold text-center w-14">#</th>
                            <th class="px-4 py-4 font-semibold text-center w-32">NIS</th>
                            <th class="px-4 py-4 font-semibold text-center">Nama Siswa</th>
                            <th class="px-4 py-4 font-semibold text-center w-16">L/P</th>
                            @foreach ($statusOptions as $opt)
                                <th class="px-3 py-4 font-semibold text-center w-16">
                                    <div class="flex flex-col items-center gap-0.5">
                                        <span class="text-[10px] font-normal text-slate-300 uppercase">{{ $statusLabels[$opt] }}</span>
                                        <span>{{ $opt }}</span>
                                    </div>
                                </th>
                            @endforeach
                            <th class="px-4 py-4 font-semibold text-center w-44">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $index => $student)
                            <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-slate-50/60' }} border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-4 text-center text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-4 text-center text-slate-700">{{ $student['nis'] }}</td>
                                <td class="px-4 py-4 text-center font-semibold text-slate-900">{{ $student['name'] }}</td>
                                <td class="px-4 py-4 text-center text-slate-600">{{ $student['gender'] }}</td>
                                @foreach ($statusOptions as $opt)
                                    <td class="px-3 py-4 text-center">
                                        <label class="inline-flex items-center justify-center cursor-pointer">
                                            <input type="radio" name="status_{{ $index }}" value="{{ $opt }}" class="sr-only peer" {{ $student['status'] === $opt ? 'checked' : '' }}>
                                            <span class="w-6 h-6 rounded-full border-2 border-slate-300 peer-checked:border-[#1e2567] peer-checked:bg-[#1e2567] flex items-center justify-center transition-all duration-200">
                                                <span class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity"></span>
                                            </span>
                                        </label>
                                    </td>
                                @endforeach
                                <td class="px-4 py-4">
                                    <input type="text" name="keterangan_{{ $index }}" placeholder="Keterangan..." class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 bg-slate-50 focus:outline-none focus:border-[#1e2567] focus:ring-1 focus:ring-[#1e2567] focus:bg-white transition-all placeholder-slate-400">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="px-6 py-5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-4">
                <label class="inline-flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                    <input type="checkbox" class="w-5 h-5 rounded border-slate-300 text-[#1e2567] focus:ring-[#1e2567] cursor-pointer">
                    Saya yakin akan mengubah data tersebut
                </label>
                <button class="bg-red-500 hover:bg-red-600 text-white px-8 py-2.5 rounded-lg text-sm font-semibold transition-colors shadow-sm">
                    Simpan
                </button>
            </div>
        </div>

    </div>

    <style>
        /* Custom radio button styling */
        input[type="radio"].sr-only:checked + span {
            border-color: #1e2567;
            background-color: #1e2567;
        }
        input[type="radio"].sr-only:checked + span > span {
            opacity: 1;
        }
    </style>
@endsection