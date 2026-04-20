@extends('layouts.index')

@php
    $role = 'admin';
@endphp

@section('title', 'Data Siswa')

@section('content')
@include('components.navbar')

<div class="px-4 py-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Data Siswa</h1>
            <p class="text-gray-500 text-sm">Kelola semua siswa....</p>
        </div>

        <div class="flex gap-3 mt-4 md:mt-0">
            <a href="{{ route('admin.biodata.create') }}"
                class="flex items-center gap-2 bg-blue-600 text-white px-5 py-3 rounded-xl shadow hover:bg-blue-700">
                <!-- Heroicon Plus -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>
                Tambah Data Siswa
            </a>

            <button
                class="flex items-center gap-2 bg-yellow-400 text-white px-5 py-3 rounded-xl shadow hover:bg-yellow-500">
                <!-- Heroicon Arrow Down -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12v-8m0 8l-3-3m3 3l3-3" />
                </svg>
                Import Data Siswa
            </button>
        </div>
    </div>

    <!-- Card -->
    <div class="bg-white border border-green-200 rounded-2xl p-6">

        <!-- Search -->
        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Pencarian</label>
            <input type="text" placeholder="Cari nama siswa.."
                class="border rounded-xl px-4 py-2 w-72 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm border">
                <thead class="bg-[#2E3192] text-white">
                    <tr>
                        <th class="p-3">#</th>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">Kelas</th>
                        <th class="p-3 text-left">NIS</th>
                        <th class="p-3 text-left">NUPTK</th>
                        <th class="p-3 text-left">Jenis Kelamin</th>
                        <th class="p-3 text-left">Telepon</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-gray-50">
                    @forelse ($admins as $index => $admin)
                        <tr class="border-t">
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3 font-medium">{{ $admin->nama }}</td>
                            <td class="p-3">VII A</td>
                            <td class="p-3">{{ $admin->nip ?? '-' }}</td>
                            <td class="p-3">{{ $admin->nuptk ?? '-' }}</td>
                            <td class="p-3">{{ $admin->gender ?? '-' }}</td>
                            <td class="p-3">{{ $admin->phone ?? '-' }}</td>
                            <td class="p-3">
                                <span class="bg-green-500 text-white px-3 py-1 rounded text-xs">
                                    AKTIF
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="p-3 flex gap-2">

                                <!-- Detail -->
                                <button class="bg-green-500 text-white p-2 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </button>

                                <!-- Edit -->
                                <a href="{{ route('admin.biodata.edit', $admin->id) }}"
                                    class="bg-blue-500 text-white p-2 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5h2m-1-1v2m-6 8l-1 4 4-1 9-9-3-3-9 9z" />
                                    </svg>
                                </a>

                                <!-- Delete -->
                                <button onclick="confirmDelete('{{ route('admin.biodata.destroy', $admin->id) }}','{{ $admin->nama }}')"
                                    class="bg-red-500 text-white p-2 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 7h12M9 7v12m6-12v12M5 7l1-3h12l1 3" />
                                    </svg>
                                </button>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection