@extends('layouts.index')

@php
    $role = 'orangtua';
@endphp

@section('title', 'Detail Pengumuman - Orang Tua')

@section('content')
@include('components.navbar', ['role' => $role])

<div class="px-4 py-6">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pengumuman</h1>
            <p class="text-gray-500 mt-1">semua pengumuman</p>
        </div>
        <a href="{{ route('orangtua.pengumuman') }}" class="bg-[#1E2567] text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-[#2a348c] transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-sm border border-blue-200">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $pengumuman->judul }}</h2>
        
        <div class="space-y-1 text-gray-600 mb-8 border-b border-gray-100 pb-6">
            <p><span class="font-medium">Tanggal Mulai:</span> {{ optional($pengumuman->tanggal_mulai)->format('d/m/Y') ?? '-' }}</p>
            <p><span class="font-medium">Tanggal Selesai:</span> {{ optional($pengumuman->tanggal_selesai)->format('d/m/Y') ?? '-' }}</p>
        </div>

        <div class="prose prose-blue max-w-none text-gray-700 leading-relaxed mb-8">
            {!! nl2br(e($pengumuman->deskripsi)) !!}
        </div>

        @if($pengumuman->file)
            <div class="border-t border-gray-100 pt-6">
                <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl w-fit border border-gray-200">
                    <div class="bg-red-100 p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $pengumuman->display_file_name }}</p>
                        <a href="{{ route('pengumuman.file', $pengumuman->id_pengumuman) }}" target="_blank" class="text-blue-600 hover:underline text-sm">
                            Download Dokumen
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
