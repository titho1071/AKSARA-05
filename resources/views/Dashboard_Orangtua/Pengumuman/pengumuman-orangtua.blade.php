@extends('layouts.index')

@php
    $role = 'orangtua';
@endphp

@section('title', 'Pengumuman - Orang Tua')

@section('content')
@include('components.navbar', ['role' => $role])

{{-- Load PDF.js dari CDN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
</script>

<div class="px-0 py-4 sm:py-6">
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Pengumuman</h1>
        <p class="text-gray-500 mt-1 text-sm sm:text-base">Semua pengumuman</p>
    </div>

    <!-- Student Tabs -->
    <div class="flex flex-wrap gap-2 sm:gap-4 mb-6 sm:mb-8">
        @foreach($siswa as $s)
            @php
                $isActive = $activeSiswa && $activeSiswa->id_siswa == $s->id_siswa;
                $initials = collect(explode(' ', $s->nama))
                    ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                    ->take(2)
                    ->implode('');
            @endphp
            <a
                href="{{ route('orangtua.pengumuman', ['siswa_id' => $s->id_siswa]) }}"
                class="flex items-center gap-2 sm:gap-3 px-4 sm:px-6 py-2 sm:py-3 rounded-2xl transition-all
                {{ $isActive ? 'bg-[#1E2567] text-white shadow-lg' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
            >
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-sm
                    {{ $isActive ? 'bg-white/20' : 'bg-gray-300' }}">
                    {{ $initials }}
                </div>
                <div>
                    <p class="font-bold text-xs sm:text-sm">{{ $s->nama }}</p>
                    <p class="text-xs {{ $isActive ? 'text-blue-100' : 'text-gray-500' }}">{{ $s->nama_kelas }}</p>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Thumbnail Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
        @forelse($pengumuman as $item)
            @php
                $ext = $item->file
                    ? strtolower(pathinfo($item->display_file_name, PATHINFO_EXTENSION))
                    : null;
                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                $isPdf   = $ext === 'pdf';
            @endphp

            <div class="bg-white rounded-2xl border border-gray-100 hover:border-gray-200 hover:shadow-md overflow-hidden transition-all">

                {{-- ===== Area Thumbnail ===== --}}

                @if($item->file && $isImage)
                    {{-- Gambar: tampil langsung --}}
                    <img
                        src="{{ route('pengumuman.file', $item->id_pengumuman) }}"
                        alt="{{ $item->judul }}"
                        class="w-full h-44 object-cover"
                    >

                @elseif($item->file && $isPdf)
                    {{-- PDF: render halaman pertama via PDF.js --}}
                    <div class="w-full h-44 bg-gray-100 relative overflow-hidden flex items-center justify-center">
                        {{-- Canvas tempat PDF.js menggambar --}}
                        <canvas
                            class="pdf-preview-canvas w-full h-full object-contain"
                            data-pdf-url="{{ route('pengumuman.file', $item->id_pengumuman) }}"
                            style="display:none;"
                        ></canvas>

                        {{-- Skeleton loading --}}
                        <div class="pdf-skeleton absolute inset-0 flex flex-col gap-2 p-3 animate-pulse">
                            <div class="bg-gray-200 rounded h-3 w-3/4"></div>
                            <div class="bg-gray-200 rounded h-3 w-full"></div>
                            <div class="bg-gray-200 rounded h-3 w-5/6"></div>
                            <div class="bg-gray-200 rounded h-3 w-full"></div>
                            <div class="bg-gray-200 rounded h-3 w-2/3"></div>
                            <div class="bg-gray-200 rounded h-3 w-full"></div>
                            <div class="bg-gray-200 rounded h-3 w-4/5"></div>
                        </div>

                        {{-- Fallback ikon jika gagal --}}
                        <div class="pdf-fallback hidden flex-col items-center justify-center gap-2 absolute inset-0 bg-red-50">
                            <div class="w-14 h-14 rounded-xl bg-red-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-xs text-red-600 font-medium">PDF</span>
                        </div>

                        {{-- Badge PDF di pojok --}}
                        <div class="absolute bottom-2 right-2 bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full z-10">
                            PDF
                        </div>
                    </div>

                @elseif($item->file && in_array($ext, ['doc', 'docx']))
                    {{-- Word --}}
                    <div class="w-full h-44 flex flex-col items-center justify-center gap-3 bg-blue-50">
                        <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-10 h-10">
                                <path fill="#2196F3" d="M41,10H25v28h16c0.553,0,1-0.447,1-1V11C42,10.447,41.553,10,41,10z"/>
                                <path fill="#fff" d="M25 15.001H39V17H25zM25 19H39V21H25zM25 23.001H39V25.001H25zM25 27H39V29H25zM25 31H39V33.001H25z"/>
                                <path fill="#0D47A1" d="M27 42L6 38 6 10 27 6z"/>
                                <path fill="#fff" d="M21.167,31.012H19.Robin l-1.45-7.636c-0.025-0.135-0.06-0.367-0.1-0.689c-0.042-0.323-0.073-0.618-0.094-0.88c-0.022,0.249-0.055,0.54-0.101,0.868c-0.044,0.328-0.085,0.588-0.12,0.785l-1.608,7.552H13.52l-2.182-12h1.906l1.184,7.830c0.054,0.392,0.106,0.801,0.141,1.219c0.037,0.418,0.063,0.787,0.079,1.109c0.02-0.297,0.054-0.636,0.104-1.016c0.051-0.378,0.1-0.7,0.146-0.965l1.538-8.177H18.2l1.441,8.258c0.034,0.199,0.076,0.479,0.124,0.838c0.051,0.358,0.09,0.711,0.118,1.053c0.022-0.326,0.054-0.668,0.097-1.025c0.042-0.359,0.085-0.664,0.128-0.919l1.189-8.205h1.861L21.167,31.012z"/>
                            </svg>
                        </div>
                        <div class="text-center px-3">
                            <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide">Word Document</p>
                            <p class="text-xs text-blue-400 mt-0.5 truncate max-w-[180px]">{{ $item->display_file_name }}</p>
                        </div>
                    </div>

                @elseif($item->file && in_array($ext, ['xls', 'xlsx']))
                    {{-- Excel --}}
                    <div class="w-full h-44 flex flex-col items-center justify-center gap-3 bg-green-50">
                        <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-10 h-10">
                                <path fill="#4CAF50" d="M41,10H25v28h16c0.553,0,1-0.447,1-1V11C42,10.447,41.553,10,41,10z"/>
                                <path fill="#fff" d="M32 15H39V18H32zM32 19H39V22H32zM32 23H39V26H32zM32 27H39V30H32zM32 31H39V34H32z"/>
                                <path fill="#2E7D32" d="M27 42L6 38 6 10 27 6z"/>
                                <path fill="#fff" d="M19.129,31l-2.411-4.561c-0.092-0.171-0.186-0.483-0.284-0.938h-0.037c-0.046,0.215-0.154,0.541-0.324,0.979L13.652,31H11.5l3.896-6.01L11.8,19h2.217l2.092,4.139c0.162,0.323,0.292,0.632,0.39,0.932h0.037c0.104-0.331,0.238-0.647,0.401-0.946L19.067,19H21.1l-3.747,5.959L21.282,31H19.129z"/>
                            </svg>
                        </div>
                        <div class="text-center px-3">
                            <p class="text-xs font-semibold text-green-700 uppercase tracking-wide">Excel Spreadsheet</p>
                            <p class="text-xs text-green-400 mt-0.5 truncate max-w-[180px]">{{ $item->display_file_name }}</p>
                        </div>
                    </div>

                @elseif($item->file && in_array($ext, ['ppt', 'pptx']))
                    {{-- PowerPoint --}}
                    <div class="w-full h-44 flex flex-col items-center justify-center gap-3 bg-orange-50">
                        <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-10 h-10">
                                <path fill="#FF5722" d="M41,10H25v28h16c0.553,0,1-0.447,1-1V11C42,10.447,41.553,10,41,10z"/>
                                <path fill="#fff" d="M25 15.001H39V17H25zM25 19H39V21H25zM25 23.001H39V25.001H25zM25 27H39V29H25z"/>
                                <path fill="#BF360C" d="M27 42L6 38 6 10 27 6z"/>
                                <path fill="#fff" d="M13,18h3.7c2.3,0,3.7,1.3,3.7,3.3c0,2.3-1.6,3.6-4,3.6H15V31h-2V18z M15,23.3h1.3c1.3,0,2-0.6,2-1.8c0-1.2-0.6-1.8-1.9-1.8H15V23.3z"/>
                            </svg>
                        </div>
                        <div class="text-center px-3">
                            <p class="text-xs font-semibold text-orange-700 uppercase tracking-wide">PowerPoint</p>
                            <p class="text-xs text-orange-400 mt-0.5 truncate max-w-[180px]">{{ $item->display_file_name }}</p>
                        </div>
                    </div>

                @elseif($item->file)
                    {{-- File tipe lain --}}
                    <div class="w-full h-44 flex flex-col items-center justify-center gap-3 bg-gray-50">
                        <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="text-center px-3">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ strtoupper($ext) }}</p>
                            <p class="text-xs text-gray-400 mt-0.5 truncate max-w-[180px]">{{ $item->display_file_name }}</p>
                        </div>
                    </div>

                @else
                    {{-- Tanpa file --}}
                    <div class="w-full h-44 flex flex-col items-center justify-center gap-3 bg-gray-50">
                        <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <p class="text-xs text-gray-400">Pengumuman umum</p>
                    </div>
                @endif

                {{-- ===== Body Card ===== --}}
                <div class="p-4 border-t border-gray-50">
                    @if($item->kelas)
                        <span class="text-xs bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full border border-blue-100 inline-block mb-2">
                            {{ $item->kelas->nama_kelas }}
                        </span>
                    @endif

                    <h2 class="text-sm font-semibold text-gray-900 mb-1 line-clamp-2 leading-snug">
                        {{ $item->judul }}
                    </h2>

                    <p class="text-xs text-gray-400 mb-3">
                        {{ optional($item->tanggal_mulai)->format('d/m/Y') ?? '-' }}
                        —
                        {{ optional($item->tanggal_selesai)->format('d/m/Y') ?? '-' }}
                    </p>

                    <div class="flex justify-end">
                        <a
                            href="{{ route('orangtua.pengumuman.detail', $item->id_pengumuman) }}"
                            class="text-xs text-blue-600 hover:underline font-medium inline-flex items-center gap-1"
                        >
                            Lihat Detail
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
                <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Belum Ada Pengumuman</h3>
                <p class="text-gray-500 mt-1">Pengumuman terbaru untuk Anda akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Script PDF.js render halaman pertama --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvases = document.querySelectorAll('.pdf-preview-canvas');

    canvases.forEach(function (canvas) {
        const url = canvas.dataset.pdfUrl;
        const wrapper = canvas.closest('.relative');
        const skeleton = wrapper.querySelector('.pdf-skeleton');
        const fallback = wrapper.querySelector('.pdf-fallback');

        pdfjsLib.getDocument(url).promise
            .then(function (pdf) {
                return pdf.getPage(1);
            })
            .then(function (page) {
                const containerWidth = canvas.parentElement.offsetWidth || 300;
                const viewport = page.getViewport({ scale: 1 });
                const scale = containerWidth / viewport.width;
                const scaledViewport = page.getViewport({ scale: scale });

                canvas.width  = scaledViewport.width;
                canvas.height = scaledViewport.height;

                return page.render({
                    canvasContext: canvas.getContext('2d'),
                    viewport: scaledViewport,
                }).promise;
            })
            .then(function () {
                skeleton.remove();
                canvas.style.display = 'block';
            })
            .catch(function () {
                skeleton.remove();
                fallback.classList.remove('hidden');
                fallback.classList.add('flex');
            });
    });
});
</script>
@endsection