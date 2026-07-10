@extends('layouts.index')

@php
    $role = 'guru';
@endphp

@section('content')
@include('components.navbar', ['role' => $role])
    <div class="max-w-[1400px] mx-auto space-y-6">

        <!-- Header -->
        <div class="space-y-1">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Absensi</h1>
            <p class="text-sm text-gray-500">Kelola Absensi</p>
        </div>

        <!-- Back Link -->
        <a href="{{ route('guru.absensi.detail', ['id' => $kelas->id_kelas, 'bulan' => $bulan]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#1e2567] hover:text-blue-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Detail Absensi
        </a>

        <!-- Info Card -->
        <div class="bg-white rounded-[20px] shadow-sm border border-slate-200">
            <div class="flex">
                <div class="w-1.5 bg-amber-400 rounded-l-[20px] flex-shrink-0"></div>
                <div class="p-6 w-full">
                    <div class="space-y-1">
                        <p class="text-sm text-slate-800">
                            <span class="font-bold">Kelas</span> :
                            {{ $kelas->nama_kelas }}
                        </p>
                        <p class="text-sm text-slate-800">
                            <span class="font-bold">Wali Kelas</span> :
                            {{ $kelas->guru->nama ?? '-' }}
                        </p>
                        <p class="text-sm text-slate-800">
                            <span class="font-bold">Tahun Pelajaran</span> :
                            {{ $kelas->tahunPelajaran->tahun_pelajaran ?? '-' }}
                        </p>
                        <p class="text-sm text-slate-800">
                            <span class="font-bold">Tanggal</span> :
                            {{ $tanggalAbsensi->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <form
            action="{{ route('guru.absensi.simpan', [
                'id' => $kelas->id_kelas,
                'bulan' => $bulan,
                'tanggal' => $tanggal
            ]) }}"
            method="POST"
        >
            @csrf

            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: '{{ session('success') }}',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#6366f1'
                        });
                    });
                </script>
            @endif

            <div class="bg-white rounded-[20px] shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    @php
                        $statusOptions = ['H', 'S', 'I', 'A'];
                        $statusLabels  = [
                            'H' => 'Hadir',
                            'S' => 'Sakit',
                            'I' => 'Izin',
                            'A' => 'Alpha',
                        ];
                    @endphp

                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-[#1e2567] text-white">
                                <th class="px-4 py-4 font-semibold text-center w-14">#</th>
                                <th class="px-4 py-4 font-semibold text-center w-32">NIS</th>
                                <th class="px-4 py-4 font-semibold text-center">Nama Siswa</th>
                                <th class="px-4 py-4 font-semibold text-center w-16">L/P</th>
                                @foreach ($statusOptions as $opt)
                                    <th class="px-3 py-4 font-semibold text-center w-16">
                                        <div class="flex flex-col items-center gap-0.5">
                                            <span class="text-[10px] font-normal text-slate-300 uppercase">{{ $statusLabels[$opt] }}</span>
                                            <span>{{ $opt }}</span>
                                        </div>
                                    </th>
                                @endforeach
                                <th class="px-4 py-4 font-semibold text-center w-56">Keterangan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($kelas->siswa as $index => $siswa)
                                @php
                                    $dataAbsensi = $absensi[$siswa->id_siswa] ?? null;
                                    $status      = $dataAbsensi->status_kehadiran ?? 'H';
                                @endphp

                                <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-slate-50/60' }} border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-4 text-center text-slate-500">{{ $index + 1 }}</td>
                                    <td class="px-4 py-4 text-center text-slate-700">{{ $siswa->nis }}</td>
                                    <td class="px-4 py-4 font-semibold text-slate-900">{{ $siswa->nama }}</td>
                                    <td class="px-4 py-4 text-center text-slate-600">{{ $siswa->jenis_kelamin }}</td>

                                    @foreach ($statusOptions as $opt)
                                        <td class="px-3 py-4 text-center">
                                            <label class="inline-flex items-center justify-center cursor-pointer">
                                                <input
                                                    type="radio"
                                                    name="status[{{ $siswa->id_siswa }}]"
                                                    value="{{ $opt }}"
                                                    class="sr-only peer"
                                                    {{ $status == $opt ? 'checked' : '' }}
                                                >
                                                <span class="w-6 h-6 rounded-full border-2 border-slate-300 peer-checked:border-[#1e2567] peer-checked:bg-[#1e2567] flex items-center justify-center transition-all duration-200">
                                                    <span class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity"></span>
                                                </span>
                                            </label>
                                        </td>
                                    @endforeach

                                    <td class="px-4 py-4">
                                        <input
                                            type="text"
                                            name="keterangan[{{ $siswa->id_siswa }}]"
                                            id="keterangan_{{ $siswa->id_siswa }}"
                                            value="{{ $dataAbsensi->keterangan ?? '' }}"
                                            placeholder="Keterangan..."
                                            data-siswa="{{ $siswa->id_siswa }}"
                                            class="keterangan-input w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 bg-slate-50 focus:outline-none focus:border-[#1e2567] focus:ring-1 focus:ring-[#1e2567] focus:bg-white transition-all placeholder-slate-400 disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed"
                                        >
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-8 text-center text-slate-500">Tidak ada data siswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer -->
                <div class="px-6 py-5 border-t border-slate-100 flex items-center justify-end">
                    <button
                        type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-8 py-2.5 rounded-lg text-sm font-semibold transition-colors shadow-sm"
                    >
                        Simpan
                    </button>
                </div>
            </div>
        </form>

    </div>

    <style>
        input[type="radio"].sr-only:checked + span {
            border-color: #1e2567;
            background-color: #1e2567;
        }
        input[type="radio"].sr-only:checked + span > span {
            opacity: 1;
        }
    </style>
    <script>
        function updateKeterangan(siswaId, status) {
            const input = document.getElementById('keterangan_' + siswaId);
            if (!input) return;

            const unlocked = status === 'S' || status === 'I';
            input.disabled = !unlocked;

            if (!unlocked) {
                input.value = '';
                input.classList.remove('bg-white');
                input.classList.add('bg-slate-100');
            } else {
                input.classList.remove('bg-slate-100');
                input.classList.add('bg-white');
                input.focus();
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Set initial state berdasarkan status yang sudah terpilih
            document.querySelectorAll('input[type="radio"]').forEach(function (radio) {
                if (radio.checked) {
                    const name  = radio.name; // status[id_siswa]
                    const match = name.match(/\[(\d+)\]/);
                    if (match) updateKeterangan(match[1], radio.value);
                }

                // Listen perubahan
                radio.addEventListener('change', function () {
                    const match = this.name.match(/\[(\d+)\]/);
                    if (match) updateKeterangan(match[1], this.value);
                });
            });
        });
    </script>
@endsection