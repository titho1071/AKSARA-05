@extends('layouts.index')

@section('title', 'Tahun Pelajaran')

@section('content')
@include('components.navbar')

<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Data Tahun Pelajaran</h1>
            <p class="text-gray-600 mt-1">Semua data tahun pelajaran untuk periode aktif.</p>
        </div>
        <button id="btn-add-tapel" class="inline-flex items-center gap-2 rounded-[16px] bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
            + Tambah Data Tahun Pelajaran
        </button>
    </div>
</div>

<div class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-[#1E2567] text-white">
                <tr>
                    <th class="px-4 py-4 text-left font-semibold">No</th>
                    <th class="px-4 py-4 text-left font-semibold">Tahun Pelajaran</th>
                    <th class="px-4 py-4 text-left font-semibold">Semester</th>
                    <th class="px-4 py-4 text-left font-semibold">Status</th>
                    <th class="px-4 py-4 text-left font-semibold">Jumlah Kelas</th>
                    <th class="px-4 py-4 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody id="tapel-table-body" class="divide-y divide-slate-200 bg-slate-50">
                <tr class="bg-white">
                    <td colspan="5" class="px-4 py-6 text-center text-slate-500">Memuat data tahun pelajaran...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Tahun Pelajaran -->
<div id="tapel-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6">
    <div class="w-full max-w-xl rounded-[2rem] bg-white p-6 shadow-2xl">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div class="mb-6">
                <h2 id="modal-title" class="text-2xl font-bold text-slate-900">
                    Tambah Data Tapel
                </h2>
                <p id="modal-desc" class="text-sm text-slate-500 mt-1">
                    Isi tahun pelajaran dan semester baru.
                </p>
            </div>
        </div>

        <div id="modal-alert" class="hidden rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="tahun_awal" class="block text-sm font-medium text-slate-700 mb-2">Tahun Pelajaran</label>
                <div class="flex items-center gap-3">
                    <input id="tahun_awal" type="text" maxlength="4" placeholder="20xx" class="w-1/2 rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <span class="text-slate-500">/</span>
                    <input id="tahun_akhir" type="text" maxlength="4" placeholder="20xx" class="w-1/2 rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label for="semester" class="block text-sm font-medium text-slate-700 mb-2">Semester</label>
                <select id="semester" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="">-- Pilih Semester --</option>
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="inline-flex items-center gap-3 text-sm text-slate-600">
                    <input
                        id="is_active"
                        type="checkbox"
                        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span>Jadikan sebagai Tahun Pelajaran Aktif</span>
                </label>
            </div>
            <div class="md:col-span-2">
                <label class="inline-flex items-center gap-3 text-sm text-slate-600">
                    <input id="tapel-confirm" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
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
        let tapelData = [];

        const tableBody = document.getElementById('tapel-table-body');
        const countEl = null;
        const modal = document.getElementById('tapel-modal');
        const btnAdd = document.getElementById('btn-add-tapel');
        const btnCancel = document.getElementById('modal-cancel');
        const btnSave = document.getElementById('modal-save');
        const modalTitle = document.getElementById('modal-title');
        const modalDesc = document.getElementById('modal-desc');
        const alertBox = document.getElementById('modal-alert');

        const fields = {
            tahun_awal: document.getElementById('tahun_awal'),
            tahun_akhir: document.getElementById('tahun_akhir'),
            semester: document.getElementById('semester'),
            is_active: document.getElementById('is_active'),
            confirm: document.getElementById('tapel-confirm')
        };

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        async function loadTapelData() {
            try {
                const response = await fetch('/admin/tahun-pelajaran/data');
                if (response.ok) {
                    tapelData = await response.json();
                    renderTable();
                } else {
                    console.error('Failed to load data');
                }
            } catch (error) {
                console.error('Error loading data:', error);
            }
        }

        function renderTable() {
            if (!tapelData.length) {
                tableBody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada tahun pelajaran.</td></tr>';
                return;
            }

            tableBody.innerHTML = tapelData.map((item, index) => `
                <tr class="odd:bg-slate-50 even:bg-white">
                    <td class="px-4 py-4 text-slate-600">${index + 1}</td>
                    <td class="px-4 py-4 font-semibold text-slate-900">
                        ${item.tahun_pelajaran}
                    </td>

                    <td class="px-4 py-4 text-slate-700">
                        ${item.semester}
                    </td>

                    <td class="px-4 py-4">
                        ${
                            item.is_active
                            ? `<span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                    Aktif
                            </span>`
                            : `<span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                    Tidak Aktif
                            </span>`
                        }
                    </td>

                    <td class="px-4 py-4 text-slate-700">
                        ${item.jumlah_kelas || 0}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            ${!item.is_active ? `
                            <button
                                data-id="${item.id_tapel}"
                                data-action="activate"
                                class="inline-flex items-center rounded-xl bg-emerald-100 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-200">
                                Jadikan Aktif
                            </button>
                            ` : ''}

                             <button
                                data-id="${item.id_tapel}"
                                data-action="delete"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-red-100 text-red-600 transition hover:bg-red-200"
                                title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function openModal() {
            modalTitle.textContent = 'Tambah Data Tapel';
            modalDesc.textContent = 'Isi detail tahun pelajaran baru.';
            alertBox.classList.add('hidden');

            fields.tahun_awal.value = '';
            fields.tahun_akhir.value = '';
            fields.semester.value = '';
            fields.is_active.checked = false;
            fields.confirm.checked = false;

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

        async function saveTapel() {
            const tahun_awal = fields.tahun_awal.value.trim();
            const tahun_akhir = fields.tahun_akhir.value.trim();
            const semester = fields.semester.value;
            const is_active = fields.is_active.checked ? 1 : 0;
            const confirmed = fields.confirm.checked;

            if (!tahun_awal.match(/^20\d{2}$/) || !tahun_akhir.match(/^20\d{2}$/)) {
                showError('Masukkan tahun pelajaran yang valid, contoh 2024 / 2025.');
                return;
            }

            if (!semester) {
                showError('Pilih semester.');
                return;
            }

            if (!confirmed) {
                showError('Centang konfirmasi sebelum menyimpan.');
                return;
            }

            const tahun_pelajaran = `${tahun_awal}/${tahun_akhir}-${semester}`;

            const data = {
                tahun_pelajaran,
                semester,
                is_active,
                _token: csrfToken
            };

            try {
                const url = '/admin/tahun-pelajaran';
                const method = 'POST';
                const bodyData = data;
                const response = await fetch(url, {
                    method,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams(bodyData)
                });

                if (response.ok) {
                    loadTapelData();
                    closeModal();
                } else {
                    const error = await response.text();
                    showError(error || 'Gagal menyimpan data');
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
            const item = tapelData.find(t => t.id_tapel == id);

            // Set aktif
            if (action === 'activate') {

                if (!confirm('Jadikan tahun pelajaran ini sebagai yang aktif?')) {
                    return;
                }

                try {
                    const response = await fetch(`/admin/tahun-pelajaran/${id}/aktif`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        loadTapelData();
                    } else {
                        const errorData = await response.json();
                        alert(errorData.message || 'Gagal mengubah status aktif');
                    }

                } catch (error) {
                    alert('Terjadi kesalahan: ' + error.message);
                }

                return;
            }

            // Hapus
            if (action === 'delete' && item) {
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    try {
                        const response = await fetch(`/admin/tahun-pelajaran/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            loadTapelData();
                        } else {
                            const errorData = await response.json();
                            alert(errorData.message || 'Gagal menghapus data');
                        }

                    } catch (error) {
                        alert('Terjadi kesalahan: ' + error.message);
                    }
                }
            }
        });

        btnAdd.addEventListener('click', function () {
            openModal('create');
        });
        btnCancel.addEventListener('click', closeModal);
        btnSave.addEventListener('click', function (event) {
            event.preventDefault();
            saveTapel();
        });

        loadTapelData();
    })();
</script>
@endsection
