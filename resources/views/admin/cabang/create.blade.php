<x-app-layout :title="$title" :subtitle="$subtitle">

    <div class="max-w-xl">
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Form Tambah Cabang</h2>
            </div>

            <form method="POST" action="{{ route('admin.cabang.store') }}" class="px-6 py-5 space-y-4">
                @csrf

                {{-- Kode Cabang --}}
                <div>
                    <label for="kode_cabang" class="block text-sm font-medium text-gray-700 mb-1">
                        Kode Cabang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="kode_cabang" name="kode_cabang" value="{{ old('kode_cabang') }}"
                        placeholder="Contoh: 112"
                        class="w-full px-3 py-2 border rounded-lg text-sm font-mono
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                               {{ $errors->has('kode_cabang') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('kode_cabang')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Cabang --}}
                <div>
                    <label for="nama_cabang" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Cabang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_cabang" name="nama_cabang" value="{{ old('nama_cabang') }}"
                        placeholder="Contoh: Kantor Cabang Pare"
                        class="w-full px-3 py-2 border rounded-lg text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                               {{ $errors->has('nama_cabang') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('nama_cabang')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea id="alamat" name="alamat" rows="3" placeholder="Alamat lengkap kantor cabang..."
                        class="w-full px-3 py-2 border rounded-lg text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                               {{ $errors->has('alamat') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        Simpan Cabang
                    </button>
                    <a href="{{ route('admin.cabang.index') }}"
                        class="px-5 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
