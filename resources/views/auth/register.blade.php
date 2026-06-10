<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — SI Stok Tab-Dep</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center font-sans antialiased py-8">

    <div class="w-full max-w-lg px-4">

        {{-- Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="bg-slate-900 px-8 py-6 text-center">
                <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <span class="text-white font-bold text-lg">SI</span>
                </div>
                <h1 class="text-white font-semibold text-lg">Daftar Akun Baru</h1>
                <p class="text-slate-400 text-sm mt-0.5">SI Stok Buku Tabungan & Deposito</p>
            </div>

            {{-- Info Banner --}}
            <div class="px-6 pt-5">
                <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg flex items-start gap-2">
                    <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-xs text-amber-800">
                        Setelah mendaftar, akun Anda akan <strong>menunggu persetujuan</strong> dari Super Admin
                        sebelum dapat digunakan untuk login.
                    </p>
                </div>
            </div>

            {{-- Form --}}
            <div class="px-8 py-6">
                <form method="POST" action="{{ route('register') }}" x-data class="space-y-4">
                    @csrf

                    {{-- NIK --}}
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">
                            Nomor Induk Karyawan (NIK) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nik" name="nik" value="{{ old('nik') }}"
                            placeholder="Contoh: AP123456789" maxlength="11" autocomplete="off"
                            class="w-full px-3 py-2.5 border rounded-lg text-sm font-mono
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                               {{ $errors->has('nik') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @error('nik')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">Format: AP + 9 digit angka</p>
                    </div>

                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            placeholder="Nama sesuai identitas"
                            class="w-full px-3 py-2.5 border rounded-lg text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                               {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="email@perusahaan.co.id"
                            class="w-full px-3 py-2.5 border rounded-lg text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                               {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Cabang --}}
                    <div>
                        <label for="id_cabang" class="block text-sm font-medium text-gray-700 mb-1">
                            Kantor Cabang <span class="text-red-500">*</span>
                        </label>
                        <select id="id_cabang" name="id_cabang"
                            class="w-full px-3 py-2.5 border rounded-lg text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                               {{ $errors->has('id_cabang') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            <option value="">— Pilih Kantor Cabang Anda —</option>
                            @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->id }}"
                                    {{ old('id_cabang') == $cabang->id ? 'selected' : '' }}>
                                    {{ $cabang->kode_cabang }} — {{ $cabang->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_cabang')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="password" name="password"
                                class="w-full px-3 py-2.5 border rounded-lg text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                   {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-400">Minimal 6 karakter</p>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>

                    {{-- Role Info --}}
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs text-gray-500">
                            Registrasi mandiri hanya untuk <strong>PIC Cabang</strong>.
                            Role lain didaftarkan langsung oleh Super Admin.
                        </p>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-4
                           rounded-lg text-sm transition-colors focus:outline-none focus:ring-2
                           focus:ring-indigo-500 focus:ring-offset-2">
                        Daftar Sekarang
                    </button>
                </form>
            </div>

            {{-- Footer --}}
            <div class="px-8 pb-6 text-center">
                <p class="text-sm text-gray-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                        Masuk di sini
                    </a>
                </p>
            </div>

        </div>
    </div>

</body>

</html>
