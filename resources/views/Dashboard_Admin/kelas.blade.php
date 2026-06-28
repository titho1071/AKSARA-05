@extends('layouts.index')

@section('title', 'Kelas')

@section('content')
@include('components.navbar')

<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Data Kelas</h1>
            <p class="text-gray-600 mt-1">Kelola semua data kelas dan guru wali kelas.</p>
        </div>
        <button id="btn-add-kelas" class="inline-flex items-center gap-2 rounded-[16px] bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
            + Tambah Data Kelas
        </button>
    </div>
</div>

<div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
            <thead class="bg-[#1E2567] text-white">
                <tr>
                    <th class="px-6 py-4 font-semibold">No</th>
                    <th class="px-6 py-4 font-semibold">Nama Kelas</th>
                    <th class="px-6 py-4 font-semibold">Tingkat</th>
                    <th class="px-6 py-4 font-semibold">Tahun Pelajaran</th>
                    <th class="px-6 py-4 font-semibold">Guru</th>
                    <th class="px-6 py-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody id="kelas-table-body" class="divide-y divide-slate-200 bg-white text-slate-700">
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">Memuat data kelas...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="kelas-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6">
    <div class="w-full max-w-xl rounded-[2rem] bg-white p-6 shadow-2xl">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h2 id="modal-title" class="text-2xl font-bold text-slate-900">Tambah Data Kelas</h2>
                <p id="modal-desc" class="text-sm text-slate-500 mt-1">Isi detail kelas baru.</p>
            </div>
            <button id="modal-close" class="rounded-full bg-slate-100 p-3 text-slate-700 hover:bg-slate-200">×</button>
        </div>

        <div id="modal-alert" class="hidden rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 mb-4"></div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="nama_kelas" class="block text-sm font-medium text-slate-700 mb-2">Nama Kelas</label>
                <input id="nama_kelas" type="text" maxlength="100" placeholder="Contoh: VII A" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                <p id="nama-kelas-hint" class="hidden mt-1.5 text-xs text-red-600 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 shrink-0"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" /></svg>
                    <span id="nama-kelas-hint-text"></span>
                </p>
            </div>
            <div>
                <label for="tingkat" class="block text-sm font-medium text-slate-700 mb-2">Tingkat</label>
                <input id="tingkat" type="number" min="1" placeholder="Contoh: 7" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
            <div>
                <label for="tapel_id" class="block text-sm font-medium text-slate-700 mb-2">Tahun Pelajaran</label>
                <select id="tapel_id" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="">-- Pilih Tahun Pelajaran --</option>
                </select>
            </div>
            <div>
                <label for="guru_id" class="block text-sm font-medium text-slate-700 mb-2">Guru</label>
                <select id="guru_id" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="">-- Pilih Guru --</option>
                </select>
                <p id="guru-hint" class="hidden mt-1.5 text-xs text-orange-600 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 shrink-0"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" /></svg>
                    <span id="guru-hint-text"></span>
                </p>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <button id="modal-cancel" class="rounded-2xl border border-slate-300 bg-slate-100 px-6 py-3 text-sm font-medium text-slate-700 hover:bg-slate-200">Batal</button>
            <button id="modal-save" class="rounded-2xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">Simpan</button>
        </div>
    </div>
</div>

