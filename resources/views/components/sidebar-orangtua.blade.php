<script src="//unpkg.com/alpinejs" defer></script>

@php
    $route = request()->route()?->getName();
    $pengumumanActive = request()->routeIs('orangtua.pengumuman*');
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

            <a href="{{ route('orangtua.dashboard') }}"
                class="block px-3 py-2 rounded transition
                {{ $route === 'orangtua.dashboard' ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Dashboard
            </a>

            <a href="{{ route('orangtua.absensi') }}"
                class="block px-3 py-2 rounded transition
                {{ request()->routeIs('orangtua.absensi') ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Absensi
            </a>

            <a href="{{ route('orangtua.dokumentasi') }}"
                class="block px-3 py-2 rounded transition
                {{ str_contains($route, 'orangtua.dokumentasi') ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Dokumentasi
            </a>

            <a href="{{ route('orangtua.pengumuman') }}"
                class="block px-3 py-2 rounded transition
                {{ $pengumumanActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Pengumuman
            </a>

            <a href="{{ route('orangtua.jadwal') }}"
                class="block px-3 py-2 rounded transition
                {{ $route === 'orangtua.jadwal' ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Jadwal
            </a>
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

            <a href="{{ route('orangtua.dashboard') }}"
                class="block px-3 py-2 rounded transition
                {{ $route === 'orangtua.dashboard' ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Dashboard
            </a>

            <a href="{{ route('orangtua.absensi') }}"
                class="block px-3 py-2 rounded transition
                {{ request()->routeIs('orangtua.absensi') ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Absensi
            </a>

            <a href="{{ route('orangtua.dokumentasi') }}"
                class="block px-3 py-2 rounded transition
                {{ str_contains($route, 'orangtua.dokumentasi') ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Dokumentasi
            </a>

            <a href="{{ route('orangtua.pengumuman') }}"
                class="block px-3 py-2 rounded transition
                {{ $pengumumanActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Pengumuman
            </a>

            <a href="{{ route('orangtua.jadwal') }}"
                class="block px-3 py-2 rounded transition
                {{ $route === 'orangtua.jadwal' ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Jadwal
            </a>
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
