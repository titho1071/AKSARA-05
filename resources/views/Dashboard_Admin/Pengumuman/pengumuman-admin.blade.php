@extends('layouts.index')

@section('title', 'Pengumuman')

@section('content')

@include('components.navbar')
<!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Pengumuman</h1>
                <p class="text-gray-600 mt-1">Kelola Pengumuman</p>
            </div>
            <a id="btn-add-pengumuman" href="{{ route('admin.pengumuman.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-colors inline-flex">
                <span>+</span> Tambah Pengumuman
            </a>
        </div>
    </div>

    <!-- Statistics Card -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8 w-fit">
        <div class="flex items-center gap-4">
            <div class="bg-blue-100 rounded-2xl p-4">
                <span class="text-3xl">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="blue" class="size-6">
                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                    </svg>
                </span>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Pengumuman</p>
                <p id="pengumuman-count" class="text-3xl font-bold text-gray-900">0</p>
            </div>
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

    <!-- Form Tambah/Edit Pengumuman -->
    <div id="pengumuman-form" class="hidden bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
        <div class="flex items-center justify-between mb-6 gap-4">
            <div>
                <h2 id="form-title" class="text-xl font-bold text-gray-900">Tambah Data Pengumuman</h2>
                <p id="form-description" class="text-gray-600 mt-1">Isi form untuk menambahkan atau memperbarui pengumuman.</p>
            </div>
            <button id="btn-cancel" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium transition-colors">
                Batal
            </button>
        </div>

        <div id="pengumuman-alert" class="hidden mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>

        <div class="grid gap-6 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2" for="judul">Judul Pengumuman <span class="text-red-500">*</span></label>
                <input id="judul" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Tambahkan judul pengumuman...">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2" for="deskripsi">Deskripsi Pengumuman <span class="text-red-500">*</span></label>
                <textarea id="deskripsi" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Jelaskan detail pengumuman yang mau disampaikan..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" for="kelas_id">Kelas ID</label>
                <input id="kelas_id" type="number" min="1" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Masukkan Kelas ID...">
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="tanggal_mulai">Tanggal Mulai Pengumuman</label>
                    <input id="tanggal_mulai" type="date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="tanggal_selesai">Tanggal Selesai Pengumuman</label>
                    <input id="tanggal_selesai" type="date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2" for="file">Upload File Pengumuman</label>
                <input id="file" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200" accept=".jpg,.jpeg,.png,.svg,.pdf">
                <p class="mt-2 text-xs text-gray-500">JPG, JPEG, PNG, SVG, PDF — maksimal 10 MB.</p>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <button id="btn-save" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                Simpan Data
            </button>
            <button id="btn-cancel-bottom" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-medium transition-colors">
                Batal
            </button>
        </div>
    </div>

    <!-- Pengumuman Table -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div id="pengumuman-detail" class="hidden mb-6 rounded-2xl border border-blue-200 bg-blue-50 p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 id="detail-judul" class="text-lg font-semibold text-slate-900">Detail Pengumuman</h3>
                    <p id="detail-deskripsi" class="mt-2 text-sm text-slate-700"></p>
                    <p id="detail-meta" class="mt-3 text-sm text-slate-600"></p>
                </div>
                <button id="btn-detail-close" type="button" class="text-slate-500 hover:text-slate-900">Tutup</button>
            </div>
        </div>
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
            const countEl = document.getElementById('pengumuman-count');
            const totalEl = document.getElementById('pengumuman-total');
            const btnAdd = document.getElementById('btn-add-pengumuman');
            const btnSearch = document.getElementById('btn-search');
            const btnCancel = document.getElementById('btn-cancel');
            const btnCancelBottom = document.getElementById('btn-cancel-bottom');
            const btnSave = document.getElementById('btn-save');
            const btnDetailClose = document.getElementById('btn-detail-close');
            const formTitle = document.getElementById('form-title');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const defaultFetchOptions = {
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
            };
            const formDescription = document.getElementById('form-description');
            const formContainer = document.getElementById('pengumuman-form');
            const detailContainer = document.getElementById('pengumuman-detail');
            const detailJudul = document.getElementById('detail-judul');
            const detailDeskripsi = document.getElementById('detail-deskripsi');
            const detailMeta = document.getElementById('detail-meta');
            const alertBox = document.getElementById('pengumuman-alert');
            const searchInput = document.getElementById('search-input');
            const tbody = document.getElementById('pengumuman-list');

            const fields = {
                judul: document.getElementById('judul'),
                deskripsi: document.getElementById('deskripsi'),
                kelas_id: document.getElementById('kelas_id'),
                tanggal_mulai: document.getElementById('tanggal_mulai'),
                tanggal_selesai: document.getElementById('tanggal_selesai'),
                file: document.getElementById('file'),
            };

            let editingId = null;

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
                alertBox.classList.add('hidden');
            }

            function resetForm() {
                editingId = null;
                formTitle.textContent = 'Tambah Data Pengumuman';
                formDescription.textContent = 'Isi form untuk menambahkan atau memperbarui pengumuman.';
                fields.judul.value = '';
                fields.deskripsi.value = '';
                fields.kelas_id.value = '';
                fields.tanggal_mulai.value = '';
                fields.tanggal_selesai.value = '';
                fields.file.value = '';
                hideAlert();
            }

            function openForm() {
                formContainer.classList.remove('hidden');
            }

            function closeForm() {
                formContainer.classList.add('hidden');
                resetForm();
            }

            function showDetail(data) {
                detailJudul.textContent = data.judul || 'Detail Pengumuman';
                detailDeskripsi.textContent = data.deskripsi || '-';
                detailMeta.textContent = `Kelas: ${data.kelas_id || '-'} · Mulai: ${formatDateString(data.tanggal_mulai)} · Selesai: ${formatDateString(data.tanggal_selesai)}`;
                detailContainer.classList.remove('hidden');
            }

            function hideDetail() {
                detailContainer.classList.add('hidden');
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
                    countEl.textContent = '0';
                    totalEl.textContent = 'Total 0 Pengumuman';
                    return;
                }

                tbody.innerHTML = data.map(item => {
                    return `
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 text-gray-900 font-medium">
                                <div>${item.judul}</div>
                                ${item.file_url ? `<a href="${item.file_url}" class="text-blue-600 text-sm mt-1 inline-block hover:underline" target="_blank">Lampiran</a>` : ''}
                            </td>
                            <td class="px-4 py-4 text-gray-700">${item.kelas_nama || 'Semua Kelas'}</td>
                            <td class="px-4 py-4 text-gray-600">${formatDateString(item.tanggal_mulai)}</td>
                            <td class="px-4 py-4 text-gray-600">${formatDateString(item.tanggal_selesai)}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="/admin/pengumuman/${item.id_pengumuman}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </a>
                                    <a href="/admin/pengumuman/${item.id_pengumuman}/edit" class="text-yellow-500 hover:text-yellow-700 transition-colors" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                        </svg>
                                    </a>
                                    <button type="button" data-action="delete" data-id="${item.id_pengumuman}" class="text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m5 0V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" />
                                            <line x1="10" y1="11" x2="10" y2="17" />
                                            <line x1="14" y1="11" x2="14" y2="17" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('');

                countEl.textContent = String(data.length);
                totalEl.textContent = `Total ${data.length} Pengumuman`;
            }

            async function loadPengumuman(search = '') {
                try {
                    const url = search ? `${apiUrl}?search=${encodeURIComponent(search)}` : apiUrl;
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

            async function savePengumuman() {
                const payload = new FormData();
                payload.append('judul', fields.judul.value.trim());
                payload.append('deskripsi', fields.deskripsi.value.trim());
                payload.append('kelas_id', fields.kelas_id.value ? fields.kelas_id.value.trim() : '');
                payload.append('tanggal_mulai', fields.tanggal_mulai.value);
                payload.append('tanggal_selesai', fields.tanggal_selesai.value);

                if (fields.file.files.length) {
                    payload.append('file', fields.file.files[0]);
                }

                try {
                    const method = editingId ? 'POST' : 'POST';
                    if (editingId) {
                        payload.append('_method', 'PUT');
                    }

                    const response = await fetch(editingId ? `${apiUrl}/${editingId}` : apiUrl, {
                        ...defaultFetchOptions,
                        method,
                        body: payload,
                    });

                    const payloadResponse = await response.json();

                    if (!response.ok || !payloadResponse.success) {
                        const message = payloadResponse.message || 'Gagal menyimpan pengumuman';
                        throw new Error(message);
                    }

                    showAlert(payloadResponse.message || 'Data berhasil disimpan', 'success');
                    loadPengumuman(searchInput.value.trim());
                    closeForm();
                } catch (error) {
                    showAlert(error.message || 'Terjadi kesalahan saat menyimpan data');
                }
            }

            async function deletePengumuman(id) {
                if (!confirm('Yakin ingin menghapus pengumuman ini?')) {
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

                    showAlert(payloadResponse.message || 'Pengumuman berhasil dihapus', 'success');
                    loadPengumuman(searchInput.value.trim());
                } catch (error) {
                    showAlert(error.message || 'Terjadi kesalahan saat menghapus data');
                }
            }

            tbody.addEventListener('click', function (event) {
                const button = event.target.closest('button');
                if (!button) return;

                const action = button.dataset.action;
                const id = button.dataset.id;

                if (action === 'detail' && id) {
                    closeForm();
                    hideAlert();
                    fetch(`${apiUrl}/${id}`, {
                        ...defaultFetchOptions,
                    })
                        .then(response => response.json())
                        .then(payload => {
                            if (!payload.success) {
                                throw new Error('Gagal memuat detail pengumuman');
                            }
                            showDetail(payload.data);
                        })
                        .catch(error => showAlert(error.message || 'Tidak dapat memuat detail pengumuman'));
                }

                if (action === 'edit' && id) {
                    openForm();
                    editingId = id;
                    formTitle.textContent = 'Edit Pengumuman';
                    formDescription.textContent = 'Perbarui informasi pengumuman yang dipilih.';
                    hideAlert();
                    fetch(`${apiUrl}/${id}`, {
                        ...defaultFetchOptions,
                    })
                        .then(response => response.json())
                        .then(payload => {
                            if (!payload.success) {
                                throw new Error('Gagal memuat data pengumuman');
                            }
                            const data = payload.data;
                            fields.judul.value = data.judul || '';
                            fields.deskripsi.value = data.deskripsi || '';
                            fields.kelas_id.value = data.kelas_id || '';
                            fields.tanggal_mulai.value = data.tanggal_mulai || '';
                            fields.tanggal_selesai.value = data.tanggal_selesai || '';
                            fields.file.value = '';
                        })
                        .catch(error => showAlert(error.message || 'Tidak dapat memuat data pengumuman'));
                }

                if (action === 'delete' && id) {
                    deletePengumuman(id);
                }
            });

            if (btnAdd) {
                // Let the anchor link navigate to the create page.
            }

            btnCancel.addEventListener('click', closeForm);
            btnCancelBottom.addEventListener('click', closeForm);
            if (btnDetailClose) {
                btnDetailClose.addEventListener('click', function () {
                    hideDetail();
                });
            }
            btnSearch.addEventListener('click', function () {
                loadPengumuman(searchInput.value.trim());
            });
            btnSave.addEventListener('click', function (event) {
                event.preventDefault();
                savePengumuman();
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
