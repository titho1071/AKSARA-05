@extends('layouts.index')

@section('title', 'Jadwal Mengajar')

@php $role = 'guru'; @endphp

@section('content')
<div class="min-h-screen px-4 py-6">
    @include('components.navbar', ['role' => $role])

    {{-- ── Header ── --}}
    <div class="mb-8 pt-4">
        <h1 class="text-3xl font-bold text-gray-900">Jadwal Mengajar</h1>
        <p class="text-gray-500 mt-1">Semua jadwal mengajar Anda</p>
    </div>

    {{-- ── Teacher Info Card ── --}}
    <div class="bg-[#F0F4FF] rounded-3xl p-6 mb-8 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-[#1E2567] rounded-full flex items-center justify-center text-white text-2xl font-bold">
                {{ $initials }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $guru->nama }}</h2>
                <p class="text-sm text-gray-500">NIP · {{ $guru->nip ?? '-' }}</p>
            </div>
        </div>

        <div class="flex gap-4">
            <div class="bg-white/70 rounded-xl px-6 py-4 text-center min-w-[90px]">
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_jp'] }}</div>
                <div class="text-xs text-gray-500">Total JP</div>
            </div>
            <div class="bg-white/70 rounded-xl px-6 py-4 text-center min-w-[90px]">
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_kelas'] }}</div>
                <div class="text-xs text-gray-500">Kelas</div>
            </div>
            <div class="bg-white/70 rounded-xl px-6 py-4 text-center min-w-[90px]">
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_hari'] }}</div>
                <div class="text-xs text-gray-500">Hari</div>
            </div>
        </div>
    </div>

    {{-- ── Peringatan jika tidak ada tapel aktif ── --}}
    @if($tapelError)
        <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-700 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
            </svg>
            Tidak ada tahun pelajaran aktif. Hubungi admin untuk mengaktifkan tahun pelajaran.
        </div>
    @endif

    {{-- ── Day Selector ── --}}
    <div class="mb-6">
        <h3 class="text-sm font-bold text-gray-700 mb-4">List Jadwal Mengajar</h3>
        <div class="flex gap-3 flex-wrap">
            @foreach($days as $day)
                @if(in_array($day, $hariAktif->all()))
                    @php
                        $shortDay = ['Senin'=>'Sen','Selasa'=>'Sel','Rabu'=>'Rab','Kamis'=>'Kam','Jumat'=>'Jum'][$day];
                        $isSelected = $selectedHari === $day;
                    @endphp
                    <a href="{{ route('guru.jadwal', ['hari' => $day]) }}"
                       class="rounded-xl px-5 py-3 text-center min-w-[72px] transition-all
                              {{ $isSelected
                                  ? 'bg-[#1E2567] text-white shadow-lg shadow-blue-200'
                                  : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                        <div class="text-xs font-medium uppercase opacity-80">{{ $shortDay }}</div>
                        <div class="text-lg font-bold">{{ count($jadwalPerHari[$day]) }}</div>
                        <div class="text-[10px] opacity-60">JP</div>
                    </a>
                @endif
            @endforeach

            @if($hariAktif->isEmpty())
                <p class="text-gray-400 text-sm">Belum ada jadwal mengajar.</p>
            @endif
        </div>
    </div>

    {{-- ── Schedule List ── --}}
    @if(!empty($jadwalPerHari[$selectedHari]))
        <div class="space-y-4">
            @foreach($jadwalPerHari[$selectedHari] as $item)
                @php
                    $jam        = $item->jamPelajaran;
                    $jamMulai   = $jam ? substr($jam->jam_mulai, 0, 5) : '-';
                    $jamSelesai = $jam ? substr($jam->jam_selesai, 0, 5) : '-';
                    $isKegiatan = !empty($item->nama_kegiatan);
                    $judul      = $isKegiatan ? $item->nama_kegiatan : ($item->mataPelajaran->nama_mapel ?? '-');
                    $kelas      = $item->kelas->nama_kelas ?? '-';
                @endphp

                <div class="bg-white rounded-2xl p-5 border-l-4 {{ $isKegiatan ? 'border-violet-500' : 'border-[#1E2567]' }} shadow-sm flex items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="text-xs font-medium text-gray-400">{{ $jamMulai }} - {{ $jamSelesai }}</div>
                        <h4 class="text-lg font-bold {{ $isKegiatan ? 'text-violet-800' : 'text-gray-900' }}">
                            {{ $judul }}
                        </h4>
                        @if($isKegiatan)
                            <div class="text-xs text-violet-500 font-medium">Kegiatan</div>
                        @else
                            <div class="text-sm text-gray-500">{{ $kelas }}</div>
                        @endif
                    </div>
                    <div>
                        <span class="px-5 py-2 rounded-xl text-sm font-bold shadow-sm
                                     {{ $isKegiatan
                                         ? 'bg-violet-100 text-violet-700'
                                         : 'bg-[#1E2567] text-white shadow-blue-200' }}">
                            {{ $kelas }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
            <p class="text-gray-400">Tidak ada jadwal untuk hari {{ $selectedHari }}.</p>
        </div>
    @endif
</div>
@endsection