<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - AKSARA</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <div class="flex-1 lg:ml-56">

            <!-- Content -->
            <main class="p-4">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>

        </div>

    </div>

</body>
</html>