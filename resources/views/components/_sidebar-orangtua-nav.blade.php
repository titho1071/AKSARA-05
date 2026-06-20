{{-- Nav links Orangtua – dipakai di desktop sidebar & mobile drawer --}}
@php
    $route = request()->route()?->getName();
@endphp

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
        {{ $route === 'orangtua.pengumuman' ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
        Pengumuman
    </a>

    <a href="{{ route('orangtua.jadwal') }}"
        class="block px-3 py-2 rounded transition
        {{ $route === 'orangtua.jadwal' ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
        Jadwal
    </a>
</nav>
