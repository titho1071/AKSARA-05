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
                {{-- Badge tapel aktif --}}
                <div class="mt-2">
                    <span id="tapel-badge" class="hidden items-center gap-1.5 text-xs font-medium text-blue-700 bg-blue-100 px-3 py-1.5 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.25 4.533A9.707 9.707 0 0 0 6 3a9.735 9.735 0 0 0-3.25.555.75.75 0 0 0-.5.707v14.25a.75.75 0 0 0 1 .707A8.237 8.237 0 0 1 6 18.75c1.995 0 3.823.707 5.25 1.886V4.533ZM12.75 20.636A8.214 8.214 0 0 1 18 18.75c.966 0 1.89.166 2.75.47a.75.75 0 0 0 1-.708V4.262a.75.75 0 0 0-.5-.707A9.735 9.735 0 0 0 18 3a9.707 9.707 0 0 0-5.25 1.533v16.103Z" />
                        </svg>
                        <span id="tapel-badge-text">-</span>
                    </span>
                    <span id="tapel-badge-error" class="hidden items-center gap-1.5 text-xs font-medium text-red-700 bg-red-100 px-3 py-1.5 rounded-full">
                        ⚠ Tidak ada tahun pelajaran aktif
                    </span>
                </div>
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
                    {{-- Info tapel di dalam modal --}}
                    <p id="modal-tapel-info" class="text-xs text-blue-600 mt-1 font-medium"></p>
                </div>
            </div>

            <div id="modal-alert" class="hidden mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>

            <div class="space-y-5">

                {{-- Baris 1: Hari + Jam --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide" for="hari">Hari</label>
                        <select id="hari" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition">
                            <option value="">Pilih Hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide" for="jam">Jam Pelajaran</label>
                        <select id="jam" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition">
                            <option value="">Pilih Jam</option>
                        </select>
                    </div>
                </div>

                {{-- Baris 2: Kelas + Mapel --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide" for="kelas">Kelas</label>
                        <select id="kelas" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition">
                            <option value="">Pilih Kelas</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide" for="mapel">Mata Pelajaran</label>
                        <select id="mapel" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                            <option value="">Pilih Mata Pelajaran</option>
                        </select>
                    </div>
                </div>

                {{-- Baris 3: Guru --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide" for="guru">
                        Guru Pengajar
                        <span id="guru-label-hint" class="ml-1 normal-case font-normal text-gray-400">(Opsional)</span>
                    </label>
                    <select id="guru" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                        <option value="">-- Tidak Ada / Pilih Guru --</option>
                    </select>
                </div>

                {{-- Divider --}}
                <div class="relative">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
                    <div class="relative flex justify-center">
                        <span class="bg-white px-3 text-xs text-gray-400 font-medium">atau isi kegiatan</span>
                    </div>
                </div>

                {{-- Baris 4: Kegiatan --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide" for="kegiatan">
                        Kegiatan
                        <span class="ml-1 normal-case font-normal text-gray-400">(Opsional)</span>
                    </label>
                    <input
                        type="text"
                        id="kegiatan"
                        placeholder="Contoh: Upacara Bendera, Olahraga Bersama..."
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-100 placeholder:text-gray-400 transition"
                    />
                    <p class="text-xs text-gray-400">Jika diisi, mata pelajaran & guru akan dikosongkan otomatis.</p>
                </div>

            </div>

            <div class="mt-8 flex gap-3 justify-end">
                <button id="modal-cancel" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Batal</button>
                <button id="modal-save" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">Simpan Jadwal</button>
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
            let guruData           = [];
            let currentEditId      = null;
            let selectedKelasId    = null;
            let tapelAktif         = null;

            const tableBody       = document.getElementById('schedule-table-body');
            const countEl         = document.getElementById('schedule-count');
            const summaryEl       = document.getElementById('schedule-summary');
            const modal           = document.getElementById('schedule-modal');
            const modalTitle      = document.getElementById('modal-title');
            const modalSubtitle   = document.getElementById('modal-subtitle');
            const modalTapelInfo  = document.getElementById('modal-tapel-info');
            const alertBox        = document.getElementById('modal-alert');
            const btnAdd          = document.getElementById('btn-add-schedule');
            const btnCancel       = document.getElementById('modal-cancel');
            const btnSave         = document.getElementById('modal-save');
            const filterKelas     = document.getElementById('filter-kelas');
            const tapelBadge      = document.getElementById('tapel-badge');
            const tapelBadgeText  = document.getElementById('tapel-badge-text');
            const tapelBadgeError = document.getElementById('tapel-badge-error');

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

            // ── Logika mapel → label guru wajib/opsional ───────────
            const guruLabelHint = document.getElementById('guru-label-hint');

            fields.mapel.addEventListener('change', function () {
                if (this.value) {
                    guruLabelHint.textContent = '(Wajib)';
                    guruLabelHint.classList.remove('text-gray-400');
                    guruLabelHint.classList.add('text-red-400');
                } else {
                    guruLabelHint.textContent = '(Opsional)';
                    guruLabelHint.classList.remove('text-red-400');
                    guruLabelHint.classList.add('text-gray-400');
                }
            });

            // ── Logika kegiatan ↔ mapel ─────────────────────────────
            fields.kegiatan.addEventListener('input', function () {
                const hasKegiatan = this.value.trim() !== '';
                if (hasKegiatan) {
                    fields.mapel.value    = '';
                    fields.mapel.disabled = true;
                    fields.mapel.classList.add('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
                    fields.guru.value     = '';
                    fields.guru.disabled  = true;
                    fields.guru.classList.add('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
                } else {
                    fields.mapel.disabled = false;
                    fields.mapel.classList.remove('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
                    fields.guru.disabled  = false;
                    fields.guru.classList.remove('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
                }
            });

            // ── Tampilkan badge tapel ───────────────────────────────
            function renderTapelBadge(tapel) {
                if (tapel) {
                    tapelBadgeText.textContent = `${tapel.tahun_pelajaran} — Semester ${tapel.semester}`;
                    tapelBadge.classList.remove('hidden');
                    tapelBadge.classList.add('inline-flex');
                    tapelBadgeError.classList.add('hidden');
                } else {
                    tapelBadge.classList.add('hidden');
                    tapelBadgeError.classList.remove('hidden');
                    tapelBadgeError.classList.add('inline-flex');
                    btnAdd.disabled = true;
                    btnAdd.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            // ── Fetch semua master data ─────────────────────────────
            async function fetchMasterData() {
                try {
                    const [jamRes, kelasRes, mapelRes, guruRes] = await Promise.all([
                        fetch('/api/jam-pelajaran',   { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } }),
                        fetch('/api/kelas',           { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } }),
                        fetch('/api/mata-pelajaran',  { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } }),
                        fetch('/api/guru-jadwal',     { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } }),
                    ]);

                    const jamResult   = await jamRes.json();
                    const mapelResult = await mapelRes.json();
                    const guruResult  = await guruRes.json();
                    kelasData         = await kelasRes.json();

                    if (jamResult.success)   { jamPelajaranData  = jamResult.data;   populateJamDropdown(); }
                    if (mapelResult.success) { mataPelajaranData = mapelResult.data; populateMapelDropdown(); }
                    if (guruResult.success)  { guruData          = guruResult.data;  populateGuruDropdown(); }

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
                    // Lewati jam istirahat agar tidak muncul di form
                    if (jam.keterangan && jam.keterangan.toLowerCase().includes('istirahat')) return;
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

            // ── Fetch jadwal ────────────────────────────────────────
            async function fetchJadwal() {
                tableBody.innerHTML = `<tr><td colspan="6" class="border border-gray-200 px-4 py-8 text-center text-slate-500">Memuat jadwal...</td></tr>`;
                try {
                    const res    = await fetch('/api/jadwal-pelajaran/by-hari', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } });
                    const result = await res.json();

                    if (result.success) {
                        jadwalData = result.data;
                        tapelAktif = result.tapel || null;
                        renderTapelBadge(tapelAktif);
                        applyFilter();
                    } else {
                        tableBody.innerHTML = `<tr><td colspan="6" class="border border-gray-200 px-4 py-8 text-center text-red-500">Gagal memuat jadwal</td></tr>`;
                        renderTapelBadge(null);
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
                        const kegiatanName = item.nama_kegiatan || null; // ← field teks bebas
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

            // ── Reset state mapel/kegiatan ──────────────────────────
            function resetMapelKegiatanState() {
                fields.mapel.disabled = false;
                fields.mapel.classList.remove('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
                fields.guru.disabled  = false;
                fields.guru.classList.remove('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
                // Reset label guru ke opsional
                if (guruLabelHint) {
                    guruLabelHint.textContent = '(Opsional)';
                    guruLabelHint.classList.remove('text-red-400');
                    guruLabelHint.classList.add('text-gray-400');
                }
            }

            // ── Modal ───────────────────────────────────────────────
            function openModal(mode, item = null) {
                currentEditId = item ? item.id_jadwal : null;
                modalTitle.textContent    = mode === 'edit' ? 'Edit Jadwal' : 'Tambah Jadwal';
                modalSubtitle.textContent = mode === 'edit' ? 'Perbarui data jadwal yang dipilih.' : 'Buat jadwal pelajaran baru untuk kelas dan jam yang dipilih.';
                modalTapelInfo.textContent = tapelAktif
                    ? `Tahun Pelajaran: ${tapelAktif.tahun_pelajaran} — Semester ${tapelAktif.semester}`
                    : '';
                alertBox.classList.add('hidden');

                resetMapelKegiatanState();

                if (item) {
                    fields.hari.value     = item.hari;
                    fields.jam.value      = item.jam_id;
                    fields.kelas.value    = item.kelas_id || '';
                    fields.mapel.value    = item.id_mapel || '';
                    fields.kegiatan.value = item.nama_kegiatan || '';
                    fields.guru.value     = item.id_guru || '';

                    // Trigger event input agar logika disable mapel berjalan otomatis
                    fields.kegiatan.dispatchEvent(new Event('input'));
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
                resetMapelKegiatanState();
            }

            function showModalError(msg) {
                alertBox.textContent = msg;
                alertBox.classList.remove('hidden');
            }

            // ── Simpan jadwal ───────────────────────────────────────
            async function saveSchedule() {
                const hari          = fields.hari.value;
                const jam_id        = fields.jam.value;
                const kelas_id      = fields.kelas.value;
                const nama_kegiatan = fields.kegiatan.value.trim();
                // Ambil id_mapel hanya jika tidak ada kegiatan (mapel bisa disabled)
                const id_mapel      = nama_kegiatan ? '' : fields.mapel.value;

                if (!hari)   { showModalError('Pilih hari terlebih dahulu.');          return; }
                if (!jam_id) { showModalError('Pilih jam pelajaran terlebih dahulu.'); return; }
                if (!id_mapel && !nama_kegiatan) {
                    showModalError('Pilih mata pelajaran atau isi kegiatan.');
                    return;
                }
                // Jika pilih mapel (bukan kegiatan), guru wajib dipilih
                if (id_mapel && !nama_kegiatan && !fields.guru.value) {
                    showModalError('Guru pengajar wajib dipilih jika mengisi mata pelajaran.');
                    return;
                }

                const payload = {
                    hari,
                    jam_id:        parseInt(jam_id),
                    kelas_id:      kelas_id  ? parseInt(kelas_id) : null,
                    id_mapel:      id_mapel  ? parseInt(id_mapel) : null,
                    nama_kegiatan: nama_kegiatan || null,
                    id_guru:       fields.guru.value ? parseInt(fields.guru.value) : null,
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
                    const res  = await fetch(`/api/jadwal-pelajaran/${id}`, {
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
                        Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Gagal menghapus jadwal.', confirmButtonColor: '#6366f1' });
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire({ icon: 'error', title: 'Error', text: err.message, confirmButtonColor: '#6366f1' });
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