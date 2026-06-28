@extends('layouts.index')

@php
    $role = 'orangtua';
    $ext = $pengumuman->file
        ? strtolower(pathinfo($pengumuman->display_file_name, PATHINFO_EXTENSION))
        : null;
    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    $isPdf   = $ext === 'pdf';
@endphp

@section('title', 'Detail Pengumuman - Orang Tua')

@section('content')
@include('components.navbar', ['role' => $role])

{{-- PDF.js hanya load kalau filenya PDF --}}
@if($pengumuman->file && $isPdf)
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
</script>
@endif

<div class="px-4 py-6 max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pengumuman</h1>
            <p class="text-gray-500 mt-1">Detail pengumuman</p>
        </div>
        <a href="{{ route('orangtua.pengumuman') }}" class="bg-[#1E2567] text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-[#2a348c] transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-blue-200 overflow-hidden">

        {{-- ===== Area Preview File ===== --}}
        @if($pengumuman->file && $isImage)
            {{-- Preview gambar penuh --}}
            <div class="w-full bg-gray-900 flex items-center justify-center max-h-96 overflow-hidden">
                <img
                    src="{{ route('pengumuman.file', $pengumuman->id_pengumuman) }}"
                    alt="{{ $pengumuman->judul }}"
                    class="max-h-96 w-full object-contain"
                >
            </div>

        @elseif($pengumuman->file && $isPdf)
            {{-- Preview PDF via PDF.js --}}
            <div class="w-full bg-gray-800 flex flex-col items-center py-4 px-4 gap-3">
                {{-- Info bar --}}
                <div class="w-full max-w-2xl flex items-center justify-between">
                    <span class="text-white text-xs font-medium opacity-70">Preview PDF</span>
                    <a href="{{ route('pengumuman.file', $pengumuman->id_pengumuman) }}" target="_blank"
                       class="text-xs bg-white/20 hover:bg-white/30 text-white px-3 py-1 rounded-full transition-colors flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download
                    </a>
                </div>

                {{-- Canvas PDF --}}
                <div class="w-full max-w-2xl relative bg-white rounded-lg overflow-hidden shadow-xl" style="min-height: 360px;">
                    <canvas
                        id="pdf-detail-canvas"
                        class="w-full"
                        data-pdf-url="{{ route('pengumuman.file', $pengumuman->id_pengumuman) }}"
                        style="display:none;"
                    ></canvas>

                    {{-- Skeleton loading --}}
                    <div id="pdf-skeleton" class="absolute inset-0 p-6 flex flex-col gap-3 animate-pulse">
                        <div class="bg-gray-200 rounded h-4 w-1/2"></div>
                        <div class="bg-gray-200 rounded h-3 w-full"></div>
                        <div class="bg-gray-200 rounded h-3 w-5/6"></div>
                        <div class="bg-gray-200 rounded h-3 w-full"></div>
                        <div class="bg-gray-200 rounded h-3 w-3/4"></div>
                        <div class="bg-gray-200 rounded h-3 w-full"></div>
                        <div class="bg-gray-200 rounded h-3 w-4/5"></div>
                        <div class="bg-gray-200 rounded h-3 w-full"></div>
                        <div class="bg-gray-200 rounded h-3 w-2/3"></div>
                    </div>

                    {{-- Fallback --}}
                    <div id="pdf-fallback" class="hidden absolute inset-0 flex flex-col items-center justify-center gap-3 bg-red-50">
                        <div class="w-16 h-16 rounded-2xl bg-red-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-sm text-red-500">Tidak dapat menampilkan preview</p>
                    </div>
                </div>

                {{-- Navigasi halaman --}}
                <div class="flex items-center gap-3">
                    <button id="pdf-prev"
                        class="bg-white/20 hover:bg-white/30 text-white text-xs px-3 py-1.5 rounded-full transition-colors disabled:opacity-30"
                        disabled>
                        ← Sebelumnya
                    </button>
                    <span id="pdf-page-info" class="text-white text-xs opacity-70">Halaman 1</span>
                    <button id="pdf-next"
                        class="bg-white/20 hover:bg-white/30 text-white text-xs px-3 py-1.5 rounded-full transition-colors disabled:opacity-30"
                        disabled>
                        Berikutnya →
                    </button>
                </div>
            </div>

        @elseif($pengumuman->file && in_array($ext, ['doc', 'docx']))
            <div class="w-full h-40 flex items-center justify-center gap-4 bg-blue-50 border-b border-blue-100">
                <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-10 h-10">
                        <path fill="#2196F3" d="M41,10H25v28h16c0.553,0,1-0.447,1-1V11C42,10.447,41.553,10,41,10z"/>
                        <path fill="#fff" d="M25 15.001H39V17H25zM25 19H39V21H25zM25 23.001H39V25.001H25zM25 27H39V29H25zM25 31H39V33.001H25z"/>
                        <path fill="#0D47A1" d="M27 42L6 38 6 10 27 6z"/>
                        <path fill="#fff" d="M21.167,31.012H19.437l-1.45-7.636c-0.025-0.135-0.06-0.367-0.1-0.689c-0.042-0.323-0.073-0.618-0.094-0.88c-0.022,0.249-0.055,0.54-0.101,0.868c-0.044,0.328-0.085,0.588-0.12,0.785l-1.608,7.552H13.52l-2.182-12h1.906l1.184,7.83c0.054,0.392,0.106,0.801,0.141,1.219s0.063,0.787,0.079,1.109c0.02-0.297,0.054-0.636,0.104-1.016s0.1-0.7,0.146-0.965l1.538-8.177H18.2l1.441,8.258c0.034,0.199,0.076,0.479,0.124,0.838s0.09,0.711,0.118,1.053c0.022-0.326,0.054-0.668,0.097-1.025s0.085-0.664,0.128-0.919l1.189-8.205h1.861L21.167,31.012z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-blue-700">Word Document</p>
                    <p class="text-xs text-blue-400">{{ $pengumuman->display_file_name }}</p>
                </div>
            </div>

        @elseif($pengumuman->file && in_array($ext, ['xls', 'xlsx']))
            <div class="w-full h-40 flex items-center justify-center gap-4 bg-green-50 border-b border-green-100">
                <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-10 h-10">
                        <path fill="#4CAF50" d="M41,10H25v28h16c0.553,0,1-0.447,1-1V11C42,10.447,41.553,10,41,10z"/>
                        <path fill="#fff" d="M32 15H39V18H32zM32 19H39V22H32zM32 23H39V26H32zM32 27H39V30H32zM32 31H39V34H32z"/>
                        <path fill="#2E7D32" d="M27 42L6 38 6 10 27 6z"/>
                        <path fill="#fff" d="M19.129,31l-2.411-4.561c-0.092-0.171-0.186-0.483-0.284-0.938h-0.037c-0.046,0.215-0.154,0.541-0.324,0.979L13.652,31H11.5l3.896-6.01L11.8,19h2.217l2.092,4.139c0.162,0.323,0.292,0.632,0.39,0.932h0.037c0.104-0.331,0.238-0.647,0.401-0.946L19.067,19H21.1l-3.747,5.959L21.282,31H19.129z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-green-700">Excel Spreadsheet</p>
                    <p class="text-xs text-green-400">{{ $pengumuman->display_file_name }}</p>
                </div>
            </div>

        @elseif($pengumuman->file && in_array($ext, ['ppt', 'pptx']))
            <div class="w-full h-40 flex items-center justify-center gap-4 bg-orange-50 border-b border-orange-100">
                <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-10 h-10">
                        <path fill="#FF5722" d="M41,10H25v28h16c0.553,0,1-0.447,1-1V11C42,10.447,41.553,10,41,10z"/>
                        <path fill="#fff" d="M25 15.001H39V17H25zM25 19H39V21H25zM25 23.001H39V25.001H25zM25 27H39V29H25z"/>
                        <path fill="#BF360C" d="M27 42L6 38 6 10 27 6z"/>
                        <path fill="#fff" d="M13,18h3.7c2.3,0,3.7,1.3,3.7,3.3c0,2.3-1.6,3.6-4,3.6H15V31h-2V18z M15,23.3h1.3c1.3,0,2-0.6,2-1.8c0-1.2-0.6-1.8-1.9-1.8H15V23.3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-orange-700">PowerPoint</p>
                    <p class="text-xs text-orange-400">{{ $pengumuman->display_file_name }}</p>
                </div>
            </div>
        @endif

        {{-- ===== Konten Detail ===== --}}
        <div class="p-8">
            @if($pengumuman->kelas)
                <span class="text-xs bg-blue-50 text-blue-600 px-3 py-1 rounded-full border border-blue-100 inline-block mb-4">
                    {{ $pengumuman->kelas->nama_kelas }}
                </span>
            @endif

            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $pengumuman->judul }}</h2>

            <div class="flex flex-wrap gap-6 text-gray-600 mb-6 pb-6 border-b border-gray-100">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Tanggal Mulai</p>
                    <p class="text-sm font-semibold text-gray-700">{{ optional($pengumuman->tanggal_mulai)->format('d/m/Y') ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Tanggal Selesai</p>
                    <p class="text-sm font-semibold text-gray-700">{{ optional($pengumuman->tanggal_selesai)->format('d/m/Y') ?? '-' }}</p>
                </div>
            </div>

            <div class="prose prose-blue max-w-none text-gray-700 leading-relaxed mb-8">
                {!! nl2br(e($pengumuman->deskripsi)) !!}
            </div>

            {{-- Tombol download di bawah --}}
            @if($pengumuman->file)
                <div class="border-t border-gray-100 pt-6">
                    <a
                        href="{{ route('pengumuman.file', $pengumuman->id_pengumuman) }}"
                        target="_blank"
                        class="inline-flex items-center gap-2 bg-[#1E2567] hover:bg-[#2a348c] text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download {{ strtoupper($ext) }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Script PDF.js dengan navigasi halaman --}}
@if($pengumuman->file && $isPdf)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas   = document.getElementById('pdf-detail-canvas');
    const skeleton = document.getElementById('pdf-skeleton');
    const fallback = document.getElementById('pdf-fallback');
    const prevBtn  = document.getElementById('pdf-prev');
    const nextBtn  = document.getElementById('pdf-next');
    const pageInfo = document.getElementById('pdf-page-info');

    let pdfDoc      = null;
    let currentPage = 1;

    function renderPage(num) {
        pdfDoc.getPage(num).then(function (page) {
            const containerWidth = canvas.parentElement.offsetWidth || 600;
            const viewport       = page.getViewport({ scale: 1 });
            const scale          = containerWidth / viewport.width;
            const scaledViewport = page.getViewport({ scale });

            canvas.width  = scaledViewport.width;
            canvas.height = scaledViewport.height;

            page.render({
                canvasContext: canvas.getContext('2d'),
                viewport: scaledViewport,
            }).promise.then(function () {
                skeleton.style.display = 'none';
                canvas.style.display   = 'block';
                pageInfo.textContent   = `Halaman ${num} dari ${pdfDoc.numPages}`;
                prevBtn.disabled       = num <= 1;
                nextBtn.disabled       = num >= pdfDoc.numPages;
            });
        });
    }

    pdfjsLib.getDocument(canvas.dataset.pdfUrl).promise
        .then(function (pdf) {
            pdfDoc = pdf;
            nextBtn.disabled = pdf.numPages <= 1;
            renderPage(1);
        })
        .catch(function () {
            skeleton.style.display = 'none';
            fallback.classList.remove('hidden');
            fallback.classList.add('flex');
        });

    prevBtn.addEventListener('click', function () {
        if (currentPage > 1) { currentPage--; renderPage(currentPage); }
    });

    nextBtn.addEventListener('click', function () {
        if (currentPage < pdfDoc.numPages) { currentPage++; renderPage(currentPage); }
    });
});
</script>
@endif

@endsection