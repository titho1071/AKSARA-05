@extends('layouts.index')

@section('title', 'Pengumuman')

@php
    $guruId = \Illuminate\Support\Facades\DB::table('guru')->where('user_id', auth()->id())->value('id_guru');
    $kelas = \Illuminate\Support\Facades\DB::table('kelas')->where('guru_id', $guruId)->first();
    $kelasId = $kelas ? $kelas->id_kelas : null;
    $namaKelas = $kelas ? $kelas->nama_kelas : 'Belum Ada Kelas';
    $role = 'guru';
@endphp

@section('content')

@include('components.navbar', ['role' => $role])
<!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Pengumuman</h1>
                <p class="text-gray-600 mt-1">Kelola Pengumuman Kelas: <span class="font-semibold">{{ $namaKelas }}</span></p>
            </div>
            @if($kelasId)
            <a id="btn-add-pengumuman" href="{{ route('guru.pengumuman.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-colors inline-flex">
                <span>+</span> Tambah Pengumuman
            </a>
            @endif
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Filter & Pencarian</h2>
        
        <div>
            <label for="search-input" class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
            <div class="flex gap-2">
                <input 
                    id="search-input"
                    type="text" 
                    placeholder="Cari judul atau deskripsi..." 
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                >
                <button id="btn-search" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari
                </button>
            </div>
        </div>
    </div>




    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div id="pengumuman-alert" class="hidden mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px]">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left px-4 py-4 font-semibold text-gray-700 text-sm">Judul</th>
                        <th class="text-left px-4 py-4 font-semibold text-gray-700 text-sm">Kelas</th>
                        <th class="text-left px-4 py-4 font-semibold text-gray-700 text-sm">Tanggal Mulai</th>
                        <th class="text-left px-4 py-4 font-semibold text-gray-700 text-sm">Tanggal Selesai</th>
                        <th class="text-left px-4 py-4 font-semibold text-gray-700 text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody id="pengumuman-list">
                    <tr class="border-b border-gray-100">
                        <td class="px-4 py-6 text-gray-500" colspan="5">Memuat data pengumuman...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Total Info -->
        <div class="mt-6 text-sm text-gray-600">
            <p id="pengumuman-total">Total 0 Pengumuman</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const apiUrl = '/api/pengumuman';
            const currentKelasId = '{{ $kelasId }}';
            const countEl = document.getElementById('pengumuman-count');
            const totalEl = document.getElementById('pengumuman-total');
            const btnAdd = document.getElementById('btn-add-pengumuman');
            const btnSearch = document.getElementById('btn-search');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const defaultFetchOptions = {
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
            };
            const alertBox = document.getElementById('pengumuman-alert');
            const searchInput = document.getElementById('search-input');
            const tbody = document.getElementById('pengumuman-list');

            function showAlert(message, type = 'error') {
                alertBox.textContent = message;
                alertBox.classList.remove('hidden', 'border-red-200', 'bg-red-50', 'text-red-700', 'border-green-200', 'bg-green-50', 'text-green-700');
                if (type === 'success') {
                    alertBox.classList.add('border-green-200', 'bg-green-50', 'text-green-700');
                } else {
                    alertBox.classList.add('border-red-200', 'bg-red-50', 'text-red-700');
                }
            }

            function hideAlert() {
                if(alertBox) alertBox.classList.add('hidden');
            }

            function formatDateString(dateString) {
                if (!dateString) return '-';
                const date = new Date(dateString);
                if (Number.isNaN(date.getTime())) return dateString;
                return date.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
            }

            function renderRows(data) {
                if (!data || !data.length) {
                    tbody.innerHTML = '<tr class="border-b border-gray-100"><td class="px-4 py-6 text-gray-500" colspan="5">Belum ada pengumuman.</td></tr>';
                    if (countEl) countEl.textContent = '0';
                    if (totalEl) totalEl.textContent = 'Total 0 Pengumuman';
                    return;
                }

                tbody.innerHTML = data.map(item => {
                    const isOwnClass = item.kelas_id == currentKelasId;
                    
                    return `
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 text-gray-900 font-medium">
                                <div>${item.judul}</div>
                                ${item.file_url ? `<a href="${item.file_url}" class="text-blue-600 text-sm mt-1 inline-block hover:underline" target="_blank">${item.nama_file || 'Lampiran'}</a>` : ''}
                            </td>
                            <td class="px-4 py-4 text-gray-700">
                                ${(!item.kelas_nama || item.kelas_nama === 'Semua Kelas')
                                    ? '<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">Semua Kelas</span>' 
                                    : `<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700">${item.kelas_nama}</span>`
                                }
                                ${!isOwnClass ? '<span class="ml-2 inline-flex items-center text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">Publik</span>' : ''}
                            </td>
                            <td class="px-4 py-4 text-gray-600">${formatDateString(item.tanggal_mulai)}</td>
                            <td class="px-4 py-4 text-gray-600">${formatDateString(item.tanggal_selesai)}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="/guru/pengumuman/${item.id_pengumuman}" class="flex items-center justify-center w-9 h-9 rounded-xl bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors" title="Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                            <path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a3 3 0 1 1 5.37-1.83l2.6 2.6a.75.75 0 1 1-1.06 1.06l-2.6-2.6A3 3 0 0 1 7.5 15Z" />
                                            <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                        </svg>
                                    </a>
                                    ${isOwnClass ? `
                                    <a href="/guru/pengumuman/${item.id_pengumuman}/edit" class="flex items-center justify-center w-9 h-9 rounded-xl bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition-colors" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                        </svg>
                                    </a>
                                    <button type="button" data-action="delete" data-id="${item.id_pengumuman}" class="flex items-center justify-center w-9 h-9 rounded-xl bg-red-100 text-red-600 hover:bg-red-200 transition-colors" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m5 0V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" />
                                            <line x1="10" y1="11" x2="10" y2="17" />
                                            <line x1="14" y1="11" x2="14" y2="17" />
                                        </svg>
                                    </button>
                                    ` : ''}
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('');

                if (countEl) countEl.textContent = String(data.length);
                if (totalEl) totalEl.textContent = `Total ${data.length} Pengumuman`;
            }

            async function loadPengumuman(search = '') {
                try {
                    let url = apiUrl;
                    const params = new URLSearchParams();
                    if (search) params.append('search', search);
                    if (currentKelasId) params.append('kelas_id', currentKelasId);
                    
                    if (params.toString()) {
                        url += '?' + params.toString();
                    }

                    const response = await fetch(url, {
                        ...defaultFetchOptions,
                    });
                    const payload = await response.json();

                    if (!payload.success) {
                        throw new Error('Gagal memuat pengumuman');
                    }

                    renderRows(payload.data);
                } catch (error) {
                    tbody.innerHTML = '<tr class="border-b border-gray-100"><td class="px-4 py-6 text-red-500" colspan="5">Tidak dapat memuat data pengumuman.</td></tr>';
                    showAlert(error.message || 'Terjadi kesalahan saat mengambil data');
                }
            }



            async function deletePengumuman(id) {
                const result = await Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Pengumuman yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                });

                if (!result.isConfirmed) {
                    return;
                }

                try {
                    const response = await fetch(`${apiUrl}/${id}`, {
                        ...defaultFetchOptions,
                        method: 'DELETE',
                    });

                    const payloadResponse = await response.json();

                    if (!response.ok || !payloadResponse.success) {
                        const message = payloadResponse.message || 'Gagal menghapus pengumuman';
                        throw new Error(message);
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: payloadResponse.message || 'Pengumuman berhasil dihapus',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#6366f1'
                    }).then(() => {
                        loadPengumuman(searchInput.value.trim());
                    });
                } catch (error) {
                    showAlert(error.message || 'Terjadi kesalahan saat menghapus data');
                }
            }

            tbody.addEventListener('click', function (event) {
                const button = event.target.closest('button');
                if (!button) return;

                const action = button.dataset.action;
                const id = button.dataset.id;

                if (action === 'delete' && id) {
                    deletePengumuman(id);
                }
            });
            
            btnSearch.addEventListener('click', function () {
                loadPengumuman(searchInput.value.trim());
            });

            searchInput.addEventListener('keyup', function (event) {
                if (event.key === 'Enter') {
                    loadPengumuman(searchInput.value.trim());
                }
            });

            loadPengumuman();
        });
    </script>
@endsection
