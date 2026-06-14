@extends('layouts.index')

@section('title', 'Mata Pelajaran')

@section('content')
@include('components.navbar')

<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Data Mata Pelajaran</h1>
            <p class="text-gray-600 mt-1">Semua data mata pelajaran sesuai kurikulum.</p>
        </div>
        <button id="btn-add-mapel" class="inline-flex items-center gap-2 rounded-[16px] bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
            + Tambah Data Mata Pelajaran
        </button>
    </div>
</div>

<div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
            <thead class="bg-[#1E2567] text-white">
                <tr>
                    <th class="px-6 py-4 font-semibold">No</th>
                    <th class="px-6 py-4 font-semibold">Mata Pelajaran</th>
                    <th class="px-6 py-4 font-semibold">Tahun Pelajaran</th>
                    <th class="px-6 py-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody id="mapel-table-body" class="divide-y divide-slate-200 bg-white text-slate-700">
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">Memuat data mata pelajaran...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Mata Pelajaran -->
<div id="mapel-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6">
    <div class="w-full max-w-lg rounded-[2rem] bg-white p-8 shadow-2xl">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h2 id="modal-title" class="text-2xl font-bold text-slate-900">Tambah Mata Pelajaran</h2>
                <p id="modal-desc" class="text-sm text-slate-500 mt-1">Isi nama mata pelajaran baru.</p>
            </div>
            <button id="modal-close" class="rounded-full bg-slate-100 p-3 text-slate-700 hover:bg-slate-200 transition">×</button>
        </div>

        <div id="modal-alert" class="hidden rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 mb-6"></div>

        <div class="space-y-6">
            <div>
                <label for="nama_mapel" class="block text-sm font-medium text-slate-700 mb-3">NAMA MATA PELAJARAN</label>
                <input id="nama_mapel" type="text" placeholder="Contoh: Matematika, Bahasa Indonesia" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
            <div>
                <label for="id_tapel" class="block text-sm font-medium text-slate-700 mb-3">TAHUN PELAJARAN</label>
                <select id="id_tapel" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="">-- Pilih Tahun Pelajaran --</option>
                </select>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <button id="modal-cancel" class="rounded-2xl border border-slate-300 bg-slate-100 px-6 py-3 text-sm font-medium text-slate-700 hover:bg-slate-200 transition">Batal</button>
            <button id="modal-save" class="rounded-2xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">Simpan</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let mapelData = [];
        let tahunPelajaranData = [];

        const tableBody = document.getElementById('mapel-table-body');
        const modal = document.getElementById('mapel-modal');
        const btnAdd = document.getElementById('btn-add-mapel');
        const btnClose = document.getElementById('modal-close');
        const btnCancel = document.getElementById('modal-cancel');
        const btnSave = document.getElementById('modal-save');
        const modalTitle = document.getElementById('modal-title');
        const modalDesc = document.getElementById('modal-desc');
        const alertBox = document.getElementById('modal-alert');
        const namaInput = document.getElementById('nama_mapel');
        const tapelSelect = document.getElementById('id_tapel');

        let editingId = null;

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Fetch Tahun Pelajaran
        async function fetchTahunPelajaran() {
            try {
                const response = await fetch('/api/tahun-pelajaran', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                const result = await response.json();
                if (Array.isArray(result)) {
                    tahunPelajaranData = result;
                    populateTapelDropdown();
                } else if (result.success) {
                    tahunPelajaranData = result.data;
                    populateTapelDropdown();
                }
            } catch (error) {
                console.error('Error fetching tahun pelajaran:', error);
            }
        }

        function populateTapelDropdown() {
            tapelSelect.innerHTML = '<option value="">-- Pilih Tahun Pelajaran --</option>' + 
                tahunPelajaranData.map(tp => {
                    const activeText = tp.is_active === 1 ? ' (Aktif)' : '';
                    return `<option value="${tp.id_tapel}">${tp.tahun_pelajaran} - Semester ${tp.semester}${activeText}</option>`;
                }).join('');
        }

        // Fetch Mata Pelajaran
        async function fetchMataPelajaran() {
            try {
                tableBody.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">Memuat data mata pelajaran...</td></tr>';
                
                const response = await fetch('/api/mata-pelajaran', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                const result = await response.json();
                
                if (result.success) {
                    mapelData = result.data;
                    renderTable();
                } else {
                    showError('Gagal mengambil data mata pelajaran');
                }
            } catch (error) {
                console.error('Error:', error);
                tableBody.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-red-500">Gagal memuat data. Silakan refresh halaman.</td></tr>';
            }
        }

        function renderTable() {
            if (!mapelData.length) {
                tableBody.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada mata pelajaran.</td></tr>';
                return;
            }

            tableBody.innerHTML = mapelData.map((item, index) => {
                const tapel = item.tahun_pelajaran || item.tahunPelajaran;
                const tapelText = tapel ? `${tapel.tahun_pelajaran} - Semester ${tapel.semester}` : '-';
                return `
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-700">${index + 1}</td>
                        <td class="px-6 py-4 text-slate-700">${item.nama_mapel}</td>
                        <td class="px-6 py-4 text-slate-700">${tapelText}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button data-id="${item.id_mapel}" data-action="edit" title="Edit" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-600 transition hover:bg-amber-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                    </svg>
                                </button>
                                <button data-id="${item.id_mapel}" data-action="delete" title="Hapus" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-red-100 text-red-600 transition hover:bg-red-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function openModal(mode, item = null) {
            editingId = item ? item.id_mapel : null;
            modalTitle.textContent = mode === 'edit' ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran';
            modalDesc.textContent = mode === 'edit' ? 'Perbarui nama dan tahun pelajaran yang dipilih.' : 'Isi nama dan pilih tahun pelajaran baru.';
            alertBox.classList.add('hidden');

            if (item) {
                namaInput.value = item.nama_mapel;
                tapelSelect.value = item.id_tapel || '';
            } else {
                namaInput.value = '';
                const activeTapel = tahunPelajaranData.find(tp => tp.is_active === 1);
                tapelSelect.value = activeTapel ? activeTapel.id_tapel : '';
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            namaInput.focus();
        }

        function closeModal() {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        function showError(message) {
            alertBox.textContent = message;
            alertBox.classList.remove('hidden');
        }

        async function saveMapel() {
            const nama = namaInput.value.trim();
            const tapelId = tapelSelect.value;

            if (!nama) {
                showError('Masukkan nama mata pelajaran.');
                return;
            }

            if (nama.length < 3) {
                showError('Nama mata pelajaran minimal 3 karakter.');
                return;
            }

            if (!tapelId) {
                showError('Pilih tahun pelajaran.');
                return;
            }

            const payload = {
                nama_mapel: nama,
                id_tapel: tapelId
            };

            try {
                btnSave.disabled = true;
                btnSave.textContent = 'Menyimpan...';

                const url = editingId ? `/api/mata-pelajaran/${editingId}` : '/api/mata-pelajaran';
                const method = editingId ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.success) {
                    await fetchMataPelajaran();
                    closeModal();
                } else {
                    showError(result.message || 'Gagal menyimpan data');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Terjadi kesalahan saat menyimpan data');
            } finally {
                btnSave.disabled = false;
                btnSave.textContent = 'Simpan';
            }
        }

        async function deleteMapel(id) {
            if (!confirm('Hapus mata pelajaran ini? Semua jadwal terkait akan ikut terhapus.')) {
                return;
            }

            try {
                const response = await fetch(`/api/mata-pelajaran/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await response.json();

                if (result.success) {
                    await fetchMataPelajaran();
                } else {
                    alert(result.message || 'Gagal menghapus data');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus data');
            }
        }

        tableBody.addEventListener('click', function (event) {
            const button = event.target.closest('button');
            if (!button) return;

            const action = button.dataset.action;
            const id = Number(button.dataset.id);
            const item = mapelData.find(m => m.id_mapel === id);

            if (action === 'edit' && item) {
                openModal('edit', item);
            }
            if (action === 'delete') {
                deleteMapel(id);
            }
        });

        btnAdd.addEventListener('click', function () {
            openModal('create');
        });
        btnClose.addEventListener('click', closeModal);
        btnCancel.addEventListener('click', closeModal);
        btnSave.addEventListener('click', function (event) {
            event.preventDefault();
            saveMapel();
        });
        namaInput.addEventListener('keypress', function (event) {
            if (event.key === 'Enter') {
                saveMapel();
            }
        });

        // Initial load
        fetchTahunPelajaran().then(() => fetchMataPelajaran());
    });
</script>
@endsection
