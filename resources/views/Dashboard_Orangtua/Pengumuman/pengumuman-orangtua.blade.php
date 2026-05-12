@extends('layouts.index')

@php
    $role = 'orangtua';
@endphp

@section('title', 'Pengumuman - Orang Tua')

@section('content')
@include('components.navbar', ['role' => $role])

<div class="px-4 py-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Pengumuman</h1>
        <p class="text-gray-500 mt-1">semua pengumuman</p>
    </div>

    <!-- Student Tabs -->
    <div class="flex flex-wrap gap-4 mb-8">
        @foreach($siswa as $s)
            @php
                $isActive = $activeSiswa && $activeSiswa->id_siswa == $s->id_siswa;

                $initials = collect(explode(' ', $s->nama))
                    ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                    ->take(2)
                    ->implode('');
            @endphp

            <a
                href="{{ route('orangtua.pengumuman', ['siswa_id' => $s->id_siswa]) }}"
                class="flex items-center gap-3 px-6 py-3 rounded-2xl transition-all
                {{ $isActive
                    ? 'bg-[#1E2567] text-white shadow-lg'
                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                }}"
            >
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold
                    {{ $isActive ? 'bg-white/20' : 'bg-gray-300' }}">
                    {{ $initials }}
                </div>

                <div>
                    <p class="font-bold text-sm">
                        {{ $s->nama }}
                    </p>

                    <p class="text-xs
                        {{ $isActive ? 'text-blue-100' : 'text-gray-500' }}">
                        {{ $s->nama_kelas }}
                    </p>
                </div>
            </a>
        @endforeach
    </div>
    <div class="space-y-6">
        @forelse($pengumuman as $item)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-blue-200 hover:shadow-md transition-shadow relative">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $item->judul }}</h2>
                        
                        <div class="space-y-1 text-gray-600 mb-6">
                            <p><span class="font-medium">Tanggal Mulai:</span> {{ optional($item->tanggal_mulai)->format('d/m/Y') ?? '-' }}</p>
                            <p><span class="font-medium">Tanggal Selesai:</span> {{ optional($item->tanggal_selesai)->format('d/m/Y') ?? '-' }}</p>
                        </div>

                        @if($item->file)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl w-fit">
                                <div class="bg-red-100 p-2 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <a href="{{ asset('storage/' . $item->file) }}" target="_blank" class="text-blue-600 hover:underline font-medium">
                                    {{ $item->nama_file ?: basename($item->file) }}
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="md:text-right mt-4 md:mt-0">
                        <a href="{{ route('orangtua.pengumuman.detail', $item->id_pengumuman) }}" class="text-blue-600 hover:underline font-medium inline-flex items-center gap-2">
                            Lihat Detail Pengumuman
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                @if($item->kelas)
                    <div class="absolute top-6 right-6 hidden md:block">
                        <span class="text-xs bg-blue-50 text-blue-600 px-3 py-1 rounded-full border border-blue-100">
                            {{ $item->kelas->nama_kelas }}
                        </span>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
                <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Belum Ada Pengumuman</h3>
                <p class="text-gray-500 mt-1">Pengumuman terbaru untuk Anda akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
