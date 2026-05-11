@extends('layouts.index')

@section('title', 'Kelas')

@section('content')
@include('components.navbar')

<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Data Kelas</h1>
            <p class="text-gray-500 mt-2">Kelola semua data kelas dan guru wali kelas.</p>
        </div>
        <button id="btn-add-kelas" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-colors">
            <span class="text-xl">+</span>
            Tambah Data Kelas
        </button>
    </div>
</div>

<div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
            <thead class="bg-slate-900 text-white">
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

        <div id="modal-alert" class="hidden rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="nama_kelas" class="block text-sm font-medium text-slate-700 mb-2">Nama Kelas</label>
                <input id="nama_kelas" type="text" maxlength="100" placeholder="Contoh: VII A" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
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
            <div class="md:col-span-2">
                <label for="guru_id" class="block text-sm font-medium text-slate-700 mb-2">Guru</label>
                <select id="guru_id" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="">-- Pilih Guru --</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="inline-flex items-center gap-3 text-sm text-slate-600">
                    <input id="kelas-confirm" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span>Saya yakin sudah mengisi dengan benar</span>
                </label>
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
            confirm: document.getElementById('kelas-confirm')
        };

        let editingId = null;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        async function loadKelas() {
    try {

        const response = await fetch('/admin/kelas/data');

        const data = await response.json();

        console.log(data);

        if (response.ok) {

            kelasData = data;
            renderTable();

        } else {

            console.error(data);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-red-500">
                        ${data.message || 'Gagal memuat data'}
                    </td>
                </tr>
            `;
        }

    } catch (error) {

        console.error(error);

        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-red-500">
                    ${error.message}
                </td>
            </tr>
        `;
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
                } else {
                    console.error('Failed to load guru list');
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
                } else {
                    console.error('Failed to load tapel list');
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
                            <button data-id="${item.id_kelas}" data-action="edit" title="Edit" class="rounded-lg p-2 text-blue-600 hover:bg-blue-100">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                    <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                </svg>
                            </button>
                            <button data-id="${item.id_kelas}" data-action="delete" title="Hapus" class="rounded-lg p-2 text-red-600 hover:bg-red-100">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                    <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 0 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

function openModal(item = null) {
    editingId = item ? item.id_kelas : null;

    modalTitle.textContent = item
        ? 'Edit Data Kelas'
        : 'Tambah Data Kelas';

    modalDesc.textContent = item
        ? 'Perbarui data kelas yang dipilih.'
        : 'Isi detail kelas baru.';

    alertBox.classList.add('hidden');

    if (item) {
        fields.nama_kelas.value = item.nama_kelas || '';
        fields.tingkat.value = item.tingkat || '';
        fields.tapel_id.value = item.tapel_id || '';
        fields.guru_id.value = item.guru_id || '';
        fields.confirm.checked = false;
    } else {
        fields.nama_kelas.value = '';
        fields.tingkat.value = '';
        fields.tapel_id.value = '';
        fields.guru_id.value = '';
        fields.confirm.checked = false;
    }

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

        async function saveKelas() {
    const nama_kelas = fields.nama_kelas.value.trim();
    const tingkat = fields.tingkat.value.trim();
    const tapel_id = fields.tapel_id.value.trim();
    const guru_id = fields.guru_id.value.trim();
    const confirmed = fields.confirm.checked;

    if (!nama_kelas) {
        showError('Masukkan nama kelas.');
        return;
    }

    if (!tingkat || isNaN(tingkat) || Number(tingkat) <= 0) {
        showError('Masukkan tingkat kelas yang valid.');
        return;
    }

    if (!tapel_id) {
        showError('Pilih tahun pelajaran.');
        return;
    }

    if (!confirmed) {
        showError('Centang konfirmasi sebelum menyimpan.');
        return;
    }

    const payload = {
        nama_kelas,
        tingkat: Number(tingkat),
        tapel_id: tapel_id,
        guru_id: guru_id || '',
        _token: csrfToken,
    };

    if (editingId) {
        payload._method = 'PUT';
    }

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
            loadKelas();
            closeModal();
        } else {
            console.log(data);
            showError(data.message || 'Gagal menyimpan data.');
        }
    } catch (error) {
        console.log(error);
        showError('Terjadi kesalahan: ' + error.message);
    }
}

        tableBody.addEventListener('click', async function (event) {
            const button = event.target.closest('button');
            if (!button) return;

            const action = button.dataset.action;
            const id = button.dataset.id;
            const item = kelasData.find(k => k.id_kelas === Number(id));

            if (action === 'edit' && item) {
                openModal(item);
            }

            if (action === 'delete' && item) {
                if (confirm('Apakah Anda yakin ingin menghapus kelas ini?')) {
                    try {
                        const response = await fetch(`/admin/kelas/${id}`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: new URLSearchParams({ _method: 'DELETE', _token: csrfToken })
                        });
                        if (response.ok) {
                            loadKelas();
                        } else {
                            const errorData = await response.json();
                            alert(errorData.message || 'Gagal menghapus kelas.');
                        }
                    } catch (error) {
                        alert('Terjadi kesalahan: ' + error.message);
                    }
                }
            }
        });

        btnAdd.addEventListener('click', function () {
            openModal();
        });
        btnClose.addEventListener('click', closeModal);
        btnCancel.addEventListener('click', closeModal);
        btnSave.addEventListener('click', function (event) {
            event.preventDefault();
            saveKelas();
        });

        loadGuruOptions();
        loadTapelOptions();
        loadKelas();
    })();
</script>
@endsection
