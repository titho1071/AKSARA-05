@extends('layouts.index')

@section('title', 'Jadwal')

@section('content')
@include('components.navbar')
    <!-- Header Section -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 py-2 pt-2">
            <div>
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900">Jadwal Pelajaran</h1>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Kelola Jadwal Pelajaran untuk seluruh kelas</p>
            </div>
            <div class="flex gap-3">
                <button id="btn-add-schedule" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-[16px] bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 min-h-[44px]">
                    + Tambah Jadwal Pelajaran
                </button>
            </div>
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
    <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-3">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-900">Jadwal Mingguan</h2>
                <p class="text-gray-500 text-xs sm:text-sm">Lihat dan edit jadwal pelajaran per jam dan per kelas.</p>
            </div>
            <div class="flex items-center gap-2">
                <label for="filter-kelas" class="text-sm font-medium text-gray-700 whitespace-nowrap">Kelas:</label>
                <select id="filter-kelas" class="flex-1 sm:flex-none rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 min-h-[40px]"></select>
            </div>
        </div>

        <div class="mb-3">
            <span id="schedule-summary" class="text-xs sm:text-sm text-gray-600">Pilih kelas untuk melihat jadwal</span>
        </div>

        <div class="overflow-x-auto -mx-4 sm:mx-0">
            <div class="inline-block min-w-full px-4 sm:px-0">
                <table class="min-w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="border border-gray-200 px-3 py-3 text-xs sm:text-sm font-semibold text-gray-700 whitespace-nowrap">Jam / Hari</th>
                            <th class="border border-gray-200 px-3 py-3 text-xs sm:text-sm font-semibold text-gray-700">Senin</th>
                            <th class="border border-gray-200 px-3 py-3 text-xs sm:text-sm font-semibold text-gray-700">Selasa</th>
                            <th class="border border-gray-200 px-3 py-3 text-xs sm:text-sm font-semibold text-gray-700">Rabu</th>
                            <th class="border border-gray-200 px-3 py-3 text-xs sm:text-sm font-semibold text-gray-700">Kamis</th>
                            <th class="border border-gray-200 px-3 py-3 text-xs sm:text-sm font-semibold text-gray-700">Jumat</th>
                        </tr>
                    </thead>
                    <tbody id="schedule-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Jadwal -->
    <div id="schedule-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-3 py-4 sm:px-6 sm:py-8">
        <div class="w-full max-w-full sm:max-w-2xl rounded-2xl sm:rounded-3xl bg-white p-4 sm:p-6 shadow-2xl overflow-y-auto max-h-[90vh]">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h2 id="modal-title" class="text-2xl font-bold text-slate-900">Tambah Jadwal</h2>
                    <p id="modal-subtitle" class="text-sm text-gray-500 mt-1">Buat jadwal pelajaran baru untuk kelas dan jam yang dipilih.</p>
                </div>
            </div>

            <div id="modal-alert" class="hidden mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="hari">Hari</label>
                    <select id="hari" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">-- Pilih Hari --</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="jam">Jam Pelajaran</label>
                    <select id="jam" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">-- Pilih Jam --</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="kelas">Kelas</label>
                    <select id="kelas" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">-- Pilih Kelas --</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="mapel">Mata Pelajaran</label>
                    <select id="mapel" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="guru">
                        Guru Pengajar
                        <span class="ml-1 text-xs font-normal text-gray-400">(Opsional)</span>
                    </label>
                    <select id="guru" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">-- Tidak Ada / Pilih Guru --</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="kegiatan">
                        Kegiatan
                        <span class="ml-1 text-xs font-normal text-gray-400">(Opsional — kosongkan jika tidak ada kegiatan)</span>
                    </label>
                    <select id="kegiatan" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">
                        <option value="">-- Tidak Ada Kegiatan --</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1.5">
                        Pilih kegiatan jika slot ini digunakan untuk kegiatan sekolah (upacara, dll).
                        Kegiatan dikelola oleh guru.
                    </p>
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
            let jadwalData         = [];
            let jadwalDataFiltered = {};
            let jamPelajaranData   = [];
            let kelasData          = [];
            let mataPelajaranData  = [];
            let kegiatanData       = [];
            let guruData           = [];
            let currentEditId      = null;
            let selectedKelasId    = null;

            const tableBody     = document.getElementById('schedule-table-body');
            const countEl       = document.getElementById('schedule-count');
            const summaryEl     = document.getElementById('schedule-summary');
            const modal         = document.getElementById('schedule-modal');
            const modalTitle    = document.getElementById('modal-title');
            const modalSubtitle = document.getElementById('modal-subtitle');
            const alertBox      = document.getElementById('modal-alert');
            const btnAdd        = document.getElementById('btn-add-schedule');
            const btnCancel     = document.getElementById('modal-cancel');
            const btnSave       = document.getElementById('modal-save');
            const filterKelas   = document.getElementById('filter-kelas');

            const fields = {
                hari:     document.getElementById('hari'),
                jam:      document.getElementById('jam'),
                kelas:    document.getElementById('kelas'),
                mapel:    document.getElementById('mapel'),
                kegiatan: document.getElementById('kegiatan'),
                guru:     document.getElementById('guru'),
            };

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

            // ── Fetch semua master data ─────────────────────────────
            async function fetchMasterData() {
                try {
                    const [jamRes, kelasRes, mapelRes, kegiatanRes, guruRes] = await Promise.all([
                        fetch('/api/jam-pelajaran',   { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } }),
                        fetch('/api/kelas',           { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } }),
                        fetch('/api/mata-pelajaran',  { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } }),
                        fetch('/api/kegiatan-jadwal', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } }),
                        fetch('/api/guru-jadwal',     { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } }),
                    ]);

                    const jamResult      = await jamRes.json();
                    const mapelResult    = await mapelRes.json();
                    const kegiatanResult = await kegiatanRes.json();
                    const guruResult     = await guruRes.json();
                    kelasData            = await kelasRes.json();

                    if (jamResult.success)      { jamPelajaranData  = jamResult.data;      populateJamDropdown(); }
                    if (mapelResult.success)    { mataPelajaranData = mapelResult.data;    populateMapelDropdown(); }
                    if (kegiatanResult.success) { kegiatanData      = kegiatanResult.data; populateKegiatanDropdown(); }
                    if (guruResult.success)     { guruData          = guruResult.data;     populateGuruDropdown(); }

                    populateKelasDropdown();
                    populateKelasFilter();
                } catch (err) {
                    console.error('Error fetching master data:', err);
                }
            }

            // ── Populate dropdowns ──────────────────────────────────
            function populateJamDropdown() {
                fields.jam.innerHTML = '<option value="">-- Pilih Jam --</option>';
                jamPelajaranData.forEach(jam => {
                    const o = document.createElement('option');
                    o.value = jam.id_jam;
                    o.textContent = `${jam.jam_mulai.substring(0,5)} - ${jam.jam_selesai.substring(0,5)}${jam.keterangan ? ' (' + jam.keterangan + ')' : ''}`;
                    fields.jam.appendChild(o);
                });
            }

            function populateKelasDropdown() {
                fields.kelas.innerHTML = '<option value="">-- Pilih Kelas --</option>';
                kelasData.forEach(k => {
                    const o = document.createElement('option');
                    o.value = k.id_kelas;
                    o.textContent = k.nama_kelas;
                    fields.kelas.appendChild(o);
                });
            }

            function populateKelasFilter() {
                filterKelas.innerHTML = '';
                kelasData.forEach(k => {
                    const o = document.createElement('option');
                    o.value = k.id_kelas;
                    o.textContent = k.nama_kelas;
                    filterKelas.appendChild(o);
                });
                if (kelasData.length > 0) {
                    selectedKelasId = kelasData[0].id_kelas.toString();
                    filterKelas.value = selectedKelasId;
                }
            }

            function populateMapelDropdown() {
                fields.mapel.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>';
                mataPelajaranData.forEach(m => {
                    const o = document.createElement('option');
                    o.value = m.id_mapel;
                    o.textContent = m.nama_mapel;
                    fields.mapel.appendChild(o);
                });
            }

            function populateGuruDropdown() {
                fields.guru.innerHTML = '<option value="">-- Tidak Ada / Pilih Guru --</option>';
                guruData.forEach(g => {
                    const o = document.createElement('option');
                    o.value = g.id_guru;
                    o.textContent = g.nama + (g.nip ? ' · ' + g.nip : '');
                    fields.guru.appendChild(o);
                });
            }

            function populateKegiatanDropdown() {
                fields.kegiatan.innerHTML = '<option value="">-- Tidak Ada Kegiatan --</option>';
                kegiatanData.forEach(kg => {
                    const o = document.createElement('option');
                    o.value = kg.id_kegiatan;
                    const tgl = new Date(kg.tanggal).toLocaleDateString('id-ID', {
                        day: 'numeric', month: 'short', year: 'numeric'
                    });
                    o.textContent = `${kg.judul} (${tgl})`;
                    fields.kegiatan.appendChild(o);
                });
            }

            // ── Fetch jadwal ────────────────────────────────────────
            async function fetchJadwal() {
                tableBody.innerHTML = `<tr><td colspan="6" class="border border-gray-200 px-4 py-8 text-center text-slate-500">Memuat jadwal...</td></tr>`;
                try {
                    const res    = await fetch('/api/jadwal-pelajaran/by-hari', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } });
                    const result = await res.json();
                    if (result.success) {
                        jadwalData = result.data;
                        applyFilter();
                    } else {
                        tableBody.innerHTML = `<tr><td colspan="6" class="border border-gray-200 px-4 py-8 text-center text-red-500">Gagal memuat jadwal</td></tr>`;
                    }
                } catch (err) {
                    console.error(err);
                    tableBody.innerHTML = `<tr><td colspan="6" class="border border-gray-200 px-4 py-8 text-center text-red-500">Gagal memuat data. Silakan refresh halaman.</td></tr>`;
                }
            }

            function applyFilter() {
                jadwalDataFiltered = {};
                if (selectedKelasId) {
                    for (const hari in jadwalData) {
                        jadwalDataFiltered[hari] = jadwalData[hari].filter(j => j.kelas_id === parseInt(selectedKelasId));
                    }
                }
                renderSchedule();
            }

            filterKelas.addEventListener('change', function () {
                selectedKelasId = this.value;
                applyFilter();
            });

            // ── Render tabel jadwal ─────────────────────────────────
            function renderSchedule() {
                if (!jamPelajaranData.length) {
                    tableBody.innerHTML = `<tr><td colspan="6" class="border border-gray-200 px-4 py-8 text-center text-yellow-600">Belum ada jam pelajaran.</td></tr>`;
                    return;
                }

                let totalSchedule = 0;

                const rows = jamPelajaranData.map(jam => {
                    const isIstirahat = jam.keterangan && jam.keterangan.toLowerCase().includes('istirahat');

                    if (isIstirahat) {
                        return `<tr class="bg-orange-50">
                            <td class="border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-700">
                                ${jam.jam_mulai.substring(0,5)} - ${jam.jam_selesai.substring(0,5)}
                            </td>
                            <td colspan="5" class="border border-gray-200 px-4 py-3 text-center">
                                <span class="inline-block rounded-lg bg-orange-100 px-4 py-2 text-sm font-semibold text-orange-700">
                                    ${jam.keterangan} : ${jam.jam_mulai.substring(0,5)} - ${jam.jam_selesai.substring(0,5)}
                                </span>
                            </td>
                        </tr>`;
                    }

                    const cells = days.map(day => {
                        const item = (jadwalDataFiltered[day] || []).find(j => j.jam_id === jam.id_jam);

                        if (!item) {
                            return `<td class="border border-gray-200 px-4 py-4 align-top">
                                <div class="min-h-[90px] flex items-center justify-center rounded-2xl border border-dashed border-gray-300 bg-slate-50 text-xs text-gray-400">Kosong</div>
                            </td>`;
                        }

                        totalSchedule++;
                        const mapelName    = item.mata_pelajaran ? item.mata_pelajaran.nama_mapel : '-';
                        const kelasName    = item.kelas ? item.kelas.nama_kelas : '-';
                        const kegiatanName = item.kegiatan ? item.kegiatan.judul : null;
                        const guruName     = item.guru ? item.guru.nama : null;
                        const displayName  = kegiatanName || mapelName;
                        const isKegiatan   = !!kegiatanName;

                        return `<td class="border border-gray-200 px-4 py-4 align-top">
                            <div class="rounded-2xl border ${isKegiatan ? 'border-violet-200 bg-violet-50' : 'border-slate-200 bg-slate-50'} p-4 text-sm shadow-sm">
                                <div class="font-semibold ${isKegiatan ? 'text-violet-900' : 'text-slate-900'}">${displayName}</div>
                                <div class="text-xs mt-1 ${isKegiatan ? 'text-violet-500' : 'text-slate-600'}">${isKegiatan ? 'Kegiatan' : kelasName}</div>
                                ${guruName && !isKegiatan ? `<div class="text-[11px] text-slate-400 mt-0.5">${guruName}</div>` : ''}
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <button data-id="${item.id_jadwal}" data-action="edit"   class="rounded-lg bg-amber-100 px-3 py-2 text-[11px] font-semibold text-amber-700 hover:bg-amber-200">Edit</button>
                                    <button data-id="${item.id_jadwal}" data-action="delete" class="rounded-lg bg-red-100   px-3 py-2 text-[11px] font-semibold text-red-700   hover:bg-red-200">Hapus</button>
                                </div>
                            </div>
                        </td>`;
                    }).join('');

                    return `<tr class="hover:bg-slate-50 transition-colors">
                        <td class="border border-gray-200 px-4 py-4 align-top text-sm font-semibold text-gray-700">
                            ${jam.jam_mulai.substring(0,5)} - ${jam.jam_selesai.substring(0,5)}
                            ${jam.keterangan ? '<br><span class="text-xs font-normal text-gray-500">' + jam.keterangan + '</span>' : ''}
                        </td>
                        ${cells}
                    </tr>`;
                }).join('');

                tableBody.innerHTML = rows;
                countEl.textContent = String(totalSchedule);

                if (selectedKelasId) {
                    const kelas = kelasData.find(k => k.id_kelas === parseInt(selectedKelasId));
                    summaryEl.textContent = `Menampilkan ${totalSchedule} jadwal untuk kelas ${kelas ? kelas.nama_kelas : ''}`;
                } else {
                    summaryEl.textContent = 'Pilih kelas untuk melihat jadwal';
                }
            }

            // ── Modal ───────────────────────────────────────────────
            function openModal(mode, item = null) {
                currentEditId = item ? item.id_jadwal : null;
                modalTitle.textContent    = mode === 'edit' ? 'Edit Jadwal' : 'Tambah Jadwal';
                modalSubtitle.textContent = mode === 'edit' ? 'Perbarui data jadwal yang dipilih.' : 'Buat jadwal pelajaran baru untuk kelas dan jam yang dipilih.';
                alertBox.classList.add('hidden');

                if (item) {
                    fields.hari.value     = item.hari;
                    fields.jam.value      = item.jam_id;
                    fields.kelas.value    = item.kelas_id || '';
                    fields.mapel.value    = item.id_mapel;
                    fields.kegiatan.value = item.kegiatan_id || '';
                    fields.guru.value     = item.id_guru || '';
                } else {
                    fields.hari.value     = '';
                    fields.jam.value      = '';
                    fields.kelas.value    = selectedKelasId || '';
                    fields.mapel.value    = '';
                    fields.kegiatan.value = '';
                    fields.guru.value     = '';
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            function showModalError(msg) {
                alertBox.textContent = msg;
                alertBox.classList.remove('hidden');
            }

            // ── Simpan jadwal ───────────────────────────────────────
            async function saveSchedule() {
                const hari     = fields.hari.value;
                const jam_id   = fields.jam.value;
                const kelas_id = fields.kelas.value;
                const id_mapel = fields.mapel.value;

                if (!hari)   { showModalError('Pilih hari terlebih dahulu.');          return; }
                if (!jam_id) { showModalError('Pilih jam pelajaran terlebih dahulu.'); return; }
                if (!id_mapel && !fields.kegiatan.value) {
                    showModalError('Pilih mata pelajaran atau kegiatan.');
                    return;
                }

                const payload = {
                    hari,
                    jam_id:      parseInt(jam_id),
                    kelas_id:    kelas_id ? parseInt(kelas_id) : null,
                    id_mapel:    parseInt(id_mapel),
                    kegiatan_id: fields.kegiatan.value ? parseInt(fields.kegiatan.value) : null,
                    id_guru:     fields.guru.value     ? parseInt(fields.guru.value)     : null,
                };

                try {
                    btnSave.disabled    = true;
                    btnSave.textContent = 'Menyimpan...';

                    const url    = currentEditId ? `/api/jadwal-pelajaran/${currentEditId}` : '/api/jadwal-pelajaran';
                    const method = currentEditId ? 'PUT' : 'POST';

                    const res    = await fetch(url, {
                        method,
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify(payload)
                    });
                    const result = await res.json();

                    if (result.success) {
                        closeModal();
                        await Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: currentEditId ? 'Jadwal berhasil diperbarui!' : 'Jadwal berhasil ditambahkan!',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#6366f1'
                        });
                        await fetchJadwal();
                    } else {
                        showModalError(result.errors
                            ? Object.values(result.errors).flat().join(', ')
                            : result.message || 'Gagal menyimpan jadwal');
                    }
                } catch (err) {
                    console.error(err);
                    showModalError('Terjadi kesalahan saat menyimpan data');
                } finally {
                    btnSave.disabled    = false;
                    btnSave.textContent = 'Simpan Jadwal';
                }
            }

            // ── Hapus jadwal ────────────────────────────────────────
            async function deleteSchedule(id) {
                const result = await Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Jadwal yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                });

                if (!result.isConfirmed) return;

                try {
                    const res    = await fetch(`/api/jadwal-pelajaran/${id}`, {
                        method: 'DELETE',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                    });
                    const data = await res.json();

                    if (data.success) {
                        await Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: 'Jadwal berhasil dihapus.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#6366f1'
                        });
                        await fetchJadwal();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message || 'Gagal menghapus jadwal.',
                            confirmButtonColor: '#6366f1'
                        });
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: err.message,
                        confirmButtonColor: '#6366f1'
                    });
                }
            }

            function findJadwalById(id) {
                for (const hari in jadwalData) {
                    const item = jadwalData[hari].find(j => j.id_jadwal === id);
                    if (item) return item;
                }
                return null;
            }

            // ── Event listeners ─────────────────────────────────────
            tableBody.addEventListener('click', function (e) {
                const btn = e.target.closest('button');
                if (!btn) return;
                const action = btn.dataset.action;
                const id     = parseInt(btn.dataset.id);
                if (!action || !id) return;
                const item = findJadwalById(id);
                if (action === 'edit' && item) openModal('edit', item);
                if (action === 'delete') deleteSchedule(id);
            });

            btnAdd.addEventListener('click',    () => openModal('create'));
            btnCancel.addEventListener('click', closeModal);
            btnSave.addEventListener('click',   e => { e.preventDefault(); saveSchedule(); });

            fetchMasterData().then(() => fetchJadwal());
        });
    </script>
@endsection