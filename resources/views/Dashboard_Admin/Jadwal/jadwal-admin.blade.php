@extends('layouts.index')

@section('title', 'Jadwal')

@section('content')
@include('components.navbar')
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Jadwal Pelajaran</h1>
                <p class="text-gray-600 mt-1">Kelola Jadwal Pelajaran untuk seluruh kelas</p>
            </div>
            <button id="btn-add-schedule" class="inline-flex items-center gap-2 rounded-[16px] bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                + Tambah Jadwal Pelajaran
            </button>
        </div>
    </div>

    <!-- Statistics Card -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8 w-fit">
        <div class="flex items-center gap-4">
            <div class="bg-blue-100 rounded-2xl p-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="blue" class="h-6 w-6">
                    <path d="M7.5 3.75a.75.75 0 0 0-1.5 0V5.25H4.5A2.25 2.25 0 0 0 2.25 7.5v11.25A2.25 2.25 0 0 0 4.5 21h15a2.25 2.25 0 0 0 2.25-2.25V7.5A2.25 2.25 0 0 0 19.5 5.25h-1.5V3.75a.75.75 0 0 0-1.5 0V5.25H7.5V3.75ZM19.5 8.25v9.75H4.5V8.25h15ZM6 10.5a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H6.75A.75.75 0 0 1 6 10.5Z" />
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Jadwal</p>
                <p id="schedule-count" class="text-3xl font-bold text-gray-900">0</p>
            </div>
        </div>
    </div>

    <!-- Schedule Grid -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6 gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Jadwal Mingguan</h2>
                <p class="text-gray-500 text-sm">Lihat dan edit jadwal pelajaran per jam dan per kelas.</p>
            </div>
            <div class="text-sm text-gray-500">
                <span id="schedule-summary">Menampilkan semua kelas</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse text-left">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-700">Jam / Hari</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-700">Senin</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-700">Selasa</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-700">Rabu</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-700">Kamis</th>
                        <th class="border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-700">Jumat</th>
                    </tr>
                </thead>
                <tbody id="schedule-table-body">
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah/Edit Jadwal -->
    <div id="schedule-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6">
        <div class="w-full max-w-2xl rounded-3xl bg-white p-6 shadow-2xl">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h2 id="modal-title" class="text-2xl font-bold text-slate-900">Tambah Jadwal</h2>
                    <p id="modal-subtitle" class="text-sm text-gray-500 mt-1">Buat jadwal pelajaran baru untuk kelas dan jam yang dipilih.</p>
                </div>
                <button id="close-modal" class="rounded-lg border border-slate-200 bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">Batal</button>
            </div>

            <div id="modal-alert" class="hidden mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="hari">Hari</label>
                    <select id="hari" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="jam">Jam Ke-</label>
                    <select id="jam" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="1">Jam 1 (07:00 - 07:45)</option>
                        <option value="2">Jam 2 (07:45 - 08:30)</option>
                        <option value="3">Jam 3 (08:30 - 09:15)</option>
                        <option value="4">Jam 4 (09:30 - 10:15)</option>
                        <option value="5">Jam 5 (10:15 - 11:00)</option>
                        <option value="6">Jam 6 (11:00 - 11:45)</option>
                        <option value="7">Jam 7 (12:30 - 13:15)</option>
                        <option value="8">Jam 8 (13:15 - 14:00)</option>
                        <option value="9">Jam 9 (14:00 - 14:45)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="kelas">Kelas</label>
                    <select id="kelas" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="VII-A">VII-A</option>
                        <option value="VII-B">VII-B</option>
                        <option value="VIII-A">VIII-A</option>
                        <option value="VIII-B">VIII-B</option>
                        <option value="IX-A">IX-A</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="mapel">Mata Pelajaran</label>
                    <select id="mapel" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="Matematika">Matematika</option>
                        <option value="Bahasa Indonesia">Bahasa Indonesia</option>
                        <option value="Bahasa Inggris">Bahasa Inggris</option>
                        <option value="IPA">IPA</option>
                        <option value="IPS">IPS</option>
                        <option value="PPKN">PPKN</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="guru">Guru Pengajar</label>
                    <select id="guru" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="Budi Santoso">Budi Santoso</option>
                        <option value="Rina Hastuti, S.Pd">Rina Hastuti, S.Pd</option>
                        <option value="Sari Dewi">Sari Dewi</option>
                        <option value="Doni Kusuma">Doni Kusuma</option>
                    </select>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap gap-3 justify-end">
                <button id="modal-cancel" class="bg-slate-200 hover:bg-slate-300 text-slate-900 px-6 py-3 rounded-xl font-medium transition-colors">Batal</button>
                <button id="modal-save" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-colors">Simpan Jadwal</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const scheduleData = [
                { id: 1, hari: 'Senin', jam: '1', kelas: 'VII-A', mapel: 'Matematika', guru: 'Budi Santoso', waktu: '07:00 - 07:45' },
                { id: 2, hari: 'Selasa', jam: '2', kelas: 'VII-A', mapel: 'Bahasa Inggris', guru: 'Rina Hastuti, S.Pd', waktu: '07:45 - 08:30' },
                { id: 3, hari: 'Rabu', jam: '3', kelas: 'VII-A', mapel: 'IPS', guru: 'Doni Kusuma', waktu: '08:30 - 09:15' }
            ];

            const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
            const times = [
                { jam: '1', label: '07:00 - 07:45' },
                { jam: '2', label: '07:45 - 08:30' },
                { jam: '3', label: '08:30 - 09:15' },
                { jam: '4', label: '09:30 - 10:15' },
                { jam: '5', label: '10:15 - 11:00' },
                { jam: '6', label: '11:00 - 11:45' },
                { jam: '7', label: '12:30 - 13:15' },
                { jam: '8', label: '13:15 - 14:00' },
                { jam: '9', label: '14:00 - 14:45' }
            ];

            let currentEditId = null;

            const tableBody = document.getElementById('schedule-table-body');
            const countEl = document.getElementById('schedule-count');
            const summaryEl = document.getElementById('schedule-summary');
            const modal = document.getElementById('schedule-modal');
            const modalTitle = document.getElementById('modal-title');
            const modalSubtitle = document.getElementById('modal-subtitle');
            const alertBox = document.getElementById('modal-alert');
            const btnAdd = document.getElementById('btn-add-schedule');
            const btnClose = document.getElementById('close-modal');
            const btnCancel = document.getElementById('modal-cancel');
            const btnSave = document.getElementById('modal-save');

            const fields = {
                hari: document.getElementById('hari'),
                jam: document.getElementById('jam'),
                kelas: document.getElementById('kelas'),
                mapel: document.getElementById('mapel'),
                guru: document.getElementById('guru')
            };

            function renderSchedule() {
                const rows = times.map(time => {
                    const cells = days.map(day => {
                        const item = scheduleData.find(entry => entry.hari === day && entry.jam === time.jam);
                        if (!item) {
                            return `<td class="border border-gray-200 px-4 py-4 align-top text-sm text-gray-500">` +
                                `<div class="min-h-[90px] flex items-center justify-center rounded-2xl border border-dashed border-gray-300 bg-slate-50 text-xs text-gray-400">Kosong</div>` +
                                `</td>`;
                        }

                        return `<td class="border border-gray-200 px-4 py-4 align-top">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm shadow-sm">
                                <div class="font-semibold text-slate-900">${item.mapel}</div>
                                <div class="text-slate-600 text-xs mt-1">${item.kelas}</div>
                                <div class="text-slate-500 text-xs mt-1">${item.guru}</div>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <button data-id="${item.id}" data-action="edit" class="rounded-lg bg-amber-100 px-3 py-2 text-[11px] font-semibold text-amber-700 hover:bg-amber-200">Edit</button>
                                    <button data-id="${item.id}" data-action="delete" class="rounded-lg bg-red-100 px-3 py-2 text-[11px] font-semibold text-red-700 hover:bg-red-200">Hapus</button>
                                </div>
                            </div>
                        </td>`;
                    }).join('');

                    return `<tr class="hover:bg-slate-50 transition-colors">
                        <td class="border border-gray-200 px-4 py-4 align-top text-sm font-semibold text-gray-700">Jam ${time.jam}<br><span class="text-xs font-normal text-gray-500">${time.label}</span></td>
                        ${cells}
                    </tr>`;
                }).join('');

                tableBody.innerHTML = rows;
                countEl.textContent = String(scheduleData.length);
                summaryEl.textContent = `Menampilkan ${scheduleData.length} jadwal aktif`;
            }

            function openModal(mode, item = null) {
                currentEditId = item ? item.id : null;
                modalTitle.textContent = mode === 'edit' ? 'Edit Jadwal' : 'Tambah Jadwal';
                modalSubtitle.textContent = mode === 'edit' ? 'Perbarui data jadwal yang dipilih.' : 'Isi data jadwal baru untuk ditambahkan ke tabel.';
                alertBox.classList.add('hidden');

                if (item) {
                    fields.hari.value = item.hari;
                    fields.jam.value = item.jam;
                    fields.kelas.value = item.kelas;
                    fields.mapel.value = item.mapel;
                    fields.guru.value = item.guru;
                } else {
                    fields.hari.value = 'Senin';
                    fields.jam.value = '1';
                    fields.kelas.value = 'VII-A';
                    fields.mapel.value = 'Matematika';
                    fields.guru.value = 'Budi Santoso';
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            function showModalError(message) {
                alertBox.textContent = message;
                alertBox.classList.remove('hidden');
            }

            function saveSchedule() {
                const newEntry = {
                    hari: fields.hari.value,
                    jam: fields.jam.value,
                    kelas: fields.kelas.value,
                    mapel: fields.mapel.value,
                    guru: fields.guru.value,
                    waktu: times.find(t => t.jam === fields.jam.value).label
                };

                const conflict = scheduleData.find(entry => entry.hari === newEntry.hari && entry.jam === newEntry.jam && entry.id !== currentEditId);
                if (conflict) {
                    showModalError('Jadwal sudah terisi pada hari dan jam yang sama. Pilih waktu lain.');
                    return;
                }

                if (currentEditId) {
                    const index = scheduleData.findIndex(entry => entry.id === currentEditId);
                    if (index !== -1) {
                        scheduleData[index] = { ...scheduleData[index], ...newEntry };
                    }
                } else {
                    const nextId = scheduleData.length ? Math.max(...scheduleData.map(item => item.id)) + 1 : 1;
                    scheduleData.push({ id: nextId, ...newEntry });
                }

                renderSchedule();
                closeModal();
            }

            function deleteSchedule(id) {
                const index = scheduleData.findIndex(entry => entry.id === Number(id));
                if (index !== -1) {
                    scheduleData.splice(index, 1);
                    renderSchedule();
                }
            }

            tableBody.addEventListener('click', function (event) {
                const button = event.target.closest('button');
                if (!button) return;

                const action = button.dataset.action;
                const id = button.dataset.id;
                if (!action || !id) return;

                const item = scheduleData.find(entry => entry.id === Number(id));
                if (action === 'edit' && item) {
                    openModal('edit', item);
                }
                if (action === 'delete') {
                    deleteSchedule(id);
                }
            });

            btnAdd.addEventListener('click', function () {
                openModal('create');
            });
            btnClose.addEventListener('click', closeModal);
            btnCancel.addEventListener('click', closeModal);
            btnSave.addEventListener('click', function (event) {
                event.preventDefault();
                saveSchedule();
            });

            renderSchedule();
        });
    </script>
@endsection
