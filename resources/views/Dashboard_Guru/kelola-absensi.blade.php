@extends('layouts.index')

@php
    $role = 'guru';
@endphp

@section('content')
    <div class="max-w-[1180px] mx-auto space-y-8">
        <div class="space-y-2">
            <p class="text-sm font-semibold text-slate-600">Absensi Siswa</p>
            <h1 class="text-3xl font-semibold text-slate-950">Kelola Absensi</h1>
            <p class="text-sm text-slate-500">Isi dan perbarui data absensi untuk kelas ini.</p>
        </div>

        <div class="rounded-[32px] bg-white p-6 shadow-[0_24px_60px_rgba(15,23,42,0.08)] mb-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="space-y-2">
                        <p class="text-xs uppercase tracking-[0.3em] text-amber-600">Kelas</p>
                        <p class="text-base font-semibold text-slate-900">III A</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs uppercase tracking-[0.3em] text-amber-600">Wali Kelas</p>
                        <p class="text-base font-semibold text-slate-900">Budi Santoso, S.Pd</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs uppercase tracking-[0.3em] text-amber-600">Tahun Pelajaran</p>
                        <p class="text-base font-semibold text-slate-900">2023/2024 - Semester 1</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs uppercase tracking-[0.3em] text-amber-600">Hari, Tanggal</p>
                        <p class="text-base font-semibold text-slate-900">Kamis, 7 September 2023</p>
                    </div>
                </div>
                <a href="{{ route('guru.absensi') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 hover:text-slate-900">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.66667 12L4 7.33333L8.66667 2.66667" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Kembali ke Pilihan Kelas
                </a>
            </div>
        </div>

            <div class="grid gap-4 md:grid-cols-5 mb-6">
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 text-center">
                    <p class="text-sm text-slate-500">Hadir</p>
                    <p class="mt-3 text-3xl font-semibold text-emerald-600">6</p>
                </div>
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 text-center">
                    <p class="text-sm text-slate-500">Sakit</p>
                    <p class="mt-3 text-3xl font-semibold text-sky-600">2</p>
                </div>
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 text-center">
                    <p class="text-sm text-slate-500">Izin</p>
                    <p class="mt-3 text-3xl font-semibold text-amber-500">1</p>
                </div>
                <div class="rounded-[24px] border border-slate-200 bg-white p-5 text-center">
                    <p class="text-sm text-slate-500">Alpha</p>
                    <p class="mt-3 text-3xl font-semibold text-red-600">1</p>
                </div>
                <div class="rounded-[24px] border border-slate-200 bg-[#1E2567] p-5 text-center text-white">
                    <p class="text-sm">Total Siswa</p>
                    <p class="mt-3 text-3xl font-semibold">10</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-[#1E2567] text-white">
                        <tr>
                            <th class="px-4 py-4 text-left font-semibold">#</th>
                            <th class="px-4 py-4 text-left font-semibold">NIS</th>
                            <th class="px-4 py-4 text-left font-semibold">Nama Siswa</th>
                            <th class="px-4 py-4 text-left font-semibold">L/P</th>
                            <th class="px-4 py-4 text-left font-semibold">Status</th>
                            <th class="px-4 py-4 text-left font-semibold">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-slate-50">
                        @php
                            $students = [
                                ['nis' => '024342412', 'name' => 'ELFAN', 'gender' => 'L', 'status' => 'Hadir'],
                                ['nis' => '024342121', 'name' => 'BUNGA', 'gender' => 'P', 'status' => 'Sakit'],
                                ['nis' => '024342401', 'name' => 'ANDRE', 'gender' => 'L', 'status' => 'Izin'],
                                ['nis' => '024342402', 'name' => 'RENAL', 'gender' => 'L', 'status' => 'Alpha'],
                                ['nis' => '024342404', 'name' => 'DIMAS', 'gender' => 'L', 'status' => 'Hadir'],
                                ['nis' => '024342406', 'name' => 'RAFLI', 'gender' => 'L', 'status' => 'Sakit'],
                                ['nis' => '024342407', 'name' => 'KHIKMAL', 'gender' => 'L', 'status' => 'Izin'],
                                ['nis' => '024342408', 'name' => 'TRIO', 'gender' => 'L', 'status' => 'Alpha'],
                                ['nis' => '024342409', 'name' => 'DWI', 'gender' => 'P', 'status' => 'Hadir'],
                                ['nis' => '024112410', 'name' => 'RIFAUL', 'gender' => 'P', 'status' => 'Sakit'],
                            ];
                            $options = ['Hadir', 'Sakit', 'Izin', 'Alpha'];
                        @endphp
                        @foreach ($students as $index => $student)
                            <tr class="odd:bg-slate-50 even:bg-white">
                                <td class="px-4 py-4 text-slate-600">{{ $index + 1 }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ $student['nis'] }}</td>
                                <td class="px-4 py-4 font-semibold text-slate-900">{{ $student['name'] }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ $student['gender'] }}</td>
                                <td class="px-4 py-4">
                                    <select class="w-full rounded-[12px] border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100">
                                        @foreach ($options as $option)
                                            <option value="{{ $option }}" {{ $student['status'] === $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-4">
                                    <input type="text" class="w-full rounded-[12px] border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100" placeholder="Keterangan..." />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <label class="inline-flex items-center gap-3 text-sm text-slate-700">
                    <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500" />
                    Saya yakin akan mengubah data tersebut
                </label>
                <button class="inline-flex h-12 items-center justify-center rounded-[16px] bg-emerald-500 px-6 text-sm font-semibold text-white transition hover:bg-emerald-600">Simpan</button>
            </div>
        </div>
    </div>
@endsection