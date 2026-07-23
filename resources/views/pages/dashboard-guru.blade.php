@extends('layouts.index')

@php
    $role = 'guru';
@endphp

@section('title', 'Dashboard Guru')

@section('content')
@include('components.navbar', ['role' => $role])
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Dashboard Guru</h1>
                <p class="text-gray-600 mt-1">Koleksi semua aktivitas dan data sekolah</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Row 1 -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 px-4">

    <!-- Absensi Siswa -->
<div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition">
    <div class="flex items-center gap-4">
        <div class="bg-blue-100 p-4 rounded-full">
            <!-- clipboard-check solid -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-blue-600">
                <path fill-rule="evenodd" d="M9 2.25A.75.75 0 0 1 9.75 3v.75h4.5V3a.75.75 0 0 1 1.5 0v.75H18A2.25 2.25 0 0 1 20.25 6v13.5A2.25 2.25 0 0 1 18 21.75H6A2.25 2.25 0 0 1 3.75 19.5V6A2.25 2.25 0 0 1 6 3.75h2.25V3A.75.75 0 0 1 9 2.25Zm5.03 7.22a.75.75 0 1 0-1.06-1.06l-2.97 2.97-1.03-1.03a.75.75 0 0 0-1.06 1.06l1.56 1.56a.75.75 0 0 0 1.06 0l3.5-3.5Z" clip-rule="evenodd"/>
            </svg>
        </div>

        <div>
            <p class="text-1x3 font-bold text-slate-900">Absensi Siswa</p>
            <p class="text-sm text-gray-500">Total entri bulan ini</p>
        </div>
    </div>
    <p class="mt-5 text-3xl font-bold text-slate-900">{{ $absensiSummary['total'] ?? 0 }}</p>
    <a href="{{ route('guru.absensi') }}" class="text-sm text-blue-500 mt-5 inline-block hover:underline">
        Lihat Detail
    </a>
</div>

<!-- Data Kelas -->
<div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition">
    <div class="flex items-center gap-4">
        <div class="bg-amber-100 p-4 rounded-full">
            <!-- building-office solid -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-amber-600">
                <path d="M3 21V7.5A1.5 1.5 0 0 1 4.5 6H9v15H3Z"/>
                <path d="M9 21V3h6v18H9Z"/>
                <path d="M15 21V10.5A1.5 1.5 0 0 1 16.5 9H21v12h-6Z"/>
            </svg>
        </div>

        <div>
            <p class="text-1x3 font-bold text-slate-900">Data Kelas</p>
            <p class="text-sm text-gray-500">Jumlah kelas yang Anda wali</p>
        </div>
    </div>
    <p class="mt-5 text-3xl font-bold text-slate-900">{{ $countKelasGuru ?? 0 }}</p>
    <a href="{{ route('guru.siswa.index') }}" class="text-sm text-amber-600 mt-5 inline-block hover:underline">
        Lihat Detail
    </a>
</div>

<!-- Data Siswa -->
<div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition">
    <div class="flex items-center gap-4">
        <div class="bg-sky-100 p-4 rounded-full">
            <!-- user-group solid -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-sky-600">
                <path d="M16 11c1.657 0 3-1.567 3-3.5S17.657 4 16 4s-3 1.567-3 3.5S14.343 11 16 11Z"/>
                <path d="M8 11c1.657 0 3-1.567 3-3.5S9.657 4 8 4 5 5.567 5 7.5 6.343 11 8 11Z"/>
                <path d="M8 13c-2.761 0-5 1.79-5 4v1h10v-1c0-2.21-2.239-4-5-4Z"/>
                <path d="M16 13c-.508 0-.993.065-1.45.185A6.978 6.978 0 0 1 18 17v1h4v-1c0-2.21-2.239-4-5-4Z"/>
            </svg>
        </div>

        <div>
            <p class="text-1x3 font-bold text-slate-900">Data Siswa</p>
            <p class="text-sm text-gray-500">Jumlah siswa dalam kelas</p>
        </div>
    </div>
    <p class="mt-5 text-3xl font-bold text-slate-900">{{ $countSiswa ?? 0 }}</p>
    <a href="{{ route('guru.siswa.index') }}" class="text-sm text-sky-600 mt-5 inline-block hover:underline">
        Lihat Detail
    </a>
