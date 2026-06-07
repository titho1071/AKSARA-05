@extends('layouts.index')
@php
    $role = 'guru';
    /** @var \Illuminate\Pagination\LengthAwarePaginator $kelas */
    /** @var \Illuminate\Support\Collection $tahunPelajaran */
@endphp
@section('title', 'Absensi')

@section('content')
@include('components.navbar', ['role' => $role])

    <div class="mb-8">
        <div class="px-4 py-2 pt-4 mb-8" >
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Absensi</h1>
            <p class="text-gray-600 mt-1">Daftar Kelas</p>
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
                            <details class="group">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
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
                    </div>
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
                            <th class="px-6 py-4 font-semibold text-center rounded-r-xl w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-transparent">
                        @foreach($kelas as $item)
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
                                <a href="{{ route('guru.absensi.pilih-bulan', $item->id_kelas) }}"
                                    class="inline-flex items-center justify-center gap-1.5 bg-[#3B82F6] hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    Kelola
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
