@extends('Dashboard_Admin.app')

@section('title', 'Pengumuman')

@section('content')
    <!-- Header Section -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Pengumuman</h1>
            <p class="text-gray-500">Koleksi semua pengumuman</p>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-colors">
            <span>+</span> Tambah Pengumuman
        </button>
    </div>

    <!-- Statistics Card -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8 w-fit">
        <div class="flex items-center gap-4">
            <div class="bg-blue-100 rounded-2xl p-4">
                <span class="text-3xl">📋</span>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Pengumuman</p>
                <p class="text-3xl font-bold text-gray-900">1</p>
            </div>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Filter & Pencarian</h2>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
            <div class="flex gap-2">
                <input 
                    type="text" 
                    placeholder="Cari judul atau deskripsi..." 
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                >
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                    <span>🔍</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Pengumuman Table -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left px-4 py-4 font-semibold text-gray-700 text-sm">Judul</th>
                        <th class="text-left px-4 py-4 font-semibold text-gray-700 text-sm">Tag</th>
                        <th class="text-left px-4 py-4 font-semibold text-gray-700 text-sm">Tanggal</th>
                        <th class="text-left px-4 py-4 font-semibold text-gray-700 text-sm">Pengumuman Dari</th>
                        <th class="text-left px-4 py-4 font-semibold text-gray-700 text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-4 text-gray-900 font-medium">Pengumuman Libur Sekolah</td>
                        <td class="px-4 py-4">
                            <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">Semua Kelas</span>
                        </td>
                        <td class="px-4 py-4 text-gray-600">04/04/2026</td>
                        <td class="px-4 py-4 text-gray-600">Admin</td>
                        <td class="px-4 py-4">
                            <div class="flex gap-3">
                                <button class="text-yellow-500 hover:text-yellow-700 transition-colors" title="Edit">
                                    <span class="text-lg">✏️</span>
                                </button>
                                <button class="text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                                    <span class="text-lg">🗑️</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Total Info -->
        <div class="mt-6 text-sm text-gray-600">
            <p>Total 1 Pengumuman</p>
        </div>
    </div>
@endsection
