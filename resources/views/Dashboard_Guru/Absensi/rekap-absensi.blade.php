@extends('layouts.index')
@php
    $role = 'guru';
    /** @var \Illuminate\Pagination\LengthAwarePaginator $kelas */
    /** @var \Illuminate\Support\Collection $tahunPelajaran */
@endphp
@section('title', 'Rekap Absensi')

@section('content')
@include('components.navbar', ['role' => $role])

    <div class="mb-8">
        <div class="px-4 py-2 pt-4 mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Rekap Absensi</h1>
            <p class="text-gray-600 mt-1">Pilih Kelas</p>
        </div>

        <div class="bg-white rounded-[24px] shadow-sm border border-slate-200">
            <!-- Header Section -->
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-xl font-bold text-[#1e2567]">Daftar Kelas</h2>
                <p class="text-sm text-slate-500 mt-1 mb-4">Tahun Pelajaran</p>
            </div>

            <!-- Table Section -->
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead>
                        <tr class="bg-[#1e2567] text-white">
                            <th class="px-6 py-4 font-semibold text-center rounded-l-xl w-16">No</th>
                            <th class="px-6 py-4 font-semibold text-center w-1/4">Kelas</th>
                            <th class="px-6 py-4 font-semibold text-center w-1/3">Wali Kelas</th>
                            <th class="px-6 py-4 font-semibold text-center rounded-r-xl w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-transparent">
                        @forelse($kelas as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-center">
                                {{ $kelas->firstItem() + $loop->index }}
                            </td>

                            <td class="px-6 py-4 text-center font-medium">
                                {{ $item->nama_kelas }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                {{ $item->guru->nama ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <button
                                    onclick="openRekapModal('{{ $item->nama_kelas }}', '{{ $item->id_kelas }}')"
                                    class="inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                                    </svg>
                                    Cetak Rekapitulasi
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm">Tidak ada kelas yang tersedia</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ===== MODAL CETAK REKAPITULASI ===== -->
    <div id="rekapModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6">
        <div class="w-full max-w-lg rounded-3xl bg-white shadow-2xl overflow-hidden">

            <!-- Header -->
            <div class="bg-indigo-700 px-5 py-4 flex items-center justify-between">
                <span class="text-white text-sm font-semibold tracking-wide uppercase">Rekapitulasi Absensi</span>
                <button onclick="closeRekapModal()" class="text-white/70 hover:text-white text-xl leading-none">&times;</button>
            </div>

            <div class="p-6">
                <!-- Info Kelas -->
                <p class="text-sm text-slate-600 mb-5">
                    Kelas : <span id="modalNamaKelas" class="font-bold text-slate-900"></span>
                </p>

                <!-- Tab Pilih Periode -->
                <div class="flex gap-2 flex-wrap mb-4" id="periodeTab">
                    <button onclick="setPeriode('1bulan')"
                        data-periode="1bulan"
                        class="periode-btn px-4 py-2 rounded-xl text-sm font-semibold border transition-colors
                            bg-indigo-700 text-white border-indigo-700">
                        Per 1 Bulan
                    </button>
                    <button onclick="setPeriode('3bulan')"
                        data-periode="3bulan"
                        class="periode-btn px-4 py-2 rounded-xl text-sm font-semibold border transition-colors
                            bg-white text-slate-600 border-slate-300 hover:border-indigo-400">
                        Per 3 Bulan
                    </button>
                    <button onclick="setPeriode('semester')"
                        data-periode="semester"
                        class="periode-btn px-4 py-2 rounded-xl text-sm font-semibold border transition-colors
                            bg-white text-slate-600 border-slate-300 hover:border-indigo-400">
                        Per Semester
                    </button>
                    <button onclick="setPeriode('tahun')"
                        data-periode="tahun"
                        class="periode-btn px-4 py-2 rounded-xl text-sm font-semibold border transition-colors
                            bg-white text-slate-600 border-slate-300 hover:border-indigo-400">
                        Per Tahun
                    </button>
                </div>

                <p class="text-sm text-slate-400 mb-6">Pilih periode rekap yang ingin dicetak</p>

                <!-- Footer -->
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                    <button onclick="closeRekapModal()"
                        class="bg-slate-200 hover:bg-slate-300 text-slate-900 px-6 py-3 rounded-xl font-medium transition-colors">
                        Batal
                    </button>
                    <button onclick="lanjutPeriode()"
                        class="bg-indigo-700 hover:bg-indigo-800 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                        Lanjut
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MODAL PER 1 BULAN ===== -->
    <div id="bulanModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6">
        <div class="w-full max-w-md rounded-3xl bg-white shadow-2xl overflow-hidden">

            <!-- Header -->
            <div class="bg-indigo-700 px-5 py-4 flex items-center justify-between">
                <span class="text-white text-sm font-semibold tracking-wide uppercase">Rekapitulasi Absensi: Per 1 Bulan</span>
                <button onclick="closeBulanModal()" class="text-white/70 hover:text-white text-xl leading-none">&times;</button>
            </div>

            <div class="p-6">
                <!-- Info Kelas -->
                <p class="text-sm text-slate-600 mb-5">
                    Kelas : <span id="bulanModalNamaKelas" class="font-bold text-slate-900"></span>
                </p>

                <!-- Dropdown Bulan -->
                <label class="block text-sm font-semibold text-slate-700 mb-2">Bulan</label>
                <div class="relative">
                    <select id="bulanSelect"
                        class="w-full appearance-none border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 pr-10">
                        <option value="" disabled selected>-- Pilih --</option>
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-3 mt-6">
                    <button onclick="closeBulanModal()"
                        class="bg-slate-200 hover:bg-slate-300 text-slate-900 px-5 py-3 rounded-xl font-medium transition-colors">
                        Batal
                    </button>
                    <button onclick="downloadBulan()"
                        class="bg-indigo-700 hover:bg-indigo-800 text-white px-5 py-3 rounded-xl font-medium transition-colors">
                        Rekap
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MODAL PER 3 BULAN ===== -->   
    <div id="tribulanModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6">
        <div class="w-full max-w-md rounded-3xl bg-white shadow-2xl overflow-hidden">
            <div class="bg-indigo-700 px-5 py-4 flex items-center justify-between">
                <span class="text-white text-sm font-semibold tracking-wide uppercase">Rekapitulasi Absensi: Per 3 Bulan</span>
                <button onclick="closeTribulanModal()" class="text-white/70 hover:text-white text-xl leading-none">&times;</button>
            </div>
            <div class="p-6">
                <p class="text-sm text-slate-600 mb-5">
                    Kelas : <span id="tribulanModalNamaKelas" class="font-bold text-slate-900"></span>
                </p>
                <label class="block text-sm font-semibold text-slate-700 mb-3">Kuartal</label>
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="setKuartal(this, 1, 3)"
                        class="kuartal-btn border border-slate-300 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:border-indigo-400 transition-colors text-left">
                        <span class="block text-indigo-700 font-bold mb-0.5">Kuartal 1</span>
                        Januari – Maret
                    </button>
                    <button onclick="setKuartal(this, 4, 6)"
                        class="kuartal-btn border border-slate-300 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:border-indigo-400 transition-colors text-left">
                        <span class="block text-indigo-700 font-bold mb-0.5">Kuartal 2</span>
                        April – Juni
                    </button>
                    <button onclick="setKuartal(this, 7, 9)"
                        class="kuartal-btn border border-slate-300 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:border-indigo-400 transition-colors text-left">
                        <span class="block text-indigo-700 font-bold mb-0.5">Kuartal 3</span>
                        Juli – September
                    </button>
                    <button onclick="setKuartal(this, 10, 12)"
                        class="kuartal-btn border border-slate-300 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:border-indigo-400 transition-colors text-left">
                        <span class="block text-indigo-700 font-bold mb-0.5">Kuartal 4</span>
                        Oktober – Desember
                    </button>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button onclick="closeTribulanModal()"
                        class="bg-slate-200 hover:bg-slate-300 text-slate-900 px-5 py-3 rounded-xl font-medium transition-colors">
                        Batal
                    </button>
                    <button onclick="downloadTribulan()"
                        class="bg-indigo-700 hover:bg-indigo-800 text-white px-5 py-3 rounded-xl font-medium transition-colors">
                        Rekap
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MODAL PER SEMESTER ===== -->
    <div id="semesterModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6">
        <div class="w-full max-w-md rounded-3xl bg-white shadow-2xl overflow-hidden">
            <div class="bg-indigo-700 px-5 py-4 flex items-center justify-between">
                <span class="text-white text-sm font-semibold tracking-wide uppercase">Rekapitulasi Absensi: Per Semester</span>
                <button onclick="closeSemesterModal()" class="text-white/70 hover:text-white text-xl leading-none">&times;</button>
            </div>
            <div class="p-6">
                <p class="text-sm text-slate-600 mb-2">
                    Kelas : <span id="semesterModalNamaKelas" class="font-bold text-slate-900"></span>
                </p>
                <p class="text-sm text-slate-400 mb-6">
                    Rekap akan dibuat sesuai semester tahun pelajaran kelas ini secara otomatis.
                </p>
                <div class="flex justify-end gap-3 mt-6">
                    <button onclick="closeSemesterModal()"
                        class="bg-slate-200 hover:bg-slate-300 text-slate-900 px-5 py-3 rounded-xl font-medium transition-colors">
                        Batal
                    </button>
                    <button onclick="downloadSemester()"
                        class="bg-indigo-700 hover:bg-indigo-800 text-white px-5 py-3 rounded-xl font-medium transition-colors">
                        Rekap
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MODAL PER TAHUN ===== -->
    <div id="tahunModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6">
        <div class="w-full max-w-md rounded-3xl bg-white shadow-2xl overflow-hidden">
            <div class="bg-indigo-700 px-5 py-4 flex items-center justify-between">
                <span class="text-white text-sm font-semibold tracking-wide uppercase">Rekapitulasi Absensi: Per Tahun</span>
                <button onclick="closeTahunModal()" class="text-white/70 hover:text-white text-xl leading-none">&times;</button>
            </div>
            <div class="p-6">
                <p class="text-sm text-slate-600 mb-2">
                    Kelas : <span id="tahunModalNamaKelas" class="font-bold text-slate-900"></span>
                </p>
                <p class="text-sm text-slate-400 mb-6">
                    Rekap akan dibuat untuk seluruh periode tahun pelajaran kelas ini.
                </p>
                <div class="flex justify-end gap-3 mt-6">
                    <button onclick="closeTahunModal()"
                        class="bg-slate-200 hover:bg-slate-300 text-slate-900 px-5 py-3 rounded-xl font-medium transition-colors">
                        Batal
                    </button>
                    <button onclick="downloadTahun()"
                        class="bg-indigo-700 hover:bg-indigo-800 text-white px-5 py-3 rounded-xl font-medium transition-colors">
                        Rekap
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MODAL SUKSES ===== -->
    <div id="successModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl shadow-xl w-full max-w-sm mx-4 overflow-hidden">

            {{-- Header --}}
            <div class="bg-indigo-700 px-5 py-4 flex items-center justify-between">
                <span class="text-white text-sm font-semibold tracking-wide uppercase">
                    Rekapitulasi Absensi: Per 1 Bulan
                </span>
                <button onclick="closeSuccessModal()"
                    class="text-white/70 hover:text-white text-xl leading-none">&times;</button>
            </div>

            {{-- Body --}}
            <div class="px-8 py-8 text-center">
                {{-- Ikon centang --}}
                <div class="w-20 h-20 mx-auto mb-5 rounded-full bg-green-500 flex items-center justify-center shadow-lg shadow-green-200">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="3"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h2 class="text-xl font-bold text-slate-900">File Berhasil Dibuat!</h2>

                {{-- Info file --}}
                <p id="successFileName" class="mt-2 text-sm font-semibold text-slate-700">
                    Rekap_KelasVIIA_Jan2025.pdf
                </p>
                <p class="mt-1 text-xs text-slate-400">
                    <span id="successFileKelas">Kelas VII A</span>
                    &middot;
                    <span id="successFileBulan">Januari 2025</span>
                    &middot; PDF
                </p>
            </div>

            {{-- Actions --}}
            <div class="px-6 pb-6 flex gap-3">
                <button
                    id="btnLihatPdf"
                    class="flex-1 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-white text-sm font-semibold py-3 rounded-2xl transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat PDF
                </button>

                <button
                    onclick="closeSuccessModal()"
                    class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold py-3 rounded-2xl transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentKelasId = '';
        let currentNamaKelas = '';
        let currentPeriode = '1bulan';
        let pdfUrl = '';
        let selectedBulanAwal = null;
        let selectedBulanAkhir = null;

        const namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        const labelKuartal = {
            '1-3':   'Jan–Mar',
            '4-6':   'Apr–Jun',
            '7-9':   'Jul–Sep',
            '10-12': 'Okt–Des',
        };

        // ===== MODAL REKAP (PILIH PERIODE) =====
        function openRekapModal(namaKelas, kelasId) {
            currentKelasId = kelasId;
            currentNamaKelas = namaKelas;
            document.getElementById('modalNamaKelas').textContent = namaKelas;
            setPeriode('1bulan');
            const modal = document.getElementById('rekapModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeRekapModal() {
            const modal = document.getElementById('rekapModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function setPeriode(periode) {
            currentPeriode = periode;
            document.querySelectorAll('.periode-btn').forEach(btn => {
                const isActive = btn.dataset.periode === periode;
                btn.classList.toggle('bg-indigo-700', isActive);
                btn.classList.toggle('text-white', isActive);
                btn.classList.toggle('border-indigo-700', isActive);
                btn.classList.toggle('bg-white', !isActive);
                btn.classList.toggle('text-slate-600', !isActive);
                btn.classList.toggle('border-slate-300', !isActive);
            });
        }

        function lanjutPeriode() {
            closeRekapModal();
            if (currentPeriode === '1bulan') {
                openBulanModal();
            } else if (currentPeriode === '3bulan') {
                openTribulanModal();
            } else if (currentPeriode === 'semester') {
                openSemesterModal();
            } else if (currentPeriode === 'tahun') {
                openTahunModal();
            } else {
                downloadRekap(currentPeriode);
            }
        }

        function downloadRekap(rentang) {
            const params = new URLSearchParams({
                kelas_id: currentKelasId,
                kelas: currentNamaKelas,
                rentang: rentang
            });
            window.location.href = `/guru/absensi/rekap/download?${params.toString()}`;
        }

        // ===== MODAL PER 1 BULAN =====
        function openBulanModal() {
            document.getElementById('bulanModalNamaKelas').textContent = currentNamaKelas;
            document.getElementById('bulanSelect').value = '';
            const modal = document.getElementById('bulanModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeBulanModal() {
            const modal = document.getElementById('bulanModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function downloadBulan() {
            const bulanVal = document.getElementById('bulanSelect').value;
            if (!bulanVal) {
                alert('Pilih bulan terlebih dahulu.');
                return;
            }

            const params = new URLSearchParams({
                kelas_id: currentKelasId,
                bulan: bulanVal
            });

            pdfUrl = `/guru/absensi/rekap/preview?${params.toString()}`;

            document.getElementById('successFileName').textContent =
                `Rekap_${currentNamaKelas.replace(/\s/g, '')}_${namaBulan[bulanVal]}${new Date().getFullYear()}.pdf`;
            document.getElementById('successFileKelas').textContent = currentNamaKelas;
            document.getElementById('successFileBulan').textContent =
                `${namaBulan[bulanVal]} ${new Date().getFullYear()}`;

            closeBulanModal();
            openSuccessModal();
        }

        // ===== MODAL PER 3 BULAN =====
        function openTribulanModal() {
            selectedBulanAwal = null;
            selectedBulanAkhir = null;
            document.querySelectorAll('.kuartal-btn').forEach(btn => {
                btn.classList.remove('bg-indigo-700', 'text-white', 'border-indigo-700');
                btn.classList.add('bg-white', 'text-slate-600', 'border-slate-300');
                btn.querySelector('span').classList.remove('text-white');
                btn.querySelector('span').classList.add('text-indigo-700');
            });
            document.getElementById('tribulanModalNamaKelas').textContent = currentNamaKelas;
            const modal = document.getElementById('tribulanModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeTribulanModal() {
            const modal = document.getElementById('tribulanModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function setKuartal(el, awal, akhir) {
            selectedBulanAwal = awal;
            selectedBulanAkhir = akhir;
            document.querySelectorAll('.kuartal-btn').forEach(btn => {
                btn.classList.remove('bg-indigo-700', 'text-white', 'border-indigo-700');
                btn.classList.add('bg-white', 'text-slate-600', 'border-slate-300');
                btn.querySelector('span').classList.remove('text-white');
                btn.querySelector('span').classList.add('text-indigo-700');
            });
            el.classList.add('bg-indigo-700', 'text-white', 'border-indigo-700');
            el.classList.remove('bg-white', 'text-slate-600', 'border-slate-300');
            el.querySelector('span').classList.add('text-white');
            el.querySelector('span').classList.remove('text-indigo-700');
        }

        function downloadTribulan() {
            if (!selectedBulanAwal || !selectedBulanAkhir) {
                alert('Pilih kuartal terlebih dahulu.');
                return;
            }

            const params = new URLSearchParams({
                kelas_id: currentKelasId,
                bulan_awal: selectedBulanAwal,
                bulan_akhir: selectedBulanAkhir
            });

            pdfUrl = `/guru/absensi/rekap/preview-3bulan?${params.toString()}`;

            const key = `${selectedBulanAwal}-${selectedBulanAkhir}`;
            const labelPeriode = labelKuartal[key] ?? `Bulan ${selectedBulanAwal}–${selectedBulanAkhir}`;

            document.getElementById('successFileName').textContent =
                `Rekap_${currentNamaKelas.replace(/\s/g, '')}_${labelPeriode.replace(/[–\s]/g, '')}_${new Date().getFullYear()}.pdf`;
            document.getElementById('successFileKelas').textContent = currentNamaKelas;
            document.getElementById('successFileBulan').textContent =
                `${labelPeriode} ${new Date().getFullYear()}`;

            closeTribulanModal();
            openSuccessModal();
        }

        // ===== MODAL PER SEMESTER =====
        function openSemesterModal() {
            document.getElementById('semesterModalNamaKelas').textContent = currentNamaKelas;
            const modal = document.getElementById('semesterModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeSemesterModal() {
            const modal = document.getElementById('semesterModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function downloadSemester() {
            const params = new URLSearchParams({
                kelas_id: currentKelasId
            });

            pdfUrl = `/guru/absensi/rekap/preview-semester?${params.toString()}`;

            document.getElementById('successFileName').textContent =
                `Rekap_${currentNamaKelas.replace(/\s/g, '')}_Semester.pdf`;
            document.getElementById('successFileKelas').textContent = currentNamaKelas;
            document.getElementById('successFileBulan').textContent = `Per Semester`;

            closeSemesterModal();
            openSuccessModal();
        }

        // ===== MODAL PER TAHUN =====
        function openTahunModal() {
            document.getElementById('tahunModalNamaKelas').textContent = currentNamaKelas;
            const modal = document.getElementById('tahunModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeTahunModal() {
            const modal = document.getElementById('tahunModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function downloadTahun() {
            const params = new URLSearchParams({
                kelas_id: currentKelasId
            });

            pdfUrl = `/guru/absensi/rekap/preview-tahun?${params.toString()}`;

            document.getElementById('successFileName').textContent =
                `Rekap_${currentNamaKelas.replace(/\s/g, '')}_FullTahun.pdf`;
            document.getElementById('successFileKelas').textContent = currentNamaKelas;
            document.getElementById('successFileBulan').textContent = `Tahun Pelajaran Penuh`;

            closeTahunModal();
            openSuccessModal();
        }

        // ===== MODAL SUKSES =====
        function openSuccessModal() {
            const modal = document.getElementById('successModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeSuccessModal() {
            const modal = document.getElementById('successModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        document.getElementById('btnLihatPdf').addEventListener('click', function () {
            window.open(pdfUrl, '_blank');
            closeSuccessModal();
        });

        // ===== EVENT LISTENERS =====
        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeRekapModal();
                    closeBulanModal();
                    closeTribulanModal();
                    closeSemesterModal();
                    closeTahunModal();
                    closeSuccessModal();
                }
            });

            document.getElementById('rekapModal').addEventListener('click', function (e) {
                if (e.target === this) closeRekapModal();
            });

            document.getElementById('bulanModal').addEventListener('click', function (e) {
                if (e.target === this) closeBulanModal();
            });

            document.getElementById('tribulanModal').addEventListener('click', function (e) {
                if (e.target === this) closeTribulanModal();
            });

            document.getElementById('semesterModal').addEventListener('click', function (e) {
                if (e.target === this) closeSemesterModal();
            });

            document.getElementById('tahunModal').addEventListener('click', function (e) {
                if (e.target === this) closeTahunModal();
            });

            document.getElementById('successModal').addEventListener('click', function (e) {
                if (e.target === this) closeSuccessModal();
            });
        });
    </script>
@endsection