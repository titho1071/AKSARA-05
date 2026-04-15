<aside class="hidden lg:flex w-[280px] flex-col bg-[#1E2567] text-white min-h-screen px-6 py-8">
    <div class="flex items-center gap-3 mb-10">
        <div class="h-12 w-12 rounded-3xl bg-white p-2 shadow-sm flex items-center justify-center">
            <img src="{{ asset('storage/aksara.png') }}" alt="Aksara Logo" class="h-full w-full object-contain" />
        </div>
        <div>
            <p class="font-semibold text-lg">AKSARA</p>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-300">Guru</p>
        </div>
    </div>

    @php $route = request()->route()?->getName(); @endphp
    <nav class="space-y-2 text-sm font-medium">
        <a href="{{ route('dashboard') }}" class="block rounded-3xl px-4 py-3 {{ $route === 'dashboard' ? 'bg-[#F59E0B] text-slate-950' : 'hover:bg-white/10' }}">Dashboard</a>
        <a href="{{ route('dashboard.absensi') }}" class="block rounded-3xl px-4 py-3 {{ $route === 'dashboard.absensi' ? 'bg-[#FFB81C] text-slate-950' : 'hover:bg-white/10' }}">Absensi</a>
        <a href="{{ route('dashboard.absensi.recap') }}" class="block rounded-3xl px-4 py-3 {{ $route === 'dashboard.absensi.recap' ? 'bg-[#FFB81C] text-slate-950' : 'hover:bg-white/10' }}">Rekap Absensi</a>
        <a href="#" class="block rounded-3xl px-4 py-3 hover:bg-white/10">Dokumentasi</a>
        <a href="#" class="block rounded-3xl px-4 py-3 hover:bg-white/10">Pengumuman</a>
        <a href="#" class="block rounded-3xl px-4 py-3 hover:bg-white/10">Jadwal</a>
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
