<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#2D2E78] min-h-screen flex items-center justify-center">

<div class="w-[1100px] bg-[#2D2E78] rounded-2xl p-8 flex gap-8">

    <!-- LEFT -->
    <div class="w-1/2 flex flex-col items-center justify-center text-center text-white">
        <img src="{{ asset('storage/aksara.png') }}" class="w-72 mb-6">

        <div class="bg-white/10 p-6 rounded-2xl text-sm leading-relaxed max-w-md">
            Website yang dirancang sebagai media komunikasi digital antara sekolah dan orang tua siswa. 
            Website ini membantu menyampaikan informasi sekolah secara cepat, terpusat, dan mudah diakses.
        </div>
    </div>

    <!-- RIGHT -->
    <div class="w-1/2 bg-white rounded-2xl p-10">

        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            Selamat Datang Kembali
        </h1>

        <p class="text-gray-500 text-sm mb-8">
            Masuk untuk mengakses informasi kehadiran siswa, pengumuman sekolah, dokumentasi kegiatan, dan jadwal pelajaran secara langsung.
        </p>

        <form id="login-form" class="space-y-5">
            @csrf

            <div id="api-error" class="hidden text-sm text-red-600"></div>

            <!-- Email -->
            <div>
    <label class="text-sm font-medium text-gray-700">
        Username/Email
    </label>

    <div class="relative mt-2">
        <input
            type="text"
            name="login"
            placeholder="Masukkan Username/Email"
            class="w-full h-12 pl-4 pr-10 rounded-lg bg-gray-100 focus:ring-2 focus:ring-orange-400 outline-none"
        />
    </div>
</div>

            <!-- Password -->
            <div class="relative mt-2">
    <input
        id="password"
        type="password"
        name="password"
        placeholder="Masukkan Kata Sandi"
        class="w-full h-12 pl-4 pr-10 rounded-lg bg-gray-100 focus:ring-2 focus:ring-orange-400 outline-none"
    />

    <button
        type="button"
        id="toggle-password"
        class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-500"
    >
        <!-- mata buka -->
        <svg id="eye-open" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M2 9s3-5 7-5 7 5 7 5-3 5-7 5-7-5-7-5z"/>
            <circle cx="9" cy="9" r="2"/>
        </svg>

        <!-- mata tutup -->
        <svg id="eye-closed" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" class="hidden">
            <path d="M2 2l14 14"/>
            <path d="M2 9s3-5 7-5 7 5 7 5-3 5-7 5-7-5-7-5z"/>
        </svg>
    </button>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.getElementById('toggle-password');
    const eyeOpen = document.getElementById('eye-open');     // mata terbuka
    const eyeClosed = document.getElementById('eye-closed'); // mata tertutup

    // tampilkan icon saat ada isi
    passwordInput.addEventListener('keyup', function () {
        if (this.value.length > 0) {
            toggleBtn.classList.remove('hidden');

            // default: password tertutup → tampilkan mata tertutup
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            toggleBtn.classList.add('hidden');
            this.type = 'password';
        }
    });

    // toggle
    toggleBtn.addEventListener('click', function () {
        if (passwordInput.type === 'password') {
            // buka password
            passwordInput.type = 'text';

            // tampilkan mata terbuka
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        } else {
            // tutup password
            passwordInput.type = 'password';

            // tampilkan mata tertutup
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        }
    });
});
</script>
            <!-- Button -->
            <button
                type="submit"
                class="w-full h-12 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600 transition">
                Masuk
            </button>
        </form>

        <p class="text-xs text-gray-400 mt-6 text-center">
            <span class="text-blue-600 font-semibold">AKSARA</span> – Akses Komunikasi Sekolah dan Orang Tua
        </p>

    </div>

</div>

</body>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('login-form');
    const apiError = document.getElementById('api-error');
    const submitButton = loginForm.querySelector('button[type="submit"]');

    loginForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        apiError.classList.add('hidden');
        apiError.textContent = '';

        const formData = new FormData(loginForm);
        const data = {
            login: formData.get('login'),
            password: formData.get('password'),
        };

        submitButton.disabled = true;
        submitButton.textContent = 'Sedang Masuk...';

        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await response.json().catch(() => null);

            // SUCCESS
            if (response.ok && result?.success) {
                window.location.href = result.redirect;
                return;
            }

            // ERROR HANDLING
            if (response.status === 404) {
                apiError.textContent = 'Akun tidak ditemukan';
            } 
            else if (response.status === 401) {
                apiError.textContent = 'Email atau password salah';
            } 
            else if (response.status === 422 && result?.errors) {
                apiError.textContent = Object.values(result.errors).flat()[0];
            } 
            else {
                apiError.textContent = result?.message || 'Terjadi kesalahan. Silakan coba lagi';
            }

            apiError.classList.remove('hidden');

        } catch (err) {
            apiError.textContent = 'Terjadi kesalahan. Silakan coba lagi';
            apiError.classList.remove('hidden');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = 'Masuk';
        }
    });
});
</script>
</html>