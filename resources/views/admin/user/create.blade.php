<x-app-layout :title="$title" :subtitle="$subtitle">

    <div class="max-w-xl">
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Form Tambah User</h2>
            </div>

            <form method="POST" action="{{ route('admin.user.store') }}" class="px-6 py-5 space-y-4"
                x-data="{ role: '{{ old('role', '') }}' }">
                @csrf

                {{-- NIK --}}
                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">
                        NIK <span class="text-red-500">*</span>
                        <span class="text-gray-400 font-normal text-xs">(Format: AP123456789)</span>
                    </label>
                    <input type="text" id="nik" name="nik" value="{{ old('nik') }}"
                        placeholder="AP123456789" maxlength="11"
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
                        <input type="password" id="password" name="password"
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
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
