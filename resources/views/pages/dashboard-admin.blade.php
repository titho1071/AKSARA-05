@extends('layouts.index')

@php
    $role = 'admin';
@endphp

@section('title', 'Dashboard Admin')

@section('content')
@include('components.navbar', ['role' => $role])
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-600 mt-1">Kelola semua aktivitas</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 px-4">
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition">
            <div class="flex items-center gap-4">
                <div class="bg-blue-100 p-4 rounded-3xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-6 h-6 text-blue-600">
                        <path fill-rule="evenodd" d="M2.25 12a9.75 9.75 0 1 1 19.5 0 9.75 9.75 0 0 1-19.5 0Zm9.75-4.5a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm0 7.5c-2.485 0-4.5 1.567-4.5 3.5h9c0-1.933-2.015-3.5-4.5-3.5Z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-slate-900">{{ $countAdmin }}</p>
                    <p class="text-sm text-slate-500">Data Admin</p>
                </div>
            </div>
            <a href="{{ route('admin.biodata.index') }}" class="text-sm text-blue-500 mt-5 inline-block hover:underline">Lihat Detail</a>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition">
            <div class="flex items-center gap-4">
                <div class="bg-emerald-100 p-4 rounded-3xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-6 h-6 text-emerald-600">
                        <path d="M11.7 2.805a.75.75 0 0 1 .6 0l9 4.5a.75.75 0 0 1 0 1.39l-9 4.5a.75.75 0 0 1-.6 0l-9-4.5a.75.75 0 0 1 0-1.39l9-4.5Z"/>
                        <path d="M3 10.94v4.31c0 .502.3.958.764 1.158l7.5 3.214a.75.75 0 0 0 .472 0l7.5-3.214A1.25 1.25 0 0 0 21 15.25v-4.31l-8.7 4.35a2.25 2.25 0 0 1-2.02 0L3 10.94Z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-slate-900">{{ $countGuru }}</p>
                    <p class="text-sm text-slate-500">Data Guru</p>
                </div>
            </div>
            <a href="#" class="text-sm text-emerald-500 mt-5 inline-block hover:underline">Lihat Detail</a>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition">
            <div class="flex items-center gap-4">
                <div class="bg-sky-100 p-4 rounded-3xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-6 h-6 text-sky-600">
                        <path d="M16 11c1.657 0 3-1.567 3-3.5S17.657 4 16 4s-3 1.567-3 3.5S14.343 11 16 11Z"/>
                        <path d="M8 11c1.657 0 3-1.567 3-3.5S9.657 4 8 4 5 5.567 5 7.5 6.343 11 8 11Z"/>
                        <path d="M8 13c-2.761 0-5 1.79-5 4v1h10v-1c0-2.21-2.239-4-5-4Z"/>
                        <path d="M16 13c-.508 0-.993.065-1.45.185A6.978 6.978 0 0 1 18 17v1h4v-1c0-2.21-2.239-4-5-4Z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-slate-900">{{ $countSiswa }}</p>
                    <p class="text-sm text-slate-500">Data Siswa</p>
                </div>
            </div>
            <a href="#" class="text-sm text-sky-500 mt-5 inline-block hover:underline">Lihat Detail</a>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition">
            <div class="flex items-center gap-4">
                <div class="bg-amber-100 p-4 rounded-3xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-6 h-6 text-amber-600">
                        <path d="M12 3.172 2.25 10.5V20.25A.75.75 0 0 0 3 21h6.75v-6h4.5v6H21a.75.75 0 0 0 .75-.75V10.5L12 3.172Z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-slate-900">{{ $countOrangTua }}</p>
                    <p class="text-sm text-slate-500">Data Orang Tua</p>
                </div>
            </div>
            <a href="#" class="text-sm text-amber-600 mt-5 inline-block hover:underline">Lihat Detail</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 px-4">
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition">
            <div class="flex items-center gap-4">
                <div class="bg-amber-100 p-4 rounded-3xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-6 h-6 text-amber-600">
                        <path d="M3 21V7.5A1.5 1.5 0 0 1 4.5 6H9v15H3Z"/>
                        <path d="M9 21V3h6v18H9Z"/>
                        <path d="M15 21V10.5A1.5 1.5 0 0 1 16.5 9H21v12h-6Z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-slate-900">{{ $countKelas }}</p>
                    <p class="text-sm text-slate-500">Data Kelas</p>
                </div>
            </div>
            <a href="#" class="text-sm text-amber-600 mt-5 inline-block hover:underline">Lihat Detail</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 px-4">
        <div class="lg:col-span-1 bg-white rounded-3xl p-6 shadow-sm border border-emerald-200">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Grafik Absensi</h2>
                </div>
                <div class="flex flex-col gap-2 sm:flex-row">
                    <div x-data="{ open: false, selected: 'April 2026' }" class="relative">
                        <button @click="open = !open" class="inline-flex items-center justify-between gap-2 rounded-3xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 w-full sm:w-auto">
                            <span x-text="selected"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition @click.outside="open = false" class="absolute right-0 z-20 mt-2 w-48 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg">
                            <button @click="selected = 'Januari 2026'; open = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Januari 2026</button>
                            <button @click="selected = 'Februari 2026'; open = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Februari 2026</button>
                            <button @click="selected = 'Maret 2026'; open = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Maret 2026</button>
                            <button @click="selected = 'April 2026'; open = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">April 2026</button>
                        </div>
                    </div>
                    <div x-data="{ open: false, selected: 'Kelas III A' }" class="relative">
                        <button @click="open = !open" class="inline-flex items-center justify-between gap-2 rounded-3xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 w-full sm:w-auto">
                            <span x-text="selected"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition @click.outside="open = false" class="absolute right-0 z-20 mt-2 w-48 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg">
                            <button @click="selected = 'Kelas I A'; open = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Kelas I A</button>
                            <button @click="selected = 'Kelas II A'; open = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Kelas II A</button>
                            <button @click="selected = 'Kelas III A'; open = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Kelas III A</button>
                            <button @click="selected = 'Kelas III B'; open = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Kelas III B</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="absenceChart"></canvas>
            </div>
            <a href="#" class="text-blue-500 text-sm font-medium mt-4 inline-block hover:underline">Lihat Detail</a>
        </div>

        <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-sm border border-emerald-200" x-data="pengumumanDashboard()" x-init="initPengumuman()">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Pengumuman</h2>
                </div>
                <div class="relative">
                        <button @click="open = !open" class="inline-flex items-center justify-between gap-2 rounded-3xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 w-full sm:w-auto">
                            <span x-text="selectedClassName"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition @click.outside="open = false" class="absolute right-0 z-20 mt-2 w-48 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg" style="display: none;">
                            <button @click="selectClass('', 'Semua Kelas')" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Semua Kelas</button>
                            <template x-for="k in classes" :key="k.id_kelas">
                                <button @click="selectClass(k.id_kelas, k.nama_kelas)" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100" x-text="k.nama_kelas"></button>
                            </template>
                        </div>
                    </div>
            </div>
            <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                <template x-if="isLoading">
                    <p class="text-sm text-slate-500">Memuat pengumuman...</p>
                </template>
                <template x-if="!isLoading && filteredPengumuman.length === 0">
                    <p class="text-sm text-slate-500">Belum ada pengumuman.</p>
                </template>
                <template x-for="p in filteredPengumuman" :key="p.id_pengumuman">
                    <div class="border-l-4 pl-4 py-3" :class="p.kelas_id ? 'border-blue-500' : 'border-green-500'">
                        <p class="text-gray-700 font-medium" x-text="p.judul"></p>
                        <p class="text-gray-500 text-sm mt-1" x-text="p.deskripsi.substring(0, 100) + (p.deskripsi.length > 100 ? '...' : '')"></p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs text-gray-400" x-text="p.created_at_human"></span>
                            <template x-if="p.kelas_id">
                                <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full" x-text="p.kelas_nama"></span>
                            </template>
                            <template x-if="!p.kelas_id">
                                <span class="text-xs bg-green-50 text-green-600 px-2 py-0.5 rounded-full">Semua Kelas</span>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
            <a href="{{ route('admin.pengumuman') }}" class="text-blue-500 text-sm font-medium mt-6 inline-block hover:underline">Lihat Semua Pengumuman</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 px-4 mb-8">
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-sm border border-emerald-200">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
                <h2 class="text-xl font-semibold text-slate-900">Dokumentasi</h2>
                <div x-data="{ open: false, selected: 'Kelas III A' }" class="relative">
                    <button @click="open = !open" class="inline-flex items-center justify-between gap-2 rounded-3xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 w-full sm:w-auto">
                        <span x-text="selected"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition @click.outside="open = false" class="absolute right-0 z-20 mt-2 w-48 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg">
                        <button @click="selected = 'Kelas I A'; open = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Kelas I A</button>
                        <button @click="selected = 'Kelas II A'; open = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Kelas II A</button>
                        <button @click="selected = 'Kelas III A'; open = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Kelas III A</button>
                        <button @click="selected = 'Kelas III B'; open = false" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Kelas III B</button>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-[140px_minmax(0,1fr)] md:items-center">
                <div class="overflow-hidden rounded-3xl">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=900&h=450&fit=crop" alt="Upacara Bendera" class="h-28 w-full object-cover md:h-24 md:w-36" />
                </div>
                <div>
                    <p class="text-base font-semibold text-slate-900">Upacara Bendera</p>
                    <p class="text-sm text-slate-600 leading-relaxed">Upacara bendera hari Senin merupakan kegiatan rutin yang dilaksanakan setiap awal pekan sebagai bentuk penghormatan nilai kedisiplinan, tanggung jawab, dan rasa cinta tanah air.</p>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <a href="#" class="text-blue-500 text-sm font-medium hover:underline">Lihat Detail</a>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('absenceChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Sakit', 'Izin', 'Alpha'],
                    datasets: [{
                        data: [8, 12, 2, 11],
                        backgroundColor: ['#3B82F6', '#F97316', '#FCD34D', '#A0AEC0'],
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

    document.addEventListener('alpine:init', () => {
        Alpine.data('pengumumanDashboard', () => ({
            open: false,
            selectedClassId: '',
            selectedClassName: 'Semua Kelas',
            classes: [],
            pengumuman: [],
            isLoading: true,
            
            async initPengumuman() {
                this.isLoading = true;
                try {
                    // Fetch classes
                    const classRes = await fetch('/admin/kelas/data');
                    if (classRes.ok) {
                        this.classes = await classRes.json();
                    }
                    
                    // Fetch pengumuman
                    const pengRes = await fetch('/api/pengumuman');
                    if (pengRes.ok) {
                        const data = await pengRes.json();
                        if (data.success) {
                            this.pengumuman = data.data;
                        }
                    }
                } catch (e) {
                    console.error('Error fetching data', e);
                } finally {
                    this.isLoading = false;
                }
            },
            
            selectClass(id, name) {
                this.selectedClassId = id;
                this.selectedClassName = name;
                this.open = false;
            },
            
            get filteredPengumuman() {
                const today = new Date().toISOString().split('T')[0];
                let activePengumuman = this.pengumuman.filter(p => {
                    const mulai = p.tanggal_mulai;
                    const selesai = p.tanggal_selesai;
                    if (mulai && mulai > today) return false;
                    if (selesai && selesai < today) return false;
                    return true;
                });

                if (!this.selectedClassId) {
                    return activePengumuman.slice(0, 5); // Tampilkan maksimal 5 jika "Semua Kelas"
                }
                return activePengumuman.filter(p => p.kelas_id == this.selectedClassId || p.kelas_id == null).slice(0, 5);
            }
        }));
    });
</script>
