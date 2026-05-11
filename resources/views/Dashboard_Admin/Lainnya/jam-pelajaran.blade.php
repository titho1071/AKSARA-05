@extends('layouts.index')

@section('title', 'Jam Pelajaran')

@section('content')
@include('components.navbar')

<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Data Jam Pelajaran</h1>
            <p class="text-gray-500 mt-2">Semua data jam pelajaran dalam sehari.</p>
        </div>
        <button id="btn-add-jam" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-colors">
            <span class="text-xl">+</span>
            Tambah Jam Pelajaran
        </button>
    </div>
</div>

<div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
            <thead class="bg-slate-900 text-white">
                <tr>
                    <th class="px-6 py-4 font-semibold">No</th>
                    <th class="px-6 py-4 font-semibold">Jam Mulai</th>
                    <th class="px-6 py-4 font-semibold">Jam Selesai</th>
                    <th class="px-6 py-4 font-semibold">Tipe</th>
                    <th class="px-6 py-4 font-semibold">Keterangan</th>
                    <th class="px-6 py-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody id="jam-table-body" class="divide-y divide-slate-200 bg-white text-slate-700">
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">Memuat data jam pelajaran...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Jam Pelajaran -->
<div id="jam-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6">
    <div class="w-full max-w-lg rounded-[2rem] bg-white p-8 shadow-2xl">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h2 id="modal-title" class="text-2xl font-bold text-slate-900">Tambah Jam Pelajaran</h2>
                <p id="modal-desc" class="text-sm text-slate-500 mt-1">Isi data jam pelajaran baru.</p>
            </div>
            <button id="modal-close" class="rounded-full bg-slate-100 p-3 text-slate-700 hover:bg-slate-200 transition">×</button>
        </div>

        <div id="modal-alert" class="hidden rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 mb-6"></div>

        <div class="space-y-6">
            <div>
                <label for="tipe_slot" class="block text-sm font-medium text-slate-700 mb-3">TIPE SLOT</label>
                <select id="tipe_slot" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="">-- Pilih Tipe --</option>
                    <option value="Jam Pelajaran">Jam Pelajaran</option>
                    <option value="Istirahat">Istirahat</option>
                    <option value="Upacara">Upacara</option>
                </select>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="jam_mulai" class="block text-sm font-medium text-slate-700 mb-3">JAM MULAI</label>
                    <input id="jam_mulai" type="time" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label for="jam_selesai" class="block text-sm font-medium text-slate-700 mb-3">JAM SELESAI</label>
                    <input id="jam_selesai" type="time" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium text-slate-700 mb-3">KETERANGAN (Opsional)</label>
                <input id="keterangan" type="text" placeholder="Contoh: Jam tambahan, Ekskul" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <button id="modal-cancel" class="rounded-2xl border border-slate-300 bg-slate-100 px-6 py-3 text-sm font-medium text-slate-700 hover:bg-slate-200 transition">Batal</button>
            <button id="modal-save" class="rounded-2xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">Simpan Slot Jam</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jamData = [
            { id: 1, jam_mulai: '07:00', jam_selesai: '07:45', tipe: 'Jam Pelajaran', keterangan: '-' }
        ];

        const tableBody = document.getElementById('jam-table-body');
        const modal = document.getElementById('jam-modal');
        const btnAdd = document.getElementById('btn-add-jam');
        const btnClose = document.getElementById('modal-close');
        const btnCancel = document.getElementById('modal-cancel');
        const btnSave = document.getElementById('modal-save');
        const modalTitle = document.getElementById('modal-title');
        const modalDesc = document.getElementById('modal-desc');
        const alertBox = document.getElementById('modal-alert');

        const fields = {
            tipe_slot: document.getElementById('tipe_slot'),
            jam_mulai: document.getElementById('jam_mulai'),
            jam_selesai: document.getElementById('jam_selesai'),
            keterangan: document.getElementById('keterangan')
        };

        let editingId = null;

        function renderTable() {
            if (!jamData.length) {
                tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada jam pelajaran.</td></tr>';
                return;
            }

            tableBody.innerHTML = jamData.map((item, index) => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-700">${index + 1}</td>
                    <td class="px-6 py-4 text-slate-700">${item.jam_mulai}</td>
                    <td class="px-6 py-4 text-slate-700">${item.jam_selesai}</td>
                    <td class="px-6 py-4 text-slate-700">${item.tipe}</td>
                    <td class="px-6 py-4 text-slate-700">${item.keterangan}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button data-id="${item.id}" data-action="edit" title="Edit" class="rounded-lg p-2 text-blue-600 hover:bg-blue-100">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                    <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                </svg>
                            </button>
                            <button data-id="${item.id}" data-action="delete" title="Hapus" class="rounded-lg p-2 text-red-600 hover:bg-red-100">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                    <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function openModal(mode, item = null) {
            editingId = item ? item.id : null;
            modalTitle.textContent = mode === 'edit' ? 'Edit Jam Pelajaran' : 'Tambah Jam Pelajaran';
            modalDesc.textContent = mode === 'edit' ? 'Perbarui data jam pelajaran yang dipilih.' : 'Isi data jam pelajaran baru.';
            alertBox.classList.add('hidden');

            if (item) {
                fields.tipe_slot.value = item.tipe;
                fields.jam_mulai.value = item.jam_mulai;
                fields.jam_selesai.value = item.jam_selesai;
                fields.keterangan.value = item.keterangan === '-' ? '' : item.keterangan;
            } else {
                fields.tipe_slot.value = '';
                fields.jam_mulai.value = '';
                fields.jam_selesai.value = '';
                fields.keterangan.value = '';
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

        function saveJam() {
            const tipe = fields.tipe_slot.value;
            const jam_mulai = fields.jam_mulai.value;
            const jam_selesai = fields.jam_selesai.value;
            const keterangan = fields.keterangan.value.trim() || '-';

            if (!tipe) {
                showError('Pilih tipe slot.');
                return;
            }

            if (!jam_mulai || !jam_selesai) {
                showError('Masukkan jam mulai dan jam selesai.');
                return;
            }

            if (jam_selesai <= jam_mulai) {
                showError('Jam selesai harus lebih besar dari jam mulai.');
                return;
            }

            const conflict = jamData.find(j => {
                if (j.id === editingId) return false;
                const jStart = j.jam_mulai;
                const jEnd = j.jam_selesai;
                return (jam_mulai < jEnd && jam_selesai > jStart);
            });

            if (conflict) {
                showError('Jam ini sudah terisi dengan jam pelajaran lain.');
                return;
            }

            if (editingId) {
                const index = jamData.findIndex(item => item.id === editingId);
                if (index !== -1) {
                    jamData[index].tipe = tipe;
                    jamData[index].jam_mulai = jam_mulai;
                    jamData[index].jam_selesai = jam_selesai;
                    jamData[index].keterangan = keterangan;
                }
            } else {
                const nextId = jamData.length ? Math.max(...jamData.map(item => item.id)) + 1 : 1;
                jamData.push({ id: nextId, tipe, jam_mulai, jam_selesai, keterangan });
            }

            jamData.sort((a, b) => a.jam_mulai.localeCompare(b.jam_mulai));
            renderTable();
            closeModal();
        }

        tableBody.addEventListener('click', function (event) {
            const button = event.target.closest('button');
            if (!button) return;

            const action = button.dataset.action;
            const id = Number(button.dataset.id);
            const item = jamData.find(j => j.id === id);

            if (action === 'edit' && item) {
                openModal('edit', item);
            }
            if (action === 'delete' && item) {
                if (confirm(`Hapus jam ${item.jam_mulai} - ${item.jam_selesai}?`)) {
                    const index = jamData.findIndex(j => j.id === id);
                    if (index !== -1) {
                        jamData.splice(index, 1);
                        renderTable();
                    }
                }
            }
        });

        btnAdd.addEventListener('click', function () {
            openModal('create');
        });
        btnClose.addEventListener('click', closeModal);
        btnCancel.addEventListener('click', closeModal);
        btnSave.addEventListener('click', function (event) {
            event.preventDefault();
            saveJam();
        });

        renderTable();
    });
</script>
@endsection
