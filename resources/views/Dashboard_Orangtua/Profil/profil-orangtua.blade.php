@extends('layouts.index')

@php
    $role = 'orangtua';
@endphp

@section('title', 'Profil Saya - Orang Tua')

@section('content')
@include('components.navbar', ['role' => $role])

<div class="px-4 py-6" x-data="{ activeTab: 'edit-profil' }">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Profil Saya</h1>
    </div>



    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: User Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 text-center">
                <div class="relative w-32 h-32 mx-auto mb-6">
                    @if($ortu->foto_profil)
                        <img src="{{ asset('storage/' . $ortu->foto_profil) }}" alt="Foto Profil" class="w-full h-full rounded-full object-cover border-4 border-white shadow-md">
                    @else
                        <div class="w-full h-full rounded-full bg-gray-200 flex items-center justify-center border-4 border-white shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    @endif
                </div>
                
                <h2 class="text-2xl font-bold text-gray-900">{{ $ortu->nama ?? 'Orang tua' }}</h2>
                <p class="text-gray-500 mb-8">Orang tua</p>

                <div class="space-y-4 text-left border-t border-gray-100 pt-6">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 text-sm">Username</span>
                        <span class="font-medium text-gray-900">{{ $user->username }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 text-sm">Email</span>
                        <span class="font-medium text-gray-900 text-xs">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 text-sm">Role</span>
                        <span class="font-medium text-gray-900">Orang tua</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Tabs and Forms -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Tab Header -->
                <div class="flex border-b border-gray-100">
                    <button @click="activeTab = 'edit-profil'" :class="activeTab === 'edit-profil' ? 'border-[#1E2567] text-[#1E2567]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-8 py-4 text-sm font-bold border-b-2 transition-colors">
                        Edit Profil
                    </button>
                    <button @click="activeTab = 'edit-foto'" :class="activeTab === 'edit-foto' ? 'border-[#1E2567] text-[#1E2567]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-8 py-4 text-sm font-bold border-b-2 transition-colors">
                        Edit Foto
                    </button>
                    <button @click="activeTab = 'edit-akun'" :class="activeTab === 'edit-akun' ? 'border-[#1E2567] text-[#1E2567]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-8 py-4 text-sm font-bold border-b-2 transition-colors">
                        Edit Akun
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="p-8">
                    <!-- Edit Profil Form -->
                    <div x-show="activeTab === 'edit-profil'">
                        <form action="{{ route('orangtua.profil.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4">
                                <label class="md:col-span-1 font-bold text-gray-700">Nama</label>
                                <div class="md:col-span-3">
                                    <input type="text" name="nama" value="{{ old('nama', $ortu->nama) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4">
                                <label class="md:col-span-1 font-bold text-gray-700">Jenis Kelamin</label>
                                <div class="md:col-span-3">
                                    <select name="jenis_kelamin" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all appearance-none bg-no-repeat bg-[right_1rem_center] bg-[length:1em_1em]" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%236b7280%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%222%22 d=%22M19 9l-7 7-7-7%22 /%3E%3C/svg%3E');">
                                        <option value="Laki-laki" {{ old('jenis_kelamin', $ortu->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin', $ortu->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4">
                                <label class="md:col-span-1 font-bold text-gray-700">NIK</label>
                                <div class="md:col-span-3">
                                    <input type="text" name="nik" value="{{ old('nik', $ortu->nik) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4">
                                <label class="md:col-span-1 font-bold text-gray-700">No. Hp</label>
                                <div class="md:col-span-3">
                                    <input type="text" name="no_hp" value="{{ old('no_hp', $ortu->no_hp) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 items-start gap-4">
                                <label class="md:col-span-1 font-bold text-gray-700 pt-3">Alamat</label>
                                <div class="md:col-span-3">
                                    <textarea name="alamat" rows="4" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">{{ old('alamat', $ortu->alamat) }}</textarea>
                                </div>
                            </div>

                            <div class="pt-6">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-bold transition-all shadow-md">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Edit Foto Form -->
                    <div x-show="activeTab === 'edit-foto'" style="display: none;">
                        <form action="{{ route('orangtua.profil.foto') }}" method="POST" enctype="multipart/form-data" class="text-center">
                            @csrf
                            @method('PUT')
                            <h3 class="text-lg font-bold mb-6">Foto</h3>
                            <div class="w-32 h-32 mx-auto mb-8 relative group">
                                <img id="preview-image" src="{{ $ortu->foto_profil ? asset('storage/' . $ortu->foto_profil) : '' }}" alt="Preview" class="w-full h-full rounded-full object-cover {{ $ortu->foto_profil ? '' : 'hidden' }}">
                                
                                <div id="preview-placeholder" class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center {{ $ortu->foto_profil ? 'hidden' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>

                            <p class="text-gray-400 text-xs italic mb-4">Ganti foto user</p>
                            
                            <div class="flex max-w-md mx-auto items-center border border-gray-200 rounded-lg overflow-hidden mb-6">
                                <input type="file" id="foto-input" name="foto" accept="image/*" class="flex-1 px-4 py-2 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                                <button type="submit" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 text-sm font-bold transition-colors">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Edit Akun Form -->
                    <div x-show="activeTab === 'edit-akun'" style="display: none;">
                        <form id="form-edit-akun" action="{{ route('orangtua.profil.akun') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4">
                                <label class="md:col-span-1 font-bold text-gray-700">Username</label>
                                <div class="md:col-span-3">
                                    <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4">
                                <label class="md:col-span-1 font-bold text-gray-700">Email <span class="text-xs font-normal text-gray-400">(Opsional)</span></label>
                                <div class="md:col-span-3">
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4">
                                <label class="md:col-span-1 font-bold text-gray-700">Password Baru <span class="text-xs font-normal text-gray-400">(Opsional)</span></label>
                                <div class="md:col-span-3 relative">
                                    <input type="password" id="password" name="password" placeholder="Masukkan password baru" class="w-full rounded-xl border border-gray-200 px-4 py-3 pr-12 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                                    <button type="button" onclick="togglePassword('password', 'eye-password')" class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-gray-600">
                                        <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.574-3.007-9.964-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4">
                                <div class="md:col-span-1"></div>
                                <div class="md:col-span-3">
                                    <p id="password-length-msg" class="hidden text-xs text-red-500 mt-1">Password harus minimal 8 karakter.</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4">
                                <label class="md:col-span-1 font-bold text-gray-700">Konfirmasi Password</label>
                                <div class="md:col-span-3 relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru" class="w-full rounded-xl border border-gray-200 px-4 py-3 pr-12 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                                    <button type="button" onclick="togglePassword('password_confirmation', 'eye-confirm')" class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-gray-600">
                                        <svg id="eye-confirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.574-3.007-9.964-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4">
                                <div class="md:col-span-1"></div>
                                <div class="md:col-span-3">
                                    <p id="password-match-msg" class="hidden text-xs text-red-500">Password tidak cocok.</p>
                                </div>
                            </div>

                            <div class="pt-6">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-bold transition-all shadow-md">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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

    const formAkun = document.getElementById('form-edit-akun');
    if (formAkun) {
        formAkun.addEventListener('submit', function (e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const matchMsg = document.getElementById('password-match-msg');
            const lengthMsg = document.getElementById('password-length-msg');
            
            let hasError = false;

            // Reset
            matchMsg.classList.add('hidden');
            lengthMsg.classList.add('hidden');
            document.getElementById('password').classList.remove('border-red-400');
            document.getElementById('password_confirmation').classList.remove('border-red-400');

            if (password) {
                if (password.length < 8) {
                    e.preventDefault();
                    lengthMsg.classList.remove('hidden');
                    document.getElementById('password').classList.add('border-red-400');
                    hasError = true;
                }
                
                if (password !== confirm) {
                    e.preventDefault();
                    matchMsg.classList.remove('hidden');
                    document.getElementById('password_confirmation').classList.add('border-red-400');
                    hasError = true;
                }
            }
        });
    }

    const passInput = document.getElementById('password');
    if (passInput) {
        passInput.addEventListener('input', function () {
            document.getElementById('password-length-msg').classList.add('hidden');
            this.classList.remove('border-red-400');
        });
    }

    const passConfirm = document.getElementById('password_confirmation');
    if (passConfirm) {
        passConfirm.addEventListener('input', function () {
            document.getElementById('password-match-msg').classList.add('hidden');
            this.classList.remove('border-red-400');
        });
    }

    // Preview Foto
    const fotoInput = document.getElementById('foto-input');
    const previewImage = document.getElementById('preview-image');
    const previewPlaceholder = document.getElementById('preview-placeholder');

    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                    if (previewPlaceholder) {
                        previewPlaceholder.classList.add('hidden');
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#6366f1'
        });
    @endif
</script>
@endpush
