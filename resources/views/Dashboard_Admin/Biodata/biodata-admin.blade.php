@extends('layouts.index')

@php
    $role = 'admin';
@endphp

@section('title', 'Biodata Admin')

@section('content')
    @include('components.navbar', ['role' => $role])

    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Kelola Biodata Admin</h1>
                <p class="text-gray-600 mt-1">Lihat dan tambahkan data admin sekolah.</p>
            </div>
            <a href="{{ route('admin.biodata.create') }}"
                class="inline-flex items-center gap-2 rounded-[16px] bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                + Tambah Data Admin
            </a>
        </div>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6366f1'
                });
            });
        </script>
    @endif

    <div class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-6 grid gap-4 xl:grid-cols-[1fr_auto]">
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-500">Pencarian</label>
                <form action="{{ route('admin.biodata.index') }}" method="GET">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama admin..."
                        class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-[#1E2567] text-white">
                    <tr>
                        <th class="px-4 py-4 text-left font-semibold">No</th>
                        <th class="px-4 py-4 text-left font-semibold">Nama Admin</th>
                        <th class="px-4 py-4 text-left font-semibold">Jenis Kelamin</th>
                        <th class="px-4 py-4 text-left font-semibold">NIP</th>
                        <th class="px-4 py-4 text-left font-semibold">NUPTK</th>
                        <th class="px-4 py-4 text-left font-semibold">Telepon</th>
                        <th class="px-4 py-4 text-left font-semibold">Status</th>
                        <th class="px-4 py-4 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-slate-50">
                    @forelse ($admins as $index => $admin)
                        <tr class="odd:bg-slate-50 even:bg-white">
                            <td class="px-4 py-4 text-slate-600">{{ $index + 1 }}</td>
                            <td class="px-4 py-4 font-semibold text-slate-900">{{ $admin->nama }}</td>
                            <td class="px-4 py-4 text-slate-700">@include('components.gender-label', ['value' => $admin->gender])</td>
                            <td class="px-4 py-4 text-slate-700">{{ $admin->nip ?? '-' }}</td>
                            <td class="px-4 py-4 text-slate-700">{{ $admin->nuptk ?? '-' }}</td>
                            <td class="px-4 py-4 text-slate-700">{{ $admin->phone ?? '-' }}</td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $admin->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ ucwords(str_replace('_', ' ', $admin->status ?? 'tidak_aktif')) }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.biodata.edit', $admin->id) }}"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-600 transition hover:bg-amber-200"
                                        title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <button type="button"
                                        onclick="confirmDelete('{{ route('admin.biodata.destroy', $admin->id) }}', '{{ $admin->nama }}')"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-red-100 text-red-600 transition hover:bg-red-200"
                                        title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white">
                            <td colspan="8" class="px-4 py-6 text-center text-slate-500">Tidak ada data admin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function confirmDelete(actionUrl, adminName) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menghapus admin ${adminName}. Tindakan ini tidak dapat dibatalkan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form via fetch
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    fetch(actionUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: data.message || 'Data admin berhasil dihapus',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#6366f1'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message || 'Terjadi kesalahan saat menghapus data',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#6366f1'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menghapus data',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#6366f1'
                        });
                    });
                }
            });
        }
    </script>
@endsection