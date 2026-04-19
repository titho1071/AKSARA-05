@extends('layouts.index')

@php
    $role = 'guru';
@endphp

@section('content')
    <div class="max-w-[1180px] mx-auto space-y-8">
        <div class="space-y-2">
            <p class="text-sm font-semibold text-slate-600">Absensi Siswa</p>
            <h1 class="text-3xl font-semibold text-slate-950">Absensi Siswa</h1>
            <p class="text-sm text-slate-500">Pilih kelas yang akan dikelola</p>
        </div>

        <div class="rounded-[32px] bg-white p-6 shadow-[0_24px_60px_rgba(15,23,42,0.08)]">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between text-sm text-slate-500 mb-6">
                <div class="flex flex-col gap-2">
                    <div class="inline-flex items-center gap-2 text-slate-600">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.6667 12L6 7.33333L10.6667 2.66667" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Pilih kelas untuk melihat absensi
                    </div>
                    <p class="text-sm text-slate-500">Pilih kelas sebelum mengelola atau melihat rekap.</p>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                @php
                    $classes = [
                        ['name' => 'Kelas III A', 'teacher' => 'Budi Santoso, S.Pd', 'students' => 10, 'status' => 'Sudah diisi hari ini', 'badge' => 'green'],
                        ['name' => 'Kelas III B', 'teacher' => 'Nurul Aini, S.Pd', 'students' => 31, 'status' => 'Belum diisi hari ini', 'badge' => 'amber'],
                        ['name' => 'Kelas IV A', 'teacher' => 'Hendra Putra, S.Pd', 'students' => 26, 'status' => 'Sudah diisi hari ini', 'badge' => 'green'],
                        ['name' => 'Kelas IV B', 'teacher' => 'Fitriana, S.Pd', 'students' => 32, 'status' => 'Belum diisi hari ini', 'badge' => 'amber'],
                    ];
                @endphp

                <div class="grid gap-4 xl:grid-cols-2">
                    @foreach ($classes as $class)
                        <div class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex flex-col gap-3">
                                <div>
                                    <p class="text-sm uppercase tracking-[0.3em] text-slate-400">{{ $class['name'] }}</p>
                                    <p class="text-lg font-semibold text-slate-900">{{ $class['teacher'] }}</p>
                                    <p class="text-sm text-slate-500">Siswa: {{ $class['students'] }} terdaftar</p>
                                </div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <a href="{{ route('guru.absensi.detail') }}" class="rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-900 transition hover:border-slate-300">Detail</a>
                                    <a href="{{ route('guru.absensi.kelola') }}" class="rounded-[16px] bg-[#F59E0B] px-4 py-2 text-sm font-semibold text-slate-950 transition hover:bg-[#d97706]">Kelola</a>
                                </div>
                                <div class="mt-4 inline-flex items-center gap-2 rounded-full px-3 py-2 text-sm font-medium text-slate-700 bg-slate-100">
                                    <span class="h-2.5 w-2.5 rounded-full bg-{{ $class['badge'] === 'green' ? 'emerald-500' : 'amber-500' }}"></span>
                                    {{ $class['status'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 rounded-[32px] border border-slate-200 bg-slate-50 p-6 shadow-sm">
                <div class="grid gap-4 sm:grid-cols-4">
                    <div class="rounded-[24px] border border-slate-200 bg-white p-5 text-center">
                        <p class="text-sm text-slate-500">Total kelas</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900">4</p>
                    </div>
                    <div class="rounded-[24px] border border-slate-200 bg-white p-5 text-center">
                        <p class="text-sm text-slate-500">Sudah absen</p>
                        <p class="mt-3 text-3xl font-semibold text-emerald-600">4</p>
                    </div>
                    <div class="rounded-[24px] border border-slate-200 bg-white p-5 text-center">
                        <p class="text-sm text-slate-500">Belum absen</p>
                        <p class="mt-3 text-3xl font-semibold text-amber-500">3</p>
                    </div>
                    <div class="rounded-[24px] border border-slate-200 bg-[#1E2567] p-5 text-center text-white">
                        <p class="text-sm">Total Siswa</p>
                        <p class="mt-3 text-3xl font-semibold">10</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
