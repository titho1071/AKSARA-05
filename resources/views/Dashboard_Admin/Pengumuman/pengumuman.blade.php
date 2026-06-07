@extends('layouts.index')

@php $role = auth()->user()->getRoleAttribute() @endphp

@section('title', 'Pengumuman')

@section('content')
    <div class="space-y-6">
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Pengumuman</h1>
                    <p class="text-gray-600 mt-1">Pengumuman terbaru untuk semua pengguna</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600">Pengumuman akan ditampilkan di sini.</p>
        </div>
    </div>
@endsection
