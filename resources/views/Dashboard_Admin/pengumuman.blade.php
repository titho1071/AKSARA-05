@extends('layouts.index')

@php $role = auth()->user()->getRoleAttribute() @endphp

@section('title', 'Pengumuman')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pengumuman</h1>
            <p class="text-gray-500 mt-2">Pengumuman terbaru untuk semua pengguna</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600">Pengumuman akan ditampilkan di sini.</p>
        </div>
    </div>
@endsection
