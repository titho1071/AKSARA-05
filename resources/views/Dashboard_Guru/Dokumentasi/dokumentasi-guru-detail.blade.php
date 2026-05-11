@extends('layouts.index')
@php $role = 'guru'; @endphp
@section('title', 'Detail Dokumentasi Kegiatan')

@section('content')
@include('components.navbar', ['role' => $role])

<div class="mb-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm font-semibold text-slate-500">Dokumentasi</p>
            <h1 class="text-3xl font-bold text-slate-950">Detail Dokumentasi</h1>
            <p class="text-sm text-slate-500">Informasi lengkap kegiatan.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('guru.dokumentasi.edit', $kegiatan->id_kegiatan) }}"
                class="inline-flex items-center gap-2 rounded-[16px] bg-amber-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-amber-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                </svg>
                Edit
            </a>
            <button type="button"
                onclick="confirmDelete('{{ route('guru.dokumentasi.destroy', $kegiatan->id_kegiatan) }}', '{{ $kegiatan->judul }}')"
                class="inline-flex items-center gap-2 rounded-[16px] bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
                Hapus
            </button>
            <a href="{{ route('guru.dokumentasi.index') }}"
                class="inline-flex items-center gap-2 rounded-[16px] border border-slate-200 bg-slate-50 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    {{-- Kolom kiri: info kegiatan --}}
    <div class="space-y-6 lg:col-span-2">
        {{-- Info utama --}}
        <div class="rounded-[32px] border border-slate-200 bg-white p-8 shadow-sm">
            <h2 class="mb-1 text-2xl font-bold text-slate-900">{{ $kegiatan->judul }}</h2>

            <div class="mb-6 flex flex-wrap gap-2">
                @if($kegiatan->kelas_id)
                    @if($kegiatan->kelas_id === 'semua_kelas')
                        <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">
                            Semua Kelas
                        </span>
                    @else
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                            Kelas {{ $kegiatan->kelas_id }}
                        </span>
                    @endif
                @endif
                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                    {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}
                </span>
                <span class="rounded-full px-3 py-1 text-xs font-semibold
                    {{ $kegiatan->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                    {{ ucfirst($kegiatan->status) }}
                </span>
            </div>

            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-slate-400">Deskripsi</p>
                <p class="text-sm leading-relaxed text-slate-600">{{ $kegiatan->deskripsi ?? '-' }}</p>
            </div>
        </div>

        {{-- Foto dokumentasi --}}
        <div class="rounded-[32px] border border-slate-200 bg-white p-8 shadow-sm">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-900">Dokumentasi Kegiatan</h3>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                    {{ $kegiatan->dokumentasi->count() }} foto
                </span>
            </div>

            @if ($kegiatan->dokumentasi->count() > 0)
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                    @foreach ($kegiatan->dokumentasi as $foto)
                        <a href="{{ asset('storage/' . $foto->foto) }}" target="_blank"
                            class="group relative overflow-hidden rounded-[20px]">
                            <img src="{{ asset('storage/' . $foto->foto) }}"
                                class="h-40 w-full object-cover transition duration-300 group-hover:scale-105" />
                            <div class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 transition group-hover:opacity-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-8 w-8 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mb-3 h-12 w-12">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <p class="text-sm">Belum ada foto dokumentasi.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Kolom kanan: ringkasan --}}
    <div class="space-y-6">
        <div class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-5 text-sm font-bold uppercase tracking-widest text-slate-400">Ringkasan</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Total foto</span>
                    <span class="text-sm font-semibold text-slate-900">{{ $kegiatan->dokumentasi->count() }} foto</span>
                </div>
                @if($kegiatan->kelas_id)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">Kelas</span>
                        <span class="text-sm font-semibold text-slate-900">
                            @if($kegiatan->kelas_id === 'semua_kelas')
                                Semua Kelas
                            @else
                                Kelas {{ $kegiatan->kelas_id }}
                            @endif
                        </span>
                    </div>
                @endif
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Tanggal kegiatan</span>
                    <span class="text-sm font-semibold text-slate-900">
                        {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d M Y') }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Status</span>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold
                        {{ $kegiatan->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ ucfirst($kegiatan->status) }}
                    </span>
                </div>
            </div>
        </div>
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
        <p class="mb-6 text-center text-xs text-slate-400">Semua foto kegiatan juga akan ikut terhapus.</p>
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