@extends('layouts.index')
@php $role = 'guru'; @endphp
@section('title', 'Dokumentasi Kegiatan')

@section('content')
@include('components.navbar', ['role' => $role])

<div class="mb-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm font-semibold text-slate-500">Dokumentasi</p>
            <h1 class="text-3xl font-bold text-slate-950">Dokumentasi Kegiatan</h1>
            <p class="text-sm text-slate-500">Kelola dokumentasi foto kegiatan sekolah.</p>
        </div>
        <a href="{{ route('guru.dokumentasi.create') }}"
            class="inline-flex items-center gap-2 rounded-[16px] bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Kegiatan
        </a>
    </div>
</div>

@if (session('success'))
    <div class="mb-6 rounded-[16px] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-6 rounded-[16px] border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
        {{ session('error') }}
    </div>
@endif

{{-- Statistik --}}
<div class="mb-8 w-fit rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex items-center gap-4">
        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-7 w-7 text-blue-600">
                <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" clip-rule="evenodd" />
            </svg>
        </div>
        <div>
            <p class="text-sm text-slate-500">Total Kegiatan</p>
            <p class="text-3xl font-bold text-slate-900">{{ $kegiatans->count() }}</p>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="mb-8 rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
    <h2 class="mb-4 text-base font-bold text-slate-900">Filter & Pencarian</h2>
    <form method="GET" action="{{ route('guru.dokumentasi.index') }}">
        <div class="flex gap-3">
            <input type="text" name="search" value="{{ $search ?? '' }}"
                placeholder="Cari judul kegiatan..."
                class="flex-1 rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
            <button type="submit"
                class="inline-flex items-center justify-center rounded-[16px] bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </button>
        </div>
    </form>
</div>

{{-- Tabel --}}
<div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="px-4 py-4 text-left text-sm font-semibold text-slate-700">#</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-slate-700">Judul</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-slate-700">Kelas</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-slate-700">Tanggal</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-slate-700">Foto</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-slate-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kegiatans as $index => $kegiatan)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="px-4 py-4 text-sm text-slate-600">{{ $index + 1 }}</td>
                        <td class="px-4 py-4 text-sm font-semibold text-slate-900">{{ $kegiatan->judul }}</td>
                        <td class="px-4 py-4 text-sm text-slate-600">
                            @if($kegiatan->kelas_id)
                                @if($kegiatan->kelas_id === 'semua_kelas')
                                    <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">Semua Kelas</span>
                                @else
                                    <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">Kelas {{ $kegiatan->kelas_id }}</span>
                                @endif
                            @else
                                <span class="text-xs text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-sm text-slate-600">
                            {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-4">
                            @php $foto = $kegiatan->dokumentasi->first(); @endphp
                            @if ($foto)
                                <img src="{{ asset('storage/' . $foto->foto) }}"
                                    class="h-12 w-20 rounded-xl object-cover" alt="foto">
                            @else
                                <span class="text-xs text-slate-400">Belum ada foto</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                {{-- Detail --}}
                                <a href="{{ route('guru.dokumentasi.show', $kegiatan->id_kegiatan) }}"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-blue-600 transition hover:bg-blue-200"
                                    title="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                        <path d="M11.625 16.5a1.875 1.875 0 1 0 0-3.75 1.875 1.875 0 0 0 0 3.75Z" />
                                        <path fill-rule="evenodd" d="M5.625 1.5H9a3.75 3.75 0 0 1 3.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 0 1 3.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 0 1-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875Zm6 16.5c.66 0 1.277-.19 1.797-.518l1.048 1.048a.75.75 0 0 0 1.06-1.06l-1.047-1.048A3.375 3.375 0 1 0 11.625 18Z" clip-rule="evenodd" />
                                        <path d="M14.25 5.25a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963A5.23 5.23 0 0 0 16.5 7.5h-1.875a.375.375 0 0 1-.375-.375V5.25Z" />
                                    </svg>
                                </a>
                                {{-- Edit --}}
                                <a href="{{ route('guru.dokumentasi.edit', $kegiatan->id_kegiatan) }}"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-600 transition hover:bg-amber-200"
                                    title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                    </svg>
                                </a>
                                {{-- Hapus --}}
                                <button type="button"
                                    onclick="confirmDelete('{{ route('guru.dokumentasi.destroy', $kegiatan->id_kegiatan) }}', '{{ $kegiatan->judul }}')"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-red-100 text-red-600 transition hover:bg-red-200"
                                    title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-sm text-slate-400">
                            Belum ada kegiatan. Klik "Tambah Kegiatan" untuk mulai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6 text-sm text-slate-500">
        Total {{ $kegiatans->count() }} Kegiatan
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="relative w-full max-w-sm rounded-[28px] bg-white p-8 shadow-xl">
        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-8 w-8 text-red-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
            </svg>
        </div>
        <h2 class="mb-2 text-center text-lg font-bold text-slate-900">Hapus Kegiatan</h2>
        <p class="mb-1 text-center text-sm text-slate-500">Apakah kamu yakin ingin menghapus</p>
        <p id="deleteNama" class="mb-6 text-center text-sm font-semibold text-slate-800"></p>
        <p class="mb-6 text-center text-xs text-slate-400">Semua foto dokumentasi juga akan ikut terhapus.</p>
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
    function confirmDelete(actionUrl, nama) {
        document.getElementById('deleteForm').action = actionUrl;
        document.getElementById('deleteNama').textContent = nama;
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDeleteModal(); });
</script>

@endsection