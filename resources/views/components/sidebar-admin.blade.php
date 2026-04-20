<script src="//unpkg.com/alpinejs" defer></script>

<!-- Sidebar -->
<aside class="hidden lg:flex fixed inset-y-0 left-0 z-40 w-56 flex-col bg-[#1E2567] text-white rounded-e-md border-r border-white/10">
    <div class="p-4 text-2xl font-bold border-b border-white/20 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-full bg-white p-1 shadow-sm flex items-center justify-center">
                <img src="{{ asset('storage/aksara.png') }}" alt="Aksara Logo" class="h-full w-full object-contain" />
            </div>
            <span>AKSARA</span>
        </div>
    </div>

    @php $route = request()->route()?->getName(); @endphp

    <nav class="sidebar-scrollbar flex-1 p-4 space-y-2 overflow-y-auto">

        {{-- DASHBOARD --}}
        <div class="my-6 text-slate-400 uppercase text-xs tracking-wider">Dashboard</div>

        <a href="{{ route('admin.dashboard') }}"
            class="block px-3 py-2 rounded transition
            {{ $route === 'admin.dashboard' ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
            Dashboard
        </a>

        <a href="{{ route('admin.absensi') }}"
            class="block px-3 py-2 rounded transition bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950">
            Absensi
        </a>

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

        {{-- MASTER DATA --}}
        <div class="my-4 text-slate-400 uppercase text-xs tracking-wider">Master Data</div>

        {{-- Dropdown Biodata --}}
        @php
            $biodataRoutes = [
                'admin.biodata.index', 'admin.biodata.create',
                'admin.guru.index', 'admin.guru.create',
                'admin.orangtua.index', 'admin.orangtua.create',
                'admin.siswa.index',
            ];
            $biodataActive = in_array($route, $biodataRoutes);
        @endphp
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
                <a href="#"
                    class="block px-3 py-2 rounded transition bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950">
                    Siswa
                </a>
                <a href="{{ route('admin.orangtua.index') }}"
                    class="block px-3 py-2 rounded transition
                    {{ in_array($route, ['admin.orangtua.index', 'admin.orangtua.create']) ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                    Orang Tua
                </a>
            </div>
        </div>

        {{-- Dropdown Data Sekolah --}}
        @php
            $sekolahRoutes = ['admin.kelas', 'admin.tahun-pelajaran'];
            $sekolahActive = in_array($route, $sekolahRoutes);
        @endphp
        <div x-data="{ open: {{ $sekolahActive ? 'true' : 'false' }} }" class="relative">
            <button @click="open = !open"
                class="w-full flex justify-between items-center px-3 py-2 rounded transition
                {{ $sekolahActive ? 'bg-[#F59E0B] text-slate-950' : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Data Sekolah
                <span x-bind:class="{'rotate-180': open}" class="transition-transform">&#9662;</span>
            </button>
            <div x-show="open" x-transition class="mt-1 ml-2 space-y-1">
                <a href="#"
                    class="block px-3 py-2 rounded transition bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950">
                    Kelas
                </a>
                <a href="#"
                    class="block px-3 py-2 rounded transition bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950">
                    Tahun Pelajaran
                </a>
            </div>
        </div>

        {{-- SAYA --}}
        <div class="my-6 text-slate-400 uppercase text-xs tracking-wider">Saya</div>

        <a href="#"
            class="block px-3 py-2 rounded transition bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950">
            Profil
        </a>

        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit"
                class="w-full text-left block px-3 py-2 rounded transition bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950">
                Logout
            </button>
        </form>

    </nav>

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

</aside>