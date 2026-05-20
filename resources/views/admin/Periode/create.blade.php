<x-app-layout :title="$title" :subtitle="$subtitle">

    <div class="max-w-lg">
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Generate Periode Baru</h2>
            </div>

            <form method="POST" action="{{ route('admin.periode.store') }}" class="px-6 py-5 space-y-5">
                @csrf

                {{-- Info --}}
                <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-800">
                    <strong>ℹ Informasi:</strong> Saat periode di-generate, sistem otomatis membuat laporan
                    (tabungan & deposito) untuk semua cabang aktif. Saldo awal setiap cabang diambil dari
                    saldo akhir periode sebelumnya.
                </div>

                {{-- Tanggal --}}
                <div>
                    <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Periode <span class="text-red-500">*</span>
                        <span class="text-gray-400 font-normal">(harus hari Jumat)</span>
                    </label>
                    <input type="date" id="tanggal_akhir" name="tanggal_akhir"
                        value="{{ old('tanggal_akhir', $nextFriday) }}"
                        class="w-full px-3 py-2 border rounded-lg text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                           {{ $errors->has('tanggal_akhir') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('tanggal_akhir')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-400">
                        Saran otomatis: Jumat berikutnya
                        ({{ \Carbon\Carbon::parse($nextFriday)->locale('id')->isoFormat('dddd, D MMMM Y') }})
                    </p>
                </div>

                {{-- Preview nama periode --}}
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg" id="preview-periode">
                    <p class="text-xs text-gray-500 mb-0.5">Preview nama periode:</p>
                    <p class="text-sm font-semibold text-gray-900" id="preview-nama">
                        Periode {{ \Carbon\Carbon::parse($nextFriday)->locale('id')->isoFormat('D MMMM Y') }}
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-1">
                    <button type="submit"
                        class="px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        Generate Periode
                    </button>
                    <a href="{{ route('admin.periode.index') }}"
                        class="px-5 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Preview nama periode saat tanggal diubah
        document.getElementById('tanggal_akhir').addEventListener('change', function() {
            const val = this.value;
            if (!val) return;
            const d = new Date(val + 'T00:00:00');
            const opts = {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            };
            const formatted = d.toLocaleDateString('id-ID', opts);
            document.getElementById('preview-nama').textContent = 'Periode ' + formatted;
        });
    </script>

</x-app-layout>
