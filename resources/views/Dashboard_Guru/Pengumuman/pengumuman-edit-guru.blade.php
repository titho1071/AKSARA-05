@extends('layouts.index')

@section('title', 'Edit Pengumuman')

@php
    $guruId = \Illuminate\Support\Facades\DB::table('guru')->where('user_id', auth()->id())->value('id_guru');
    $kelas = \Illuminate\Support\Facades\DB::table('kelas')->where('guru_id', $guruId)->first();
    $kelasId = $kelas ? $kelas->id_kelas : null;
    $namaKelas = $kelas ? $kelas->nama_kelas : 'Belum Ada Kelas';
    $role = 'guru';
@endphp

@section('content')
@include('components.navbar', ['role' => $role])

<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Edit Pengumuman</h1>
            <p class="text-gray-600 mt-1">Perbarui pengumuman untuk Kelas: <span class="font-semibold">{{ $namaKelas }}</span></p>
        </div>
        <a href="{{ route('guru.pengumuman') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-colors justify-center">
            <span>←</span> Kembali
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 max-w-4xl mx-auto">
    <form id="pengumuman-form" enctype="multipart/form-data">
        @csrf
        <!-- spoofing PUT method -->
        <input type="hidden" name="_method" value="PUT">
        
        <div id="form-alert" class="hidden mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>

        <input type="hidden" name="kelas_id" value="{{ $kelasId }}">

        <div class="grid gap-6 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2" for="judul">
                    Judul Pengumuman <span class="text-red-500">*</span>
                </label>
                <input 
                    id="judul" 
                    type="text" 
                    name="judul"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" 
                    placeholder="Masukkan judul pengumuman..."
                >
                <small class="text-gray-500 mt-1 block">Maksimal 255 karakter</small>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2" for="deskripsi">
                    Deskripsi Pengumuman <span class="text-red-500">*</span>
                </label>
                <textarea 
                    id="deskripsi" 
                    name="deskripsi"
                    required
                    rows="6" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" 
                    placeholder="Jelaskan detail pengumuman yang ingin disampaikan..."
                ></textarea>
                <small class="text-gray-500 mt-1 block">Uraian lengkap tentang pengumuman</small>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="tanggal_mulai">
                        Tanggal Mulai <span class="text-gray-400 text-xs">(Opsional)</span>
                    </label>
                    <input 
                        id="tanggal_mulai" 
                        type="date" 
                        name="tanggal_mulai"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="tanggal_selesai">
                        Tanggal Selesai <span class="text-gray-400 text-xs">(Opsional)</span>
                    </label>
                    <input 
                        id="tanggal_selesai" 
                        type="date" 
                        name="tanggal_selesai"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    >
                    <small class="text-gray-500 mt-1 block">Harus lebih besar atau sama dengan tanggal mulai</small>
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2" for="file">
                    Upload File Baru <span class="text-gray-400 text-xs">(Opsional)</span>
                </label>
                <div id="current-file-info" class="hidden mb-3 flex items-center gap-3 p-3 bg-blue-50 border border-blue-100 rounded-xl">
                    <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-blue-600 font-medium">File saat ini</p>
                        <a id="current-file-link" href="#" target="_blank" class="text-sm text-blue-700 hover:underline font-semibold">Lihat Lampiran</a>
                    </div>
                </div>
                <input 
                    id="file" 
                    type="file" 
                    name="file"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200" 
                    accept=".jpg,.jpeg,.png,.svg,.pdf"
                >
                <small class="text-gray-500 mt-2 block">
                    Format: JPG, JPEG, PNG, SVG, PDF — Maksimal 2 MB. Kosongkan jika tidak ingin mengubah file.
                </small>
                <!-- File Preview -->
                <div id="file-preview" class="hidden mt-3 flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    <div id="preview-image-wrap" class="hidden">
                        <img id="preview-img" src="" alt="Preview" class="h-16 w-16 object-cover rounded-lg border">
                    </div>
                    <div id="preview-pdf-wrap" class="hidden flex items-center justify-center h-16 w-16 rounded-lg bg-red-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p id="preview-name" class="text-sm font-medium text-gray-800"></p>
                        <p id="preview-size" class="text-xs text-gray-500 mt-0.5"></p>
                    </div>
                    <button type="button" id="btn-remove-file" class="ml-auto text-gray-400 hover:text-red-500 transition-colors" title="Hapus pilihan">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <button 
                type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-colors"
            >
                Perbarui Pengumuman
            </button>
            <a 
                href="{{ route('guru.pengumuman') }}" 
                class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-3 rounded-lg font-medium transition-colors"
            >
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('pengumuman-form');
        const alertBox = document.getElementById('form-alert');
        const pengumumanId = '{{ request()->route("id") }}';

        // File preview
        const fileInput = document.getElementById('file');
        const filePreview = document.getElementById('file-preview');
        const previewImg = document.getElementById('preview-img');
        const previewImgWrap = document.getElementById('preview-image-wrap');
        const previewPdfWrap = document.getElementById('preview-pdf-wrap');
        const previewName = document.getElementById('preview-name');
        const previewSize = document.getElementById('preview-size');
        const btnRemoveFile = document.getElementById('btn-remove-file');

        fileInput.addEventListener('change', function () {
            const file = fileInput.files[0];
            if (!file) { filePreview.classList.add('hidden'); return; }
            if (file.size > 2 * 1024 * 1024) {
                showAlert('Ukuran file melebihi 2 MB. Silakan pilih file yang lebih kecil.', 'error');
                fileInput.value = '';
                filePreview.classList.add('hidden');
                return;
            }
            const isPdf = file.type === 'application/pdf';
            previewName.textContent = file.name;
            previewSize.textContent = (file.size / 1024).toFixed(1) + ' KB';
            if (isPdf) {
                previewImgWrap.classList.add('hidden');
                previewPdfWrap.classList.remove('hidden');
            } else {
                previewPdfWrap.classList.add('hidden');
                previewImgWrap.classList.remove('hidden');
                const reader = new FileReader();
                reader.onload = e => { previewImg.src = e.target.result; };
                reader.readAsDataURL(file);
            }
            filePreview.classList.remove('hidden');
        });

        btnRemoveFile.addEventListener('click', function () {
            fileInput.value = '';
            filePreview.classList.add('hidden');
            previewImg.src = '';
        });

        function showAlert(message, type = 'error') {
            alertBox.textContent = message;
            alertBox.classList.remove('hidden', 'border-red-200', 'bg-red-50', 'text-red-700', 'border-green-200', 'bg-green-50', 'text-green-700');
            if (type === 'success') {
                alertBox.classList.add('border-green-200', 'bg-green-50', 'text-green-700');
            } else {
                alertBox.classList.add('border-red-200', 'bg-red-50', 'text-red-700');
            }
        }

        // Fetch current data
        fetch(`/api/pengumuman/${pengumumanId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(payload => {
            if (payload.success) {
                const data = payload.data;
                document.getElementById('judul').value = data.judul || '';
                document.getElementById('deskripsi').value = data.deskripsi || '';
                document.getElementById('tanggal_mulai').value = data.tanggal_mulai || '';
                document.getElementById('tanggal_selesai').value = data.tanggal_selesai || '';
                
                if (data.file_url) {
                    const fileInfo = document.getElementById('current-file-info');
                    const fileLink = document.getElementById('current-file-link');
                    fileLink.href = data.file_url;
                    fileInfo.classList.remove('hidden');
                }
            } else {
                showAlert('Gagal mengambil data pengumuman');
            }
        })
        .catch(err => {
            showAlert('Terjadi kesalahan saat memuat data: ' + err.message);
        });

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            
            try {
                const response = await fetch(`/api/pengumuman/${pengumumanId}`, {
                    method: 'POST', // using POST with _method=PUT
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: formData,
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    const message = result.message || 'Gagal memperbarui pengumuman';
                    showAlert(message, 'error');
                    return;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Pengumuman berhasil diperbarui!',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6366f1'
                }).then(() => {
                    window.location.href = '{{ route('guru.pengumuman') }}';
                });
            } catch (error) {
                showAlert(error.message || 'Terjadi kesalahan saat menyimpan data');
            }
        });

        // Validasi tanggal
        const tanggalMulai = document.getElementById('tanggal_mulai');
        const tanggalSelesai = document.getElementById('tanggal_selesai');

        tanggalSelesai.addEventListener('change', function () {
            if (tanggalMulai.value && tanggalSelesai.value) {
                if (new Date(tanggalSelesai.value) < new Date(tanggalMulai.value)) {
                    showAlert('Tanggal selesai harus lebih besar atau sama dengan tanggal mulai', 'error');
                    tanggalSelesai.value = '';
                }
            }
        });
    });
</script>
@endsection
