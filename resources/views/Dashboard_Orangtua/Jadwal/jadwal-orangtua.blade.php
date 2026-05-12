@extends('layouts.index')

@php
    $role = 'orangtua';
@endphp

@section('title', 'Jadwal Pelajaran - Orang Tua')

@section('content')
@include('components.navbar', ['role' => $role])

<div class="px-4 py-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Jadwal Pelajaran</h1>
        <p class="text-gray-500 mt-1">semua Jadwal Pelajaran</p>
    </div>

    <!-- Student Tabs -->
    <div class="flex flex-wrap gap-4 mb-8">
        @foreach($siswa as $s)
            @php
                $isActive = $activeSiswa && $activeSiswa->id_siswa == $s->id_siswa;
                $initials = collect(explode(' ', $s->nama))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->implode('');
            @endphp
            <a href="{{ route('orangtua.jadwal', ['siswa_id' => $s->id_siswa]) }}" 
               class="flex items-center gap-3 px-6 py-3 rounded-2xl transition-all {{ $isActive ? 'bg-[#1E2567] text-white shadow-lg' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold {{ $isActive ? 'bg-white/20' : 'bg-gray-300' }}">
                    {{ $initials }}
                </div>
                <div>
                    <p class="font-bold text-sm">{{ $s->nama }}</p>
                    <p class="text-xs {{ $isActive ? 'text-blue-100' : 'text-gray-500' }}">{{ $s->nama_kelas }}</p>
                </div>
            </a>
        @endforeach
    </div>

    @if($activeSiswa)
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-gray-500 text-sm mb-1">Total JP</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl font-bold text-green-500">20</span>
                    <span class="text-gray-400 text-sm">JP / minggu</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-gray-500 text-sm mb-1">Mata Pelajaran</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl font-bold text-blue-500">10</span>
                    <span class="text-gray-400 text-sm">mapel</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-gray-500 text-sm mb-1">Hari Belajar</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl font-bold text-amber-500">5</span>
                    <span class="text-gray-400 text-sm">hari</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-gray-500 text-sm mb-1">JP Hari Ini</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl font-bold text-rose-500">5</span>
                    <span class="text-gray-400 text-sm">JP</span>
                </div>
            </div>
            <div class="bg-[#1E2567] p-6 rounded-2xl shadow-lg text-white">
                <p class="text-blue-200 text-xs mb-1">Tahun Pelajaran</p>
                <p class="text-xl font-bold">2024/2025</p>
                <p class="text-xs text-blue-200">Semester 1 • Aktif</p>
                <div class="mt-2 h-1 bg-amber-400 rounded-full w-full"></div>
            </div>
        </div>

        <!-- Filter & Mode -->
        <div class="flex flex-wrap items-center gap-4 mb-6 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Filter Hari:</span>
                <button class="px-4 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-700">Semua Hari (Grid)</button>
            </div>
            <div class="flex items-center gap-2 ml-auto">
                <span class="text-sm text-gray-500">Mode:</span>
                <button class="px-4 py-1.5 rounded-full text-sm font-medium bg-[#1E2567] text-white">Grid Mingguan</button>
            </div>
        </div>

        <!-- Schedule Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $day)
                <div class="space-y-4">
                    <div class="bg-[#1E2567] text-white py-3 px-4 rounded-xl text-center font-bold">
                        {{ $day }}
                    </div>
                    
                    @foreach($jadwal[$day] ?? [] as $item)
                        @if(isset($item['type']) && $item['type'] == 'istirahat')
                            <div class="bg-amber-50 border border-dashed border-amber-300 rounded-xl py-2 px-4 text-center">
                                <p class="text-amber-700 text-xs font-bold uppercase tracking-wider">Istirahat {{ $item['jam'] }}</p>
                            </div>
                        @else
                            @php
                                $colorClasses = [
                                    'blue' => 'bg-blue-50 border-blue-200 text-blue-700',
                                    'green' => 'bg-emerald-50 border-emerald-200 text-emerald-700',
                                    'orange' => 'bg-orange-50 border-orange-200 text-orange-700',
                                    'purple' => 'bg-purple-50 border-purple-200 text-purple-700',
                                    'red' => 'bg-rose-50 border-rose-200 text-rose-700',
                                    'amber' => 'bg-amber-50 border-amber-200 text-amber-700',
                                    'teal' => 'bg-teal-50 border-teal-200 text-teal-700',
                                    'yellow' => 'bg-yellow-50 border-yellow-200 text-yellow-700',
                                    'pink' => 'bg-pink-50 border-pink-200 text-pink-700',
                                ];
                                $colorClass = $colorClasses[$item['color']] ?? $colorClasses['blue'];
                            @endphp
                            <div class="{{ $colorClass }} border-l-4 border-l-current rounded-xl p-4 shadow-sm relative group hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-1">
                                    <h3 class="font-bold text-sm">{{ $item['mapel'] }}</h3>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-white/50 font-bold">{{ $item['jp'] }} JP</span>
                                </div>
                                <p class="text-[11px] opacity-80 mb-1 font-medium">{{ $item['jam'] }}</p>
                                <p class="text-[11px] opacity-60">{{ $item['guru'] }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
            <p class="text-gray-500">Pilih siswa untuk melihat jadwal.</p>
        </div>
    @endif
</div>
@endsection
