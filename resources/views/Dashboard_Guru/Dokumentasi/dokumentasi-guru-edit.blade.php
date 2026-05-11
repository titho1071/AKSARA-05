@extends('layouts.index')
@php $role = 'guru'; @endphp
@section('title', 'Edit Kegiatan')

@section('content')
@include('components.navbar', ['role' => $role])

<div class="mb-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm font-semibold text-slate-500">Dokumentasi</p>
            <h1 class="text-3xl font-bold text-slate-950">Edit Dokumentasi Kegiatan</h1>
            <p class="text-sm text-slate-500">Perbarui data kegiatan dan foto dokumentasinya.</p>
        </div>
        <a href="{{ route('guru.dokumentasi.index') }}"
            class="inline-flex items-center gap-2 rounded-[16px] border border-slate-200 bg-slate-50 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali
        </a>
    </div>
</div>

@if ($errors->any())
    <div class="mb-6 rounded-[24px] border border-red-200 bg-red-50 p-5 text-sm text-red-700">
        <ul class="space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="rounded-[32px] border border-slate-200 bg-white p-8 shadow-sm">
    <form action="{{ route('guru.dokumentasi.update', $kegiatan->id_kegiatan) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Judul --}}
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">
                Judul Kegiatan <span class="text-red-500">*</span>
            </label>
            <input type="text" name="judul" value="{{ old('judul', $kegiatan->judul) }}"
                placeholder="Tambahkan judul kegiatan..."
                class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                required />
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">
                Deskripsi Kegiatan <span class="text-red-500">*</span>
            </label>
            <textarea name="deskripsi" rows="4"
                placeholder="Jelaskan detail kegiatan..."
                class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                required>{{ old('deskripsi', $kegiatan->deskripsi) }}</textarea>
        </div>

        {{-- Tanggal & Kelas --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Tanggal Kegiatan <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal" value="{{ old('tanggal', $kegiatan->tanggal) }}"
                    class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    required />
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Kelas <span class="text-slate-400 font-normal text-xs">(opsional)</span>
                </label>
                <select name="kelas_id"
                    class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">-- Pilih Kelas --</option>
                    <option value="semua_kelas" {{ old('kelas_id', $kegiatan->kelas_id) === 'semua_kelas' ? 'selected' : '' }}>Semua Kelas</option>
                    @foreach(['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'] as $kelas)
                        <option value="{{ $kelas }}" {{ old('kelas_id', $kegiatan->kelas_id) === $kelas ? 'selected' : '' }}>Kelas {{ $kelas }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Foto yang sudah ada --}}
        @if ($kegiatan->dokumentasi->count() > 0)
            <div>
                <label class="mb-3 block text-sm font-semibold text-slate-700">Foto Saat Ini</label>
                <div class="grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-6">
                    @foreach ($kegiatan->dokumentasi as $dok)
                        <div class="group relative" id="foto-{{ $dok->id_dokumentasi }}">
                            <img src="{{ asset('storage/' . $dok->foto) }}"
                                class="h-24 w-full rounded-[14px] object-cover" />
                            <div class="absolute inset-0 flex items-center justify-center rounded-[14px] bg-black/40 opacity-0 transition group-hover:opacity-100">
                                <button type="button"
                                    onclick="hapusFoto({{ $dok->id_dokumentasi }})"
                                    class="rounded-full bg-red-600 p-1.5 text-white hover:bg-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="mt-2 text-xs text-slate-400">Hover foto lalu klik X untuk menghapus foto tertentu.</p>
            </div>
        @endif

        {{-- Upload foto baru --}}
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">
                Tambah Foto Baru
                <span class="font-normal text-slate-400">(opsional)</span>
            </label>
            <div id="dropZone"
                class="relative flex min-h-[160px] cursor-pointer flex-col items-center justify-center rounded-[20px] border-2 border-dashed border-slate-300 bg-slate-50 p-6 transition hover:border-blue-400 hover:bg-blue-50">
                <input type="file" id="fotoInput" name="foto[]" multiple
                    accept="image/jpg,image/jpeg,image/png,image/webp"
                    class="absolute inset-0 cursor-pointer opacity-0" />
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mb-3 h-8 w-8 text-slate-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                <p id="dropText" class="text-sm font-semibold text-slate-600">Klik atau drag & drop foto baru</p>
                <p class="mt-1 text-xs text-slate-400">JPG, JPEG, PNG, WEBP • Maks 3MB per file</p>
            </div>
            <div id="previewContainer" class="mt-4 hidden">
                <p class="mb-3 text-sm font-semibold text-slate-700">Preview Foto Baru:</p>
                <div id="previewGrid" class="grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-6"></div>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('guru.dokumentasi.index') }}"
                class="inline-flex items-center rounded-[16px] border border-slate-200 bg-slate-50 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-[16px] bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    const input       = document.getElementById('fotoInput');
    const previewCont = document.getElementById('previewContainer');
    const previewGrid = document.getElementById('previewGrid');
    const dropZone    = document.getElementById('dropZone');

    input.addEventListener('change', () => renderPreviews(input.files));

    function renderPreviews(files) {
        previewGrid.innerHTML = '';
        if (!files.length) { previewCont.classList.add('hidden'); return; }
        previewCont.classList.remove('hidden');
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = `<img src="${e.target.result}" class="h-24 w-full rounded-[14px] object-cover" />`;
                previewGrid.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-blue-500'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-blue-500'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-500');
        input.files = e.dataTransfer.files;
        renderPreviews(e.dataTransfer.files);
    });

    function hapusFoto(idDok) {
        if (!confirm('Hapus foto ini?')) return;
        fetch(`/guru/dokumentasi/foto/${idDok}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById(`foto-${idDok}`).remove();
            } else {
                alert('Gagal menghapus foto.');
            }
        })
        .catch(() => alert('Gagal menghapus foto.'));
    }
</script>

@endsection