</div>

<!-- Jadwal Mengajar -->
<div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition">
    <div class="flex items-center gap-4">
        <div class="bg-emerald-100 p-4 rounded-full">
            <!-- calendar-days solid -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-emerald-600">
                <path d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3a.75.75 0 0 1 1.5 0v1.5H19.5A2.25 2.25 0 0 1 21.75 6.75v12A2.25 2.25 0 0 1 19.5 21h-15A2.25 2.25 0 0 1 2.25 18.75v-12A2.25 2.25 0 0 1 4.5 4.5H6V3a.75.75 0 0 1 .75-.75Z"/>
                <path d="M3.75 9h16.5v9.75a.75.75 0 0 1-.75.75h-15a.75.75 0 0 1-.75-.75V9Z"/>
            </svg>
        </div>

        <div>
            <p class="text-1x3 font-bold text-slate-900">Jadwal Mengajar</p>
        </div>
    </div>

    <a href="{{ route('guru.jadwal') }}" class="text-sm text-emerald-600 mt-5 inline-block hover:underline">
        Lihat Detail
    </a>
</div>

</div>

    <!-- Charts and Content Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 px-4">
        <!-- Statistik Absensi -->
        <div class="lg:col-span-1 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Grafik Absensi</h2>
                    <p class="text-sm text-gray-500">{{ $selectedClassName ?? 'Kelas belum ditentukan' }}</p>
                </div>
                <form method="GET" action="{{ route('guru.dashboard') }}" class="flex flex-wrap items-center gap-2">
                    <select name="bulan" class="min-w-[140px] border border-gray-300 rounded-3xl px-3 py-2 text-sm bg-white">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromDate($tahun, $m, 1)->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    <button type="submit" class="bg-[#313589] text-white rounded-3xl px-4 py-2 text-sm whitespace-nowrap">Terapkan</button>
                </form>
            </div>
            <div class="grid gap-4 mb-6">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-gray-500">Hadir</p>
                    <p class="text-2xl font-semibold text-slate-900">{{ $absensiSummary['hadir'] ?? 0 }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-gray-500">Sakit</p>
                    <p class="text-2xl font-semibold text-slate-900">{{ $absensiSummary['sakit'] ?? 0 }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-gray-500">Izin</p>
                    <p class="text-2xl font-semibold text-slate-900">{{ $absensiSummary['izin'] ?? 0 }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-gray-500">Alpha</p>
                    <p class="text-2xl font-semibold text-slate-900">{{ $absensiSummary['alpha'] ?? 0 }}</p>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="absenceChart"></canvas>
            </div>
            <a href="#" class="text-blue-500 text-sm font-medium mt-4 inline-block hover:underline">Lihat Detail</a>
        </div>

        <!-- Pengumuman -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Pengumuman</h2>
            <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                @forelse($pengumuman as $item)
                    <div class="border-l-4 {{ $item->kelas_id ? 'border-blue-500' : 'border-green-500' }} pl-4 py-3">
                        <p class="text-gray-700 font-medium">{{ $item->judul }}</p>
                        <p class="text-gray-500 text-sm mt-1">{{ Str::limit($item->deskripsi, 100) }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs text-gray-400">{{ $item->created_at->diffForHumans() }}</span>
                            @if($item->kelas)
                                <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">{{ $item->kelas->nama_kelas }}</span>
                            @else
                                <span class="text-xs bg-green-50 text-green-600 px-2 py-0.5 rounded-full">Semua Kelas</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500">Tidak ada pengumuman terbaru.</p>
                    </div>
                @endforelse
            </div>
            <a href="{{ route('guru.pengumuman') }}" class="text-blue-500 text-sm font-medium mt-6 inline-block hover:underline">Lihat Semua Pengumuman</a>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-4 mb-8">
        <!-- Dokumentasi -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Dokumentasi Terbaru</h2>
            @if ($latestDokumentasi)
                @php
                    $foto = $latestDokumentasi->dokumentasi->first()?->foto;
                    $imageUrl = $foto ? asset('storage/' . $foto) : 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=500&h=300&fit=crop';
                @endphp
                <div class="mb-6 overflow-hidden rounded-xl">
                    <img src="{{ $imageUrl }}" alt="Dokumentasi {{ $latestDokumentasi->judul }}" class="w-full object-cover h-40">
                </div>
                <div>
                    <p class="font-semibold text-gray-900 mb-2">{{ $latestDokumentasi->judul }}</p>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ Str::limit($latestDokumentasi->deskripsi, 140) }}</p>
                    <p class="text-xs text-slate-500 mt-3">{{ $latestDokumentasi->kelas?->nama_kelas ?? 'Semua Kelas' }} • {{ \Carbon\Carbon::parse($latestDokumentasi->tanggal)->translatedFormat('d F Y') }}</p>
                </div>
            @else
                <div class="text-gray-500">
                    <p class="font-semibold mb-3">Tidak ada dokumentasi terbaru.</p>
                    <p class="text-sm">Dokumentasi kelas Anda akan tampil di sini setelah tersedia.</p>
                </div>
            @endif
            <a href="#" class="text-blue-500 text-sm font-medium mt-4 inline-block hover:underline">Lihat Detail</a>
        </div>

        <!-- Jadwal Pelajaran -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between gap-3 mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Data Jadwal Mengajar</h2>
                    <p class="text-sm text-gray-500">Seluruh jadwal mengajar yang tersedia</p>
                </div>
            </div>
            <div class="space-y-4">
                @if($jadwalMengajarAktif->isNotEmpty())
                    @foreach($jadwalMengajarAktif->take(3) as $jadwal)
                        @php
                            $jam = $jadwal->jamPelajaran;
                            $jamMulai = $jam ? substr($jam->jam_mulai, 0, 5) : '-';
                            $jamSelesai = $jam ? substr($jam->jam_selesai, 0, 5) : '-';
                            $judul = $jadwal->nama_kegiatan ?: ($jadwal->mataPelajaran->nama_mapel ?? '-');
                            $kelas = $jadwal->kelas->nama_kelas ?? '-';
                        @endphp
                        <div class="pb-4 border-b border-gray-200 last:border-b-0">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $judul }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $jadwal->hari }} · {{ $jamMulai }} - {{ $jamSelesai }}</p>
                                    <p class="text-sm text-gray-500 mt-1">{{ $kelas }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $jadwal->nama_kegiatan ? 'bg-violet-100 text-violet-700' : 'bg-[#1E2567] text-white' }}">
                                    {{ $jadwal->nama_kegiatan ? 'Kegiatan' : 'Pelajaran' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-sm text-gray-500">Belum ada data jadwal mengajar.</div>
                @endif
            </div>
            <a href="{{ route('guru.jadwal') }}" class="text-blue-500 text-sm font-medium mt-4 inline-block hover:underline">Lihat Semua Jadwal</a>
        </div>
    </div>
@endsection

<script>
    // Initialize Chart.js for Grafik Absensi
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('absenceChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Sakit', 'Izin', 'Alpha'],
                    datasets: [{
                        data: [{{ $absensiChart[0] ?? 0 }}, {{ $absensiChart[1] ?? 0 }}, {{ $absensiChart[2] ?? 0 }}, {{ $absensiChart[3] ?? 0 }}],
                        backgroundColor: [
                            '#3B82F6',
                            '#F97316',
                            '#FCD34D',
                            '#A0AEC0'
                        ],
                        borderColor: '#FFFFFF',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                font: { size: 12 },
                                padding: 15,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }
    });
</script>
