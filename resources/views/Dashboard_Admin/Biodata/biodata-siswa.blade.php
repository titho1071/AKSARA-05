@extends('layouts.index')
@php $role = 'admin'; @endphp
@section('title', 'Data Siswa')

@section('content')
@include('components.navbar', ['role' => $role])

<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Kelola Data Siswa</h1>
            <p class="text-gray-600 mt-1">Lihat dan tambahkan data siswa sekolah.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.siswa.template') }}"
                class="inline-flex items-center gap-2 rounded-[16px] bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                </svg>
                Download Template
            </a>
            <button type="button"
                onclick="document.getElementById('importModalSiswa').classList.remove('hidden'); document.getElementById('importModalSiswa').classList.add('flex')"
                class="inline-flex items-center gap-2 rounded-[16px] bg-green-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-green-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M17 8l-5-5-5 5M12 3v12"/>
                </svg>
                Import Excel
            </button>
            <a href="{{ route('admin.siswa.create') }}"
                class="inline-flex items-center gap-2 rounded-[16px] bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                + Tambah Data Siswa
            </a>
        </div>
    </div>
</div>

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#6366f1'
            });
        });
    </script>
@endif

<div id="importModalSiswa" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4">
    <div class="w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl">
        <div class="flex items-center justify-between bg-indigo-700 px-5 py-4">
            <span class="text-sm font-semibold uppercase tracking-wide text-white">Import Data Siswa</span>
            <button type="button" onclick="document.getElementById('importModalSiswa').classList.add('hidden'); document.getElementById('importModalSiswa').classList.remove('flex')" class="text-xl leading-none text-white/70 hover:text-white">&times;</button>
        </div>
        <div class="p-6">
            <p class="mb-4 text-sm text-slate-500">Upload file Excel/CSV. Pastikan format sesuai template yang sudah diunduh. Data dengan NIS/NISN yang sudah ada akan dilewati.</p>
            <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="mb-2 block text-sm font-semibold text-slate-700">File Excel / CSV</label>
                <input id="siswaFileInput" type="file" name="file" accept=".xlsx,.xls,.csv" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('importModalSiswa').classList.add('hidden'); document.getElementById('importModalSiswa').classList.remove('flex')" class="rounded-xl bg-slate-200 px-5 py-3 font-medium text-slate-900 transition hover:bg-slate-300">Batal</button>
                    <button id="siswaPreviewBtn" type="button" class="rounded-xl bg-indigo-700 px-5 py-3 font-medium text-white transition hover:bg-indigo-800">Preview</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="previewModalSiswa" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
    <div class="w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-2xl">
        <div class="flex items-center justify-between bg-indigo-700 px-5 py-4">
            <span class="text-sm font-semibold uppercase tracking-wide text-white">Preview Data Siswa (max 10 baris)</span>
            <button type="button" onclick="document.getElementById('previewModalSiswa').classList.add('hidden'); document.getElementById('previewModalSiswa').classList.remove('flex')" class="text-xl leading-none text-white/70 hover:text-white">&times;</button>
        </div>
        <div class="p-4 overflow-auto">
            <div id="siswaPreviewContainer"></div>
        </div>
        <div class="p-4 flex justify-end gap-3">
            <button type="button" onclick="document.getElementById('previewModalSiswa').classList.add('hidden'); document.getElementById('previewModalSiswa').classList.remove('flex')" class="rounded-xl bg-slate-200 px-5 py-3 font-medium text-slate-900 transition hover:bg-slate-300">Tutup</button>
            <button id="siswaConfirmImport" type="button" class="rounded-xl bg-green-600 px-5 py-3 font-medium text-white transition hover:bg-green-700">Import Sekarang</button>
        </div>
    </div>
</div>

