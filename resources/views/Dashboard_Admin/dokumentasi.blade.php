@extends('layouts.index')

@section('title', 'Dokumentasi')

@section('content')
@include('components.navbar')

<!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Dokumentasi</h1>
                <p class="text-gray-600 mt-1">Semua Dokumentasi</p>
            </div>
        </div>
    </div>

<!-- Statistik -->
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8 w-fit">
    <div class="flex items-center gap-4">
        <div class="bg-blue-100 rounded-2xl p-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="blue" class="size-6">
                    <path d="M12 9a3.75 3.75 0 1 0 0 7.5A3.75 3.75 0 0 0 12 9Z" />
                    <path fill-rule="evenodd" d="M9.344 3.071a49.52 49.52 0 0 1 5.312 0c.967.052 1.83.585 2.332 1.39l.821 1.317c.24.383.645.643 1.11.71.386.054.77.113 1.152.177 1.432.239 2.429 1.493 2.429 2.909V18a3 3 0 0 1-3 3h-15a3 3 0 0 1-3-3V9.574c0-1.416.997-2.67 2.429-2.909.382-.064.766-.123 1.151-.178a1.56 1.56 0 0 0 1.11-.71l.822-1.315a2.942 2.942 0 0 1 2.332-1.39ZM6.75 12.75a5.25 5.25 0 1 1 10.5 0 5.25 5.25 0 0 1-10.5 0Zm12-1.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                </svg>

        </div>
        <div>
            <p class="text-gray-500 text-sm">Total Jadwal Pelajaran</p>
            <p class="text-3xl font-bold text-gray-900">1</p>
        </div>
    </div>
</div>

<!-- Filter -->
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
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center justify-center">
                <!-- Search -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left px-4 py-4 text-sm font-semibold text-gray-700">#</th>
                    <th class="text-left px-4 py-4 text-sm font-semibold text-gray-700">Judul</th>
                    <th class="text-left px-4 py-4 text-sm font-semibold text-gray-700">Tag</th>
                    <th class="text-left px-4 py-4 text-sm font-semibold text-gray-700">Tanggal</th>
                    <th class="text-left px-4 py-4 text-sm font-semibold text-gray-700">Foto</th>
                    <th class="text-left px-4 py-4 text-sm font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="px-4 py-4">1</td>
                    <td class="px-4 py-4 font-medium text-gray-900">Upacara Bendera</td>
                    <td class="px-4 py-4">Semua Kelas</td>
                    <td class="px-4 py-4 text-gray-600">06/04/2026</td>
                    <td class="px-4 py-4">
                        <img src="https://images.unsplash.com/photo-1596495578065-6e0763fa1178?w=200"
                             class="w-20 h-12 object-cover rounded">
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex gap-3">
                            <!-- Detail -->
                            <button class="text-blue-500 hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                    <path d="M11.625 16.5a1.875 1.875 0 1 0 0-3.75 1.875 1.875 0 0 0 0 3.75Z" />
                                    <path fill-rule="evenodd" d="M5.625 1.5H9a3.75 3.75 0 0 1 3.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 0 1 3.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 0 1-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875Zm6 16.5c.66 0 1.277-.19 1.797-.518l1.048 1.048a.75.75 0 0 0 1.06-1.06l-1.047-1.048A3.375 3.375 0 1 0 11.625 18Z" clip-rule="evenodd" />
                                    <path d="M14.25 5.25a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963A5.23 5.23 0 0 0 16.5 7.5h-1.875a.375.375 0 0 1-.375-.375V5.25Z" />
                                </svg>

                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="mt-6 text-sm text-gray-600">
        Total 1 Dokumentasi
    </div>
</div>

@endsection