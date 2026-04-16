@extends('Dashboard_Admin.app')

@section('title', 'Dashboard Admin')

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Dashboard</h1>
        <p class="text-gray-500">Koleksi semua aktivitas</p>
    </div>

    <!-- Statistics Cards Row 1 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Data Admin Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-blue-100 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-2">Data Admin</p>
                    <p class="text-4xl font-bold text-gray-900">1</p>
                </div>
                <div class="bg-blue-100 rounded-2xl p-3">
                    <span class="text-2xl">👤</span>
                </div>
            </div>
            <a href="#" class="text-blue-500 text-sm font-medium mt-4 inline-block hover:underline">Lihat Detail</a>
        </div>

        <!-- Data Guru Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-green-100 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-2">Data Guru</p>
                    <p class="text-4xl font-bold text-gray-900">1</p>
                </div>
                <div class="bg-green-100 rounded-2xl p-3">
                    <span class="text-2xl">👨‍🏫</span>
                </div>
            </div>
            <a href="#" class="text-green-500 text-sm font-medium mt-4 inline-block hover:underline">Lihat Detail</a>
        </div>

        <!-- Data Siswa Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-blue-100 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-2">Data Siswa</p>
                    <p class="text-4xl font-bold text-gray-900">1</p>
                </div>
                <div class="bg-blue-100 rounded-2xl p-3">
                    <span class="text-2xl">👨‍🎓</span>
                </div>
            </div>
            <a href="#" class="text-blue-500 text-sm font-medium mt-4 inline-block hover:underline">Lihat Detail</a>
        </div>

        <!-- Data Orang tua Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-yellow-100 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-2">Data Orang tua</p>
                    <p class="text-4xl font-bold text-gray-900">1</p>
                </div>
                <div class="bg-yellow-100 rounded-2xl p-3">
                    <span class="text-2xl">👨‍👩‍👧</span>
                </div>
            </div>
            <a href="#" class="text-yellow-600 text-sm font-medium mt-4 inline-block hover:underline">Lihat Detail</a>
        </div>
    </div>

    <!-- Statistics Cards Row 2 -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Data Kelas Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-yellow-100 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-2">Data Kelas</p>
                    <p class="text-4xl font-bold text-gray-900">1</p>
                </div>
                <div class="bg-yellow-100 rounded-2xl p-3">
                    <span class="text-2xl">🏫</span>
                </div>
            </div>
            <a href="#" class="text-yellow-600 text-sm font-medium mt-4 inline-block hover:underline">Lihat Detail</a>
        </div>

        <!-- Total Jadwal Pelajaran Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-green-100 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-2">Total Jadwal Pelajaran</p>
                    <p class="text-4xl font-bold text-gray-900">1</p>
                </div>
                <div class="bg-green-100 rounded-2xl p-3">
                    <span class="text-2xl">📚</span>
                </div>
            </div>
            <a href="#" class="text-green-500 text-sm font-medium mt-4 inline-block hover:underline">Lihat Detail</a>
        </div>
    </div>

    <!-- Charts and Content Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Grafik Absensi -->
        <div class="lg:col-span-1 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Grafik Absensi</h2>
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-2 text-gray-700">
                    <option>Kelas III A</option>
                    <option>Kelas III B</option>
                </select>
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
                <div class="border-l-4 border-blue-500 pl-4 py-3">
                    <p class="text-gray-700 font-medium">Pengumuman Libur Lebaran</p>
                    <p class="text-gray-500 text-sm mt-1">Informasi terkait jadwal libur dan kembali sekolah</p>
                </div>
                <div class="border-l-4 border-green-500 pl-4 py-3">
                    <p class="text-gray-700 font-medium">Pengumuman Libur Lebaran</p>
                    <p class="text-gray-500 text-sm mt-1">Informasi terkait jadwal libur dan kembali sekolah</p>
                </div>
            </div>
            <a href="#" class="text-blue-500 text-sm font-medium mt-6 inline-block hover:underline">Lihat Detail</a>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Dokumentasi -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Dokumentasi</h2>
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
            <h2 class="text-xl font-bold text-gray-900 mb-6">Jadwal Pelajaran</h2>
            <div class="space-y-4">
                <div class="pb-4 border-b border-gray-200">
                    <p class="font-semibold text-gray-900 mb-2">Jadwal Pelajaran</p>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><span class="font-medium">Kelas:</span> III A</p>
                        <p><span class="font-medium">Wali Kelas:</span> Siti Rahayu, S.Pd I</p>
                        <p><span class="font-medium">Tahun pelajaran:</span> 2024/2025 - Semester 2</p>
                    </div>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 mb-2">Jadwal Pelajaran</p>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><span class="font-medium">Kelas:</span> III A</p>
                        <p><span class="font-medium">Wali Kelas:</span> Siti Rahayu, S.Pd I</p>
                        <p><span class="font-medium">Tahun pelajaran:</span> 2024/2025 - Semester 2</p>
                    </div>
                </div>
            </div>
            <a href="#" class="text-blue-500 text-sm font-medium mt-4 inline-block hover:underline">Lihat Detail</a>
        </div>
    </div>

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
@endsection
