@extends('layouts.index')

@php $role = 'orangtua'; @endphp

@section('title', 'Absensi Anak')

@section('content')
@include('components.navbar', ['role' => $role])

@php
$statusConfig = [
    'Hadir' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
    'Sakit' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
    'Izin'  => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600'],
    'Alpha' => ['bg' => 'bg-red-100', 'text' => 'text-red-600'],
];
@endphp

<div class="max-w-[1200px] mx-auto space-y-6 pb-10">

    {{-- Page Title --}}
    <div class="space-y-1">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Absensi Anak</h1>
        <p class="text-sm text-gray-500">Pantau kehadiran anak secara lengkap</p>
    </div>

    {{-- Child Selector --}}
    <div class="flex flex-wrap gap-3">
        @foreach ($siswa as $child)
            <form method="GET" action="{{ route('orangtua.absensi') }}">
                <input type="hidden"
                    name="bulan"
                    value="{{ $bulan }}">

                @if($statusFilter)
                    <input type="hidden"
                        name="status"
                        value="{{ $statusFilter }}">
                @endif
                <input type="hidden" name="siswa_id" value="{{ $child['id'] }}">

                <button type="submit"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl border transition-all duration-200
                        {{ $child['active']
                            ? 'bg-[#1e2567] text-white border-[#1e2567] shadow-md'
                            : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300 hover:shadow-sm' }}">
                            
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                        {{ $child['active'] ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">
                        {{ $child['initials'] }}
                    </div>

                    <div class="text-left">
                        <p class="text-sm font-semibold leading-tight">
                            {{ $child['name'] }}
                        </p>

                        <p class="text-xs {{ $child['active'] ? 'text-blue-200' : 'text-slate-400' }}">
                            {{ $child['kelas'] }}
                        </p>
                    </div>
                </button>
            </form>
        @endforeach
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="w-full h-1 rounded-full bg-emerald-500 mb-4"></div>
            <p class="text-sm text-slate-500 mb-1">Hadir</p>
            <p class="text-3xl font-bold text-emerald-600">{{ $summary['hadir'] }}
                <span class="text-sm font-normal text-slate-400">hari</span>
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="w-full h-1 rounded-full bg-blue-500 mb-4"></div>
            <p class="text-sm text-slate-500 mb-1">Sakit</p>
            <p class="text-3xl font-bold text-blue-600">{{ $summary['sakit'] }}
                <span class="text-sm font-normal text-slate-400">hari</span>
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="w-full h-1 rounded-full bg-amber-400 mb-4"></div>
            <p class="text-sm text-slate-500 mb-1">Izin</p>
            <p class="text-3xl font-bold text-amber-500">{{ $summary['izin'] }}
                <span class="text-sm font-normal text-slate-400">hari</span>
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="w-full h-1 rounded-full bg-red-500 mb-4"></div>
            <p class="text-sm text-slate-500 mb-1">Alpha</p>
            <p class="text-3xl font-bold text-red-600">{{ $summary['alpha'] }}
                <span class="text-sm font-normal text-slate-400">hari</span>
            </p>
        </div>

        <div class="bg-[#1e2567] rounded-2xl p-5 shadow-sm col-span-2 sm:col-span-1">
            <p class="text-sm text-blue-200 mb-1">Kehadiran</p>
            <p class="text-3xl font-bold text-white">{{ $summary['persen'] }}%
                <span class="text-xs font-normal text-blue-300">dari {{ $summary['total'] }} hari</span>
            </p>
            <div class="mt-3 w-full bg-white/20 rounded-full h-2">
                <div class="bg-amber-400 h-2 rounded-full" style="width: {{ $summary['persen'] }}%"></div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        {{-- Filter Bar --}}
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-500">Bulan:</span>
                <form method="GET" action="{{ route('orangtua.absensi') }}">
                    @if($activeSiswa)
                        <input type="hidden" name="siswa_id" value="{{ $activeSiswa->id_siswa }}">
                    @endif

                    @if($statusFilter)
                        <input type="hidden"
                            name="status"
                            value="{{ $statusFilter }}">
                    @endif

                    <select
                        name="bulan"
                        onchange="this.form.submit()"
                        class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm bg-white">

                        @for($m = 1; $m <= 12; $m++)
                            <option
                                value="{{ $m }}"
                                {{ $bulan == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endfor

                    </select>
                </form>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-500">Status:</span>
                <div class="flex gap-1.5 flex-wrap">
                    @foreach (['Semua', 'Hadir', 'Sakit', 'Izin', 'Alpha'] as $filter)
                        <form method="GET" action="{{ route('orangtua.absensi') }}">
                            @if($activeSiswa)
                                <input type="hidden"
                                    name="siswa_id"
                                    value="{{ $activeSiswa->id_siswa }}">
                            @endif

                            <input type="hidden"
                                name="bulan"
                                value="{{ $bulan }}">

                            @if($filter !== 'Semua')
                                <input type="hidden"
                                    name="status"
                                    value="{{ $filter }}">
                            @endif

                            <button type="submit"
                                class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors
                                {{ ($statusFilter ?? 'Semua') == $filter
                                    ? 'bg-[#1e2567] text-white'
                                    : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">

                                {{ $filter }}

                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>

        @if($records->isEmpty())

            <div class="p-10 text-center">
                <p class="text-slate-500">
                    Belum ada data absensi pada bulan ini.
                </p>
            </div>

        @else

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-slate-700">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50">
                            <th class="px-6 py-3 text-left font-semibold text-slate-600 w-14">No</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-600">Tanggal</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-600">Hari</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-600">Status</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-600">Keterangan</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach ($records as $record)

                            @php
                                $cfg = $statusConfig[$record['status']] ?? [
                                    'bg' => 'bg-slate-100',
                                    'text' => 'text-slate-600'
                                ];
                            @endphp

                            <tr class="hover:bg-slate-50/60 transition-colors {{ $record['status'] === 'Alpha' ? 'bg-red-50/40' : '' }}">
                                <td class="px-6 py-4 text-slate-400">
                                    {{ $record['no'] }}
                                </td>

                                <td class="px-6 py-4 font-medium text-slate-800">
                                    {{ $record['tanggal'] }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $record['hari'] }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $cfg['bg'] }} {{ $cfg['text'] }}">
                                        {{ $record['status'] }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 {{ $record['status'] === 'Alpha' ? 'text-red-500 font-medium' : 'text-slate-500' }}">
                                    @if($record['status'] === 'Alpha' && empty($record['keterangan']))
                                        Tidak ada keterangan
                                    @else
                                        {{ $record['keterangan'] ?: '-' }}
                                    @endif
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>

        <div class="px-6 py-4 border-t border-slate-100">
            <p class="text-sm text-slate-500">
                Menampilkan {{ $records->count() }} data absensi
            </p>
        </div>

        @endif
    </div>

</div>
@endsection