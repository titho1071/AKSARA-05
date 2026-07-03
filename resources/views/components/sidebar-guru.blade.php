<script src="//unpkg.com/alpinejs" defer></script>

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

<div x-data="{ open: false }" class="relative">

    {{-- ── Hamburger Button (mobile / tablet < lg) ── --}}
    <button
        @click="open = true"
        class="lg:hidden fixed top-3 left-3 z-50 flex items-center justify-center w-10 h-10 rounded-xl bg-[#1E2567] text-white shadow-lg"
        aria-label="Buka Menu"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    {{-- ── Overlay (mobile) ── --}}
    <div
        x-show="open"
        x-transition:enter="transition-opacity duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="lg:hidden fixed inset-0 z-40 bg-black/50"
        style="display:none"
    ></div>

    {{-- ── Mobile Drawer ── --}}
    <aside
        x-show="open"
        x-transition:enter="transition-transform duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="lg:hidden fixed inset-y-0 left-0 z-50 w-64 flex flex-col bg-[#1E2567] text-white rounded-e-2xl border-r border-white/10 shadow-2xl"
        style="display:none"
    >
        <div class="p-4 text-2xl font-bold border-b border-white/20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-white p-1 shadow-sm flex items-center justify-center">
                    <img src="{{ asset('storage/aksara.png') }}" alt="Aksara Logo" class="h-full w-full object-contain" />
                </div>
                <span>AKSARA</span>
            </div>
            <button @click="open = false" class="text-white/70 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <nav class="sidebar-scrollbar flex-1 p-4 space-y-2 overflow-y-auto">
            <div class="my-6 text-slate-400 uppercase text-xs tracking-wider">Dashboard</div>

            <a href="{{ route('guru.dashboard') }}"
                class="block px-3 py-2 rounded transition
                {{ $dashboardActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Dashboard
            </a>

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
    </aside>

    {{-- ── Desktop Sidebar (tetap, hanya >= lg) ── --}}
    <aside class="hidden lg:flex fixed inset-y-0 left-0 z-40 w-56 flex-col bg-[#1E2567] text-white rounded-e-md border-r border-white/10">
        <div class="p-4 text-2xl font-bold border-b border-white/20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-white p-1 shadow-sm flex items-center justify-center">
                    <img src="{{ asset('storage/aksara.png') }}" alt="Aksara Logo" class="h-full w-full object-contain" />
                </div>
                <span>AKSARA</span>
            </div>
        </div>
        <nav class="sidebar-scrollbar flex-1 p-4 space-y-2 overflow-y-auto">
            <div class="my-6 text-slate-400 uppercase text-xs tracking-wider">Dashboard</div>

            <a href="{{ route('guru.dashboard') }}"
                class="block px-3 py-2 rounded transition
                {{ $dashboardActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Dashboard
            </a>

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
    </aside>

</div>

<style>
    .sidebar-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(148, 163, 184, 0.6) transparent;
    }
    .sidebar-scrollbar::-webkit-scrollbar { width: 8px; }
    .sidebar-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .sidebar-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(148, 163, 184, 0.6);
        border-radius: 9999px;
    }
    .sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: rgba(100, 116, 139, 0.8);
    }
</style>
