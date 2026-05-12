@extends('layouts.index')

@section('title', 'Jadwal Mengajar')

@php
    $role = 'guru';
@endphp

@section('content')
<div class="min-h-screen">
    @include('components.navbar', ['role' => $role])

    <!-- Header Section -->
    <div class="mb-8 pt-4">
        <h1 class="text-3xl font-bold text-gray-900">Jadwal Mengajar</h1>
        <p class="text-gray-500">semua Jadwal Mengajar</p>
    </div>

    <!-- Teacher Info Card -->
    <div class="bg-[#F0F4FF] rounded-3xl p-6 mb-8 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                BS
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Budi Santoso, S.Pd</h2>
                <p class="text-sm text-gray-500">NIP · 198501012010011001</p>
            </div>
        </div>
        
        <div class="flex gap-4">
            <div class="bg-gray-200/50 rounded-xl px-6 py-4 text-center min-w-[100px]">
                <div class="text-2xl font-bold text-gray-900">11</div>
                <div class="text-xs text-gray-500">Total JP</div>
            </div>
            <div class="bg-gray-200/50 rounded-xl px-6 py-4 text-center min-w-[100px]">
                <div class="text-2xl font-bold text-gray-900">6</div>
                <div class="text-xs text-gray-500">Kelas</div>
            </div>
            <div class="bg-gray-200/50 rounded-xl px-6 py-4 text-center min-w-[100px]">
                <div class="text-2xl font-bold text-gray-900">5</div>
                <div class="text-xs text-gray-500">Hari</div>
            </div>
        </div>
    </div>

    <!-- Day Selector -->
    <div class="mb-8">
        <h3 class="text-sm font-bold text-gray-900 mb-4">List Jadwal Mengajar</h3>
        <div class="flex gap-3">
            <button class="bg-blue-600 text-white rounded-xl px-4 py-3 text-center min-w-[80px] shadow-lg shadow-blue-200 transition-all">
                <div class="text-xs font-medium uppercase opacity-80">Sen</div>
                <div class="text-lg font-bold">14</div>
            </button>
            <button class="bg-gray-200 text-gray-500 rounded-xl px-4 py-3 text-center min-w-[80px] hover:bg-gray-300 transition-all">
                <div class="text-xs font-medium uppercase opacity-80">Sel</div>
                <div class="text-lg font-bold">15</div>
            </button>
        </div>
    </div>

    <!-- Schedule List -->
    <div class="space-y-4">
        <!-- Schedule Item 1 -->
        <div class="bg-[#F8FAFF] rounded-2xl p-6 border-l-4 border-blue-600 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-medium text-gray-400">07:00 - 0</div>
                <h4 class="text-lg font-bold text-gray-900">Matematika</h4>
                <div class="text-sm text-gray-500">VII-A</div>
            </div>
            <div>
                <span class="bg-blue-600 text-white px-6 py-2 rounded-xl text-sm font-bold shadow-md shadow-blue-200">
                    VII A
                </span>
            </div>
        </div>

        <!-- Schedule Item 2 -->
        <div class="bg-[#F8FAFF] rounded-2xl p-6 border-l-4 border-blue-600 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-medium text-gray-400">10:15</div>
                <h4 class="text-lg font-bold text-gray-900">Matematika</h4>
                <div class="text-sm text-gray-500">VIII-B</div>
            </div>
            <div>
                <span class="bg-blue-600 text-white px-6 py-2 rounded-xl text-sm font-bold shadow-md shadow-blue-200">
                    VII A
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
