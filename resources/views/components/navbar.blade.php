@php
    $role = $role ?? 'admin';
    $user = auth()->user();

    $nama = 'User';
    $foto = null;

    if ($user) {
        $profil = null;
        if ($role === 'admin') {
            $profil = \Illuminate\Support\Facades\DB::table('admin')->where('user_id', $user->id)->first();
        } elseif ($role === 'guru') {
            $profil = \Illuminate\Support\Facades\DB::table('guru')->where('user_id', $user->id)->first();
        } elseif ($role === 'orangtua') {
            $profil = \Illuminate\Support\Facades\DB::table('orang_tua')->where('user_id', $user->id)->first();
        }

        if ($profil) {
            $nama = $profil->nama ?? $user->username ?? 'User';
            $foto = $profil->foto_profil ?? null;
        } else {
            $nama = $user->username ?? 'User';
        }
    }
@endphp

<!-- Navbar -->
<nav class="bg-white border-b border-gray-200 px-4 sm:px-6 py-3 shadow-sm">
    <div class="flex items-center justify-between">

        {{-- Spacer kiri: kosong di desktop (sidebar tetap ada), ada ruang untuk hamburger di mobile --}}
        <div class="w-10 lg:hidden"></div>

        {{-- Title / Brand (opsional, tampil di tengah pada mobile) --}}
        <div class="flex-1 text-center lg:text-left lg:flex-none">
            {{-- Bisa diisi nama halaman kalau mau --}}
        </div>

        <!-- Profile Dropdown -->
        <div class="relative">
            <button id="profileBtn" class="flex items-center gap-2 sm:gap-3 min-h-[44px] px-2 rounded-lg hover:bg-gray-100 transition">
                <!-- Nama (sembunyikan di layar sangat kecil) -->
                <span class="hidden sm:block text-gray-800 font-medium text-sm">
                    {{ $nama }}
                </span>

                <!-- Foto -->
                @if($foto)
                    <img src="{{ asset('storage/' . $foto) }}" class="w-9 h-9 rounded-full border object-cover flex-shrink-0">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($nama) }}&background=3B82F6&color=fff"
                         class="w-9 h-9 rounded-full border flex-shrink-0">
                @endif
            </button>

            <!-- Dropdown -->
            <div id="dropdownMenu"
                 class="hidden absolute right-0 mt-2 w-44 bg-white border rounded-xl shadow-lg overflow-hidden z-50">

                @php
                    $profilRoute = match($role) {
                        'admin'    => route('admin.profil'),
                        'guru'     => route('guru.profil'),
                        'orangtua' => route('orangtua.profil'),
                        default    => '#'
                    };
                @endphp

                {{-- Nama tampil di dropdown di HP --}}
                <div class="sm:hidden px-4 py-3 text-sm font-semibold text-gray-800 border-b border-gray-100 truncate">
                    {{ $nama }}
                </div>

                <a href="{{ $profilRoute }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Profil
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>

    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn  = document.getElementById('profileBtn');
    const menu = document.getElementById('dropdownMenu');

    if (btn) {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
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