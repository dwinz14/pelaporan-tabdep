<x-app-layout :title="$title" :subtitle="$subtitle">

    @php
        // Logika penentuan tab aktif (menangkap error/sukses form password)
        $defaultTab =
            session('success_password') || $errors->has('current_password') || $errors->has('password')
                ? 'password'
                : 'profile';
    @endphp

    {{-- Wrapper utama untuk memposisikan card agar floating di tengah (opsional, disesuaikan dengan padding layout parent) --}}
    <div class="py-6 sm:py-10 flex justify-center w-full">

        {{--
            UNIFIED FLOATING CARD
            - Menggunakan bg-gradient-to-br untuk gradasi indigo yang sangat halus
            - shadow premium dengan hint warna indigo
            - rounded-3xl untuk estetika modern yang tidak kaku
        --}}
        <div class="w-full max-w-4xl relative bg-gradient-to-br from-white via-slate-50/30 to-indigo-500/60 rounded-[2rem] shadow-xl border border border-[--color-border-default] shadow-xl overflow-hidden border-t-4 border-t-indigo-600 overflow-hidden ring-1 ring-slate-900/5 backdrop-blur-xl"
            x-data="{ activeTab: '{{ $defaultTab }}' }">

            {{-- Efek cahaya/glow dekoratif di sudut card (sangat subtle) --}}
            <div
                class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-300/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none">
            </div>
            <div
                class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-300/10 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4 pointer-events-none">
            </div>

            <div class="relative z-10">

                {{-- ═══ HEADER SECTION (Terintegrasi di dalam card) ═══ --}}
                <div class="px-6 sm:px-10 pt-10 pb-6 flex flex-col sm:flex-row items-center sm:items-start gap-6">
                    {{-- Avatar --}}
                    <div
                        class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-indigo-600 to-blue-700 rounded-full flex items-center justify-center flex-shrink-0 shadow-[0_0_20px_rgba(79,70,229,0.3)] ring-4 ring-white relative">
                        <span class="text-white font-bold text-3xl sm:text-4xl shadow-sm tracking-widest">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                        {{-- Status Dot --}}
                        @if ($user->is_active)
                            <div
                                class="absolute bottom-0 right-1 w-5 h-5 bg-emerald-500 border-4 border-white rounded-full">
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0 text-center sm:text-left mt-2 sm:mt-0">
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">{{ $user->name }}
                        </h2>
                        <div
                            class="flex items-center justify-center sm:justify-start gap-1.5 mt-1.5 text-indigo-600/80 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                                </path>
                            </svg>
                            <p class="text-sm">{{ $user->nik }}</p>
                        </div>

                        <div class="flex items-center justify-center sm:justify-start gap-2 mt-4 flex-wrap">
                            @php
                                $roleColor = match ($user->role) {
                                    \App\Enums\UserRole::PicCabang => 'bg-indigo-100 text-indigo-700',
                                    \App\Enums\UserRole::Akunting => 'bg-emerald-100 text-emerald-700',
                                    \App\Enums\UserRole::KepalaOperasional => 'bg-amber-100 text-amber-700',
                                    \App\Enums\UserRole::SuperAdmin => 'bg-rose-100 text-rose-700',
                                };
                            @endphp
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider {{ $roleColor }}">
                                {{ $user->roleLabel() }}
                            </span>
                            @if ($user->cabang)
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-white/60 text-slate-700 border border-slate-200/50 backdrop-blur-sm shadow-sm">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                    {{ $user->cabang->kode_cabang }} — {{ $user->cabang->nama_cabang }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ═══ INTEGRATED TAB NAVIGATION ═══ --}}
                <div class="px-6 sm:px-10 border-b border-indigo-900/5 flex gap-6 overflow-x-auto hide-scrollbar">
                    <button @click="activeTab = 'profile'"
                        class="relative py-4 text-sm font-semibold transition-colors duration-200 focus:outline-none whitespace-nowrap"
                        :class="activeTab === 'profile' ? 'text-indigo-700' : 'text-slate-500 hover:text-slate-800'">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Data Personal
                        </div>
                        {{-- Active Indicator Line --}}
                        <div x-show="activeTab === 'profile'" x-transition x-cloak
                            class="absolute bottom-0 left-0 w-full h-0.5 bg-indigo-600 rounded-t-full"></div>
                    </button>

                    <button @click="activeTab = 'password'"
                        class="relative py-4 text-sm font-semibold transition-colors duration-200 focus:outline-none whitespace-nowrap"
                        :class="activeTab === 'password' ? 'text-indigo-700' : 'text-slate-500 hover:text-slate-800'">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            Keamanan & Password
                        </div>
                        {{-- Active Indicator Line --}}
                        <div x-show="activeTab === 'password'" x-transition x-cloak
                            class="absolute bottom-0 left-0 w-full h-0.5 bg-indigo-600 rounded-t-full"></div>
                    </button>
                </div>


                {{-- ═══ TAB CONTENT CONTAINER ═══ --}}
                <div class="relative bg-white/40 backdrop-blur-md rounded-b-[2rem]">

                    {{-- ========================================== --}}
                    {{-- TAB 1: DATA PROFIL --}}
                    {{-- ========================================== --}}
                    <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0" class="pb-2" x-cloak>

                        <div
                            class="px-6 sm:px-10 pt-8 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Informasi Personal</h3>
                                <p class="text-sm text-slate-500 mt-1">Kelola data identitas dan email yang digunakan
                                    untuk komunikasi sistem.</p>
                            </div>
                            @if (session('success_profile'))
                                <span
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg shadow-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ session('success_profile') }}
                                </span>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf @method('PUT')

                            <div class="px-6 sm:px-10 pb-8 space-y-8">
                                {{-- Editable Section (Input menggunakan bg-white solid agar kontras dengan card) --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Nama --}}
                                    <div class="space-y-2">
                                        <label for="name" class="block text-sm font-semibold text-slate-700">Nama
                                            Lengkap <span class="text-rose-500">*</span></label>
                                        <input type="text" id="name" name="name"
                                            value="{{ old('name', $user->name) }}" placeholder="Nama lengkap Anda"
                                            class="w-full px-4 py-3 bg-white border shadow-xl overflow-hidden border-t-4 border-t-indigo-600 shadow-sm rounded-xl text-sm font-medium text-slate-900 placeholder-slate-400 transition-all duration-200
                                               hover:border-emerald-300 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500
                                               {{ $errors->has('name') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/10' : 'border-emerald-100' }}">
                                        @error('name')
                                            <p class="text-xs font-medium text-rose-500 flex items-center gap-1 mt-1.5"><svg
                                                    class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div class="space-y-2">
                                        <label for="email" class="block text-sm font-semibold text-slate-700">Alamat
                                            Email <span class="text-slate-400 font-normal">(opsional)</span></label>
                                        <input type="email" id="email" name="email"
                                            value="{{ old('email', $user->email) }}"
                                            placeholder="email@perusahaan.co.id"
                                            class="w-full px-4 py-3 bg-white border shadow-xl overflow-hidden border-t-4 border-t-indigo-600 shadow-sm rounded-xl text-sm font-medium text-slate-900 placeholder-slate-400 transition-all duration-200
                                               hover:border-emerald-300 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500
                                               {{ $errors->has('email') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/10' : 'border-emerald-100' }}">
                                        @error('email')
                                            <p class="text-xs font-medium text-rose-500 flex items-center gap-1 mt-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Divider Clean --}}
                                <div
                                    class="w-full h-px bg-gradient-to-r from-transparent via-indigo-900/10 to-transparent my-2">
                                </div>

                                {{-- Read-Only Section --}}
                                <div>
                                    <h4 class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-4">
                                        Informasi Akses Sistem</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        {{-- NIK --}}
                                        <div class="bg-indigo-50/50 rounded-xl p-4 border border-indigo-100/50">
                                            <p
                                                class="text-xs font-semibold text-indigo-900/50 uppercase tracking-wider mb-1.5">
                                                NIK</p>
                                            <p class="text-sm font-mono font-semibold text-indigo-900">
                                                {{ $user->nik }}</p>
                                        </div>

                                        {{-- Role --}}
                                        <div class="bg-indigo-50/50 rounded-xl p-4 border border-indigo-100/50">
                                            <p
                                                class="text-xs font-semibold text-indigo-900/50 uppercase tracking-wider mb-1.5">
                                                Tipe Akses</p>
                                            <p class="text-sm font-semibold text-indigo-900">{{ $user->roleLabel() }}
                                            </p>
                                        </div>

                                        {{-- Cabang --}}
                                        <div class="bg-indigo-50/50 rounded-xl p-4 border border-indigo-100/50">
                                            <p
                                                class="text-xs font-semibold text-indigo-900/50 uppercase tracking-wider mb-1.5">
                                                Area Cabang</p>
                                            <p class="text-sm font-semibold text-indigo-900 truncate">
                                                {{ $user->cabang ? $user->cabang->nama_cabang : 'Kantor Pusat' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Form Actions --}}
                                <div class="flex items-center justify-end pt-4">
                                    <button type="submit"
                                        class="flex items-center justify-center gap-2 px-8 py-3 bg-indigo-600 text-white text-sm
                                           font-bold rounded-xl hover:bg-indigo-700 transition-all duration-300 shadow-[0_6px_20px_rgba(79,70,229,0.25)] hover:shadow-[0_8px_25px_rgba(79,70,229,0.35)]
                                           focus:outline-none focus:ring-4 focus:ring-indigo-500/30 active:scale-[0.98]">
                                        Simpan Profil
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>


                    {{-- ========================================== --}}
                    {{-- TAB 2: GANTI PASSWORD --}}
                    {{-- ========================================== --}}
                    <div x-show="activeTab === 'password'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0" class="pb-2" x-cloak>

                        <div x-data="{
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

                            <div
                                class="px-6 sm:px-10 pt-8 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Keamanan Password</h3>
                                    <p class="text-sm text-slate-500 mt-1">Lindungi akun Anda dengan menggunakan
                                        password kombinasi yang kuat.</p>
                                </div>
                                @if (session('success_password'))
                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg shadow-sm">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ session('success_password') }}
                                    </span>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('profile.password') }}">
                                @csrf @method('PUT')

                                <div class="px-6 sm:px-10 pb-8 space-y-8 max-w-3xl">
                                    {{-- Password Saat Ini --}}
                                    <div class="space-y-2">
                                        <label for="current_password"
                                            class="block text-sm font-semibold text-slate-700">Password Lama <span
                                                class="text-rose-500">*</span></label>
                                        <div class="relative group max-w-md">
                                            <input :type="showCurrent ? 'text' : 'password'" id="current_password"
                                                name="current_password" x-model="current"
                                                placeholder="Masukkan password Anda saat ini"
                                                class="w-full px-4 py-3 pr-12 bg-white border shadow-sm rounded-xl text-sm font-medium text-slate-900 placeholder-slate-400 transition-all duration-200
                                                   hover:border-indigo-300 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500
                                                   {{ $errors->has('current_password') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/10' : 'border-indigo-100' }}">
                                            <button type="button" @click="showCurrent = !showCurrent"
                                                class="absolute right-0 top-0 bottom-0 px-4 flex items-center text-slate-400 hover:text-indigo-600 focus:outline-none">
                                                <svg x-show="!showCurrent" class="w-5 h-5" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <svg x-show="showCurrent" class="w-5 h-5" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                </svg>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <p class="text-xs font-medium text-rose-500 flex items-center gap-1 mt-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        {{-- Password Baru --}}
                                        <div class="space-y-2">
                                            <label for="password"
                                                class="block text-sm font-semibold text-slate-700">Password Baru <span
                                                    class="text-rose-500">*</span></label>
                                            <div class="relative group">
                                                <input :type="showPwd ? 'text' : 'password'" id="password"
                                                    name="password" x-model="pwd" placeholder="Ketik password baru"
                                                    class="w-full px-4 py-3 pr-12 bg-white border shadow-sm rounded-xl text-sm font-medium text-slate-900 placeholder-slate-400 transition-all duration-200
                                                       hover:border-indigo-300 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500
                                                       {{ $errors->has('password') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/10' : 'border-indigo-100' }}">
                                                <button type="button" @click="showPwd = !showPwd"
                                                    class="absolute right-0 top-0 bottom-0 px-4 flex items-center text-slate-400 hover:text-indigo-600 focus:outline-none">
                                                    <svg x-show="!showPwd" class="w-5 h-5" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <svg x-show="showPwd" class="w-5 h-5" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                    </svg>
                                                </button>
                                            </div>
                                            @error('password')
                                                <p
                                                    class="text-xs font-medium text-rose-500 flex items-center gap-1 mt-1.5">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        {{-- Konfirmasi Password Baru --}}
                                        <div class="space-y-2">
                                            <label for="password_confirmation"
                                                class="block text-sm font-semibold text-slate-700">Ulangi Password
                                                <span class="text-rose-500">*</span></label>
                                            <div class="relative group">
                                                <input :type="showConfirm ? 'text' : 'password'"
                                                    id="password_confirmation" name="password_confirmation"
                                                    x-model="confirmPwd" placeholder="Ketik ulang password baru"
                                                    class="w-full px-4 py-3 pr-12 bg-white border shadow-sm rounded-xl text-sm font-medium text-slate-900 placeholder-slate-400 transition-all duration-200
                                                       hover:border-indigo-300 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 border-indigo-100">
                                                <button type="button" @click="showConfirm = !showConfirm"
                                                    class="absolute right-0 top-0 bottom-0 px-4 flex items-center text-slate-400 hover:text-indigo-600 focus:outline-none">
                                                    <svg x-show="!showConfirm" class="w-5 h-5" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <svg x-show="showConfirm" class="w-5 h-5" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <p x-show="confirmPwd.length > 0" x-cloak
                                                class="mt-1.5 text-xs font-bold flex items-center gap-1"
                                                :class="matchOk ? 'text-emerald-600' : 'text-rose-500'">
                                                <svg x-show="matchOk" class="w-3.5 h-3.5" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <svg x-show="!matchOk" class="w-3.5 h-3.5" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                <span
                                                    x-text="matchOk ? 'Password cocok' : 'Password tidak cocok'"></span>
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Checklist Kekuatan Password --}}
                                    <div x-show="pwd.length > 0" x-cloak x-transition.opacity
                                        class="p-5 bg-white/60 backdrop-blur-sm border border-indigo-100 rounded-2xl shadow-sm">
                                        <p class="text-sm font-bold text-slate-800 mb-3">Persyaratan password aman:</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            @foreach ([['model' => 'lenOk', 'label' => 'Minimal 6 karakter'], ['model' => 'upperOk', 'label' => 'Huruf kapital (A–Z)'], ['model' => 'numOk', 'label' => 'Angka (0–9)'], ['model' => 'specialOk', 'label' => 'Karakter spesial (!@#...)'], ['model' => 'diffOk', 'label' => 'Berbeda dari password lama']] as $c)
                                                <div
                                                    class="flex items-center gap-2.5 {{ $c['model'] === 'diffOk' ? 'sm:col-span-2' : '' }}">
                                                    <div class="w-5 h-5 flex items-center justify-center rounded-full transition-all duration-300"
                                                        :class="{{ $c['model'] }} ?
                                                            'bg-emerald-500 text-white shadow-sm shadow-emerald-500/30 scale-110' :
                                                            'bg-slate-200 text-transparent'">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="3" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm font-semibold transition-colors duration-200"
                                                        :class="{{ $c['model'] }} ? 'text-slate-800' : 'text-slate-500'">{{ $c['label'] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Form Actions --}}
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 pt-4">
                                        <p
                                            class="text-xs font-medium text-slate-500 flex items-center gap-2 order-2 sm:order-1">
                                            <span
                                                class="flex items-center justify-center w-5 h-5 rounded-full bg-amber-100 text-amber-600 shrink-0">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                            </span>
                                            Sesi Anda tidak akan diakhiri setelah perubahan ini.
                                        </p>
                                        <button type="submit" :disabled="pwd.length > 0 && !allOk"
                                            class="order-1 sm:order-2 flex items-center justify-center gap-2 px-8 py-3 bg-slate-900 text-white text-sm
                                               font-bold rounded-xl hover:bg-indigo-600 transition-all duration-300 shadow-[0_6px_20px_rgba(0,0,0,0.15)] hover:shadow-[0_8px_25px_rgba(79,70,229,0.35)]
                                               focus:outline-none focus:ring-4 focus:ring-indigo-500/30 active:scale-[0.98]
                                               disabled:opacity-50 disabled:hover:bg-slate-900 disabled:shadow-none disabled:active:scale-100 disabled:cursor-not-allowed">
                                            Update Password
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

</x-app-layout>
