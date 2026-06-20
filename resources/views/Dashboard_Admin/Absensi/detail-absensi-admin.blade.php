@extends('layouts.index')

@php
    $role = 'admin';
@endphp

@section('content')
@include('components.navbar', ['role' => $role])
@php
$namaBulan = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember',
];
@endphp

    <div class="w-full max-w-full overflow-x-hidden space-y-8">
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Detail Absensi Siswa</h1>
            <p class="text-gray-600 mt-1">Lihat ringkasan hadir, sakit, izin, dan alpha per siswa.</p>
        </div>

        <div class="w-full max-w-full rounded-[32px] bg-white p-6 shadow-[0_24px_60px_rgba(15,23,42,0.08)] overflow-hidden">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="space-y-2">
                        <p class="text-xs uppercase tracking-[0.3em] text-amber-600">Kelas</p>
                        <p class="text-base font-semibold text-slate-900">
                            {{ $kelas->nama_kelas }}
                        </p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs uppercase tracking-[0.3em] text-amber-600">Wali Kelas</p>
                        <p class="text-base font-semibold text-slate-900">
                            {{ $kelas->guru?->nama ?? '-' }}
                        </p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs uppercase tracking-[0.3em] text-amber-600">Tahun Pelajaran</p>
                        <p class="text-base font-semibold text-slate-900">
                            {{ $kelas->tahunPelajaran?->tahun_pelajaran ?? '-' }}
                        </p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs uppercase tracking-[0.3em] text-amber-600">Bulan</p>
                        <p class="text-base font-semibold text-slate-900">
                            {{ ucfirst($bulan) }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('admin.absensi.pilih-bulan', $kelas->id_kelas) }}" class="inline-flex shrink-0 items-center gap-2 text-sm font-semibold text-slate-700 hover:text-slate-900">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.66667 12L4 7.33333L8.66667 2.66667" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Kembali ke Pilihan Bulan
                </a>
            </div>
        </div>

        <div class="w-full max-w-full rounded-[32px] bg-white p-6 shadow-[0_24px_60px_rgba(15,23,42,0.08)] overflow-hidden">
            @php
                $days = range(1, $jumlahHari);

                $statusColors = [
                    'H' => 'bg-emerald-500 text-white',
                    'S' => 'bg-blue-500 text-white',
                    'I' => 'bg-amber-400 text-slate-950',
                    'A' => 'bg-red-500 text-white',
                    '?' => 'bg-slate-800 text-white',
                ];
            @endphp
            <div class="w-full max-w-full overflow-x-auto">
                <table class="min-w-[1700px] w-full table-fixed border-collapse text-[11px] text-slate-900">
                    <thead class="bg-[#1E2567] text-white text-[10px] uppercase tracking-[0.08em]">
                        <tr>
                            <th class="border border-slate-700 px-2 py-3 w-[2.5rem]">#</th>
                            <th class="border border-slate-700 px-2 py-3 w-[10rem]">Nama Siswa</th>
                            <th class="border border-slate-700 px-2 py-3 w-[8rem]">NIS</th>
                            <th class="border border-slate-700 px-2 py-3 w-[3rem]">L/P</th>

                            @foreach ($days as $day)
                                <th class="border border-slate-700 px-1 py-3 text-center">
                                    {{ sprintf('%02d', $day) }}
                                </th>
                            @endforeach

                            <th class="border border-slate-700 px-2 py-3 w-[2.5rem]">H</th>
                            <th class="border border-slate-700 px-2 py-3 w-[2.5rem]">S</th>
                            <th class="border border-slate-700 px-2 py-3 w-[2.5rem]">I</th>
                            <th class="border border-slate-700 px-2 py-3 w-[2.5rem]">A</th>
                        </tr>
                    </thead>

                    <tbody class="text-[10px]">
                        @foreach ($kelas->siswa as $index => $siswa)

                            @php
                                $totals = [
                                    'H' => 0,
                                    'S' => 0,
                                    'I' => 0,
                                    'A' => 0,
                                ];
                            @endphp

                            <tr class="odd:bg-white even:bg-slate-100">
                                <td class="border border-slate-200 px-2 py-2 text-center text-slate-600">
                                    {{ $index + 1 }}
                                </td>

                                <td class="border border-slate-200 px-2 py-2 font-semibold text-slate-900">
                                    {{ $siswa->nama }}
                                </td>

                                <td class="border border-slate-200 px-2 py-2 text-slate-700">
                                    {{ $siswa->nis }}
                                </td>

                                <td class="border border-slate-200 px-2 py-2 text-center text-slate-700">
                                    {{ $siswa->jenis_kelamin }}
                                </td>

                                @foreach ($days as $day)

                                    @php
                                        $status = $absensiBulan[$siswa->id_siswa][$day] ?? '?';

                                        if (isset($totals[$status])) {
                                            $totals[$status]++;
                                        }
                                    @endphp

                                    <td class="border border-slate-200 px-1 py-2 text-center">
                                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg {{ $statusColors[$status] }} font-semibold">
                                            {{ $status }}
                                        </span>
                                    </td>

                                @endforeach

                                <td class="border border-slate-200 px-2 py-2 text-center text-emerald-700">
                                    {{ $totals['H'] }}
                                </td>

                                <td class="border border-slate-200 px-2 py-2 text-center text-sky-700">
                                    {{ $totals['S'] }}
                                </td>

                                <td class="border border-slate-200 px-2 py-2 text-center text-amber-700">
                                    {{ $totals['I'] }}
                                </td>

                                <td class="border border-slate-200 px-2 py-2 text-center text-red-700">
                                    {{ $totals['A'] }}
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection