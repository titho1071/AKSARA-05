@extends('layouts.index')

@php
    $role = 'admin';
@endphp

@section('title', 'Biodata Admin')

@section('content')
    @include('components.navbar', ['role' => $role])

    <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm font-semibold text-slate-500">Biodata Admin</p>
            <h1 class="text-3xl font-bold text-slate-950">Kelola Biodata Admin</h1>
            <p class="text-sm text-slate-500">Lihat dan tambahkan data admin sekolah.</p>
        </div>
        <a href="{{ route('admin.biodata.create') }}"
            class="inline-flex items-center gap-2 rounded-[16px] bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
            + Tambah Data Admin
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-[24px] border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700">
            {{ session('success') }}
        </div>
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
                        <th class="px-4 py-4 text-left font-semibold">#</th>
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
                            <td class="px-4 py-4 text-slate-700">{{ $admin->gender ?? '-' }}</td>
                            <td class="px-4 py-4 text-slate-700">{{ $admin->nip ?? '-' }}</td>
                            <td class="px-4 py-4 text-slate-700">{{ $admin->nuptk ?? '-' }}</td>
                            <td class="px-4 py-4 text-slate-700">{{ $admin->phone ?? '-' }}</td>
                            <td class="px-4 py-4">
                                <span
                                    class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Aktif</span>
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
    {{-- Modal Konfirmasi Hapus --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeDeleteModal()"></div>

        {{-- Modal Box --}}
        <div class="relative w-full max-w-sm rounded-[28px] bg-white p-8 shadow-xl">
            {{-- Icon --}}
            <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                    stroke="currentColor" class="h-8 w-8 text-red-600">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
            </div>

            <h2 class="mb-2 text-center text-lg font-bold text-slate-900">Hapus Admin</h2>
            <p class="mb-1 text-center text-sm text-slate-500">Apakah kamu yakin ingin menghapus</p>
            <p id="deleteAdminName" class="mb-6 text-center text-sm font-semibold text-slate-800"></p>
            <p class="mb-6 text-center text-xs text-slate-400">Tindakan ini tidak dapat dibatalkan.</p>

            <div class="flex gap-3">
                <button onclick="closeDeleteModal()"
                    class="flex-1 rounded-[14px] border border-slate-200 bg-slate-50 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full rounded-[14px] bg-red-600 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(actionUrl, adminName) {
            document.getElementById('deleteForm').action = actionUrl;
            document.getElementById('deleteAdminName').textContent = adminName;
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Tutup modal dengan tombol Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeDeleteModal();
        });
    </script>
@endsection