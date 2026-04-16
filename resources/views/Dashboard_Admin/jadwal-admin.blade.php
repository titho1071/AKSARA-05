@extends('Dashboard_Admin.app')

@section('title', 'Jadwal Pelajaran')

@section('content')
    <!-- Header Section -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Jadwal Pelajaran</h1>
            <p class="text-gray-500">Koleksi semua jadwal pelajaran</p>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-colors">
            <span>+</span> Tambah Jadwal Pelajaran
        </button>
    </div>

    <!-- Statistics Card -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8 w-fit">
        <div class="flex items-center gap-4">
            <div class="bg-blue-100 rounded-2xl p-4">
                <span class="text-3xl">📚</span>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Jadwal Pelajaran</p>
                <p class="text-3xl font-bold text-gray-900">3</p>
            </div>
        </div>
    </div>

    <!-- Jadwal Pelajaran Cards -->
    <div class="space-y-6">
        <!-- Jadwal Card 1 -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Jadwal Pelajaran</h3>
                <span class="text-gray-400 text-sm">Aksi</span>
            </div>
            <div class="space-y-3 mb-6">
                <p class="text-gray-700">
                    <span class="font-medium">Kelas:</span> III A
                </p>
                <p class="text-gray-700">
                    <span class="font-medium">Wali kelas:</span> Siti Rahayu, S.Pd I
                </p>
                <p class="text-gray-700">
                    <span class="font-medium">Tahun pelajaran:</span> 2024/2025 - Semester 2
                </p>
            </div>
            <div class="flex justify-end gap-3">
                <button class="text-yellow-500 hover:text-yellow-700 transition-colors" title="Edit">
                    <span class="text-2xl">✏️</span>
                </button>
                <button class="text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                    <span class="text-2xl">🗑️</span>
                </button>
            </div>
        </div>

        <!-- Jadwal Card 2 -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Jadwal Pelajaran</h3>
                <span class="text-gray-400 text-sm">Aksi</span>
            </div>
            <div class="space-y-3 mb-6">
                <p class="text-gray-700">
                    <span class="font-medium">Kelas:</span> III A
                </p>
                <p class="text-gray-700">
                    <span class="font-medium">Wali kelas:</span> Siti Rahayu, S.Pd I
                </p>
                <p class="text-gray-700">
                    <span class="font-medium">Tahun pelajaran:</span> 2024/2025 - Semester 2
                </p>
            </div>
            <div class="flex justify-end gap-3">
                <button class="text-yellow-500 hover:text-yellow-700 transition-colors" title="Edit">
                    <span class="text-2xl">✏️</span>
                </button>
                <button class="text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                    <span class="text-2xl">🗑️</span>
                </button>
            </div>
        </div>

        <!-- Jadwal Card 3 -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Jadwal Pelajaran</h3>
                <span class="text-gray-400 text-sm">Aksi</span>
            </div>
            <div class="space-y-3 mb-6">
                <p class="text-gray-700">
                    <span class="font-medium">Kelas:</span> III A
                </p>
                <p class="text-gray-700">
                    <span class="font-medium">Wali kelas:</span> Siti Rahayu, S.Pd I
                </p>
                <p class="text-gray-700">
                    <span class="font-medium">Tahun pelajaran:</span> 2024/2025 - Semester 2
                </p>
            </div>
            <div class="flex justify-end gap-3">
                <button class="text-yellow-500 hover:text-yellow-700 transition-colors" title="Edit">
                    <span class="text-2xl">✏️</span>
                </button>
                <button class="text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                    <span class="text-2xl">🗑️</span>
                </button>
            </div>
        </div>
    </div>
@endsection
