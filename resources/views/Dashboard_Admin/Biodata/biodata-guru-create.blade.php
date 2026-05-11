@extends('layouts.index')

@php
    $role = 'admin';
@endphp

@section('title', 'Tambah Data Guru')

@section('content')
@include('components.navbar', ['role' => $role])

<div class="mb-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm font-semibold text-slate-500">Tambah Data Guru</p>
            <h1 class="text-3xl font-bold text-slate-950">Tambah Data Guru</h1>
            <p class="text-sm text-slate-500">Masukkan informasi lengkap untuk guru baru.</p>
        </div>
        <a href="{{ route('admin.guru.index') }}" class="inline-flex items-center gap-2 rounded-[16px] border border-slate-200 bg-slate-50 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
            Kembali ke Biodata Guru
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
    <form action="{{ route('admin.guru.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid gap-6 lg:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" required />
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">NIP</label>
                <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Masukkan NIP" class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">NUPTK</label>
                <input type="text" name="nuptk" value="{{ old('nuptk') }}" placeholder="Masukkan NUPTK" class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Jenis Kelamin</label>
                <select name="gender" class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki" {{ old('gender') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('gender') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Masukkan nomor telepon" class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Alamat</label>
                <input type="text" name="address" value="{{ old('address') }}" placeholder="Masukkan alamat" class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" required />
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" placeholder="Masukkan username" class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" required />
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="Masukkan password" class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" required />
                    <button type="button" onclick="togglePassword('password', 'eye-password')" class="absolute inset-y-0 right-4 flex items-center text-slate-400 hover:text-slate-600">
                        <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.574-3.007-9.964-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" class="w-full rounded-[16px] border border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" required />
                    <button type="button" onclick="togglePassword('password_confirmation', 'eye-confirm')" class="absolute inset-y-0 right-4 flex items-center text-slate-400 hover:text-slate-600">
                        <svg id="eye-confirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.574-3.007-9.964-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                </div>
                <p id="password-match-msg" class="mt-2 hidden text-xs text-red-500">Password tidak cocok.</p>
            </div>
        </div>

        <label class="inline-flex items-center gap-3 text-sm text-slate-700">
            <input type="checkbox" required class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500" />
            Saya yakin sudah mengisi dengan benar
        </label>

        <button type="submit" class="inline-flex items-center justify-center rounded-[16px] bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">Simpan</button>
    </form>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        const isHidden = input.type === 'password';

        input.type = isHidden ? 'text' : 'password';

        icon.innerHTML = isHidden
            ? `<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />`
            : `<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.574-3.007-9.964-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />`;
    }

    document.querySelector('form').addEventListener('submit', function (e) {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;
        const msg = document.getElementById('password-match-msg');

        if (password !== confirm) {
            e.preventDefault();
            msg.classList.remove('hidden');
            document.getElementById('password_confirmation').classList.add('border-red-400', 'ring-red-100');
        }
    });

    document.getElementById('password_confirmation').addEventListener('input', function () {
        document.getElementById('password-match-msg').classList.add('hidden');
        this.classList.remove('border-red-400', 'ring-red-100');
    });
</script>
@endsection