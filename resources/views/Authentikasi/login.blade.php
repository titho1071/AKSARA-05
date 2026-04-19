<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-indigo-900 min-h-screen flex items-center justify-center">

<div class="bg-[#2D2E78]/50 backdrop-blur-md p-10 rounded-2xl flex w-[900px]">

    <!-- LEFT: LOGO -->
    <div class="w-1/2 flex items-center justify-center">
        <img src="{{ asset('storage/aksara.png') }}" class="w-72">
    </div>

    <!-- RIGHT: LOGIN CARD -->
    <div class="w-full max-w-[520px] bg-white rounded-[32px] shadow-[0_36px_90px_rgba(0,0,0,0.16)] overflow-hidden">
        <div class="bg-white p-10 sm:p-12">
            <h1 class="text-4xl font-extrabold text-[#111827] text-center mb-10">Masuk</h1>
            <form id="login-form" class="space-y-6">
                @csrf

                @if(session('error'))
                    <div id="error-message" class="rounded-2xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <div id="api-error" class="hidden rounded-2xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700"></div>

                <!-- Email -->
                <div>
                    <label for="login" class="block text-sm font-medium text-[#111827] mb-3">Email atau Username</label>
                    <div class="relative">
                        <input
                            id="login"
                            type="text"
                            name="login"
                            autocomplete="username"
                            required
                            value="{{ old('login') }}"
                            placeholder="Masukkan Email atau Username"
                            class="w-full h-14 rounded-[16px] bg-[#F2F2F2] px-5 pr-14 text-base text-[#4B5563] placeholder:text-[#9CA3AF] focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500"
                        />
                        @error('login')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <span class="absolute inset-y-0 right-5 flex items-center text-[#6B7280]">
                            <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1.66667 3.33334C1.66667 2.59747 2.26314 2 3 2H17C17.7369 2 18.3333 2.59747 18.3333 3.33334V12.6667C18.3333 13.4025 17.7369 14 17 14H3C2.26314 14 1.66667 13.4025 1.66667 12.6667V3.33334Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M1.66667 3.33334L9.99999 8.00001L18.3333 3.33334" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-[#111827] mb-3">Password</label>
                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            autocomplete="current-password"
                            required
                            placeholder="Masukkan Kata Sandi"
                            class="w-full h-14 rounded-[16px] bg-[#F2F2F2] px-5 pr-14 text-base text-[#4B5563] placeholder:text-[#9CA3AF] focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500"
                        />
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <button
                            type="button"
                            id="toggle-password"
                            class="absolute inset-y-0 right-5 flex items-center text-[#6B7280] focus:outline-none"
                        >
                            <svg id="eye-open" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="block">
                                <path d="M9 4.5C11.75 4.5 14.25 6.5 15.5 9C14.25 11.5 11.75 13.5 9 13.5C6.25 13.5 3.75 11.5 2.5 9C3.75 6.5 6.25 4.5 9 4.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9 11.25C10.2426 11.25 11.25 10.2426 11.25 9C11.25 7.75736 10.2426 6.75 9 6.75C7.75736 6.75 6.75 7.75736 6.75 9C6.75 10.2426 7.75736 11.25 9 11.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <svg id="eye-closed" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="hidden">
                                <path d="M1.5 1.5L16.5 16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M9 4.5C11.75 4.5 14.25 6.5 15.5 9C14.25 11.5 11.75 13.5 9 13.5C6.25 13.5 3.75 11.5 2.5 9C3.75 6.5 6.25 4.5 9 4.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9 11.25C10.2426 11.25 11.25 10.2426 11.25 9C11.25 7.75736 10.2426 6.75 9 6.75C7.75736 6.75 6.75 7.75736 6.75 9C6.75 10.2426 7.75736 11.25 9 11.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full h-14 rounded-[16px] bg-amber-500 text-white text-base font-medium shadow-[0_12px_24px_rgba(251,189,61,0.28)] transition hover:bg-amber-600">Masuk</button>
            </form>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('toggle-password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');
        const loginForm = document.getElementById('login-form');
        const apiError = document.getElementById('api-error');
        const submitButton = loginForm.querySelector('button[type="submit"]');

        if (toggleButton && passwordInput) {
            toggleButton.addEventListener('click', function () {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                eyeOpen.classList.toggle('hidden', !isPassword);
                eyeClosed.classList.toggle('hidden', isPassword);
            });
        }

        loginForm.addEventListener('submit', async function (e) {
            e.preventDefault();

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
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json().catch(() => null);

                if (response.ok && result && result.success) {
                    window.location.href = result.redirect;
                    return;
                }

                if (result && result.message) {
                    apiError.textContent = result.message;
                } else if (response.status === 422 && result && result.errors) {
                    apiError.textContent = Object.values(result.errors).flat()[0] || 'Validasi gagal.';
                } else if (response.status === 404) {
                    apiError.textContent = 'Endpoint tidak ditemukan.';
                } else {
                    apiError.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                }

                apiError.classList.remove('hidden');
            } catch (error) {
                apiError.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                apiError.classList.remove('hidden');
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Masuk';
            }
        });
    });
</script>
</body>
</html>