<script>
    document.getElementById('siswaPreviewBtn').addEventListener('click', async function () {
        const input = document.getElementById('siswaFileInput');
        if (!input.files.length) return alert('Pilih file terlebih dahulu.');

        const fd = new FormData();
        fd.append('file', input.files[0]);
        fd.append('_token', '{{ csrf_token() }}');

        const res = await fetch('{{ route('admin.siswa.preview') }}', { method: 'POST', body: fd });
        const data = await res.json();
        if (!data.success) return alert(data.message || 'Gagal membaca file.');

        const container = document.getElementById('siswaPreviewContainer');
        const tableId = `siswa-preview-table`;
        let tableHtml = '<table id="'+tableId+'" class="border-collapse table-auto text-sm w-full">';
        tableHtml += '<thead class="bg-slate-100"><tr>' + data.headers.map(h=>`<th class="border px-2 py-2 text-left" style="font-weight:600; white-space:nowrap;">${h.replace(/_/g, ' ').replace(/\b\w/g, c=>c.toUpperCase())}</th>`).join('') + '</tr></thead>';
        tableHtml += '<tbody>' + data.rows.map((r,ri)=>{
            const hasWarn = Array.isArray(r.warnings) && r.warnings.length > 0;
            const rowClass = hasWarn ? 'bg-yellow-50' : '';
            const cells = data.headers.map(h=>`<td class="border px-2 py-1" style="white-space:nowrap;">${(r.data[h]!==null? r.data[h] : '')}</td>`).join('');
            return `<tr id="siswa-preview-row-${ri}" class="${rowClass}">` + cells + `</tr>`;
        }).join('') + '</tbody>';
        tableHtml += '</table>';

        let warningsHtml = '<div class="mb-3"><p class="text-sm text-slate-600">Klik sebuah item untuk menyorot baris terkait.</p></div>';
        warningsHtml += '<div class="space-y-3">';
        data.rows.forEach((r,ri)=>{
            if (Array.isArray(r.warnings) && r.warnings.length) {
                warningsHtml += `<div class=\"p-3 border rounded-lg bg-rose-50\"><div class=\"text-sm font-semibold text-rose-700\">Baris ${ri+1} - Peringatan</div><ul class=\"list-disc pl-5 text-xs text-rose-700 mt-2\">`;
                r.warnings.forEach(w=>{ warningsHtml += `<li>${w}</li>` });
                warningsHtml += `</ul><div class=\"mt-2\"><button data-row=\"${ri}\" class=\"siswa-highlight-btn inline-flex items-center gap-2 rounded-md bg-rose-600 px-3 py-1 text-xs text-white\">Sorot baris</button></div></div>`;
            }
        });
        warningsHtml += '</div>';

        const html = `<div class="flex gap-4"><div class="flex-1 overflow-auto" style="max-height:60vh;">` +
            `<div class="overflow-auto" style="min-width:0">${tableHtml}</div>` +
            `</div><div class="w-80 border-l pl-4 overflow-auto" style="max-height:60vh;">${warningsHtml}</div></div>`;
        container.innerHTML = html;

        document.querySelectorAll('.siswa-highlight-btn').forEach(btn=>{
            btn.addEventListener('click', function(){
                const idx = this.getAttribute('data-row');
                const el = document.getElementById(`siswa-preview-row-${idx}`);
                if (!el) return;
                el.scrollIntoView({behavior:'smooth', block:'center'});
                el.classList.add('ring-2','ring-rose-400');
                setTimeout(()=> el.classList.remove('ring-2','ring-rose-400'), 2500);
            });
        });

        document.getElementById('previewModalSiswa').classList.remove('hidden');
        document.getElementById('previewModalSiswa').classList.add('flex');

        document.getElementById('siswaConfirmImport').onclick = function () {
            const form = input.closest('form');
            form.submit();
        };
    });
</script>

<div class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm">
    <div class="mb-6">
        <label class="mb-2 block text-sm font-semibold text-slate-500">Pencarian</label>
        <form action="{{ route('admin.siswa.index') }}" method="GET">
            <input type="text" name="search" value="{{ $search ?? '' }}"
                placeholder="Cari nama, NIS, atau NISN siswa..."
                class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-[#1E2567] text-white">
                <tr>
                    <th class="px-4 py-4 text-left font-semibold">No</th>
                    <th class="px-4 py-4 text-left font-semibold">Nama</th>
                    <th class="px-4 py-4 text-left font-semibold">Kelas</th>
                    <th class="px-4 py-4 text-left font-semibold">NIS</th>
                    <th class="px-4 py-4 text-left font-semibold">NISN</th>
                    <th class="px-4 py-4 text-left font-semibold">Jenis Kelamin</th>
                    <th class="px-4 py-4 text-left font-semibold">Tanggal Lahir</th>
                    <th class="px-4 py-4 text-left font-semibold">Status</th>
                    <th class="px-4 py-4 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($siswas as $index => $siswa)
                    <tr class="odd:bg-slate-50 even:bg-white">
                        <td class="px-4 py-4 text-slate-600">{{ $index + 1 }}</td>
                        <td class="px-4 py-4 font-semibold text-slate-900">{{ $siswa->nama }}</td>
                        <td class="px-4 py-4 text-slate-700">
                            {{ $siswa->kelas->nama_kelas ?? '-' }}
                        </td>
                        <td class="px-4 py-4 text-slate-700">{{ $siswa->nis }}</td>
                        <td class="px-4 py-4 text-slate-700">{{ $siswa->nisn }}</td>
                        <td class="px-4 py-4 text-slate-700">
                            {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </td>
                        <td class="px-4 py-4 text-slate-700">
                            {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                {{ $siswa->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ ucwords(str_replace('_', ' ', $siswa->status ?? 'tidak_aktif')) }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                {{-- Edit --}}
                                <a href="{{ route('admin.siswa.edit', $siswa->id_siswa) }}"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-600 transition hover:bg-amber-200"
                                    title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                    </svg>
                                </a>
                                {{-- Hapus --}}
                                <button type="button"
                                    onclick="confirmDelete('{{ route('admin.siswa.destroy', $siswa->id_siswa) }}', '{{ $siswa->nama }}')"
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
                    <tr class="bg-white">
                        <td colspan="9" class="px-4 py-6 text-center text-slate-500">Tidak ada data siswa.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function confirmDelete(actionUrl, nama) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Anda akan menghapus siswa ${nama}. Tindakan ini tidak dapat dibatalkan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                fetch(actionUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: data.message || 'Data siswa berhasil dihapus',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#6366f1'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat menghapus data',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#6366f1'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus data',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#6366f1'
                    });
                });
            }
        });
    }
</script>

@endsection