@extends('layouts.index')
@php $role = 'admin'; @endphp
@section('title', 'Tambah Data Siswa')

@section('content')
@include('components.navbar', ['role' => $role])

<div class="mb-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm font-semibold text-slate-500">Tambah Data Siswa</p>
            <h1 class="text-3xl font-bold text-slate-950">Tambah Data Siswa</h1>
            <p class="text-sm text-slate-500">Masukkan informasi lengkap untuk siswa baru.</p>
        </div>
        <a href="{{ route('admin.siswa.index') }}"
            class="inline-flex items-center gap-2 rounded-[16px] border border-slate-200 bg-slate-50 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
            Kembali ke Data Siswa
        </a>
    </div>
</div>

@if ($errors->any())
    <div class="mb-6 rounded-[24px] border border-red-200 bg-red-50 p-5 text-sm text-red-700">
        <ul class="space-y-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="rounded-[32px] border border-slate-200 bg-white p-8 shadow-sm">
    <form action="{{ route('admin.siswa.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid gap-6 lg:grid-cols-2">

            {{-- Nama --}}
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap"
                    class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    required />
            </div>

            {{-- Kelas --}}
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Kelas</label>
                <select name="kelas_id"
                    class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas->id_kelas }}" {{ old('kelas_id') == $kelas->id_kelas ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- NIS --}}
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">NIS <span class="text-red-500">*</span></label>
                <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Masukkan NIS"
                    class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    required />
            </div>

            {{-- NISN --}}
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">NISN <span class="text-red-500">*</span></label>
                <input type="text" name="nisn" value="{{ old('nisn') }}" placeholder="Masukkan NISN"
                    class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    required />
            </div>

            {{-- Jenis Kelamin --}}
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                <select name="jenis_kelamin"
                    class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            {{-- Tanggal Lahir --}}
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                    class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
            </div>

            {{-- Alamat (full width) --}}
            <div class="lg:col-span-2">
                <label class="mb-2 block text-sm font-semibold text-slate-700">Alamat</label>
                <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap"
                    class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('alamat') }}</textarea>
            </div>
        </div>

        <label class="inline-flex items-center gap-3 text-sm text-slate-700">
            <input type="checkbox" required class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500" />
            Saya yakin sudah mengisi dengan benar
        </label>

        <button type="submit"
            class="inline-flex items-center justify-center rounded-[16px] bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
            Simpan
        </button>
    </form>
</div>

@endsection