@extends('layouts.index')

@section('title', 'Detail Pengumuman')

@section('content')
@include('components.navbar')

{{-- Header Section --}}
<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Pengumuman</h1>
            <p class="text-gray-600 mt-1">semua pengumuman</p>
        </div>
        <a href="{{ route('admin.pengumuman') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-colors justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Kembali
        </a>
    </div>
</div>

{{-- Detail Card --}}
<div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200 max-w-4xl">

    {{-- Judul --}}
    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $pengumuman->judul }}</h2>

    {{-- Meta Info --}}
    <div class="flex flex-col gap-1 mb-6">
        @if ($pengumuman->tanggal_mulai)
            <p class="text-gray-700 text-sm">
                <span class="font-medium">Tanggal Mulai:</span>
                {{ optional($pengumuman->tanggal_mulai)->format('d/m/Y') }}
            </p>
        @endif
        @if ($pengumuman->tanggal_selesai)
            <p class="text-gray-700 text-sm">
                <span class="font-medium">Tanggal Selesai:</span>
                {{ optional($pengumuman->tanggal_selesai)->format('d/m/Y') }}
            </p>
        @endif
        @if ($pengumuman->kelas)
            <p class="text-gray-700 text-sm">
                <span class="font-medium">Kelas:</span>
                {{ $pengumuman->kelas->nama_kelas }}
            </p>
        @else
            <p class="text-gray-700 text-sm">
                <span class="font-medium">Kelas:</span> Semua Kelas
            </p>
        @endif
    </div>

    {{-- Divider --}}
    <hr class="border-gray-100 mb-6">

    {{-- Deskripsi --}}
    <div class="text-gray-800 leading-relaxed whitespace-pre-line mb-8">
        {{ $pengumuman->deskripsi }}
    </div>

    {{-- Lampiran File --}}
    @if ($pengumuman->file)
        @php
            $fileUrl  = asset('storage/' . $pengumuman->file);
            $fileName = basename($pengumuman->file);
            $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $isImage  = in_array($ext, ['jpg', 'jpeg', 'png', 'svg']);
            $isPdf    = $ext === 'pdf';
        @endphp

        <div class="border border-gray-200 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Lampiran</p>

            @if ($isImage)
                <div class="mb-3">
                    <img src="{{ $fileUrl }}" alt="{{ $fileName }}"
                         class="max-h-64 rounded-lg border border-gray-100 object-contain">
                </div>
            @endif

            <a href="{{ $fileUrl }}"
               target="_blank"
               rel="noopener noreferrer"
               class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors group">
                @if ($isPdf)
                    {{-- PDF icon --}}
                    <span class="flex items-center justify-center w-8 h-8 bg-red-100 rounded-lg group-hover:bg-red-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600" viewBox="0 0 24 24"
                             fill="currentColor">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                            <polyline points="14 2 14 8 20 8" fill="none" stroke="white" stroke-width="1.5"
                                      stroke-linecap="round"/>
                            <text x="6" y="18" font-size="5" fill="white" font-family="sans-serif"
                                  font-weight="bold">PDF</text>
                        </svg>
                    </span>
                @elseif ($isImage)
                    {{-- Image icon --}}
                    <span class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </span>
                @else
                    {{-- Generic file icon --}}
                    <span class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-lg group-hover:bg-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                    </span>
                @endif
                {{ $fileName }}
            </a>
        </div>
    @endif

    {{-- Action Buttons --}}
    <div class="mt-8 flex flex-wrap gap-3 pt-6 border-t border-gray-100">
        <a href="{{ route('admin.pengumuman.edit', $pengumuman->id_pengumuman) }}"
           class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2 transition-colors text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 20h9"/>
                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
            </svg>
            Edit Pengumuman
        </a>
        <button id="btn-hapus"
                type="button"
                data-id="{{ $pengumuman->id_pengumuman }}"
                class="bg-red-500 hover:bg-red-600 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2 transition-colors text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m5 0V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/>
                <line x1="10" y1="11" x2="10" y2="17"/>
                <line x1="14" y1="11" x2="14" y2="17"/>
            </svg>
            Hapus Pengumuman
        </button>
    </div>
</div>

<script>
    document.getElementById('btn-hapus')?.addEventListener('click', async function () {
        const id = this.dataset.id;
        if (!confirm('Yakin ingin menghapus pengumuman ini?')) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        try {
            const response = await fetch(`/api/pengumuman/${id}`, {
                method: 'DELETE',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                alert(result.message || 'Gagal menghapus pengumuman.');
                return;
            }

            window.location.href = '{{ route('admin.pengumuman') }}';
        } catch (err) {
            alert('Terjadi kesalahan saat menghapus pengumuman.');
        }
    });
</script>

@endsection
