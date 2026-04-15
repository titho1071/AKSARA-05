@extends('Dashboard_Guru.layout')

@section('content')
    <div class="max-w-[1600px] mx-auto px-4 space-y-8">
        <div class="space-y-2">
            <p class="text-sm font-semibold text-slate-600">Rekap Absensi</p>
            <h1 class="text-3xl font-semibold text-slate-950">Rekap Absensi Siswa</h1>
            <p class="text-sm text-slate-500">Lihat ringkasan hadir, sakit, izin, dan alpha per siswa.</p>
        </div>

        <div class="rounded-[32px] bg-white p-6 shadow-[0_24px_60px_rgba(15,23,42,0.08)]">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
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
        </div>

        <div class="rounded-[32px] bg-white p-6 shadow-[0_24px_60px_rgba(15,23,42,0.08)] overflow-hidden">
            @php
                $days = range(1, 30);
                $statusColors = [
                    'H' => 'bg-emerald-500 text-white',
                    'S' => 'bg-blue-500 text-white',
                    'I' => 'bg-amber-400 text-slate-950',
                    'A' => 'bg-red-500 text-white',
                    '?' => 'bg-slate-800 text-white',
                ];
                $students = [
                    ['nis' => '024342412', 'name' => 'ELFAN', 'gender' => 'L', 'attendance' => array_merge(['H','H','H','H','H','H','H','H'], array_fill(0, 22, '?'))],
                    ['nis' => '024342121', 'name' => 'BUNGA', 'gender' => 'P', 'attendance' => array_merge(['H','H','S','H','H','H','H','H'], array_fill(0, 22, '?'))],
                    ['nis' => '024342401', 'name' => 'ANDRE', 'gender' => 'L', 'attendance' => array_merge(['H','H','H','H','H','H','H','H'], array_fill(0, 22, '?'))],
                    ['nis' => '024342402', 'name' => 'RENAL', 'gender' => 'L', 'attendance' => array_merge(['S','H','H','H','H','H','H','H'], array_fill(0, 22, '?'))],
                    ['nis' => '024342404', 'name' => 'DIMAS', 'gender' => 'L', 'attendance' => array_merge(['H','H','A','H','S','H','H','H'], array_fill(0, 22, '?'))],
                    ['nis' => '024342406', 'name' => 'RAFLI', 'gender' => 'L', 'attendance' => array_merge(['H','H','H','H','H','H','H','H'], array_fill(0, 22, '?'))],
                    ['nis' => '024342407', 'name' => 'KHIKMAL', 'gender' => 'L', 'attendance' => array_merge(['H','A','H','H','S','H','H','H'], array_fill(0, 22, '?'))],
                    ['nis' => '024342408', 'name' => 'TRIO', 'gender' => 'L', 'attendance' => array_merge(['H','H','H','H','H','H','H','H'], array_fill(0, 22, '?'))],
                    ['nis' => '024342409', 'name' => 'DWI', 'gender' => 'P', 'attendance' => array_merge(['I','H','H','H','S','H','H','H'], array_fill(0, 22, '?'))],
                    ['nis' => '024112410', 'name' => 'RIFAUL', 'gender' => 'P', 'attendance' => array_merge(['H','H','H','H','H','H','H','H'], array_fill(0, 22, '?'))],
                ];
            @endphp
            <div class="overflow-x-auto">
                <table class="min-w-[1700px] w-full table-fixed border-collapse text-[11px] text-slate-900">
                    <thead class="bg-[#1E2567] text-white text-[10px] uppercase tracking-[0.08em]">
                        <tr>
                        <th class="border border-slate-700 px-2 py-3 w-[2.5rem]">#</th>
                        <th class="border border-slate-700 px-2 py-3 w-[6.5rem]">Nama Siswa</th>
                        <th class="border border-slate-700 px-2 py-3 w-[6.5rem]">NIS</th>
                        <th class="border border-slate-700 px-2 py-3 w-[3rem]">L/P</th>
                        @foreach ($days as $day)
                            <th class="border border-slate-700 px-1 py-3 text-center">{{ sprintf('%02d', $day) }}</th>
                        @endforeach
                        <th class="border border-slate-700 px-2 py-3 w-[2.5rem]">H</th>
                        <th class="border border-slate-700 px-2 py-3 w-[2.5rem]">S</th>
                        <th class="border border-slate-700 px-2 py-3 w-[2.5rem]">I</th>
                        <th class="border border-slate-700 px-2 py-3 w-[2.5rem]">A</th>
                    </tr>
                </thead>
                <tbody class="text-[10px]">
                    @foreach ($students as $index => $student)
                        @php
                            $totals = ['H' => 0, 'S' => 0, 'I' => 0, 'A' => 0];
                            foreach ($student['attendance'] as $status) {
                                if (isset($totals[$status])) {
                                    $totals[$status]++;
                                }
                            }
                        @endphp
                        <tr class="odd:bg-white even:bg-slate-100">
                            <td class="border border-slate-200 px-2 py-2 text-center text-slate-600">{{ $index + 1 }}</td>
                            <td class="border border-slate-200 px-2 py-2 font-semibold text-slate-900">{{ $student['name'] }}</td>
                            <td class="border border-slate-200 px-2 py-2 text-slate-700">{{ $student['nis'] }}</td>
                            <td class="border border-slate-200 px-2 py-2 text-center text-slate-700">{{ $student['gender'] }}</td>
                            @foreach ($student['attendance'] as $status)
                                <td class="border border-slate-200 px-1 py-2 text-center">
                                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg {{ $statusColors[$status] }} font-semibold">{{ $status }}</span>
                                </td>
                            @endforeach
                            <td class="border border-slate-200 px-2 py-2 text-center text-emerald-700">{{ $totals['H'] }}</td>
                            <td class="border border-slate-200 px-2 py-2 text-center text-sky-700">{{ $totals['S'] }}</td>
                            <td class="border border-slate-200 px-2 py-2 text-center text-amber-700">{{ $totals['I'] }}</td>
                            <td class="border border-slate-200 px-2 py-2 text-center text-red-700">{{ $totals['A'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
