<x-app-layout :title="$title" :subtitle="$subtitle">

    <div class="max-w-2xl">

        {{-- Hasil Import --}}
        @if (session('import_success'))
            <div class="mb-5 bg-emerald-50 border border-emerald-200 rounded-lg overflow-hidden">
                <div class="px-5 py-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-emerald-800">Import Berhasil</p>
                        <div class="flex gap-4 mt-1">
                            <span class="text-sm text-emerald-700">
                                <strong>{{ session('import_created') }}</strong> laporan dibuat
                            </span>
                            <span class="text-sm text-emerald-600">
                                <strong>{{ session('import_skipped') }}</strong> dilewati (sudah ada)
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Log Detail --}}
                @if (session('import_log'))
                    <div class="border-t border-emerald-200 px-5 py-3">
                        <p class="text-xs font-semibold text-emerald-700 mb-2">Log Proses:</p>
                        <div class="space-y-0.5 max-h-40 overflow-y-auto">
                            @foreach (session('import_log') as $log)
                                <p class="text-xs text-emerald-700 font-mono">{{ $log }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Errors / Warnings --}}
                @if (session('import_errors'))
                    <div class="border-t border-orange-200 bg-orange-50 px-5 py-3">
                        <p class="text-xs font-semibold text-orange-700 mb-2">
                            ⚠ {{ count(session('import_errors')) }} Peringatan:
                        </p>
                        <div class="space-y-0.5 max-h-32 overflow-y-auto">
                            @foreach (session('import_errors') as $err)
                                <p class="text-xs text-orange-700 font-mono">{{ $err }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Info Box --}}
        <div class="mb-5 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-xs font-semibold text-blue-800 mb-2">ℹ Petunjuk Import Data Historis</p>
            <ul class="text-xs text-blue-700 space-y-1">
                <li>• Upload file Excel laporan stok yang sudah difinalisasi oleh akunting pusat</li>
                <li>• Format file: <strong>.xlsx atau .xls</strong>, sesuai format file contoh yang sudah ada</li>
                <li>• File harus memiliki <strong>2 sheet</strong>: sheet pertama = Tabungan, sheet kedua = Deposito
                </li>
                <li>• Baris header harus mengandung kode cabang numerik (101, 102, dst.)</li>
                <li>• Data laporan yang <strong>sudah ada</strong> di database akan dilewati (tidak ditimpa)</li>
                <li>• Pastikan semua kode cabang di file Excel sudah terdaftar di Master Cabang</li>
                <li>• Laporan hasil import akan berstatus <strong>Terverifikasi</strong> secara otomatis</li>
                <li>• Proses import mungkin memakan waktu beberapa menit untuk file besar</li>
            </ul>
        </div>

        {{-- Form Upload --}}
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Upload File Excel</h2>
            </div>

            <form method="POST" action="{{ route('admin.import.store') }}" enctype="multipart/form-data"
                class="px-6 py-5 space-y-5">
                @csrf

                {{-- File Input --}}
                <div>
                    <label for="file_excel" class="block text-sm font-medium text-gray-700 mb-1">
                        File Excel <span class="text-red-500">*</span>
                        <span class="text-gray-400 font-normal text-xs">(.xlsx atau .xls, maks 10MB)</span>
                    </label>

                    <div x-data="{ filename: '', dragging: false }" class="relative">
                        <div @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                            @drop.prevent="
                                dragging = false;
                                const f = $event.dataTransfer.files[0];
                                if(f) { filename = f.name; $refs.fileInput.files = $event.dataTransfer.files; }
                             "
                            :class="dragging ? 'border-indigo-400 bg-indigo-50' : 'border-gray-300 hover:border-gray-400'"
                            class="border-2 border-dashed rounded-lg p-8 text-center transition-colors cursor-pointer"
                            @click="$refs.fileInput.click()">

                            <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>

                            <p x-show="!filename" class="text-sm text-gray-500">
                                Klik untuk pilih file atau drag & drop ke sini
                            </p>
                            <p x-show="filename" class="text-sm font-semibold text-indigo-700" x-text="filename"></p>
                            <p class="text-xs text-gray-400 mt-1">.xlsx atau .xls · Maksimal 10MB</p>
                        </div>

                        <input type="file" id="file_excel" name="file_excel" accept=".xlsx,.xls" x-ref="fileInput"
                            class="hidden" @change="filename = $event.target.files[0]?.name ?? ''">
                    </div>

                    @error('file_excel')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Warning --}}
                <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800">
                    <strong>⚠ Perhatian:</strong> Proses import tidak bisa dibatalkan setelah dijalankan.
                    Pastikan file Excel yang diupload sudah benar sebelum melanjutkan.
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3 pt-1">
                    <button type="submit" x-data="{ loading: false }" @click="loading = true" :disabled="loading"
                        class="flex items-center gap-2 px-5 py-2 bg-indigo-600 text-white text-sm font-medium
                               rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-60">
                        <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        <span x-text="loading ? 'Memproses...' : 'Mulai Import'"></span>
                    </button>
                    <p class="text-xs text-gray-400">Proses mungkin memakan 1-2 menit</p>
                </div>
            </form>
        </div>

        {{-- Riwayat Import dari Audit Log --}}
        <div class="mt-5 bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-900">Riwayat Import</h3>
            </div>
            @php
                $importLogs = \Spatie\Activitylog\Models\Activity::where('log_name', 'import')
                    ->with('causer')
                    ->latest()
                    ->take(10)
                    ->get();
            @endphp
            @if ($importLogs->isEmpty())
                <p class="px-5 py-6 text-xs text-gray-400 text-center">Belum ada riwayat import.</p>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Waktu</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Deskripsi</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($importLogs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2.5 text-xs text-gray-500 font-mono whitespace-nowrap">
                                    {{ $log->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-2.5 text-xs text-gray-700">{{ $log->description }}</td>
                                <td class="px-4 py-2.5 text-xs text-gray-600">
                                    {{ $log->causer?->name ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>

</x-app-layout>
