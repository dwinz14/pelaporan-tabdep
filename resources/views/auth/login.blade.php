<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SI Stok Tab-Dep</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center font-sans antialiased">

    <div class="w-full max-w-md px-4">

        {{-- Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="bg-slate-900 px-8 py-6 text-center">
                <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <span class="text-white font-bold text-lg">SI</span>
                </div>
                <h1 class="text-white font-semibold text-lg">SI Stok Tab-Dep</h1>
                <p class="text-slate-400 text-sm mt-0.5">Sistem Informasi Stok Buku Tabungan & Deposito</p>
            </div>

            {{-- Form --}}
            <div class="px-8 py-7">
                <h2 class="text-gray-900 font-semibold mb-5">Masuk ke Sistem</h2>

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- NIK --}}
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">
                            Nomor Induk Karyawan (NIK)
                        </label>
                        <input id="nik" type="text" name="nik" value="{{ old('nik') }}"
                            placeholder="Contoh: AP123456789" required autofocus autocomplete="off"
                            class="w-full px-3 py-2 border rounded-lg text-sm font-mono
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                               {{ $errors->has('nik') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @error('nik')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password
                        </label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="w-full px-3 py-2 border rounded-lg text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                               {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-4 rounded-lg
                           text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Masuk
                    </button>

                </form>
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-500">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                            Daftar di sini
                        </a>
                    </p>
                </div>
            </div>

        </div>

        <p class="text-center text-xs text-gray-400 mt-4">
            Lupa password? Hubungi Super Admin.
        </p>

    </div>

</body>

</html>
