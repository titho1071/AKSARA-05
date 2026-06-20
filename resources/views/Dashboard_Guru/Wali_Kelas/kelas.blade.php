@extends('layouts.index')

@php
    $role = 'guru';
@endphp

@section('title', 'Data Siswa Kelas')

@section('content')
@include('components.navbar', ['role' => $role])

<div id="pageContent" class="transition duration-300">
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                    Data Siswa Kelas: {{ $kelas->nama_kelas ?? 'Tidak Ada' }}
                </h1>
                <p class="text-gray-600 mt-1">Kelola siswa di kelas Anda</p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-[24px] border border-red-200 bg-red-50 p-5 text-sm text-red-700">
            <ul class="space-y-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6366f1'
                });
            });
        </script>
    @endif

    @if (!$kelas)
        <div class="rounded-[32px] border border-slate-200 bg-white p-8 text-center shadow-sm">
            <p class="text-slate-600">Anda belum ditugaskan sebagai wali kelas</p>
        </div>
    @else
        <div class="rounded-[32px] border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                @if ($siswas->count() > 0)
                    <table class="w-full">
                        <thead class="bg-[#1E2567] text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold">#</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold">Nama</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold">NIS</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold">NISN</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold">Jenis Kelamin</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold">Alamat</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($siswas as $index => $siswa)
                                <tr class="border-b border-slate-100 transition hover:bg-slate-50">
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $siswa->nama }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $siswa->nis }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $siswa->nisn }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                            {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $siswa->alamat ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex items-center gap-2">
                                            <button
                                                type="button"
                                                onclick="showDetailModal({{ $siswa->id_siswa }})"
                                                title="Lihat Detail"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-blue-600 transition hover:bg-blue-200"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.574-3.007-9.964-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </button>

                                            <a
                                                href="{{ route('guru.siswa.edit', $siswa->id_siswa) }}"
                                                title="Edit"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-600 transition hover:bg-amber-200"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-8 text-center">
                        <p class="text-slate-600">Tidak ada siswa di kelas ini</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/20 p-4">
    <div class="w-full max-w-2xl rounded-[32px] bg-white p-8 shadow-2xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-slate-950">Detail Siswa</h2>
            <button type="button" onclick="closeDetailModal()" class="text-slate-500 transition hover:text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div id="modalContent" class="space-y-4">
            <div class="h-48 animate-pulse rounded-xl bg-slate-100"></div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="button" onclick="closeDetailModal()" class="rounded-[16px] border border-slate-200 bg-slate-50 px-5 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function showDetailModal(siswaId) {
        const modal = document.getElementById('detailModal');
        const content = document.getElementById('modalContent');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');

        content.innerHTML = `<div class="h-48 animate-pulse rounded-xl bg-slate-100"></div>`;

        fetch(`/guru/siswa/${siswaId}`)
            .then(response => response.json())
            .then(data => {
                const jenisKelamin = data.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';

                const orangTuaHtml = data.orang_tua
                    ? `<div class="rounded-[16px] border border-slate-200 bg-slate-50 p-4">
                            <div class="mb-3">
                                <p class="text-sm font-semibold text-slate-900">${data.orang_tua.nama}</p>
                                <p class="text-xs text-slate-500">NIK: ${data.orang_tua.nik ?? '-'}</p>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <p class="text-xs font-semibold text-slate-500">No HP</p>
                                    <p class="text-sm text-slate-900">${data.orang_tua.no_hp ?? '-'}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-500">Alamat</p>
                                    <p class="text-sm text-slate-900">${data.orang_tua.alamat ?? '-'}</p>
                                </div>
                            </div>
                        </div>`
                    : `<div class="rounded-[16px] border border-dashed border-slate-300 p-4 text-sm text-slate-500">Tidak ada data orang tua / wali</div>`;

                content.innerHTML = `
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-xs font-semibold text-slate-500">Nama Lengkap</p>
                            <p class="text-sm text-slate-900">${data.nama}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-500">Kelas</p>
                            <p class="text-sm text-slate-900">${data.kelas?.nama_kelas || '-'}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-500">NIS</p>
                            <p class="text-sm text-slate-900">${data.nis}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-500">NISN</p>
                            <p class="text-sm text-slate-900">${data.nisn}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-500">Jenis Kelamin</p>
                            <p class="text-sm text-slate-900">${jenisKelamin}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-500">Tanggal Lahir</p>
                            <p class="text-sm text-slate-900">${data.tanggal_lahir || '-'}</p>
                        </div>
                    </div>
                    <div class="border-t border-slate-200 pt-4">
                        <p class="text-xs font-semibold text-slate-500">Alamat</p>
                        <p class="text-sm text-slate-900">${data.alamat || '-'}</p>
                    </div>
                    <div class="border-t border-slate-200 pt-4">
                        <p class="mb-3 text-xs font-semibold text-slate-500">Orang Tua / Wali</p>
                        ${orangTuaHtml}
                    </div>
                `;
            })
            .catch(error => {
                content.innerHTML = `<div class="rounded-[16px] bg-red-50 p-4 text-sm text-red-600">Error memuat data siswa</div>`;
                console.error(error);
            });
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    document.getElementById('detailModal').addEventListener('click', function (e) {
        if (e.target === this) closeDetailModal();
    });
</script>
@endsection