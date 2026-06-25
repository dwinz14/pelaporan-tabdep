<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SI Stok Tab-Dep</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Custom Font (Opsional, menggunakan font Inter untuk look yang lebih bersih) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Smooth entrance animation */
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(10px);
        }

        .delay-100 {
            animation-delay: 100ms;
        }

        .delay-200 {
            animation-delay: 200ms;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-white min-h-screen flex text-slate-800 antialiased selection:bg-indigo-100 selection:text-indigo-900">

    {{-- ========================================== --}}
    {{-- LEFT PANEL: VISUAL & BRANDING (Hidden on Mobile) --}}
    {{-- ========================================== --}}
    <div class="relative hidden lg:flex lg:w-1/2 bg-slate-900 overflow-hidden items-center justify-center">

        {{-- Background Effects --}}
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-slate-900 to-black"></div>

        {{-- Abstract Decorative Shapes --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div
                class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-indigo-600/20 blur-[100px] animate-pulse">
            </div>
            <div class="absolute bottom-[10%] -right-[10%] w-[50%] h-[50%] rounded-full bg-blue-600/20 blur-[120px]">
            </div>

            {{-- Grid Pattern Overlay --}}
            <svg class="absolute inset-0 w-full h-full opacity-[0.03]" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M0 40V0h40" fill="none" stroke="white" stroke-width="2" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-pattern)" />
            </svg>
        </div>

        {{-- Branding Content --}}
        <div class="relative z-10 w-full max-w-lg px-12 fade-in-up">
            <div
                class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mb-8 border border-white/20 shadow-2xl">
                <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
            </div>

            <h1 class="text-4xl lg:text-5xl font-bold text-white tracking-tight mb-4 leading-tight">
                Sistem Pelaporan<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">
                    Stok Tabungan & Deposito
                </span>
            </h1>

            <p class="text-slate-400 text-lg leading-relaxed mb-10 max-w-md">
                Platform terpusat untuk pelaporan buku tabungan serta bilyet deposito guna mendukung data pelaporan OBOX
                OJK.
            </p>

            {{-- Glassmorphism Information Card --}}
            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-indigo-500/20 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-medium">Aplikasi Internal</h3>
                        <p class="text-slate-400 text-sm mt-1">Sistem ini hanya dapat diakses oleh karyawan internal
                            dengan jaringan kantor.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- RIGHT PANEL: LOGIN FORM --}}
    {{-- ========================================== --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-24 relative">

        <div class="w-full max-w-[440px] fade-in-up delay-100">

            {{-- Mobile Header (Hanya tampil di layar kecil) --}}
            <div class="lg:hidden text-center mb-10">
                <div
                    class="inline-flex items-center justify-center w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl mb-4 shadow-sm border border-indigo-100">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">SI Tab-Dep</h1>
                <p class="text-slate-500 text-sm mt-1">Pelaporan Stok Buku Tabungan & Deposito</p>
            </div>

            {{-- Form Header --}}
            <div class="mb-8 hidden lg:block">
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Selamat Datang</h2>
                <p class="text-slate-500 mt-2">Silakan masuk menggunakan NIK dan password Anda.</p>
            </div>

            {{-- Formulir Login --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                {{-- NIK Input --}}
                <div class="space-y-1.5">
                    <label for="nik" class="block text-sm font-semibold text-slate-700">
                        Nomor Induk Karyawan (NIK)
                    </label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-indigo-600 {{ $errors->has('nik') ? 'text-red-500' : 'text-slate-400' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                                </path>
                            </svg>
                        </div>
                        <input id="nik" type="text" name="nik" value="{{ old('nik') }}"
                            placeholder="Contoh: AP123456789" required autofocus autocomplete="off"
                            oninput="maskNik(this)"
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border rounded-xl text-sm font-medium text-slate-900 placeholder-slate-400 transition-all duration-300
                               hover:bg-slate-100/50 hover:border-slate-300
                               focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500
                               {{ $errors->has('nik') ? 'border-red-300 focus:border-red-500 focus:ring-red-500/10' : 'border-slate-200' }}">
                    </div>
                    @error('nik')
                        <p class="text-xs font-medium text-red-500 flex items-center gap-1.5 mt-1.5 fade-in-up">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password Input with Alpine.js --}}
                <div class="space-y-1.5" x-data="{ showPassword: false }">
                    <div class="flex justify-between items-center">
                        <label for="password" class="block text-sm font-semibold text-slate-700">
                            Password
                        </label>
                        {{-- Forgot Password Link (Dipindah ke atas field agar lebih terlihat oleh mata) --}}
                        <button type="button"
                            class="text-sm font-medium text-indigo-600 hover:text-indigo-700 hover:underline underline-offset-4 transition-all focus:outline-none"
                            onclick="alert('Silakan hubungi Super Admin IT untuk mereset password Anda.')">
                            Lupa password?
                        </button>
                    </div>

                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-indigo-600 {{ $errors->has('password') ? 'text-red-500' : 'text-slate-400' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                            autocomplete="current-password" placeholder="Masukkan password Anda"
                            class="w-full pl-11 pr-12 py-3.5 bg-slate-50 border rounded-xl text-sm font-medium text-slate-900 placeholder-slate-400 transition-all duration-300
                               hover:bg-slate-100/50 hover:border-slate-300
                               focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500
                               {{ $errors->has('password') ? 'border-red-300 focus:border-red-500 focus:ring-red-500/10' : 'border-slate-200' }}">

                        {{-- Show Password Button (press & hold to reveal) --}}
                        <button type="button" @mousedown.prevent="showPassword = true"
                            @mouseup.prevent="showPassword = false" @mouseleave.prevent="showPassword = false"
                            class="absolute inset-y-0 right-0 px-4 flex items-center text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors rounded-r-xl">
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                </path>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs font-medium text-red-500 flex items-center gap-1.5 mt-1.5 fade-in-up">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember Me & Submit --}}
                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-slate-900 hover:bg-indigo-600 text-white font-semibold py-3.5 px-6 rounded-xl
                           text-sm transition-all duration-300 active:scale-[0.98] shadow-[0_4px_14px_0_rgb(0,0,0,0.1)] hover:shadow-[0_6px_20px_rgba(79,70,229,0.23)]
                           focus:outline-none focus:ring-4 focus:ring-indigo-500/30 flex justify-center items-center gap-2 group">
                        <span>Masuk ke Dashboard</span>
                        <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </div>

            </form>

            {{-- Registration / Footer Info --}}
            <div class="mt-10 text-center fade-in-up delay-200">
                <p class="text-sm text-slate-500">
                    Belum memiliki akses sistem?
                    <a href="{{ route('register') }}"
                        class="text-slate-900 font-semibold hover:text-indigo-600 transition-colors underline decoration-slate-300 hover:decoration-indigo-600 underline-offset-4">
                        Daftarkan Akun
                    </a>
                </p>
            </div>

        </div>

        {{-- Absolute Footer Text for Right Panel --}}
        <div class="absolute bottom-6 text-center w-full max-w-[440px] hidden sm:block">
            <p class="text-xs text-slate-400 font-medium">
                &copy; {{ date('Y') }} Information Technology. BPR Artha Pamenang.
            </p>
        </div>

    </div>

</body>

</html>
