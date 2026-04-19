<script src="//unpkg.com/alpinejs" defer></script>

<!-- Sidebar -->
<aside class="hidden lg:flex fixed inset-y-0 left-0 z-40 w-56 flex-col bg-[#1E2567] text-white rounded-e-md">
    <div class="p-4 text-2xl font-bold border-b border-white/20 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-full bg-white p-1 shadow-sm flex items-center justify-center">
                <img src="{{ asset('storage/aksara.png') }}" alt="Aksara Logo" class="h-full w-full object-contain" />
            </div>
            <span>AKSARA</span>
        </div>
    </div>

    @php
        $route = request()->route()?->getName();
        $dashboardActive = in_array($route, ['guru.dashboard'], true);
        $absensiActive = in_array($route, ['guru.absensi', 'guru.absensi.recap', 'guru.absensi.kelola'], true);
    @endphp
    <nav class="sidebar-scrollbar flex-1 p-4 space-y-2 overflow-y-auto">
        <div class="my-6 form-label text-slate-400 uppercase text-xs tracking-wider">Dashboard</div>

        <a href="{{ route('guru.dashboard') }}"
            class="block px-3 py-2 rounded transition 
            {{ $dashboardActive 
                ? 'bg-[#F59E0B] text-slate-950' 
                : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }} ">
            Dashboard
        </a>

        <!-- Dropdown Menu Absensi -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                class="w-full flex justify-between items-center px-3 py-2 rounded transition 
                {{ $absensiActive 
                    ? 'bg-[#F59E0B] text-slate-950' 
                    : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Absensi
                <span x-bind:class="{'rotate-180': open}" class="transition-transform">&#9662;</span>
            </button>

            <div x-show="open" x-transition class="mt-1 ml-2 space-y-1">
                <a href="{{ route('guru.absensi') }}"
                    class="block px-3 py-2 rounded transition 
                    {{ $route === 'guru.absensi' 
                        ? 'bg-[#F59E0B] text-slate-950' 
                        : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                    Kelola Absensi
                </a>
                <a href="{{ route('guru.absensi.recap') }}"
                    class="block px-3 py-2 rounded transition 
                    {{ $route === 'guru.absensi.recap' 
                        ? 'bg-[#F59E0B] text-slate-950' 
                        : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                    Recap Absensi
                </a>
            </div>
        </div>

        <a href="#"
            class="block px-3 py-2 rounded transition 
            {{ $route === 'dokumentasi' 
                ? 'bg-[#F59E0B] text-slate-950' 
                : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
            Dokumentasi
        </a>

        <a href="#"
            class="block px-3 py-2 rounded transition 
            {{ $route === 'pengumuman' 
                ? 'bg-[#F59E0B] text-slate-950' 
                : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
            Pengumuman
        </a>

        <a href="#"
            class="block px-3 py-2 rounded transition 
            {{ $route === 'jadwal' 
                ? 'bg-[#F59E0B] text-slate-950' 
                : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
            Jadwal
        </a>

        <div class="my-4 form-label text-slate-400 uppercase text-xs tracking-wider">Data</div>

        <!-- Dropdown Menu -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                class="w-full flex justify-between items-center px-3 py-2 rounded transition 
                {{ in_array($route, ['dashboard.kelas']) 
                    ? 'bg-[#F59E0B] text-slate-950' 
                    : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Wali Kelas
                <span x-bind:class="{'rotate-180': open}" class="transition-transform">&#9662;</span>
            </button>

            <div x-show="open" x-transition class="mt-1 ml-2 space-y-1">
                <a href="#"
                    class="block px-3 py-2 rounded transition 
                    {{ $route === 'dashboard.kelas' 
                        ? 'bg-[#F59E0B] text-slate-950' 
                        : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                    Kelas
                </a>
            </div>
        </div>
        <div class="my-6 form-label text-slate-400 uppercase text-xs tracking-wider">Saya</div>
            <a 
            class="block px-3 py-2 rounded transition 
            {{ $route === 'dashboard.profil' 
                ? 'bg-[#F59E0B] text-slate-950' 
                : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
            Profil
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="w-full text-left block rounded-3xl px-3 py-2 bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950 transition">
                    Logout
                </button>
            </form>
        </div>
    </nav>
<style>
        .sidebar-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(148, 163, 184, 0.6) transparent;
        }
        .sidebar-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .sidebar-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(148, 163, 184, 0.6);
            border-radius: 9999px;
        }
        .sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(100, 116, 139, 0.8);
        }
    </style>

</aside>
