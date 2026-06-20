<script src="//unpkg.com/alpinejs" defer></script>

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

        @include('components._sidebar-admin-nav')
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

        @include('components._sidebar-admin-nav')
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