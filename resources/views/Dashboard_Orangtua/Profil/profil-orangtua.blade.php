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

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

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
                                <label class="flex items-center gap-3 cursor-pointer group mb-6">
                                    <input type="checkbox" required class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500 transition-all">
                                    <span class="text-sm text-gray-600 font-medium group-hover:text-gray-900 transition-colors">Saya yakin akan mengubah data tersebut</span>
                                </label>
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
                                @if($ortu->foto_profil)
                                    <img src="{{ asset('storage/' . $ortu->foto_profil) }}" alt="Preview" class="w-full h-full rounded-full object-cover">
                                @else
                                    <div class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <p class="text-gray-400 text-xs italic mb-4">Ganti foto user</p>
                            
                            <div class="flex max-w-md mx-auto items-center border border-gray-200 rounded-lg overflow-hidden mb-6">
                                <input type="file" name="foto" class="flex-1 px-4 py-2 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                                <button type="submit" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 text-sm font-bold transition-colors">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Edit Akun Form -->
                    <div x-show="activeTab === 'edit-akun'" style="display: none;">
                        <form action="{{ route('orangtua.profil.akun') }}" method="POST" class="space-y-6">
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
                                <label class="md:col-span-1 font-bold text-gray-700">Password <span class="text-xs font-normal text-gray-400">(opsional)</span></label>
                                <div class="md:col-span-3">
                                    <input type="password" name="password" placeholder="Masukkan password baru" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                                </div>
                            </div>

                            <div class="pt-6">
                                <label class="flex items-center gap-3 cursor-pointer group mb-6">
                                    <input type="checkbox" required class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500 transition-all">
                                    <span class="text-sm text-gray-600 font-medium group-hover:text-gray-900 transition-colors">Saya yakin akan mengubah data tersebut</span>
                                </label>
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
