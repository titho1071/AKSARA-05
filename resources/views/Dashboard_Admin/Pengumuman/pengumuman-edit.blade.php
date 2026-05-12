@extends('layouts.index')

@section('title', 'Edit Pengumuman')

@section('content')
@include('components.navbar')

<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Edit Pengumuman</h1>
            <p class="text-gray-600 mt-1">Perbarui pengumuman yang sudah ada.</p>
        </div>
        <a href="{{ route('admin.pengumuman') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-colors justify-center">
            <span>←</span> Kembali
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 max-w-4xl mx-auto">
    <form id="pengumuman-form" enctype="multipart/form-data">
        @csrf
        <div id="form-alert" class="hidden mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>

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
                    value="{{ old('judul', $pengumuman->judul) }}"
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
                >{{ old('deskripsi', $pengumuman->deskripsi) }}</textarea>
                <small class="text-gray-500 mt-1 block">Uraian lengkap tentang pengumuman</small>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" for="kelas_id">
                    Pilih Kelas <span class="text-gray-400 text-xs">(Opsional)</span>
                </label>
                <select 
                    id="kelas_id"
                    name="kelas_id"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                >
                    @php
                        $selectedKelasId = old('kelas_id', $pengumuman->kelas_id);
                    @endphp
                    <option value="" {{ $selectedKelasId === null || $selectedKelasId === '' ? 'selected' : '' }}>
                        Semua Kelas
                    </option>

                    @foreach ($kelas as $item)
                        <option value="{{ $item->id_kelas }}" {{ (string) old('kelas_id', $pengumuman->kelas_id) === (string) $item->id_kelas ? 'selected' : '' }}>
                            {{ $item->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" for="tanggal_mulai">
                    Tanggal Mulai <span class="text-gray-400 text-xs">(Opsional)</span>
                </label>
                <input 
                    id="tanggal_mulai" 
                    type="date" 
                    name="tanggal_mulai"
                    value="{{ old('tanggal_mulai', optional($pengumuman->tanggal_mulai)->format('Y-m-d')) }}"
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
                    value="{{ old('tanggal_selesai', optional($pengumuman->tanggal_selesai)->format('Y-m-d')) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                >
                <small class="text-gray-500 mt-1 block">Harus lebih besar atau sama dengan tanggal mulai</small>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2" for="file">
                    Upload File <span class="text-gray-400 text-xs">(Opsional)</span>
                </label>
                <input 
                    id="file" 
                    type="file" 
                    name="file"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200" 
                    accept=".jpg,.jpeg,.png,.svg,.pdf"
                >
                <small class="text-gray-500 mt-2 block">
                    Format: JPG, JPEG, PNG, SVG, PDF — Maksimal 10 MB
                </small>
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
                href="{{ route('admin.pengumuman') }}" 
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
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const apiUrl = '/api/pengumuman/{{ $pengumuman->id_pengumuman }}';

        function showAlert(message, type = 'error') {
            alertBox.textContent = message;
            alertBox.classList.remove('hidden', 'border-red-200', 'bg-red-50', 'text-red-700', 'border-green-200', 'bg-green-50', 'text-green-700');
            if (type === 'success') {
                alertBox.classList.add('border-green-200', 'bg-green-50', 'text-green-700');
            } else {
                alertBox.classList.add('border-red-200', 'bg-red-50', 'text-red-700');
            }
        }

        async function updatePengumuman(event) {
            event.preventDefault();

            const formData = new FormData(form);
            formData.append('_method', 'PUT');

            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: formData,
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                let message = result.message || 'Gagal memperbarui pengumuman';
                if (result.errors) {
                    message = Object.values(result.errors).flat().join(' ');
                }
                showAlert(message, 'error');
                return;
            }

            showAlert('Pengumuman berhasil diperbarui!', 'success');
            setTimeout(() => {
                window.location.href = '{{ route('admin.pengumuman') }}';
            }, 1200);
        }

        form.addEventListener('submit', updatePengumuman);

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
