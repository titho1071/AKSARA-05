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
        <div class="my-6 form-label text-slate-400 uppercase text-xs tracking-wider">Dashboard</div>

        <a href="{{ route('admin.dashboard') }}"
            class="block px-3 py-2 rounded transition 
            {{ $route === 'admin.dashboard' 
                ? 'bg-[#F59E0B] text-slate-950' 
                : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }} ">
            Dashboard
        </a>

        <a 
            class="block px-3 py-2 rounded transition 
            {{ $route === '#' 
                ? 'bg-[#F59E0B] text-slate-950' 
                : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
            Absensi
        </a>

        <a 
            class="block px-3 py-2 rounded transition 
            {{ $route === '#' 
                ? 'bg-[#F59E0B] text-slate-950' 
                : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
            Dokumentasi
        </a>

        <a href="{{ route('admin.pengumuman') }}"
            class="block px-3 py-2 rounded transition 
            {{ $route === 'admin.pengumuman' 
                ? 'bg-[#F59E0B] text-slate-950' 
                : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
            Pengumuman
        </a>

        <a href="{{ route('admin.jadwal') }}"
            class="block px-3 py-2 rounded transition 
            {{ $route === 'admin.jadwal' 
                ? 'bg-[#F59E0B] text-slate-950' 
                : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
            Jadwal
        </a>

        <div class="my-4 form-label text-slate-400 uppercase text-xs tracking-wider">Master Data</div>

        <!-- Dropdown Menu -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                class="w-full flex justify-between items-center px-3 py-2 rounded transition 
                {{ in_array($route, ['admin.guru', 'admin.siswa', 'admin.orang-tua']) 
                    ? 'bg-[#F59E0B] text-slate-950' 
                    : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Biodata Data
                <span x-bind:class="{'rotate-180': open}" class="transition-transform">&#9662;</span>
            </button>

            <div x-show="open" x-transition class="mt-1 ml-2 space-y-1">
                <a href="#"
                    class="block px-3 py-2 rounded transition 
                    {{ $route === '#' 
                        ? 'bg-[#F59E0B] text-slate-950' 
                        : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                    Admin
                </a>
                <a href="#"
                    class="block px-3 py-2 rounded transition 
                    {{ $route === 'admin.guru' 
                        ? 'bg-[#F59E0B] text-slate-950' 
                        : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                    Guru
                </a>
                <a href="#"
                    class="block px-3 py-2 rounded transition 
                    {{ $route === 'admin.siswa' 
                        ? 'bg-[#F59E0B] text-slate-950' 
                        : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                    Siswa
                </a>
                <a href="#"
                    class="block px-3 py-2 rounded transition 
                    {{ $route === 'admin.orang-tua' 
                        ? 'bg-[#F59E0B] text-slate-950' 
                        : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                    Orang tua
                </a>
            
            </div>
        </div>
        <!-- Dropdown Menu -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                class="w-full flex justify-between items-center px-3 py-2 rounded transition 
                {{ in_array($route, ['admin.guru', 'admin.siswa', 'admin.orang-tua']) 
                    ? 'bg-[#F59E0B] text-slate-950' 
                    : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                Data Sekolah
                <span x-bind:class="{'rotate-180': open}" class="transition-transform">&#9662;</span>
            </button>

            <div x-show="open" x-transition class="mt-1 ml-2 space-y-1">
                <a href="#"
                    class="block px-3 py-2 rounded transition 
                    {{ $route === '#' 
                        ? 'bg-[#F59E0B] text-slate-950' 
                        : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                    Kelas
                </a>
                <a href="#"
                    class="block px-3 py-2 rounded transition 
                    {{ $route === 'admin.guru' 
                        ? 'bg-[#F59E0B] text-slate-950' 
                        : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
                    Tahun Pelajaran
                </a>
            </div>
        </div>
        <div class="my-6 form-label text-slate-400 uppercase text-xs tracking-wider">Saya</div>
            <a 
            class="block px-3 py-2 rounded transition 
            {{ $route === '#' 
                ? 'bg-[#F59E0B] text-slate-950' 
                : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
            Profil
            </a>
            <a 
            class="block px-3 py-2 rounded transition 
            {{ $route === '#' 
                ? 'bg-[#F59E0B] text-slate-950' 
                : 'bg-white/10 hover:bg-[#F59E0B] hover:text-slate-950' }}">
            Logout
            </a>
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
