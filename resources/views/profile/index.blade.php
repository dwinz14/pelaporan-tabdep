<x-app-layout :title="$title" :subtitle="$subtitle">

    <div class="max-w-2xl space-y-5">

        {{-- ═══ HEADER PROFIL ═══ --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-5">
            {{-- Avatar --}}
            <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                <span class="text-white font-bold text-2xl">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </span>
            </div>

            <div class="flex-1 min-w-0">
                <h2 class="text-lg font-bold text-gray-900 truncate">{{ $user->name }}</h2>
                <p class="text-xs font-mono text-gray-400 mt-0.5">{{ $user->nik }}</p>
                <div class="flex items-center gap-2 mt-2 flex-wrap">
                    @php
                        $roleColor = match ($user->role) {
                            \App\Enums\UserRole::PicCabang => 'bg-sky-100 text-sky-800',
                            \App\Enums\UserRole::Akunting => 'bg-emerald-100 text-emerald-800',
                            \App\Enums\UserRole::KepalaOperasional => 'bg-amber-100 text-amber-800',
                            \App\Enums\UserRole::SuperAdmin => 'bg-rose-100 text-rose-800',
                        };
                    @endphp
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $roleColor }}">
                        {{ $user->roleLabel() }}
                    </span>
                    @if ($user->cabang)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            {{ $user->cabang->kode_cabang }} — {{ $user->cabang->nama_cabang }}
                        </span>
                    @endif
                    @if ($user->is_active)
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            ● Aktif
                        </span>
                    @endif
                </div>
            </div>
        </div>


        {{-- ═══ FORM: DATA PROFIL ═══ --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Data Profil</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Informasi yang bisa Anda perbarui sendiri</p>
                </div>
                @if (session('success_profile'))
                    <span
                        class="flex items-center gap-1.5 text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-full">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ session('success_profile') }}
                    </span>
                @endif
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="px-5 py-5 space-y-4">
                @csrf @method('PUT')

                {{-- NIK — Read Only --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
                        NIK (Nomor Induk Karyawan)
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="text" value="{{ $user->nik }}" disabled readonly
                            class="flex-1 px-3 py-2.5 border border-gray-200 rounded-lg text-sm font-mono
                               bg-gray-50 text-gray-500 cursor-not-allowed">
                        <span
                            class="flex items-center gap-1 text-xs text-gray-400 bg-gray-50 border border-gray-200
                                 px-3 py-2.5 rounded-lg whitespace-nowrap flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Dikelola Admin
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-gray-400">NIK hanya dapat diubah oleh Super Admin.</p>
                </div>

                {{-- Nama --}}
                <div>
                    <label for="name"
                        class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                        placeholder="Nama lengkap Anda"
                        class="w-full px-3 py-2.5 border rounded-lg text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                           {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email"
                        class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
                        Email
                        <span class="text-gray-400 font-normal normal-case tracking-normal">(opsional)</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                        placeholder="email@perusahaan.co.id"
                        class="w-full px-3 py-2.5 border rounded-lg text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                           {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role & Cabang — Read Only --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
                            Role
                        </label>
                        <div class="flex items-center gap-2 px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg">
                            <span
                                class="w-2 h-2 rounded-full flex-shrink-0
                                     {{ match ($user->role) {
                                         \App\Enums\UserRole::PicCabang => 'bg-sky-500',
                                         \App\Enums\UserRole::Akunting => 'bg-emerald-500',
                                         \App\Enums\UserRole::KepalaOperasional => 'bg-amber-500',
                                         \App\Enums\UserRole::SuperAdmin => 'bg-rose-500',
                                     } }}"></span>
                            <span class="text-sm text-gray-600">{{ $user->roleLabel() }}</span>
                        </div>
                        <p class="mt-1 text-xs text-gray-400">Dikelola Admin</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
                            Cabang
                        </label>
                        <div class="px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg">
                            @if ($user->cabang)
                                <p class="text-sm text-gray-600">
                                    <span class="font-mono text-gray-400 mr-1">{{ $user->cabang->kode_cabang }}</span>
                                    {{ $user->cabang->nama_cabang }}
                                </p>
                            @else
                                <p class="text-sm text-gray-400">— (Pusat)</p>
                            @endif
                        </div>
                        <p class="mt-1 text-xs text-gray-400">Dikelola Admin</p>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm
                           font-semibold rounded-lg hover:bg-indigo-700 transition-colors
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>


        {{-- ═══ FORM: GANTI PASSWORD ═══ --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden" x-data="{
            current: '',
            pwd: '',
            confirmPwd: '',
            showCurrent: false,
            showPwd: false,
            showConfirm: false,
            get lenOk() { return this.pwd.length >= 6 },
            get upperOk() { return /[A-Z]/.test(this.pwd) },
            get numOk() { return /[0-9]/.test(this.pwd) },
            get specialOk() { return /[^A-Za-z0-9]/.test(this.pwd) },
            get diffOk() { return this.pwd.length > 0 && this.pwd !== this.current },
            get matchOk() { return this.confirmPwd.length > 0 && this.pwd === this.confirmPwd },
            get allOk() { return this.lenOk && this.upperOk && this.numOk && this.specialOk && this.diffOk && this.matchOk },
        }">

            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Ganti Password</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Wajib verifikasi password saat ini</p>
                </div>
                @if (session('success_password'))
                    <span
                        class="flex items-center gap-1.5 text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-full">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ session('success_password') }}
                    </span>
                @endif
            </div>

            <form method="POST" action="{{ route('profile.password') }}" class="px-5 py-5 space-y-4">
                @csrf @method('PUT')

                {{-- Password Saat Ini --}}
                <div>
                    <label for="current_password"
                        class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
                        Password Saat Ini <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showCurrent ? 'text' : 'password'" id="current_password" name="current_password"
                            x-model="current"
                            class="w-full px-3 py-2.5 pr-10 border rounded-lg text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                               {{ $errors->has('current_password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        <button type="button" @click="showCurrent = !showCurrent"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg x-show="!showCurrent" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showCurrent" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Baru --}}
                <div>
                    <label for="password"
                        class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showPwd ? 'text' : 'password'" id="password" name="password" x-model="pwd"
                            class="w-full px-3 py-2.5 pr-10 border rounded-lg text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                               {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        <button type="button" @click="showPwd = !showPwd"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg x-show="!showPwd" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPwd" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password Baru --}}
                <div>
                    <label for="password_confirmation"
                        class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
                        Konfirmasi Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation"
                            name="password_confirmation" x-model="confirmPwd"
                            class="w-full px-3 py-2.5 pr-10 border rounded-lg text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                               border-gray-300">
                        <button type="button" @click="showConfirm = !showConfirm"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showConfirm" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <p x-show="confirmPwd.length > 0" x-cloak class="mt-1 text-xs"
                        :class="matchOk ? 'text-emerald-600' : 'text-red-500'">
                        <span x-text="matchOk ? '✓ Password cocok' : '✕ Password tidak cocok'"></span>
                    </p>
                </div>

                {{-- Checklist Kekuatan Password --}}
                <div x-show="pwd.length > 0" x-cloak
                    class="p-4 bg-gray-50 border border-gray-200 rounded-xl space-y-2">
                    <p class="text-xs font-semibold text-gray-600 mb-2">Password baru harus memenuhi:</p>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ([['model' => 'lenOk', 'label' => 'Minimal 6 karakter'], ['model' => 'upperOk', 'label' => 'Huruf kapital (A–Z)'], ['model' => 'numOk', 'label' => 'Angka (0–9)'], ['model' => 'specialOk', 'label' => 'Karakter spesial (!@#$%...)'], ['model' => 'diffOk', 'label' => 'Berbeda dari password lama']] as $c)
                            <p class="text-xs flex items-center gap-1.5 col-span-1
                                  {{ $c['model'] === 'diffOk' ? 'col-span-2' : '' }}"
                                :class="{{ $c['model'] }} ? 'text-emerald-600' : 'text-gray-400'">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"
                                    x-show="{{ $c['model'] }}" x-cloak>
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" x-show="!{{ $c['model'] }}">
                                    <circle cx="12" cy="12" r="9" stroke-width="1.5" />
                                </svg>
                                {{ $c['label'] }}
                            </p>
                        @endforeach
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" :disabled="pwd.length > 0 && !allOk"
                        class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm
                           font-semibold rounded-lg hover:bg-indigo-700 transition-colors
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                           disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Perbarui Password
                    </button>
                    <p class="text-xs text-gray-400">
                        Setelah berhasil, Anda tetap login dengan session yang sama.
                    </p>
                </div>

            </form>
        </div>



    </div>

</x-app-layout>
