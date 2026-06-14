@extends('layouts.index')

@section('title', 'Jam Pelajaran')

@section('content')
@include('components.navbar')

<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Data Jam Pelajaran</h1>
            <p class="text-gray-600 mt-1">Semua data jam pelajaran dalam sehari.</p>
        </div>
        <button id="btn-add-jam" class="inline-flex items-center gap-2 rounded-[16px] bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
            + Tambah Jam Pelajaran
        </button>
    </div>
</div>

<div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
            <thead class="bg-[#1E2567] text-white">
                <tr>
                    <th class="px-6 py-4 font-semibold">No</th>
                    <th class="px-6 py-4 font-semibold">Jam Mulai</th>
                    <th class="px-6 py-4 font-semibold">Jam Selesai</th>
                    <th class="px-6 py-4 font-semibold">Keterangan</th>
                    <th class="px-6 py-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody id="jam-table-body" class="divide-y divide-slate-200 bg-white text-slate-700">
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">Memuat data jam pelajaran...</td>
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
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="jam_mulai" class="block text-sm font-medium text-slate-700 mb-3">JAM MULAI</label>
                    <input id="jam_mulai" type="text" maxlength="5" placeholder="07.30" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-slate-400">Format: HH.MM (contoh: 07.30, 13.00)</p>
                </div>
                <div>
                    <label for="jam_selesai" class="block text-sm font-medium text-slate-700 mb-3">JAM SELESAI</label>
                    <input id="jam_selesai" type="text" maxlength="5" placeholder="08.30" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-slate-400">Format: HH.MM (contoh: 07.30, 13.00)</p>
                </div>
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium text-slate-700 mb-3">KETERANGAN (Opsional)</label>
                <input id="keterangan" type="text" placeholder="Contoh: Istirahat, Jam tambahan" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
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
        let jamData = [];

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
            jam_mulai: document.getElementById('jam_mulai'),
            jam_selesai: document.getElementById('jam_selesai'),
            keterangan: document.getElementById('keterangan')
        };

        let editingId = null;

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Fetch Jam Pelajaran
        async function fetchJamPelajaran() {
            try {
                tableBody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">Memuat data jam pelajaran...</td></tr>';
                
                const response = await fetch('/api/jam-pelajaran', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                const result = await response.json();
                
                if (result.success) {
                    jamData = result.data;
                    renderTable();
                } else {
                    showError('Gagal mengambil data jam pelajaran');
                }
            } catch (error) {
                console.error('Error:', error);
                tableBody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-red-500">Gagal memuat data. Silakan refresh halaman.</td></tr>';
            }
        }

        // Ubah HH:MM:SS (dari server) → HH.MM (tampilan Indonesia)
        function formatTimeIndo(timeString) {
            if (!timeString) return '-';
            const parts = timeString.split(':');
            if (parts.length >= 2) {
                return `${parts[0]}.${parts[1]}`;
            }
            return timeString;
        }

        // Validasi dan normalise input HH.MM → HH:MM (untuk server)
        function parseTimeInput(input) {
            const trimmed = input.trim().replace(',', '.');
            const match = trimmed.match(/^([01]?\d|2[0-3])\.([0-5]\d)$/);
            if (!match) return null;
            return `${match[1].padStart(2, '0')}:${match[2]}`;
        }

        // Auto-insert titik saat mengetik angka ke-3
        function autoFormatTimeInput(input) {
            input.addEventListener('input', function () {
                let val = this.value.replace(/[^0-9.]/g, '');
                if (val.length === 2 && !val.includes('.')) {
                    val = val + '.';
                }
                this.value = val;
            });
        }

        function renderTable() {
            if (!jamData.length) {
                tableBody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada jam pelajaran.</td></tr>';
                return;
            }

            tableBody.innerHTML = jamData.map((item, index) => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-700">${index + 1}</td>
                    <td class="px-6 py-4 text-slate-700">${formatTimeIndo(item.jam_mulai)}</td>
                    <td class="px-6 py-4 text-slate-700">${formatTimeIndo(item.jam_selesai)}</td>
                    <td class="px-6 py-4 text-slate-700">${item.keterangan || '-'}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button data-id="${item.id_jam}" data-action="edit" title="Edit" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-600 transition hover:bg-amber-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                </svg>
                            </button>
                            <button data-id="${item.id_jam}" data-action="delete" title="Hapus" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-red-100 text-red-600 transition hover:bg-red-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function openModal(mode, item = null) {
            editingId = item ? item.id_jam : null;
            modalTitle.textContent = mode === 'edit' ? 'Edit Jam Pelajaran' : 'Tambah Jam Pelajaran';
            modalDesc.textContent = mode === 'edit' ? 'Perbarui data jam pelajaran yang dipilih.' : 'Isi data jam pelajaran baru.';
            alertBox.classList.add('hidden');

            if (item) {
                fields.jam_mulai.value = formatTimeIndo(item.jam_mulai);
                fields.jam_selesai.value = formatTimeIndo(item.jam_selesai);
                fields.keterangan.value = item.keterangan || '';
            } else {
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

        async function saveJam() {
            const rawMulai = fields.jam_mulai.value;
            const rawSelesai = fields.jam_selesai.value;
            const keterangan = fields.keterangan.value.trim();

            if (!rawMulai || !rawSelesai) {
                showError('Masukkan jam mulai dan jam selesai.');
                return;
            }

            const jam_mulai = parseTimeInput(rawMulai);
            const jam_selesai = parseTimeInput(rawSelesai);

            if (!jam_mulai) {
                showError('Format jam mulai tidak valid. Gunakan format HH.MM, contoh: 07.30');
                return;
            }

            if (!jam_selesai) {
                showError('Format jam selesai tidak valid. Gunakan format HH.MM, contoh: 08.30');
                return;
            }

            if (jam_selesai <= jam_mulai) {
                showError('Jam selesai harus lebih besar dari jam mulai.');
                return;
            }

            const payload = {
                jam_mulai,
                jam_selesai,
                keterangan: keterangan || null
            };

            try {
                btnSave.disabled = true;
                btnSave.textContent = 'Menyimpan...';

                const url = editingId ? `/api/jam-pelajaran/${editingId}` : '/api/jam-pelajaran';
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
                    await fetchJamPelajaran();
                    closeModal();
                } else {
                    showError(result.message || 'Gagal menyimpan data');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Terjadi kesalahan saat menyimpan data');
            } finally {
                btnSave.disabled = false;
                btnSave.textContent = 'Simpan Slot Jam';
            }
        }

        async function deleteJam(id) {
            if (!confirm('Hapus jam pelajaran ini?')) {
                return;
            }

            try {
                const response = await fetch(`/api/jam-pelajaran/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await response.json();

                if (result.success) {
                    await fetchJamPelajaran();
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
            const item = jamData.find(j => j.id_jam === id);

            if (action === 'edit' && item) {
                openModal('edit', item);
            }
            if (action === 'delete') {
                deleteJam(id);
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

        // Auto-format input waktu
        autoFormatTimeInput(fields.jam_mulai);
        autoFormatTimeInput(fields.jam_selesai);

        // Initial load
        fetchJamPelajaran();
    });
</script>
@endsection
