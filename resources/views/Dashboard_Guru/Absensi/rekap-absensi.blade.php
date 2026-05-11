@extends('layouts.index')

@php
    $role = 'guru';
@endphp

@section('content')
    <div class="max-w-[1180px] mx-auto space-y-8">
        <div class="space-y-2">
            <p class="text-sm font-semibold text-slate-600">Download Rekap Absensi</p>
            <h1 class="text-3xl font-semibold text-slate-950">Download Rekap Absensi</h1>
            <p class="text-sm text-slate-500">Unduh laporan absensi siswa per kelas atau keseluruhan.</p>
        </div>

        <div class="rounded-[32px] bg-white p-6 shadow-[0_24px_60px_rgba(15,23,42,0.08)]">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8">
                <a href="{{ route('guru.absensi') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 hover:text-slate-900">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.66667 12L4 7.33333L8.66667 2.66667" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Kembali ke Pilihan Kelas
                </a>
                <div class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
                    <span class="text-slate-300">›</span>
                    <span class="font-semibold text-slate-900">Download Rekap</span>
                </div>
            </div>

            <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-6 shadow-sm mb-8">
                <div class="grid gap-4 md:grid-cols-4">
                    <div>
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Kelas</label>
                        <select class="w-full rounded-[16px] border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100">
                            <option>Semua Kelas</option>
                            <option>Kelas I A</option>
                            <option>Kelas I B</option>
                            <option>Kelas II A</option>
                            <option>Kelas III A</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Tahun Pelajaran</label>
                        <select class="w-full rounded-[16px] border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100">
                            <option>2023/2024 Semester 1</option>
                            <option>2023/2024 Semester 2</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Bulan</label>
                        <select class="w-full rounded-[16px] border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100">
                            <option>Semua Bulan</option>
                            <option>September 2023</option>
                            <option>Oktober 2023</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button class="w-full rounded-[16px] bg-[#1E2567] px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Terapkan Filter</button>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Rekap Per Kelas</p>
                            <p class="text-sm text-slate-500">Tahun Pelajaran 2023/2024 · Semester 1</p>
                        </div>
                    </div>
                    <div class="grid gap-4 xl:grid-cols-4">
                        @php
                            $classes = [
                                ['name' => 'Kelas I A', 'teacher' => 'Siti Rahayu, S.Pd', 'students' => 28, 'month' => 'Sep 2023'],
                                ['name' => 'Kelas I B', 'teacher' => 'Ahmad Fauzi, S.Pd', 'students' => 30, 'month' => 'Sep 2023'],
                                ['name' => 'Kelas II A', 'teacher' => 'Dewi Lestari, S.Pd', 'students' => 27, 'month' => 'Sep 2023'],
                                ['name' => 'Kelas II B', 'teacher' => 'Rizal Hidayat, S.Pd', 'students' => 29, 'month' => 'Sep 2023'],
                                ['name' => 'Kelas III A', 'teacher' => 'Budi Santoso, S.Pd', 'students' => 10, 'month' => 'Sep 2023'],
                                ['name' => 'Kelas III B', 'teacher' => 'Nurul Aini, S.Pd', 'students' => 31, 'month' => 'Sep 2023'],
                                ['name' => 'Kelas IV A', 'teacher' => 'Hendra Putra, S.Pd', 'students' => 26, 'month' => 'Sep 2023'],
                                ['name' => 'Kelas IV B', 'teacher' => 'Fitriana, S.Pd', 'students' => 32, 'month' => 'Sep 2023'],
                            ];
                        @endphp
                        @foreach ($classes as $class)
                            <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-base font-semibold text-slate-900">{{ $class['name'] }}</p>
                                        <p class="text-sm text-slate-500">Wali: {{ $class['teacher'] }}</p>
                                    </div>
                                    <div class="flex flex-wrap gap-2 text-sm text-slate-600">
                                        <span class="rounded-full border border-slate-200 bg-emerald-50 px-3 py-1">{{ $class['students'] }} siswa</span>
                                        <span class="rounded-full border border-slate-200 bg-sky-50 px-3 py-1">{{ $class['month'] }}</span>
                                    </div>
                                    <div class="flex flex-wrap gap-3">
                                        <a href="#" class="inline-flex items-center gap-2 justify-center rounded-[16px] border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                            Excel
                                        </a>
                                        <a href="#" class="inline-flex items-center gap-2 justify-center rounded-[16px] border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                            PDF
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-base font-semibold text-slate-900">Download Semua Sekaligus</p>
                            <p class="text-sm text-slate-500">Unduh rekap gabungan seluruh kelas dalam satu file.</p>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-[24px] border border-emerald-200 bg-emerald-50 p-6">
                            <div class="mb-4 flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-emerald-700">XLS</div>
                                <div>
                                    <p class="font-semibold text-slate-900">Rekap Excel Semua Kelas</p>
                                    <p class="text-sm text-slate-500">Satu file .xlsx berisi semua kelas & bulan. Estimasi ukuran: ~240 KB</p>
                                </div>
                            </div>
                            {{-- Excel --}}
                            <button class="inline-flex items-center gap-2 rounded-[16px] bg-emerald-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                Download
                            </button>
                        </div>
                        <div class="rounded-[24px] border border-rose-200 bg-rose-50 p-6">
                            <div class="mb-4 flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-rose-700">PDF</div>
                                <div>
                                    <p class="font-semibold text-slate-900">Rekap PDF Semua Kelas</p>
                                    <p class="text-sm text-slate-500">Satu file .pdf siap cetak untuk semua kelas. Estimasi ukuran: ~1.2 MB</p>
                                </div>
                            </div>
                            {{-- PDF --}}
                            <button class="inline-flex items-center gap-2 rounded-[16px] bg-rose-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-rose-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                Download
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
