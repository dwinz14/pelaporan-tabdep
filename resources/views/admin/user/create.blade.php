<x-app-layout :title="$title" :subtitle="$subtitle">

    <div class="max-w-xl">
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Form Tambah User</h2>
            </div>

            <form method="POST" action="{{ route('admin.user.store') }}" class="px-6 py-5 space-y-4"
                x-data="{
                    role: '{{ old('role', '') }}',
                    pwd: '',
                    confirmPwd: '',
                    get lenOk() { return this.pwd.length >= 6 },
                    get upperOk() { return /[A-Z]/.test(this.pwd) },
                    get numOk() { return /[0-9]/.test(this.pwd) },
                    get specialOk() { return /[^A-Za-z0-9]/.test(this.pwd) },
                    get matchOk() { return this.confirmPwd.length > 0 && this.pwd === this.confirmPwd },
                }">
                @csrf

                {{-- NIK --}}
                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">
                        NIK <span class="text-red-500">*</span>
                        <span class="text-gray-400 font-normal text-xs">(Format: AP123456789)</span>
                    </label>
                    <input type="text" id="nik" name="nik" value="{{ old('nik') }}"
                        placeholder="AP123456789" maxlength="11" oninput="maskNik(this)"
                        class="w-full px-3 py-2 border rounded-lg text-sm font-mono
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                           {{ $errors->has('nik') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('nik')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        placeholder="Nama lengkap karyawan"
                        class="w-full px-3 py-2 border rounded-lg text-sm
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
                        class="w-full px-3 py-2 border rounded-lg text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                           {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password" name="password" x-model="pwd"
                            class="w-full px-3 py-2 border rounded-lg text-sm
                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                   {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            x-model="confirmPwd"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <p x-show="confirmPwd.length > 0" x-cloak class="mt-1 text-xs"
                            :class="matchOk ? 'text-emerald-600' : 'text-red-500'">
                            <span x-text="matchOk ? '✓ Cocok' : '✕ Tidak cocok'"></span>
                        </p>
                    </div>
                </div>

                {{-- Checklist Kekuatan Password --}}
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                    <p class="text-xs font-medium text-gray-600 mb-2">Password harus memenuhi:</p>
                    <div class="grid grid-cols-2 gap-1.5">
                        @foreach ([['get' => 'lenOk', 'label' => 'Minimal 6 karakter'], ['get' => 'upperOk', 'label' => 'Huruf kapital (A-Z)'], ['get' => 'numOk', 'label' => 'Angka (0-9)'], ['get' => 'specialOk', 'label' => 'Karakter spesial']] as $item)
                            <p class="text-xs flex items-center gap-1.5"
                                :class="{{ $item['get'] }} ? 'text-emerald-600' : 'text-gray-400'">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"
                                    x-show="{{ $item['get'] }}" x-cloak>
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" x-show="!{{ $item['get'] }}">
                                    <circle cx="12" cy="12" r="9" stroke-width="1.5" />
                                </svg>
                                {{ $item['label'] }}
                            </p>
                        @endforeach
                    </div>
                </div>

                {{-- Role --}}
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select id="role" name="role" x-model="role"
                        class="w-full px-3 py-2 border rounded-lg text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                           {{ $errors->has('role') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        <option value="">— Pilih Role —</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r->value }}" {{ old('role') === $r->value ? 'selected' : '' }}>
                                {{ $r->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cabang (hanya muncul jika role = pic_cabang) --}}
                <div x-show="role === 'pic_cabang'" x-transition>
                    <label for="id_cabang" class="block text-sm font-medium text-gray-700 mb-1">
                        Cabang <span class="text-red-500">*</span>
                    </label>
                    <select id="id_cabang" name="id_cabang"
                        class="w-full px-3 py-2 border rounded-lg text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                           {{ $errors->has('id_cabang') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        <option value="">— Pilih Cabang —</option>
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

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        Simpan User
                    </button>
                    <a href="{{ route('admin.user.index') }}"
                        class="px-5 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
