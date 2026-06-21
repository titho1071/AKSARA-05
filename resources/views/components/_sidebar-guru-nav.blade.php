{{-- Nav links Guru – dipakai di desktop sidebar & mobile drawer --}}
@php
    $route = request()->route()?->getName();
    $dashboardActive      = in_array($route, ['guru.dashboard'], true);
    $absensiActive        = in_array($route, ['guru.absensi','guru.absensi.recap','guru.absensi.kelola','guru.absensi.detail','guru.absensi.pilih-bulan'], true);
    $kelolaAbsensiActive  = in_array($route, ['guru.absensi','guru.absensi.kelola','guru.absensi.detail','guru.absensi.pilih-bulan'], true);
    $recapAbsensiActive   = $route === 'guru.absensi.rekap';
    $dokumentasiActive    = in_array($route, ['guru.dokumentasi.index','guru.dokumentasi.create','guru.dokumentasi.edit','guru.dokumentasi.show'], true);
    $pengumumanActive     = in_array($route, ['guru.pengumuman','guru.pengumuman.create','guru.pengumuman.edit','guru.pengumuman.show'], true);
    $jadwalActive         = in_array($route, ['guru.jadwal'], true);
    $siswaActive          = in_array($route, ['guru.siswa.index','guru.siswa.edit','guru.siswa.show'], true);
@endphp

<nav class="sidebar-scrollbar flex-1 p-4 space-y-2 overflow-y-auto">
    <div class="my-6 text-slate-400 uppercase text-xs tracking-wider">Dashboard</div>

    <a href="{{ route('guru.dashboard') }}"
        class="block px-3 py-2 rounded transition
        {{ $dashboardActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
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
            <a href="{{ route('guru.absensi') }}"
                class="block px-3 py-2 rounded transition
                {{ $kelolaAbsensiActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Kelola Absensi
            </a>
            <a href="{{ route('guru.absensi.rekap') }}"
                class="block px-3 py-2 rounded transition
                {{ $recapAbsensiActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Recap Absensi
            </a>
        </div>
    </div>

    <a href="{{ route('guru.dokumentasi.index') }}"
        class="block px-3 py-2 rounded transition
        {{ $dokumentasiActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
        Dokumentasi
    </a>

    <a href="{{ route('guru.pengumuman') }}"
        class="block px-3 py-2 rounded transition
        {{ $pengumumanActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
        Pengumuman
    </a>

    <a href="{{ route('guru.jadwal') }}"
        class="block px-3 py-2 rounded transition
        {{ $jadwalActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
        Jadwal
    </a>

    <div class="my-4 text-slate-400 uppercase text-xs tracking-wider">Data</div>

    {{-- Wali Kelas dropdown --}}
    <div x-data="{ open: {{ $siswaActive ? 'true' : 'false' }} }" class="relative">
        <button @click="open = !open"
            class="w-full flex justify-between items-center px-3 py-2 rounded transition
            {{ $siswaActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
            Wali Kelas
            <span x-bind:class="{'rotate-180': open}" class="transition-transform">&#9662;</span>
        </button>
        <div x-show="open" x-transition class="mt-1 ml-2 space-y-1">
            <a href="{{ route('guru.siswa.index') }}"
                class="block px-3 py-2 rounded transition
                {{ $siswaActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Data Siswa Kelas
            </a>
        </div>
    </div>
</nav>