<script>
    (function () {
        let kelasData = [];
        let guruData = [];

        const tableBody = document.getElementById('kelas-table-body');
        const modal = document.getElementById('kelas-modal');
        const btnAdd = document.getElementById('btn-add-kelas');
        const btnClose = document.getElementById('modal-close');
        const btnCancel = document.getElementById('modal-cancel');
        const btnSave = document.getElementById('modal-save');
        const modalTitle = document.getElementById('modal-title');
        const modalDesc = document.getElementById('modal-desc');
        const alertBox = document.getElementById('modal-alert');

        const fields = {
            nama_kelas: document.getElementById('nama_kelas'),
            tingkat: document.getElementById('tingkat'),
            tapel_id: document.getElementById('tapel_id'),
            guru_id: document.getElementById('guru_id'),
        };

        const namaKelasHint = document.getElementById('nama-kelas-hint');
        const namaKelasHintText = document.getElementById('nama-kelas-hint-text');
        const guruHint = document.getElementById('guru-hint');
        const guruHintText = document.getElementById('guru-hint-text');

        let editingId = null;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        async function loadKelas() {
            try {
                const response = await fetch('/admin/kelas/data');
                const data = await response.json();
                if (response.ok) {
                    kelasData = data;
                    renderTable();
                } else {
                    tableBody.innerHTML = `<tr><td colspan="6" class="px-6 py-8 text-center text-red-500">${data.message || 'Gagal memuat data'}</td></tr>`;
                }
            } catch (error) {
                tableBody.innerHTML = `<tr><td colspan="6" class="px-6 py-8 text-center text-red-500">${error.message}</td></tr>`;
            }
        }

        async function loadGuruOptions() {
            try {
                const response = await fetch('/admin/kelas/guru-list');
                if (response.ok) {
                    guruData = await response.json();
                    const options = ['<option value="">-- Pilih Guru --</option>'];
                    guruData.forEach(guru => {
                        options.push(`<option value="${guru.id_guru}">${guru.nama}</option>`);
                    });
                    fields.guru_id.innerHTML = options.join('');
                }
            } catch (error) {
                console.error('Error loading guru list:', error);
            }
        }

        async function loadTapelOptions() {
            try {
                const response = await fetch('/admin/tahun-pelajaran/data');
                if (response.ok) {
                    const tapelData = await response.json();
                    const options = ['<option value="">-- Pilih Tahun Pelajaran --</option>'];
                    tapelData.forEach(tapel => {
                        options.push(`<option value="${tapel.id_tapel}">${tapel.tahun_pelajaran}</option>`);
                    });
                    fields.tapel_id.innerHTML = options.join('');
                }
            } catch (error) {
                console.error('Error loading tapel list:', error);
            }
        }

        function renderTable() {
            if (!kelasData.length) {
                tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada data kelas.</td></tr>';
                return;
            }

            tableBody.innerHTML = kelasData.map((item, index) => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-700">${index + 1}</td>
                    <td class="px-6 py-4 text-slate-700">${item.nama_kelas}</td>
                    <td class="px-6 py-4 text-slate-700">${item.tingkat}</td>
                    <td class="px-6 py-4 text-slate-700">${item.tapel_nama || '-'}</td>
                    <td class="px-6 py-4 text-slate-700">${item.guru_nama || '-'}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button data-id="${item.id_kelas}" data-action="edit" title="Edit" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-600 transition hover:bg-amber-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                </svg>
                            </button>
                            <button data-id="${item.id_kelas}" data-action="delete" title="Hapus" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-red-100 text-red-600 transition hover:bg-red-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function openModal(item = null) {
            editingId = item ? item.id_kelas : null;
            modalTitle.textContent = item ? 'Edit Data Kelas' : 'Tambah Data Kelas';
            modalDesc.textContent = item ? 'Perbarui data kelas yang dipilih.' : 'Isi detail kelas baru.';
            alertBox.classList.add('hidden');

            fields.nama_kelas.value = item?.nama_kelas || '';
            fields.tingkat.value = item?.tingkat || '';
            fields.tapel_id.value = item?.tapel_id || '';
            fields.guru_id.value = item?.guru_id || '';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        function showError(message) {
            alertBox.textContent = message;
            alertBox.classList.remove('hidden');
        }

        function checkDuplicateNama() {
            const val = fields.nama_kelas.value.trim().toLowerCase();
            if (!val) {
                namaKelasHint.classList.add('hidden');
                fields.nama_kelas.classList.remove('border-red-400');
                return false;
            }
            const duplicate = kelasData.find(k => k.nama_kelas.toLowerCase() === val && k.id_kelas !== editingId);
            if (duplicate) {
                namaKelasHintText.textContent = `Kelas "${duplicate.nama_kelas}" sudah ada!`;
                namaKelasHint.classList.remove('hidden');
                fields.nama_kelas.classList.add('border-red-400');
                return true;
            } else {
                namaKelasHint.classList.add('hidden');
                fields.nama_kelas.classList.remove('border-red-400');
                return false;
            }
        }

        function checkDuplicateGuru() {
            const guruId = fields.guru_id.value;
            if (!guruId) {
                guruHint.classList.add('hidden');
                fields.guru_id.classList.remove('border-orange-400');
                return false;
            }
            const guruIdNum = Number(guruId);
            const conflict = kelasData.find(k => k.guru_id && Number(k.guru_id) === guruIdNum && k.id_kelas !== editingId);
            if (conflict) {
                const namaGuru = fields.guru_id.options[fields.guru_id.selectedIndex]?.text || 'Guru ini';
                guruHintText.textContent = `${namaGuru} sudah menjadi wali kelas "${conflict.nama_kelas}"!`;
                guruHint.classList.remove('hidden');
                fields.guru_id.classList.add('border-orange-400');
                return true;
            } else {
                guruHint.classList.add('hidden');
                fields.guru_id.classList.remove('border-orange-400');
                return false;
            }
        }

        async function saveKelas() {
            const nama_kelas = fields.nama_kelas.value.trim();
            const tingkat = fields.tingkat.value.trim();
            const tapel_id = fields.tapel_id.value.trim();
            const guru_id = fields.guru_id.value.trim();

            if (!nama_kelas) { showError('Masukkan nama kelas.'); return; }
            if (checkDuplicateNama()) { showError(`Kelas "${nama_kelas}" sudah ada.`); return; }
            if (!tingkat || isNaN(tingkat) || Number(tingkat) <= 0) { showError('Masukkan tingkat kelas yang valid.'); return; }
            if (!tapel_id) { showError('Pilih tahun pelajaran.'); return; }
            if (checkDuplicateGuru()) {
                const conflict = kelasData.find(k => k.guru_id && Number(k.guru_id) === Number(guru_id) && k.id_kelas !== editingId);
                const namaGuru = fields.guru_id.options[fields.guru_id.selectedIndex]?.text || 'Guru ini';
                showError(`${namaGuru} sudah menjadi wali kelas "${conflict?.nama_kelas}".`);
                return;
            }

            const payload = {
                nama_kelas,
                tingkat: Number(tingkat),
                tapel_id,
                guru_id: guru_id || '',
                _token: csrfToken,
            };

            if (editingId) payload._method = 'PUT';

            try {
                const response = await fetch(
                    editingId ? `/admin/kelas/${editingId}` : '/admin/kelas',
                    {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'Accept': 'application/json'
                        },
                        body: new URLSearchParams(payload)
                    }
                );

                const data = await response.json();

                if (response.ok) {
                    closeModal();
                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: editingId ? 'Data kelas berhasil diperbarui!' : 'Data kelas berhasil ditambahkan!',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#6366f1'
                    });
                    loadKelas();
                } else {
                    showError(data.message || 'Gagal menyimpan data.');
                }
            } catch (error) {
                showError('Terjadi kesalahan: ' + error.message);
            }
        }

        tableBody.addEventListener('click', async function (event) {
            const button = event.target.closest('button');
            if (!button) return;

            const action = button.dataset.action;
            const id = button.dataset.id;
            const item = kelasData.find(k => k.id_kelas === Number(id));

            if (action === 'edit' && item) openModal(item);

            if (action === 'delete' && item) {
                const result = await Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Kelas yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                });

                if (!result.isConfirmed) return;

                try {
                    const response = await fetch(`/admin/kelas/${id}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({ _method: 'DELETE', _token: csrfToken })
                    });

                    if (response.ok) {
                        await Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: 'Data kelas berhasil dihapus.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#6366f1'
                        });
                        loadKelas();
                    } else {
                        const errorData = await response.json();
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: errorData.message || 'Gagal menghapus kelas.',
                            confirmButtonColor: '#6366f1'
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan: ' + error.message,
                        confirmButtonColor: '#6366f1'
                    });
                }
            }
        });

        btnAdd.addEventListener('click', () => openModal());
        btnClose.addEventListener('click', closeModal);
        btnCancel.addEventListener('click', closeModal);
        btnSave.addEventListener('click', (e) => { e.preventDefault(); saveKelas(); });
        fields.nama_kelas.addEventListener('input', checkDuplicateNama);
        fields.guru_id.addEventListener('change', checkDuplicateGuru);

        loadGuruOptions();
        loadTapelOptions();
        loadKelas();
    })();
</script>
@endsection