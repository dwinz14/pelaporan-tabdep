<x-app-layout :title="$title" :subtitle="$subtitle">

    <div class="max-w-4xl mx-auto pb-10">

        {{-- Header Navigation --}}
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.user.index') }}"
                    class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors focus:ring-2 focus:ring-indigo-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Edit Data Pengguna</h2>
                    <p class="text-sm text-slate-500">Perbarui informasi, password, dan hak akses user.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl overflow-hidden">

            {{-- User Context / Mini Profile --}}
            <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 rounded-full bg-indigo-100 text-indigo-600 font-bold text-xl flex items-center justify-center flex-shrink-0 border-4 border-white shadow-sm">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg leading-tight">{{ $user->name }}</h3>
                        <p class="text-sm font-mono text-slate-500 mt-0.5">NIK: {{ $user->nik }}</p>
                    </div>
                </div>
                <div>
                    @if ($user->is_active)
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Akun Aktif
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-50 text-slate-600 border border-slate-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Akun Nonaktif
                        </span>
                    @endif
                </div>
            </div>

            <form method="POST" action="{{ route('admin.user.update', $user) }}" x-data="{
                role: '{{ old('role', $user->role->value) }}',
                pwd: '',
                confirmPwd: '',
                get lenOk() { return this.pwd.length >= 6 },
                get upperOk() { return /[A-Z]/.test(this.pwd) },
                get numOk() { return /[0-9]/.test(this.pwd) },
                get specialOk() { return /[^A-Za-z0-9]/.test(this.pwd) },
                get matchOk() { return this.confirmPwd.length > 0 && this.pwd === this.confirmPwd }
            }">
                @csrf @method('PUT')

                <div class="px-8 py-8 space-y-10">

                    {{-- SECTION 1: Informasi Profil --}}
                    <div class="space-y-5">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">Informasi Personal</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- NIK --}}
                            <div class="space-y-2">
                                <label for="nik"
                                    class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                                    NIK <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="nik" name="nik" value="{{ old('nik', $user->nik) }}"
                                    maxlength="11" oninput="maskNik(this)" placeholder="Masukkan NIK 11 digit"
                                    class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm font-mono text-slate-800 placeholder:text-slate-400
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all
                                           {{ $errors->has('nik') ? 'border-rose-400 bg-rose-50/50' : 'border-slate-200' }}">
                                @error('nik')
                                    <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Nama --}}
                            <div class="space-y-2">
                                <label for="name"
                                    class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                                    Nama Lengkap <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="name" name="name"
                                    value="{{ old('name', $user->name) }}" placeholder="Contoh: John Doe"
                                    class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm text-slate-800 placeholder:text-slate-400
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all
                                           {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/50' : 'border-slate-200' }}">
                                @error('name')
                                    <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="md:col-span-2 space-y-2">
                                <label for="email"
                                    class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                                    Email Pribadi <span
                                        class="text-slate-400 font-medium normal-case tracking-normal">(Opsional)</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="email" id="email" name="email"
                                        value="{{ old('email', $user->email) }}" placeholder="john@example.com"
                                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border rounded-xl text-sm text-slate-800 placeholder:text-slate-400
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all
                                               {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/50' : 'border-slate-200' }}">
                                </div>
                                @error('email')
                                    <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: Keamanan --}}
                    <div class="space-y-5">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">Keamanan Akun</h3>
                        </div>

                        <div class="bg-amber-50/50 border border-amber-100 rounded-xl p-4 flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="text-sm text-amber-800">
                                <p class="font-bold">Ubah Password?</p>
                                <p class="text-amber-700/80 mt-0.5">Biarkan kedua kolom di bawah
                                    <strong>kosong</strong> jika Anda tidak ingin merubah password saat ini.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative">
                            {{-- Password --}}
                            <div class="space-y-2">
                                <label for="password"
                                    class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                                    Password Baru
                                </label>
                                <input type="password" id="password" name="password" x-model="pwd"
                                    placeholder="••••••••"
                                    class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm text-slate-800 placeholder:text-slate-400
                                           focus:outline-none focus:ring-2 focus:ring-amber-500 focus:bg-white transition-all
                                           {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/50' : 'border-slate-200' }}">
                                @error('password')
                                    <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Konfirmasi --}}
                            <div class="space-y-2">
                                <label for="password_confirmation"
                                    class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                                    Konfirmasi Password
                                </label>
                                <div class="relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        x-model="confirmPwd" placeholder="Ulangi password baru"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder:text-slate-400
                                               focus:outline-none focus:ring-2 focus:ring-amber-500 focus:bg-white transition-all pr-10">

                                    {{-- Indikator Match Password --}}
                                    <div x-show="confirmPwd.length > 0" x-transition
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg x-show="matchOk" class="w-5 h-5 text-emerald-500" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <svg x-show="!matchOk" class="w-5 h-5 text-rose-500" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <p x-show="confirmPwd.length > 0 && !matchOk" x-transition
                                    class="mt-1.5 text-xs font-medium text-rose-500">Password tidak sama.</p>
                            </div>
                        </div>

                        {{-- Checklist Kekuatan Password (Animated) --}}
                        <div x-show="pwd.length > 0" x-collapse x-cloak>
                            <div
                                class="p-4 bg-slate-900 rounded-xl shadow-lg shadow-slate-900/10 border border-slate-800 mt-2">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Syarat
                                    Keamanan Password</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-6">
                                    @foreach ([['get' => 'lenOk', 'label' => 'Minimal 6 karakter'], ['get' => 'upperOk', 'label' => 'Huruf kapital (A-Z)'], ['get' => 'numOk', 'label' => 'Mengandung Angka (0-9)'], ['get' => 'specialOk', 'label' => 'Karakter spesial (!@#$)']] as $item)
                                        <div class="flex items-center gap-2.5 transition-colors duration-300"
                                            :class="{{ $item['get'] }} ? 'text-emerald-400' : 'text-slate-500'">
                                            <div class="relative w-4 h-4 flex items-center justify-center">
                                                {{-- Checked Icon --}}
                                                <svg class="absolute inset-0 w-4 h-4 transition-transform duration-300 transform scale-0"
                                                    :class="{{ $item['get'] }} ? 'scale-100 opacity-100' : 'scale-0 opacity-0'"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                {{-- Unchecked Icon --}}
                                                <svg class="absolute inset-0 w-4 h-4 transition-transform duration-300 transform scale-100"
                                                    :class="{{ $item['get'] }} ? 'scale-0 opacity-0' : 'scale-100 opacity-100'"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="9" stroke-width="2" />
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium">{{ $item['label'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: Hak Akses --}}
                    <div class="space-y-5">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">Hak Akses & Penempatan</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Role --}}
                            <div class="space-y-2">
                                <label for="role"
                                    class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                                    Role Pengguna <span class="text-rose-500">*</span>
                                </label>
                                <select id="role" name="role" x-model="role"
                                    class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all appearance-none cursor-pointer
                                           {{ $errors->has('role') ? 'border-rose-400 bg-rose-50/50' : 'border-slate-200' }}">
                                    @foreach ($roles as $r)
                                        <option value="{{ $r->value }}"
                                            {{ old('role', $user->role->value) === $r->value ? 'selected' : '' }}>
                                            {{ $r->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Cabang (Animated Entry) --}}
                            <div x-show="role === 'pic_cabang'" x-collapse>
                                <div class="space-y-2 pb-2">
                                    <label for="id_cabang"
                                        class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                                        Penempatan Cabang <span class="text-rose-500">*</span>
                                    </label>
                                    <select id="id_cabang" name="id_cabang"
                                        class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all appearance-none cursor-pointer
                                               {{ $errors->has('id_cabang') ? 'border-rose-400 bg-rose-50/50' : 'border-slate-200' }}">
                                        <option value="">— Pilih Cabang Operasional —</option>
                                        @foreach ($cabangs as $cabang)
                                            <option value="{{ $cabang->id }}"
                                                {{ old('id_cabang', $user->id_cabang) == $cabang->id ? 'selected' : '' }}>
                                                {{ $cabang->kode_cabang }} — {{ $cabang->nama_cabang }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_cabang')
                                        <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Footer / Actions --}}
                <div
                    class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                    <a href="{{ route('admin.user.index') }}"
                        class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-100 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300 text-center">
                        Batal
                    </a>
                    <button type="submit"
                        class="w-full sm:w-auto px-8 py-2.5 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all focus:outline-none focus:ring-4 focus:ring-indigo-100 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
