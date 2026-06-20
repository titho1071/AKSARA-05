{{-- Navigasi sidebar – dipakai di desktop & mobile drawer --}}
@php
    $route = request()->route()?->getName();
    $absensiActive = in_array($route, ['admin.absensi', 'admin.absensi.rekap', 'admin.absensi.detail', 'admin.absensi.pilih-bulan'], true);
    $biodataRoutes = [
        'admin.biodata.index', 'admin.biodata.create',
        'admin.guru.index', 'admin.guru.create',
        'admin.orangtua.index', 'admin.orangtua.create',
        'admin.siswa.index',
    ];
    $biodataActive = in_array($route, $biodataRoutes);
    $sekolahRoutes = ['admin.kelas', 'admin.tahun-pelajaran'];
    $sekolahActive = in_array($route, $sekolahRoutes);
@endphp

<nav class="sidebar-scrollbar flex-1 p-4 space-y-2 overflow-y-auto">

    {{-- HALAMAN --}}
    <div class="my-6 text-slate-400 uppercase text-xs tracking-wider">Halaman</div>

    <a href="{{ route('admin.dashboard') }}"
        class="block px-3 py-2 rounded transition
        {{ $route === 'admin.dashboard' ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
        Dashboard
    </a>

    {{-- Absensi dropdown --}}
    <div x-data="{ open: {{ $absensiActive ? 'true' : 'false' }} }" class="relative">
        <button @click="open = !open"
            class="w-full flex justify-between items-center px-3 py-2 rounded transition
            {{ $absensiActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
            Absensi
            <span x-bind:class="{'rotate-180': open}" class="transition-transform">&#9662;</span>
        </button>
        <div x-show="open" x-transition class="mt-1 ml-2 space-y-1">
            <a href="{{ route('admin.absensi') }}"
                class="block px-3 py-2 rounded transition
                {{ in_array($route, ['admin.absensi', 'admin.absensi.pilih-bulan', 'admin.absensi.detail']) ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Absensi
            </a>
            <a href="{{ route('admin.absensi.rekap') }}"
                class="block px-3 py-2 rounded transition
                {{ $route === 'admin.absensi.rekap' ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Rekap Absensi
            </a>
        </div>
    </div>

    <a href="{{ route('admin.dokumentasi') }}"
        class="block px-3 py-2 rounded transition
        {{ $route === 'admin.dokumentasi' ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
        Dokumentasi
    </a>

    <a href="{{ route('admin.pengumuman') }}"
        class="block px-3 py-2 rounded transition
        {{ $route === 'admin.pengumuman' ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
        Pengumuman
    </a>

    <a href="{{ route('admin.jadwal') }}"
        class="block px-3 py-2 rounded transition
        {{ $route === 'admin.jadwal' ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
        Jadwal
    </a>

    {{-- DATA --}}
    <div class="my-4 text-slate-400 uppercase text-xs tracking-wider">Data</div>

    {{-- Dropdown Biodata --}}
    <div x-data="{ open: {{ $biodataActive ? 'true' : 'false' }} }" class="relative">
        <button @click="open = !open"
            class="w-full flex justify-between items-center px-3 py-2 rounded transition
            {{ $biodataActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
            Biodata
            <span x-bind:class="{'rotate-180': open}" class="transition-transform">&#9662;</span>
        </button>
        <div x-show="open" x-transition class="mt-1 ml-2 space-y-1">
            <a href="{{ route('admin.biodata.index') }}"
                class="block px-3 py-2 rounded transition
                {{ $route === 'admin.biodata.index' ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Admin
            </a>
            <a href="{{ route('admin.guru.index') }}"
                class="block px-3 py-2 rounded transition
                {{ in_array($route, ['admin.guru.index', 'admin.guru.create']) ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Guru
            </a>
            <a href="{{ route('admin.siswa.index') }}"
                class="block px-3 py-2 rounded transition
                {{ in_array($route, ['admin.siswa.index', 'admin.siswa.create']) ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Siswa
            </a>
            <a href="{{ route('admin.orangtua.index') }}"
                class="block px-3 py-2 rounded transition
                {{ in_array($route, ['admin.orangtua.index', 'admin.orangtua.create']) ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Orang Tua
            </a>
        </div>
    </div>

    {{-- Dropdown Sekolah --}}
    <div x-data="{ open: {{ $sekolahActive ? 'true' : 'false' }} }" class="relative">
        <button @click="open = !open"
            class="w-full flex justify-between items-center px-3 py-2 rounded transition
            {{ $sekolahActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
            Sekolah
            <span x-bind:class="{'rotate-180': open}" class="transition-transform">&#9662;</span>
        </button>
        <div x-show="open" x-transition class="mt-1 ml-2 space-y-1">
            <a href="{{ route('admin.kelas') }}"
                class="block px-3 py-2 rounded transition bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950">
                Kelas
            </a>
            <a href="{{ route('admin.tahun-pelajaran') }}"
                class="block px-3 py-2 rounded transition bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950">
                Tahun Pelajaran
            </a>
            <a href="{{ route('admin.mata-pelajaran') }}"
                class="block px-3 py-2 rounded transition bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950">
                Mata Pelajaran
            </a>
            <a href="{{ route('admin.jam-pelajaran') }}"
                class="block px-3 py-2 rounded transition bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950">
                Jam Pelajaran
            </a>
        </div>
    </div>
</nav>
