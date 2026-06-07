@extends('layouts.index')
@php
    $role = 'admin';
    /** @var \Illuminate\Pagination\LengthAwarePaginator $kelas */
    /** @var \Illuminate\Support\Collection $tahunPelajaran */
@endphp
@section('title', 'Rekap Absensi')

@section('content')
@include('components.navbar', ['role' => $role])

    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Rekap Absensi</h1>
                <p class="text-gray-600 mt-1">Pilih Kelas</p>
            </div>
        </div>
    </div>

        <div class="bg-white rounded-[24px] shadow-sm border border-slate-200">
            <!-- Header Section -->
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-xl font-bold text-[#1e2567]">Daftar Kelas</h2>
                <p class="text-sm text-slate-500 mt-1 mb-4">Tahun Pelajaran</p>

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Dropdown Tahun Pelajaran -->
                        <div class="relative">
                            <details class="group" id="tapelDetails">
                                <summary class="list-none cursor-pointer w-full sm:w-[320px] flex items-center justify-between border border-slate-200 rounded-xl px-4 py-2.5 bg-white">
                                    <div class="flex items-center gap-2">
                                        @if($tapelAktif && $tapelAktif->id_tapel == $tapelId)
                                            <span class="bg-green-50 text-green-600 text-xs px-2.5 py-1 rounded-md font-medium">
                                                Aktif
                                            </span>
                                        @endif
                                        @php
                                            $selectedTapel = $tahunPelajaran->firstWhere('id_tapel', $tapelId);
                                        @endphp
                                        <span class="text-sm text-slate-700">
                                            {{ str_replace('-'.$selectedTapel->semester, '', $selectedTapel->tahun_pelajaran) }}
                                            - Semester {{ $selectedTapel->semester }}
                                        </span>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </summary>
                                <div class="absolute z-50 mt-2 w-full bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden">
                                    @foreach($tahunPelajaran as $tapel)
                                        <a href="{{ request()->fullUrlWithQuery(['tapel' => $tapel->id_tapel]) }}"
                                           class="flex items-center justify-between px-4 py-3 hover:bg-slate-50 text-sm">
                                            <span>
                                                {{ str_replace('-'.$tapel->semester, '', $tapel->tahun_pelajaran) }}
                                                - Semester {{ $tapel->semester }}
                                            </span>
                                            @if($tapelAktif && $tapelAktif->id_tapel == $tapel->id_tapel)
                                                <span class="bg-green-50 text-green-600 text-xs px-2 py-1 rounded-md font-medium">
                                                    Aktif
                                                </span>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </details>
                        </div>

                        <!-- Dropdown Entries -->
                        <form method="GET" action="{{ route('admin.absensi.rekap') }}" id="perPageForm">
                            <input type="hidden" name="search" value="{{ $search ?? '' }}">
                            <input type="hidden" name="tapel" value="{{ $tapelId }}">
                            <div class="relative">
                                <select
                                    name="per_page"
                                    onchange="document.getElementById('perPageForm').submit()"
                                    class="appearance-none border border-slate-200 rounded-xl px-4 py-2.5 pr-8 bg-white text-sm text-slate-700 focus:outline-none focus:border-[#1e2567] focus:ring-1 focus:ring-[#1e2567]">
                                    @foreach([10, 25, 50] as $pp)
                                        <option value="{{ $pp }}" {{ request('per_page', 10) == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Search -->
                    <form method="GET" action="{{ route('admin.absensi.rekap') }}" class="relative w-full md:w-[320px]">
                        <input type="hidden" name="tapel" value="{{ $tapelId }}">
                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search ?? '' }}"
                            placeholder="Cari nama kelas..."
                            class="w-full border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-sm focus:outline-none focus:border-[#1e2567] focus:ring-1 focus:ring-[#1e2567] transition-all">
                        <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Table Section -->
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead>
                        <tr class="bg-[#1e2567] text-white">
                            <th class="px-6 py-4 font-semibold text-center rounded-l-xl w-16">No</th>
                            <th class="px-6 py-4 font-semibold text-center w-1/4">Kelas</th>
                            <th class="px-6 py-4 font-semibold text-center w-1/3">Wali Kelas</th>
                            <th class="px-6 py-4 font-semibold text-center rounded-r-xl w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-transparent">
                        @forelse($kelas as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-center">
                                {{ $kelas->firstItem() + $loop->index }}
                            </td>

                            <td class="px-6 py-4 text-center font-medium">
                                {{ $item->nama_kelas }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                {{ $item->guru->nama ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <button
                                    onclick="openRekapModal('{{ $item->nama_kelas }}', '{{ $item->id_kelas }}')"
                                    class="inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                                    </svg>
                                    Cetak Rekapitulasi
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm">Tidak ada kelas yang tersedia</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Section -->
            @if($kelas->total() > 0)
            <div class="px-6 py-4 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-slate-500">
                    Menampilkan {{ $kelas->firstItem() }}–{{ $kelas->lastItem() }}
                    dari {{ $kelas->total() }} kelas
                </p>

                {{ $kelas->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- ===== MODAL CETAK REKAPITULASI ===== -->
    <div id="rekapModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6">
        <div class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl">

            <!-- Header -->
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Rekapitulasi Absensi</h2>
                    <p class="text-sm text-gray-500 mt-1">Pilih rentang waktu untuk mengunduh laporan PDF.</p>
                </div>
                <button onclick="closeRekapModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Info Kelas -->
            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 mb-6 space-y-1 text-sm text-slate-600">
                <p>Kelas : <span id="modalNamaKelas" class="font-bold text-slate-900"></span></p>
            </div>

            <!-- Pilihan Rentang Waktu -->
            <div class="grid grid-cols-2 gap-3 mb-8">
                <button onclick="downloadRekap('bulan')"
                    class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700 hover:bg-blue-100 transition-colors">
                    Per 1 Bulan
                </button>
                <button onclick="downloadRekap('3bulan')"
                    class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700 hover:bg-blue-100 transition-colors">
                    Per 3 Bulan
                </button>
                <button onclick="downloadRekap('semester')"
                    class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700 hover:bg-blue-100 transition-colors">
                    Per Semester
                </button>
                <button onclick="downloadRekap('tahun')"
                    class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700 hover:bg-blue-100 transition-colors">
                    Per Tahun
                </button>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                <button onclick="closeRekapModal()"
                    class="bg-slate-200 hover:bg-slate-300 text-slate-900 px-6 py-3 rounded-xl font-medium transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentKelasId = '';
        let currentNamaKelas = '';

        function openRekapModal(namaKelas, kelasId) {
            currentKelasId = kelasId;
            currentNamaKelas = namaKelas;
            document.getElementById('modalNamaKelas').textContent = namaKelas;
            const modal = document.getElementById('rekapModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeRekapModal() {
            const modal = document.getElementById('rekapModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function downloadRekap(rentang) {
            const params = new URLSearchParams({
                kelas_id: currentKelasId,
                kelas: currentNamaKelas,
                rentang: rentang
            });
            window.location.href = `/admin/absensi/download-pdf?${params.toString()}`;
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeRekapModal();
            });

            document.getElementById('rekapModal').addEventListener('click', function(e) {
                if (e.target === this) closeRekapModal();
            });
        });
    </script>

@endsection