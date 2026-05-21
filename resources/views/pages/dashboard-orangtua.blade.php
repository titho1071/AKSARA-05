@extends('layouts.index')

@php
    $role = 'orangtua';
@endphp

@section('title', 'Dashboard Orang Tua')

@section('content')
@include('components.navbar', ['role' => $role])
    <!-- Header Section -->
    <div class="mb-8 px-4 py-2 pt-4">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-1">Semua aktivitas dashboard.</p>
    </div>

    <!-- Student Profile Cards -->
    <div class="flex flex-wrap items-center gap-4 mb-8 px-4">
        @forelse($siswa as $s)
            @php
                $initials = collect(explode(' ', $s->nama))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->implode('');
                $isActive = isset($activeSiswa) && $activeSiswa->id_siswa == $s->id_siswa;
            @endphp
            <a href="{{ route('orangtua.dashboard', ['siswa_id' => $s->id_siswa]) }}" class="inline-block">
                <div class="flex items-center gap-3 px-5 py-3 rounded-2xl transition-colors {{ $isActive ? 'bg-[#313589] text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold {{ $isActive ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600' }}">
                        {{ $initials }}
                    </div>
                    <div>
                        <p class="font-bold text-sm {{ $isActive ? 'text-white' : 'text-slate-900' }}">{{ $s->nama }}</p>
                        <p class="text-xs {{ $isActive ? 'text-slate-300' : 'text-slate-500' }}">{{ $s->nama_kelas }}</p>
                    </div>
                </div>
            </a>
        @empty
            <div class="w-full bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
                <p class="text-gray-500">Data siswa tidak ditemukan.</p>
            </div>
        @endforelse
    </div>

    <!-- Charts and Content Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 px-4">
        <!-- Grafik Absensi -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Grafik Absensi</h2>
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option>April 2026</option>
                </select>
            </div>
            <div class="relative h-64">
                <canvas id="absenceChart"></canvas>
            </div>
            <a href="#" class="text-blue-500 text-sm font-medium mt-4 inline-block hover:underline">Lihat Detail</a>
        </div>

        <!-- Pengumuman -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Pengumuman</h2>
            <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                @forelse($pengumuman as $item)
                    <div class="border-l-4 {{ $item->kelas_id ? 'border-blue-500' : 'border-green-500' }} pl-4 py-3">
                        <a href="{{ route('orangtua.pengumuman.detail', $item->id_pengumuman) }}" class="block text-gray-700 font-medium hover:text-blue-600 transition-colors">{{ $item->judul }}</a>
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
                    <div class="text-center py-4">
                        <p class="text-gray-500">Tidak ada pengumuman terbaru.</p>
                    </div>
                @endforelse
            </div>
            <a href="{{ route('orangtua.pengumuman') }}" class="text-blue-500 text-sm font-medium mt-6 inline-block hover:underline">Lihat Detail</a>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-4 mb-8">
        <!-- Dokumentasi -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Dokumentasi</h2>
            <div class="mb-6">
                <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=500&h=300&fit=crop" alt="Upacara Bendera" class="w-full rounded-xl object-cover h-40">
            </div>
            <div>
                <p class="font-semibold text-gray-900 mb-2">Upacara Bendera</p>
                <p class="text-gray-600 text-sm leading-relaxed">Upacara bendera hari Senin merupakan kegiatan rutin yang dilaksanakan setiap awal pekan sebagai bentuk penghormatan nilai kedisiplinan, tanggung jawab, dan rasa cinta tanah air.</p>
            </div>
            <a href="#" class="text-blue-500 text-sm font-medium mt-4 inline-block hover:underline">Lihat Detail</a>
        </div>

        <!-- Jadwal Pelajaran -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Jadwal Pelajaran</h2>
            <div class="space-y-4">
                <div class="pb-4 border-b border-gray-200">
                    <p class="font-semibold text-gray-900 mb-2">Jadwal Pelajaran Hari ini</p>
                    <p class="text-sm text-gray-600">07:30 - 09:00: Matematika</p>
                </div>
            </div>
            <a href="#" class="text-blue-500 text-sm font-medium mt-4 inline-block hover:underline">Lihat Detail</a>
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
                        data: [8, 12, 2, 11],
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
