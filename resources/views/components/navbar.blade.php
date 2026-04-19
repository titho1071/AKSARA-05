@php
    // default kalau tidak dikirim dari halaman
    $role = $role ?? 'admin';

    // mapping nama
    $nama = match($role) {
        'admin' => 'Admin Sekolah',
        'guru' => 'Guru',
        'orangtua' => 'Orang Tua',
        default => 'User'
    };
@endphp

<!-- Navbar -->
<nav class="bg-white border-b border-gray-200 px-6 py-3 shadow-sm">
    <div class="flex items-center justify-end">

        <!-- Profile Dropdown -->
        <div class="relative">
            <button id="profileBtn" class="flex items-center gap-3">

                <!-- Nama -->
                <span class="text-gray-800 font-medium">
                    {{ $nama }}
                </span>

                <!-- Foto (dynamic juga) -->
                <img src="https://ui-avatars.com/api/?name={{ urlencode($nama) }}&background=3B82F6&color=fff"
                     class="w-9 h-9 rounded-full border">

            </button>

            <!-- Dropdown -->
            <div id="dropdownMenu" 
                 class="hidden absolute right-0 mt-2 w-40 bg-white border rounded-xl shadow-md">

                <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100">
                    Profil
                </a>

                <button class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-100">
                    Logout
                </button>

            </div>
        </div>

    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('profileBtn');
    const menu = document.getElementById('dropdownMenu');

    if (btn) {
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });

        document.addEventListener('click', function(e) {
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    }
});
</script>