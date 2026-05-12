@extends('layouts.index')

@php
    $role = 'guru';
@endphp

@section('title', 'Dashboard Guru')

@section('content')
@include('components.navbar', ['role' => $role])
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Dashboard Guru</h1>
                <p class="text-gray-600 mt-1">Koleksi semua aktivitas dan data sekolah</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Row 1 -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 px-4">

    <!-- Absensi Siswa -->
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-300 hover:shadow-md transition-shadow">
    <div class="flex items-center gap-4">
        <div class="bg-blue-100 p-4 rounded-xl">
            <!-- clipboard-check -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 24 24" class="w-6 h-6 text-blue-600">
                <path fill-rule="evenodd"
                    d="M9 2.25A.75.75 0 0 1 9.75 3v.75h4.5V3a.75.75 0 0 1 1.5 0v.75H18A2.25 2.25 0 0 1 20.25 6v13.5A2.25 2.25 0 0 1 18 21.75H6A2.25 2.25 0 0 1 3.75 19.5V6A2.25 2.25 0 0 1 6 3.75h2.25V3A.75.75 0 0 1 9 2.25Zm5.03 7.22a.75.75 0 1 0-1.06-1.06l-2.97 2.97-1.03-1.03a.75.75 0 0 0-1.06 1.06l1.56 1.56a.75.75 0 0 0 1.06 0l3.5-3.5Z"
                    clip-rule="evenodd"/>
            </svg>
        </div>

        <div>
            <p class="text-gray-500 text-sm">Absensi Siswa</p>
        </div>
    </div>

    <a href="#" class="text-blue-500 text-sm mt-4 inline-block hover:underline">
        Lihat Detail
    </a>
</div>

<!-- Data Kelas -->
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-300 hover:shadow-md transition-shadow">
    <div class="flex items-center gap-4">
        <div class="bg-yellow-100 p-4 rounded-xl">
            <!-- building-library -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 24 24" class="w-6 h-6 text-yellow-600">
                <path d="M3 21h18v-2.25H3V21Zm2.25-3.75h13.5V6.94l-6.75-3.375L5.25 6.94v10.31Zm3-7.5h1.5v1.5h-1.5v-1.5Zm0 3h1.5v1.5h-1.5v-1.5Zm4.5-3h1.5v1.5h-1.5v-1.5Zm0 3h1.5v1.5h-1.5v-1.5Z"/>
            </svg>
        </div>

        <div>
            <p class="text-gray-500 text-sm">Kelas</p>
        </div>
    </div>

    <a href="#" class="text-yellow-600 text-sm mt-4 inline-block hover:underline">
        Lihat Detail
    </a>
</div>

<!-- Data Siswa -->
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-300 hover:shadow-md transition-shadow">
    <div class="flex items-center gap-4">
        <div class="bg-indigo-100 p-4 rounded-xl">
            <!-- user-group -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 24 24" class="w-6 h-6 text-indigo-600">
                <path d="M16 11c1.657 0 3-1.567 3-3.5S17.657 4 16 4s-3 1.567-3 3.5S14.343 11 16 11Z"/>
                <path d="M8 11c1.657 0 3-1.567 3-3.5S9.657 4 8 4 5 5.567 5 7.5 6.343 11 8 11Z"/>
                <path d="M8 13c-2.761 0-5 1.79-5 4v1h10v-1c0-2.21-2.239-4-5-4Z"/>
                <path d="M16 13c-.508 0-.993.065-1.45.185A6.978 6.978 0 0 1 18 17v1h4v-1c0-2.21-2.239-4-5-4Z"/>
            </svg>
        </div>

        <div>
            <p class="text-gray-500 text-sm">Siswa</p>
        </div>
    </div>

    <a href="#" class="text-indigo-600 text-sm mt-4 inline-block hover:underline">
        Lihat Detail
    </a>
</div>

<!-- Jadwal Mengajar -->
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-300 hover:shadow-md transition-shadow">
    <div class="flex items-center gap-4">
        <div class="bg-green-100 p-4 rounded-xl">
            <!-- calendar-days -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 24 24" class="w-6 h-6 text-green-600">
                <path d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3a.75.75 0 0 1 1.5 0v1.5H19.5A2.25 2.25 0 0 1 21.75 6.75v12A2.25 2.25 0 0 1 19.5 21h-15A2.25 2.25 0 0 1 2.25 18.75v-12A2.25 2.25 0 0 1 4.5 4.5H6V3a.75.75 0 0 1 .75-.75Z"/>
                <path d="M3.75 9h16.5v9.75a.75.75 0 0 1-.75.75h-15a.75.75 0 0 1-.75-.75V9Z"/>
            </svg>
        </div>

        <div>
            <p class="text-gray-500 text-sm">Jadwal Mengajar</p>
        </div>
    </div>

    <a href="#" class="text-green-600 text-sm mt-4 inline-block hover:underline">
        Lihat Detail
    </a>
