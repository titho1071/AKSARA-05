@extends('layouts.index')
@php $role = 'admin'; @endphp
@section('title', 'Detail Dokumentasi Kegiatan')

@section('content')
@include('components.navbar', ['role' => $role])

<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Detail Dokumentasi</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap kegiatan dari guru.</p>
        </div>
        <a href="{{ route('admin.dokumentasi') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali
        </a>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    {{-- Kolom kiri: info kegiatan --}}
    <div class="space-y-6 lg:col-span-2">
        {{-- Info utama --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
            <h2 class="mb-1 text-2xl font-bold text-gray-900">{{ $kegiatan->judul }}</h2>

            <div class="mb-6 flex flex-wrap gap-2">
                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                    {{ $kegiatan->guru->nama ?? $kegiatan->guru->username }}
                </span>
                @if($kegiatan->kelas_id)
                    @if($kegiatan->kelas_id === 'semua_kelas')
                        <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">
                            Semua Kelas
                        </span>
                    @else
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                            Kelas {{ $kegiatan->kelas_id }}
                        </span>
                    @endif
                @endif
                <span class="rounded-full bg-cyan-100 px-3 py-1 text-xs font-semibold text-cyan-700">
                    {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}
                </span>
                <span class="rounded-full px-3 py-1 text-xs font-semibold
                    {{ $kegiatan->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ ucfirst($kegiatan->status) }}
                </span>
            </div>

            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-gray-400">Deskripsi</p>
                <p class="text-sm leading-relaxed text-gray-600">{{ $kegiatan->deskripsi ?? '-' }}</p>
            </div>
        </div>

        {{-- Foto dokumentasi --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900">Dokumentasi Kegiatan</h3>
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                    {{ $kegiatan->dokumentasi->count() }} foto
                </span>
            </div>

            @if ($kegiatan->dokumentasi->count() > 0)
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                    @foreach ($kegiatan->dokumentasi as $foto)
                        <a href="{{ asset('storage/' . $foto->foto) }}" target="_blank"
                            class="group relative overflow-hidden rounded-lg">
                            <img src="{{ asset('storage/' . $foto->foto) }}"
                                class="h-40 w-full object-cover transition duration-300 group-hover:scale-105" />
                            <div class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 transition group-hover:opacity-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-8 w-8 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mb-3 h-12 w-12">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <p class="text-sm">Belum ada foto dokumentasi.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Kolom kanan: ringkasan --}}
    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="mb-5 text-sm font-bold uppercase tracking-widest text-gray-400">Ringkasan</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Dari Guru</span>
                    <span class="text-sm font-semibold text-gray-900">
                        {{ $kegiatan->guru->nama ?? $kegiatan->guru->username }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Total foto</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $kegiatan->dokumentasi->count() }} foto</span>
                </div>
                @if($kegiatan->kelas_id)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Kelas</span>
                        <span class="text-sm font-semibold text-gray-900">
                            @if($kegiatan->kelas_id === 'semua_kelas')
                                Semua Kelas
                            @else
                                Kelas {{ $kegiatan->kelas_id }}
                            @endif
                        </span>
                    </div>
                @endif
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Tanggal kegiatan</span>
                    <span class="text-sm font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d M Y') }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Status</span>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold
                        {{ $kegiatan->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ ucfirst($kegiatan->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
