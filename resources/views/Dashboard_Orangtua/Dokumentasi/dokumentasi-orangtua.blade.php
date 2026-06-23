@extends('layouts.index')
@php $role = 'orangtua'; @endphp
@section('title', 'Dokumentasi Kegiatan')

@section('content')
@include('components.navbar', ['role' => $role])

{{-- Header --}}
<div class="mb-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Dokumentasi</h1>
            <p class="text-gray-600 mt-1">Semua Dokumentasi Kegiatan</p>
        </div>
    </div>
</div>

{{-- Pilih Anak --}}
<div class="flex flex-wrap gap-4 mb-8">
    @foreach($siswa as $s)
        @php
            $isActive = $activeSiswa && $activeSiswa->id_siswa == $s->id_siswa;

            $initials = collect(explode(' ', $s->nama))
                ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                ->take(2)
                ->implode('');
        @endphp

        <a href="{{ route('orangtua.dokumentasi', ['siswa_id' => $s->id_siswa]) }}"
            class="flex items-center gap-3 px-6 py-3 rounded-2xl transition-all
            {{ $isActive
                ? 'bg-[#1E2567] text-white shadow-lg'
                : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">

            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold
                {{ $isActive ? 'bg-white/20' : 'bg-gray-300' }}">
                {{ $initials }}
            </div>

            <div>
                <p class="font-bold text-sm">{{ $s->nama }}</p>
                <p class="text-xs {{ $isActive ? 'text-blue-100' : 'text-gray-500' }}">
                    {{ $s->nama_kelas }}
                </p>
            </div>
        </a>
    @endforeach
</div>

{{-- Statistik --}}
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8 w-fit">
    <div class="flex items-center gap-4">
        <div class="bg-blue-100 rounded-2xl p-4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="blue" class="size-6">
                <path d="M12 9a3.75 3.75 0 1 0 0 7.5A3.75 3.75 0 0 0 12 9Z" />
                <path fill-rule="evenodd" d="M9.344 3.071a49.52 49.52 0 0 1 5.312 0c.967.052 1.83.585 2.332 1.39l.821 1.317c.24.383.645.643 1.11.71.386.054.77.113 1.152.177 1.432.239 2.429 1.493 2.429 2.909V18a3 3 0 0 1-3 3h-15a3 3 0 0 1-3-3V9.574c0-1.416.997-2.67 2.429-2.909.382-.064.766-.123 1.151-.178a1.56 1.56 0 0 0 1.11-.71l.822-1.315a2.942 2.942 0 0 1 2.332-1.39ZM6.75 12.75a5.25 5.25 0 1 1 10.5 0 5.25 5.25 0 0 1-10.5 0Zm12-1.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
            </svg>
        </div>
        <div>
            <p class="text-gray-500 text-sm">Total Kegiatan</p>
            <p class="text-3xl font-bold text-gray-900">{{ $kegiatans->total() }}</p>
            <p class="text-xs text-gray-500">{{ $activeSiswa?->nama }}</p>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
    <h2 class="text-xl font-bold text-gray-900 mb-6">Filter & Pencarian</h2>
    <form method="GET" action="{{ route('orangtua.dokumentasi') }}">
        @if($activeSiswa)
            <input type="hidden" name="siswa_id" value="{{ $activeSiswa->id_siswa }}">
        @endif
        <div class="flex gap-2 flex-col md:flex-row">
            <input type="text" name="search" value="{{ $search ?? '' }}"
                placeholder="Cari judul atau deskripsi..."
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </button>
            @if(!empty($search))
                <a href="{{ route('orangtua.dokumentasi', array_filter(['siswa_id' => $activeSiswa?->id_siswa])) }}"
                class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-2 rounded-lg flex items-center justify-center gap-2 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Dokumentasi --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

    @forelse($kegiatans as $kegiatan)

        @php
            $foto = $kegiatan->dokumentasi->first();
        @endphp

        <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-lg transition">

            {{-- Foto --}}
            <div class="h-52 bg-gray-100 overflow-hidden">
                @if($foto)
                    <img
                        src="{{ asset('storage/' . $foto->foto) }}"
                        alt="{{ $kegiatan->judul }}"
                        class="w-full h-full object-cover">
                @else
                    <div class="h-full flex items-center justify-center text-gray-400">
                        Belum ada foto
                    </div>
                @endif
            </div>

            {{-- Konten --}}
            <div class="p-5">

                <h3 class="font-bold text-lg text-gray-900 line-clamp-2">
                    {{ $kegiatan->judul }}
                </h3>

                <p class="text-sm text-gray-500 mt-2">
                    {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}
                </p>

                <p class="text-sm text-gray-600 mt-2">
                    Oleh {{ $kegiatan->guru->nama ?? $kegiatan->guru->username }}
                </p>

                <div class="mt-3 flex flex-wrap gap-2">
                    @if($kegiatan->kelas_id === 'semua_kelas')
                        <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-medium">
                            Semua Kelas
                        </span>
                    @elseif($kegiatan->kelas)
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">
                            {{ $kegiatan->kelas->nama_kelas }}
                        </span>
                    @endif
                </div>

                <div class="mt-5">
                    <a href="{{ route('orangtua.dokumentasi.detail', $kegiatan->id_kegiatan) }}"
                        class="inline-flex items-center justify-center w-full px-4 py-2 rounded-xl bg-[#1E2567] text-white hover:bg-[#2d377d] transition">
                        Lihat Detail
                    </a>
                </div>

            </div>
        </div>

    @empty

        <div class="col-span-full bg-white rounded-2xl p-12 text-center text-gray-400 shadow-sm border border-gray-100">
            Belum ada dokumentasi kegiatan.
        </div>

    @endforelse

</div>

{{-- Footer --}}
<div class="mt-6 text-sm text-gray-600">
    Total {{ $kegiatans->total() }} Dokumentasi
</div>

@if($kegiatans->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $kegiatans->links() }}
    </div>
@endif

@endsection