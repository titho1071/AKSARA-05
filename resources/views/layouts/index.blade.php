<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - AKSARA</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="min-h-screen bg-gray-50 text-gray-900">

    @php
        $role = $role ?? 'admin';
    @endphp

    <div class="flex">

        <!-- Sidebar (Dynamic) -->
        @if($role == 'admin')
            @include('components.sidebar-admin')
        @elseif($role == 'guru')
            @include('components.sidebar-guru')
        @elseif($role == 'orangtua')
            @include('components.sidebar-orangtua')
        @endif

        <!-- Main Content -->
        <div class="flex-1 lg:ml-56 min-w-0">

            <!-- Content -->
            <main class="pt-4 pb-8 px-4 sm:px-6 lg:px-8">
                <!-- Extra top padding on mobile to clear the hamburger button -->
                <div class="max-w-7xl mx-auto mt-10 lg:mt-0">
                    @yield('content')
                </div>
            </main>

        </div>

    </div>

    @stack('scripts')
</body>
</html>