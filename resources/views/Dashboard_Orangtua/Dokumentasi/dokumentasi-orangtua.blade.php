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
            <p class="text-3xl font-bold text-gray-900">{{ $kegiatans->count() }}</p>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
    <h2 class="text-xl font-bold text-gray-900 mb-6">Filter & Pencarian</h2>
    <form method="GET" action="{{ route('orangtua.dokumentasi') }}">
        <div class="flex gap-2 flex-col md:flex-row">
            <input type="text" name="search" value="{{ $search ?? '' }}"
                placeholder="Cari judul atau deskripsi..."
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
            <select name="kelas" 
                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white">
                <option value="">Semua Kelas</option>
                <option value="semua_kelas" {{ ($kelas ?? '') === 'semua_kelas' ? 'selected' : '' }}>Semua Kelas (Tag)</option>
                @foreach(['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'] as $kelasRom)
                    <option value="{{ $kelasRom }}" {{ ($kelas ?? '') === $kelasRom ? 'selected' : '' }}>
                        Kelas {{ $kelasRom }}
                    </option>
                @endforeach
            </select>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </button>
        </div>
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left px-4 py-4 text-sm font-semibold text-gray-700">No</th>
                    <th class="text-left px-4 py-4 text-sm font-semibold text-gray-700">Judul</th>
                    <th class="text-left px-4 py-4 text-sm font-semibold text-gray-700">Guru</th>
                    <th class="text-left px-4 py-4 text-sm font-semibold text-gray-700">Kelas</th>
                    <th class="text-left px-4 py-4 text-sm font-semibold text-gray-700">Tanggal</th>
                    <th class="text-left px-4 py-4 text-sm font-semibold text-gray-700">Foto</th>
                    <th class="text-left px-4 py-4 text-sm font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kegiatans as $index => $kegiatan)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-4 text-sm">{{ $index + 1 }}</td>
                        <td class="px-4 py-4 font-medium text-gray-900">{{ $kegiatan->judul }}</td>
                        <td class="px-4 py-4 text-gray-600 text-sm">
                            {{ $kegiatan->guru->nama ?? $kegiatan->guru->username }}
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-600">
                            @if($kegiatan->kelas_id)
                                @if($kegiatan->kelas_id === 'semua_kelas')
                                    <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">Semua Kelas</span>
                                @else
                                    <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">Kelas {{ $kegiatan->kelas_id }}</span>
                                @endif
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-gray-600 text-sm">
                            {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-4">
                            @php $foto = $kegiatan->dokumentasi->first(); @endphp
                            @if ($foto)
                                <img src="{{ asset('storage/' . $foto->foto) }}"
                                    class="w-20 h-12 object-cover rounded-lg" alt="foto">
                            @else
                                <span class="text-xs text-gray-400">Belum ada foto</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <a href="{{ route('orangtua.dokumentasi.detail', $kegiatan->id_kegiatan) }}"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-600 transition hover:bg-blue-200" title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                    <path d="M11.625 16.5a1.875 1.875 0 1 0 0-3.75 1.875 1.875 0 0 0 0 3.75Z" />
                                    <path fill-rule="evenodd" d="M5.625 1.5H9a3.75 3.75 0 0 1 3.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 0 1 3.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 0 1-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875Zm6 16.5c.66 0 1.277-.19 1.797-.518l1.048 1.048a.75.75 0 0 0 1.06-1.06l-1.047-1.048A3.375 3.375 0 1 0 11.625 18Z" clip-rule="evenodd" />
                                    <path d="M14.25 5.25a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963A5.23 5.23 0 0 0 16.5 7.5h-1.875a.375.375 0 0 1-.375-.375V5.25Z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-sm text-gray-400">
                            Belum ada dokumentasi kegiatan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6 text-sm text-gray-600">
        Total {{ $kegiatans->count() }} Dokumentasi
    </div>
</div>

@endsection