<script src="//unpkg.com/alpinejs" defer></script>

@php
    $route = request()->route()?->getName();
    $absensiActive = in_array($route, ['admin.absensi', 'admin.absensi.rekap', 'admin.absensi.detail', 'admin.absensi.pilih-bulan'], true);
    $dokumentasiActive = request()->routeIs('admin.dokumentasi*');
    $pengumumanActive = request()->routeIs('admin.pengumuman*');
    $biodataActive =
        request()->routeIs('admin.biodata*') ||
        request()->routeIs('admin.guru*') ||
        request()->routeIs('admin.siswa*') ||
        request()->routeIs('admin.orangtua*');
    $sekolahActive =
        request()->routeIs('admin.kelas*') ||
        request()->routeIs('admin.tahun-pelajaran*') ||
        request()->routeIs('admin.mata-pelajaran*') ||
        request()->routeIs('admin.jam-pelajaran*');
@endphp

{{-- ═══════════════════════════════════════════
     MOBILE: Hamburger Button (muncul di < lg)
═══════════════════════════════════════════ --}}
<div x-data="{ open: false }" class="relative">

    {{-- Hamburger trigger (hanya di mobile/tablet) --}}
    <button
        @click="open = true"
        class="lg:hidden fixed top-3 left-3 z-50 flex items-center justify-center w-10 h-10 rounded-xl bg-[#1E2567] text-white shadow-lg"
        aria-label="Buka Menu"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    {{-- ── Overlay gelap (mobile) ── --}}
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

    {{-- ── Sidebar Drawer (mobile) ── --}}
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
        {{-- Header drawer --}}
        <div class="p-4 text-2xl font-bold border-b border-white/20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-white p-1 shadow-sm flex items-center justify-center">
                    <img src="{{ asset('storage/aksara.png') }}" alt="Aksara Logo" class="h-full w-full object-contain" />
                </div>
                <span>AKSARA</span>
            </div>
            {{-- Tombol tutup --}}
            <button @click="open = false" class="text-white/70 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="sidebar-scrollbar flex-1 p-4 space-y-2 overflow-y-auto">
            <div class="my-6 text-slate-400 uppercase text-xs tracking-wider">Halaman</div>

            <a href="{{ route('admin.dashboard') }}"
                class="block px-3 py-2 rounded transition
                {{ $route === 'admin.dashboard' ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
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
                {{ $dokumentasiActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Dokumentasi
            </a>

            <a href="{{ route('admin.pengumuman') }}"
                class="block px-3 py-2 rounded transition
                {{ $pengumumanActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Pengumuman
            </a>

            <a href="{{ route('admin.jadwal') }}"
                class="block px-3 py-2 rounded transition
                {{ $route === 'admin.jadwal' ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Jadwal
            </a>

            <div class="my-4 text-slate-400 uppercase text-xs tracking-wider">Data</div>

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
                        {{ $biodataActive && request()->routeIs('admin.biodata*') ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                        Admin
                    </a>
                    <a href="{{ route('admin.guru.index') }}"
                        class="block px-3 py-2 rounded transition
                        {{ $biodataActive && request()->routeIs('admin.guru*') ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                        Guru
                    </a>
                    <a href="{{ route('admin.siswa.index') }}"
                        class="block px-3 py-2 rounded transition
                        {{ $biodataActive && request()->routeIs('admin.siswa*') ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                        Siswa
                    </a>
                    <a href="{{ route('admin.orangtua.index') }}"
                        class="block px-3 py-2 rounded transition
                        {{ $biodataActive && request()->routeIs('admin.orangtua*') ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                        Orang Tua
                    </a>
                </div>
            </div>

            <div x-data="{ open: {{ $sekolahActive ? 'true' : 'false' }} }" class="relative">
                <button @click="open = !open"
                    class="w-full flex justify-between items-center px-3 py-2 rounded transition
                    {{ $sekolahActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                    Sekolah
                    <span x-bind:class="{'rotate-180': open}" class="transition-transform">&#9662;</span>
                </button>
                <div x-show="open" x-transition class="mt-1 ml-2 space-y-1">
                    <a href="{{ route('admin.kelas') }}"
                    class="block px-3 py-2 rounded transition
                    {{ request()->routeIs('admin.kelas*')
                        ? 'bg-[#F59E0B] text-slate-950'
                        : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                        Kelas
                    </a>
                    <a href="{{ route('admin.tahun-pelajaran') }}"
                        class="block px-3 py-2 rounded transition
                        {{ request()->routeIs('admin.tahun-pelajaran*')
                            ? 'bg-[#F59E0B] text-slate-950'
                            : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                        Tahun Pelajaran
                    </a>
                    <a href="{{ route('admin.mata-pelajaran') }}"
                        class="block px-3 py-2 rounded transition
                        {{ request()->routeIs('admin.mata-pelajaran*')
                            ? 'bg-[#F59E0B] text-slate-950'
                            : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                        Mata Pelajaran
                    </a>
                    <a href="{{ route('admin.jam-pelajaran') }}"
                        class="block px-3 py-2 rounded transition
                        {{ request()->routeIs('admin.jam-pelajaran*')
                            ? 'bg-[#F59E0B] text-slate-950'
                            : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                        Jam Pelajaran
                    </a>
                </div>
            </div>
        </nav>
    </aside>

    {{-- ═══════════════════════════════════════════
         DESKTOP: Sidebar tetap (hanya di >= lg)
    ═══════════════════════════════════════════ --}}
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
            <div class="my-6 text-slate-400 uppercase text-xs tracking-wider">Halaman</div>

            <a href="{{ route('admin.dashboard') }}"
                class="block px-3 py-2 rounded transition
                {{ $route === 'admin.dashboard' ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
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
                {{ $dokumentasiActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Dokumentasi
            </a>

            <a href="{{ route('admin.pengumuman') }}"
                class="block px-3 py-2 rounded transition
                {{ $pengumumanActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Pengumuman
            </a>

            <a href="{{ route('admin.jadwal') }}"
                class="block px-3 py-2 rounded transition
                {{ $route === 'admin.jadwal' ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Jadwal
            </a>

            <div class="my-4 text-slate-400 uppercase text-xs tracking-wider">Data</div>

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
                        {{ $biodataActive && request()->routeIs('admin.biodata*') ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                        Admin
                    </a>
                    <a href="{{ route('admin.guru.index') }}"
                        class="block px-3 py-2 rounded transition
                        {{ $biodataActive && request()->routeIs('admin.guru*') ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                        Guru
                    </a>
                    <a href="{{ route('admin.siswa.index') }}"
                        class="block px-3 py-2 rounded transition
                        {{ $biodataActive && request()->routeIs('admin.siswa*') ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                        Siswa
                    </a>
                    <a href="{{ route('admin.orangtua.index') }}"
                        class="block px-3 py-2 rounded transition
                        {{ $biodataActive && request()->routeIs('admin.orangtua*') ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                        Orang Tua
                    </a>
                </div>
            </div>

            <div x-data="{ open: {{ $sekolahActive ? 'true' : 'false' }} }" class="relative">
                <button @click="open = !open"
                    class="w-full flex justify-between items-center px-3 py-2 rounded transition
                    {{ $sekolahActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                    Sekolah
                    <span x-bind:class="{'rotate-180': open}" class="transition-transform">&#9662;</span>
                </button>
                <div x-show="open" x-transition class="mt-1 ml-2 space-y-1">
                    <a href="{{ route('admin.kelas') }}"
                    class="block px-3 py-2 rounded transition
                    {{ request()->routeIs('admin.kelas*')
                        ? 'bg-[#F59E0B] text-slate-950'
                        : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                        Kelas
                    </a>
                    <a href="{{ route('admin.tahun-pelajaran') }}"
                        class="block px-3 py-2 rounded transition
                        {{ request()->routeIs('admin.tahun-pelajaran*')
                            ? 'bg-[#F59E0B] text-slate-950'
                            : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                        Tahun Pelajaran
                    </a>
                    <a href="{{ route('admin.mata-pelajaran') }}"
                        class="block px-3 py-2 rounded transition
                        {{ request()->routeIs('admin.mata-pelajaran*')
                            ? 'bg-[#F59E0B] text-slate-950'
                            : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                        Mata Pelajaran
                    </a>
                    <a href="{{ route('admin.jam-pelajaran') }}"
                        class="block px-3 py-2 rounded transition
                        {{ request()->routeIs('admin.jam-pelajaran*')
                            ? 'bg-[#F59E0B] text-slate-950'
                            : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                        Jam Pelajaran
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