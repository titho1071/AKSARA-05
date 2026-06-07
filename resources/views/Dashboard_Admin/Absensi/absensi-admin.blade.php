@extends('layouts.index')

@php $role = 'admin'; @endphp

@section('content')
@include('components.navbar', ['role' => $role])

    <div class="max-w-[1400px] mx-auto space-y-6">
        
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Absensi</h1>
                    <p class="text-gray-600 mt-1">Pilih Kelas</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[24px] shadow-sm border border-slate-200">
            <!-- Header Section -->
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-xl font-bold text-[#1e2567]">Daftar Kelas</h2>
                <p class="text-sm text-slate-500 mt-1 mb-4">
                    Tahun Pelajaran
                    @if($tapelAktif)
                        <span class="font-medium text-slate-700">{{ $tapelAktif->tahun_pelajaran }} - Semester {{ $tapelAktif->semester }}</span>
                    @endif
                </p>
                
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
                                        <span class="text-sm text-slate-700 font-normal">
                                            @if($selectedTapel)
                                                {{ str_replace('-'.$selectedTapel->semester, '', $selectedTapel->tahun_pelajaran) }}
                                                - Semester {{ $selectedTapel->semester }}
                                            @else
                                                Pilih Tahun Pelajaran
                                            @endif
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
                                                <span class="bg-green-50 text-green-600 text-xs px-2.5 py-1 rounded-md font-medium">
                                                    Aktif
                                                </span>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </details>
                        </div>

                        <!-- Dropdown Entries -->
                        <form method="GET" action="{{ route('admin.absensi') }}" id="perPageForm">
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
                    <form method="GET" action="{{ route('admin.absensi') }}" class="relative w-full md:w-[320px]">
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
                            <th class="px-6 py-4 font-semibold text-center rounded-l-xl w-16">#</th>
                            <th class="px-6 py-4 font-semibold text-center w-1/4">Kelas</th>
                            <th class="px-6 py-4 font-semibold text-center w-1/3">Wali Kelas</th>
                            <th class="px-6 py-4 font-semibold text-center rounded-r-xl w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-transparent">
                        @forelse($kelas as $index => $k)
                        <tr class="hover:bg-slate-50 transition-colors {{ $index % 2 == 0 ? 'bg-white' : 'bg-slate-50/50' }}">
                            <td class="px-6 py-4 text-center text-slate-500">{{ $kelas->firstItem() + $index }}</td>
                            <td class="px-6 py-4 text-center font-medium">{{ $k->nama_kelas }}</td>
                            <td class="px-6 py-4 text-center">{{ $k->guru?->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.absensi.pilih-bulan', $k->id_kelas) }}" class="inline-flex items-center justify-center gap-1.5 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                Tidak ada kelas ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Section -->
            <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-slate-500">
                    Menampilkan {{ $kelas->firstItem() ?? 0 }}–{{ $kelas->lastItem() ?? 0 }} dari {{ $kelas->total() }} kelas
                </p>
                <div class="flex items-center gap-2">
                    {{-- Tombol Prev --}}
                    @if($kelas->onFirstPage())
                        <span class="w-9 h-9 flex items-center justify-center border border-slate-200 rounded-lg text-slate-300 bg-white cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </span>
                    @else
                        <a href="{{ $kelas->previousPageUrl() }}" class="w-9 h-9 flex items-center justify-center border border-slate-200 rounded-lg text-slate-500 hover:bg-slate-50 bg-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                    @endif

                    {{-- Nomor halaman --}}
                    @foreach($kelas->getUrlRange(1, $kelas->lastPage()) as $page => $url)
                        @if($page == $kelas->currentPage())
                            <span class="w-9 h-9 flex items-center justify-center border border-transparent rounded-lg bg-[#1e2567] text-white font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center border border-slate-200 rounded-lg text-slate-500 hover:bg-slate-50 bg-white transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Tombol Next --}}
                    @if($kelas->hasMorePages())
                        <a href="{{ $kelas->nextPageUrl() }}" class="w-9 h-9 flex items-center justify-center border border-slate-200 rounded-lg text-slate-500 hover:bg-slate-50 bg-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    @else
                        <span class="w-9 h-9 flex items-center justify-center border border-slate-200 rounded-lg text-slate-300 bg-white cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection