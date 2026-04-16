<aside class="hidden lg:flex w-[280px] flex-col bg-[#1E2567] text-white min-h-screen px-6 py-8">
    <div class="flex items-center gap-3 mb-10">
        <div class="h-12 w-12 rounded-3xl bg-white p-2 shadow-sm flex items-center justify-center">
            <img src="{{ asset('storage/aksara.png') }}" alt="Aksara Logo" class="h-full w-full object-contain" />
        </div>
        <div>
            <p class="font-semibold text-lg">AKSARA</p>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-300">ADMIN</p>
        </div>
    </div>

    @php $route = request()->route()?->getName(); @endphp
    <nav class="space-y-2 text-sm font-medium">
        <a href="{{ route('admin.dashboard') }}" class="block rounded-3xl px-4 py-3 {{ $route === 'admin.dashboard' ? 'bg-[#F59E0B] text-slate-950' : 'hover:bg-white/10' }}">Dashboard</a>
        <a href="#" class="block rounded-3xl px-4 py-3 hover:bg-white/10">Absensi</a>
        <a href="#" class="block rounded-3xl px-4 py-3 hover:bg-white/10">Dokumentasi</a>
        <a href="{{ route('admin.pengumuman') }}" class="block rounded-3xl px-4 py-3 {{ $route === 'admin.pengumuman' ? 'bg-[#F59E0B] text-slate-950' : 'hover:bg-white/10' }}">Pengumuman</a>
        <a href="{{ route('admin.jadwal') }}" class="block rounded-3xl px-4 py-3 {{ $route === 'admin.jadwal' ? 'bg-[#F59E0B] text-slate-950' : 'hover:bg-white/10' }}">Jadwal</a>
    </nav>

    <div class="mt-10 border-t border-white/10 pt-6">
        <p class="text-xs uppercase tracking-[0.25em] text-slate-400 mb-4">Biodata Data</p>
        <nav class="space-y-2 text-sm font-medium">
            <a href="#" class="block rounded-3xl px-4 py-3 hover:bg-white/10">Guru</a>
            <a href="#" class="block rounded-3xl px-4 py-3 hover:bg-white/10">Siswa</a>
            <a href="#" class="block rounded-3xl px-4 py-3 hover:bg-white/10">Orang tua</a>
        </nav>
    </div>
</aside>
