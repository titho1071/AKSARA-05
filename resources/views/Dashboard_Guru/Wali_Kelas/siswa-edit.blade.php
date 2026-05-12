@extends('layouts.index')

@php
    $role = 'guru';
@endphp

@section('title', 'Edit Data Siswa')

@section('content')
@include('components.navbar', ['role' => $role])

<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<div class="mb-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm font-semibold text-slate-500">Edit Data Siswa</p>
            <h1 class="text-3xl font-bold text-slate-950">Edit Data Siswa</h1>
            <p class="text-sm text-slate-500">
                Perbarui informasi siswa dan atur akun wali siswa.
            </p>
        </div>

        <a
            href="{{ route('guru.siswa.index') }}"
            class="inline-flex items-center gap-2 rounded-[16px] border border-slate-200 bg-slate-50 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
        >
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
    <form
        action="{{ route('guru.siswa.update', $siswa->id_siswa) }}"
        method="POST"
        class="space-y-6"
    >
        @csrf
        @method('PUT')

        <!-- Data Siswa -->
        <div>
            <h2 class="mb-4 text-xl font-bold text-slate-900">
                Data Siswa
            </h2>

            <div class="grid gap-6 lg:grid-cols-2">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        name="nama"
                        value="{{ old('nama', $siswa->nama) }}"
                        placeholder="Masukkan nama lengkap"
                        class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        required
                    />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Kelas
                    </label>

                    <input
                        type="text"
                        value="{{ $kelas->nama_kelas }}"
                        disabled
                        class="w-full rounded-[16px] border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-700"
                    />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        NIS
                    </label>

                    <input
                        type="text"
                        name="nis"
                        value="{{ old('nis', $siswa->nis) }}"
                        placeholder="Masukkan NIS"
                        class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        required
                    />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        NISN
                    </label>

                    <input
                        type="text"
                        name="nisn"
                        value="{{ old('nisn', $siswa->nisn) }}"
                        placeholder="Masukkan NISN"
                        class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        required
                    />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Jenis Kelamin
                    </label>

                    <select
                        name="jenis_kelamin"
                        class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        required
                    >
                        <option value="">-- Pilih Jenis Kelamin --</option>

                        <option
                            value="L"
                            {{ old('jenis_kelamin', $siswa->jenis_kelamin) === 'L' ? 'selected' : '' }}
                        >
                            Laki-laki
                        </option>

                        <option
                            value="P"
                            {{ old('jenis_kelamin', $siswa->jenis_kelamin) === 'P' ? 'selected' : '' }}
                        >
                            Perempuan
                        </option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Tanggal Lahir
                    </label>

                    <input
                        type="date"
                        name="tanggal_lahir"
                        value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}"
                        class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    />
                </div>

                <div class="lg:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Alamat
                    </label>

                    <textarea
                        name="alamat"
                        rows="3"
                        placeholder="Masukkan alamat lengkap"
                        class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >{{ old('alamat', $siswa->alamat) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Akun Wali -->
        <div class="border-t border-slate-200 pt-6">
            <div class="mb-4">
                <h2 class="text-xl font-bold text-slate-900">
                    Akun Wali Siswa
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Cari dan pilih akun wali berdasarkan nama atau NIK.
                </p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Pilih Akun Wali
                </label>

                <select
                    id="orangTuaSelect"
                    name="orang_tua_id"
                    class="w-full"
                >
                    <option value="">
                        -- Pilih Akun Wali --
                    </option>

                    @foreach ($orangTuaList as $orangTua)
                        <option
                            value="{{ $orangTua->id_orang_tua }}"
                            {{ old('orang_tua_id', $siswa->orang_tua_id) == $orangTua->id_orang_tua ? 'selected' : '' }}
                        >
                            {{ $orangTua->nama }} - {{ $orangTua->nik }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <label class="inline-flex items-center gap-3 text-sm text-slate-700">
            <input
                type="checkbox"
                required
                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
            />

            Saya yakin sudah mengisi dengan benar
        </label>

        <div class="flex gap-3 pt-4">
            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-[16px] bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700"
            >
                Simpan Perubahan
            </button>

            <a
                href="{{ route('guru.siswa.index') }}"
                class="inline-flex items-center justify-center rounded-[16px] border border-slate-200 bg-slate-50 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
            >
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    new TomSelect("#orangTuaSelect", {
        create: false,

        sortField: {
            field: "text",
            direction: "asc"
        },

        placeholder: "Cari nama atau NIK wali siswa..."
    });
</script>
@endsection