</div>

</div>

    <!-- Charts and Content Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 px-4">
        <!-- Grafik Absensi -->
        <div class="lg:col-span-1 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Grafik Absensi</h2>
            </div>
            <div class="relative h-64">
                <canvas id="absenceChart"></canvas>
            </div>
            <a href="#" class="text-blue-500 text-sm font-medium mt-4 inline-block hover:underline">Lihat Detail</a>
        </div>

        <!-- Pengumuman -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Pengumuman</h2>
            <div class="space-y-4">
                @forelse($pengumuman as $item)
                    <div class="border-l-4 {{ $item->kelas_id ? 'border-blue-500' : 'border-green-500' }} pl-4 py-3">
                        <p class="text-gray-700 font-medium">{{ $item->judul }}</p>
                        <p class="text-gray-500 text-sm mt-1">{{ Str::limit($item->deskripsi, 100) }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs text-gray-400">{{ $item->created_at->diffForHumans() }}</span>
                            @if($item->kelas)
                                <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">{{ $item->kelas->nama_kelas }}</span>
                            @else
                                <span class="text-xs bg-green-50 text-green-600 px-2 py-0.5 rounded-full">Semua Kelas</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500">Tidak ada pengumuman terbaru.</p>
                    </div>
                @endforelse
            </div>
            <a href="{{ route('guru.pengumuman') }}" class="text-blue-500 text-sm font-medium mt-6 inline-block hover:underline">Lihat Semua Pengumuman</a>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-4 mb-8">
        <!-- Dokumentasi -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Dokumentasi Terbaru</h2>
            <div class="mb-6">
                <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=500&h=300&fit=crop" alt="Upacara Bendera" class="w-full rounded-xl object-cover h-40">
            </div>
            <div>
                <p class="font-semibold text-gray-900 mb-2">Upacara Bendera</p>
                <p class="text-gray-600 text-sm leading-relaxed">Upacara bendera hari Senin merupakan kegiatan rutin yang dilaksanakan setiap awal pekan sebagai bentuk penghormatan nilai kedisiplinan, tanggung jawab, dan rasa cinta tanah air.</p>
            </div>
            <a href="#" class="text-blue-500 text-sm font-medium mt-4 inline-block hover:underline">Lihat Detail</a>
        </div>

        <!-- Jadwal Pelajaran -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Jadwal Pelajaran Aktif</h2>
            <div class="space-y-4">
                <div class="pb-4 border-b border-gray-200">
                    <p class="font-semibold text-gray-900 mb-2">Kelas III A</p>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><span class="font-medium">Wali Kelas:</span> Siti Rahayu, S.Pd I</p>
                        <p><span class="font-medium">Tahun pelajaran:</span> 2024/2025 - Semester 2</p>
                        <p><span class="font-medium">Jumlah Siswa:</span> 35 Siswa</p>
                    </div>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 mb-2">Kelas III B</p>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><span class="font-medium">Wali Kelas:</span> Ahmad Suryanto, S.Pd</p>
                        <p><span class="font-medium">Tahun pelajaran:</span> 2024/2025 - Semester 2</p>
                        <p><span class="font-medium">Jumlah Siswa:</span> 33 Siswa</p>
                    </div>
                </div>
            </div>
            <a href="#" class="text-blue-500 text-sm font-medium mt-4 inline-block hover:underline">Lihat Semua Jadwal</a>
        </div>
    </div>
@endsection

<script>
    // Initialize Chart.js for Grafik Absensi
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('absenceChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Sakit', 'Izin', 'Alpha'],
                    datasets: [{
                        data: [8, 12, 2, 11],
                        backgroundColor: [
                            '#3B82F6',
                            '#F97316',
                            '#FCD34D',
                            '#A0AEC0'
                        ],
                        borderColor: '#FFFFFF',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                font: { size: 12 },
                                padding: 15,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }
    });
</script>
