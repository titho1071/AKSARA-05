@extends('layouts.index')
@php $role = 'guru'; @endphp
@section('title', 'Rekap Absensi')

@section('content')
@include('components.navbar', ['role' => $role])
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
                <div class="grid gap-4 md:grid-cols-3">
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
                                ['name' => 'Kelas I A',   'teacher' => 'Siti Rahayu, S.Pd',  'students' => 28, 'tahun' => '2023/2024 - Semester 1'],
                                ['name' => 'Kelas I B',   'teacher' => 'Ahmad Fauzi, S.Pd',   'students' => 30, 'tahun' => '2023/2024 - Semester 1'],
                                ['name' => 'Kelas II A',  'teacher' => 'Dewi Lestari, S.Pd',  'students' => 27, 'tahun' => '2023/2024 - Semester 1'],
                                ['name' => 'Kelas II B',  'teacher' => 'Rizal Hidayat, S.Pd', 'students' => 29, 'tahun' => '2023/2024 - Semester 1'],
                                ['name' => 'Kelas III A', 'teacher' => 'Budi Santoso, S.Pd',  'students' => 10, 'tahun' => '2023/2024 - Semester 1'],
                                ['name' => 'Kelas III B', 'teacher' => 'Nurul Aini, S.Pd',    'students' => 31, 'tahun' => '2023/2024 - Semester 1'],
                                ['name' => 'Kelas IV A',  'teacher' => 'Hendra Putra, S.Pd',  'students' => 26, 'tahun' => '2023/2024 - Semester 1'],
                                ['name' => 'Kelas IV B',  'teacher' => 'Fitriana, S.Pd',       'students' => 32, 'tahun' => '2023/2024 - Semester 1'],
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
                                    </div>
                                    <div class="flex flex-wrap gap-3">
                                        <button
                                            onclick="openPdfModal('{{ $class['name'] }}', '{{ $class['tahun'] }}')"
                                            class="inline-flex items-center gap-2 justify-center rounded-[16px] border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                            PDF
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ===== MODAL REKAPITULASI ABSENSI ===== -->
    <div id="pdfModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6">
        <!-- Modal Box -->
        <div class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl">

            <!-- Header -->
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Rekapitulasi Absensi</h2>
                    <p class="text-sm text-gray-500 mt-1">Pilih rentang waktu untuk mengunduh laporan PDF.</p>
                </div>

            </div>

            <!-- Info Kelas -->
            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 mb-6 space-y-1 text-sm text-slate-600">
                <p>Kelas : <span id="modalKelas" class="font-bold text-slate-900"></span></p>
                <p>Tahun Pelajaran : <span id="modalTahun" class="font-bold text-slate-900"></span></p>
            </div>

            <!-- Pilihan Rentang Waktu -->
            <div class="grid grid-cols-2 gap-3 mb-8">
                <button onclick="downloadPdf('bulan')"
                    class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700 hover:bg-blue-100 transition-colors">
                    Per 1 Bulan
                </button>
                <button onclick="downloadPdf('3bulan')"
                    class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700 hover:bg-blue-100 transition-colors">
                    Per 3 Bulan
                </button>
                <button onclick="downloadPdf('semester')"
                    class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700 hover:bg-blue-100 transition-colors">
                    Per Semester
                </button>
                <button onclick="downloadPdf('tahun')"
                    class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700 hover:bg-blue-100 transition-colors">
                    Per Tahun
                </button>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                <button id="cancel-pdf-modal"
                    class="bg-slate-200 hover:bg-slate-300 text-slate-900 px-6 py-3 rounded-xl font-medium transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentKelas = '';
        let currentTahun = '';

        function openPdfModal(kelas, tahun) {
            currentKelas = kelas;
            currentTahun = tahun;
            document.getElementById('modalKelas').textContent = kelas;
            document.getElementById('modalTahun').textContent = tahun;
            const modal = document.getElementById('pdfModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closePdfModal() {
            const modal = document.getElementById('pdfModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function downloadPdf(rentang) {
            const params = new URLSearchParams({
                kelas: currentKelas,
                tahun: currentTahun,
                rentang: rentang
            });
            window.location.href = `/admin/absensi/download-pdf?${params.toString()}`;
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('cancel-pdf-modal').addEventListener('click', closePdfModal);

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closePdfModal();
            });
        });
    </script>

@endsection