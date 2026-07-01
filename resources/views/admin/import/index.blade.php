<x-app-layout :title="$title" :subtitle="$subtitle">

    <div class="max-w-5xl mx-auto pb-8 space-y-4">

        {{-- ═══ PAGE HEADER ═══ --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div
                    class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0 shadow-inner">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Import Data Massal</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Unggah data pelaporan sekaligus menggunakan
                        format Excel.</p>
                </div>
            </div>
        </div>

        {{-- ═══ FLASH MESSAGES ═══ --}}
        <div class="space-y-3">
            @if (session('import_success'))
                @php $r = session('import_result'); @endphp
                <div
                    class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start gap-3 shadow-sm relative overflow-hidden">
                    <div
                        class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-emerald-100 to-transparent opacity-50">
                    </div>
                    <div
                        class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0 relative z-10">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-bold text-emerald-900">Import Data Berhasil Diselesaikan!</p>
                        <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 bg-white border border-emerald-200 rounded-lg text-[10px] font-semibold text-emerald-700">
                                <span class="text-emerald-500 font-black">{{ $r['created_laporan'] }}</span> Laporan
                                Baru
                            </span>
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 bg-white border border-emerald-200 rounded-lg text-[10px] font-semibold text-emerald-700">
                                <span class="text-emerald-500 font-black">{{ $r['created_periode'] }}</span> Periode
                                Baru
                            </span>
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 border border-emerald-200 rounded-lg text-[10px] font-semibold text-emerald-800">
                                <span class="text-emerald-600 font-black">{{ $r['skipped'] }}</span> Dilewati (Duplikat)
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('import_error'))
                <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-2 shadow-sm">
                    <svg class="w-4 h-4 text-rose-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-xs font-bold text-rose-900">Gagal Mengimport</p>
                        <p class="text-xs text-rose-700 mt-0.5">{{ session('import_error') }}</p>
                    </div>
                </div>
            @endif

            @if (session('import_info'))
                <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl flex items-start gap-2 shadow-sm">
                    <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-xs font-medium text-blue-800">{{ session('import_info') }}</p>
                </div>
            @endif
        </div>


        {{-- ═══ STEP 1: DOWNLOAD TEMPLATE ═══ --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden relative">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500"></div>

            <div class="px-5 py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div
                        class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0 text-indigo-700 font-black text-sm border-2 border-white shadow-sm ring-1 ring-indigo-200">
                        1
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Unduh Template Resmi</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Gunakan template Excel ini agar data terbaca sempurna
                            oleh sistem. Jangan merubah struktur *header* tabel.</p>

                        <div class="flex flex-wrap gap-x-3 gap-y-1 mt-2 text-[10px] font-medium text-slate-400">
                            <span class="flex items-center gap-1"><svg class="w-3 h-3 text-emerald-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg> Dropdown Referensi Cabang</span>
                            <span class="flex items-center gap-1"><svg class="w-3 h-3 text-emerald-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg> Petunjuk Pengisian Lengkap</span>
                            <span class="flex items-center gap-1"><svg class="w-3 h-3 text-emerald-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg> Format Sel Tervalidasi</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.import.template') }}"
                    class="w-full md:w-auto flex items-center justify-center gap-1.5 px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-lg hover:bg-indigo-600 transition-colors shadow-sm shadow-slate-200 flex-shrink-0 group">
                    <svg class="w-4 h-4 group-hover:-translate-y-0.5 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Unduh Template .xlsx
                </a>
            </div>
        </div>


        {{-- ═══ STEP 2: UPLOAD ZONE (Bila tidak ada preview) ═══ --}}
        @if (!$preview)
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden relative">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-slate-300"></div>

                <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                    <div
                        class="w-8 h-8 bg-white rounded-full flex items-center justify-center flex-shrink-0 text-slate-400 font-black text-sm border-2 border-white shadow-sm ring-1 ring-slate-200">
                        2
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Unggah File Data</h3>
                        <p class="text-[10px] font-medium text-slate-500 mt-0.5">Sistem akan melakukan pratinjau
                            (preview)
                            terlebih dahulu sebelum data benar-benar disimpan.</p>
                    </div>
                </div>

                <div class="p-5">
                    <form method="POST" action="{{ route('admin.import.validate') }}" enctype="multipart/form-data"
                        x-data="{ filename: '', loading: false, dragging: false }" @submit="loading = true">
                        @csrf

                        {{-- Interactive Dropzone --}}
                        <div @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                            @drop.prevent="dragging = false; const f = $event.dataTransfer.files[0]; if (f) { filename = f.name; $refs.fileInput.files = $event.dataTransfer.files; }"
                            @click="$refs.fileInput.click()"
                            :class="dragging ? 'border-indigo-500 bg-indigo-50 scale-[0.99]' :
                                'border-slate-300 hover:border-indigo-400 bg-slate-50/50 hover:bg-slate-50'"
                            class="w-full border-2 border-dashed rounded-xl p-6 text-center transition-all duration-200 cursor-pointer group relative">

                            <div class="absolute inset-0 bg-white/40 pointer-events-none rounded-xl" x-show="dragging"
                                x-transition></div>

                            <div x-show="!filename" class="relative z-10 flex flex-col items-center">
                                <div
                                    class="w-12 h-12 bg-white rounded-full shadow-sm border border-slate-200 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                </div>
                                <p class="text-xs font-bold text-slate-700">Tarik & Letakkan file Excel di sini</p>
                                <p class="text-[10px] font-medium text-slate-500 mt-1">atau klik untuk menelusuri
                                    komputer
                                    Anda</p>
                                <div class="mt-3 flex gap-1.5">
                                    <span
                                        class="px-1.5 py-0.5 bg-white border border-slate-200 rounded text-[9px] font-bold text-slate-400 uppercase tracking-wider">.XLSX</span>
                                    <span
                                        class="px-1.5 py-0.5 bg-white border border-slate-200 rounded text-[9px] font-bold text-slate-400 uppercase tracking-wider">.XLS</span>
                                </div>
                            </div>

                            <div x-show="filename" x-cloak class="relative z-10 flex flex-col items-center">
                                <div
                                    class="w-12 h-12 bg-emerald-50 rounded-full shadow-sm border border-emerald-100 flex items-center justify-center mb-3 text-emerald-500">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <p class="text-[10px] font-medium text-slate-500 uppercase tracking-wider mb-1">File
                                    Siap
                                    Divalidasi:</p>
                                <p class="text-sm font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-lg border border-indigo-100"
                                    x-text="filename"></p>
                                <p
                                    class="text-[10px] text-slate-400 mt-3 underline decoration-slate-300 underline-offset-4">
                                    Klik untuk mengganti file</p>
                            </div>
                        </div>

                        <input type="file" name="file_excel" accept=".xlsx,.xls" x-ref="fileInput"
                            class="hidden" @change="filename = $event.target.files[0]?.name ?? ''">

                        @error('file_excel')
                            <p
                                class="mt-2 text-xs font-semibold text-rose-600 bg-rose-50 p-2.5 rounded-lg border border-rose-200 text-center">
                                {{ $message }}</p>
                        @enderror

                        <button type="submit" :disabled="!filename || loading"
                            class="mt-4 w-full sm:w-auto sm:min-w-[200px] mx-auto flex items-center justify-center gap-2 px-5 py-2.5
                               bg-indigo-600 text-white text-xs font-bold rounded-lg shadow shadow-indigo-200
                               hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"
                                x-cloak>
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                            <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span
                                x-text="loading ? 'Memvalidasi Dokumen...' : 'Validasi & Tampilkan Pratinjau'"></span>
                        </button>
                    </form>
                </div>
            </div>
        @endif


        {{-- ═══ STEP 3: PREVIEW & KONFIRMASI ═══ --}}
        @if ($preview)
            <div x-data="{ confirming: false }"
                class="bg-white rounded-xl border border-indigo-300 shadow-xl shadow-indigo-900/5 overflow-hidden relative">

                {{-- Preview Header --}}
                <div
                    class="px-5 py-3 border-b border-indigo-100 bg-indigo-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center flex-shrink-0 text-white font-black text-sm shadow-md shadow-indigo-200">
                            3
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-indigo-900">Hasil Pratinjau Dokumen</h3>
                            <p class="text-xs font-medium text-indigo-700/70 mt-0.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ $preview['original_name'] }}
                            </p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.import.cancel') }}">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto px-3 py-1.5 bg-white border border-slate-200 text-slate-600 text-[10px] font-bold rounded-lg hover:bg-slate-50 hover:text-rose-600 transition-colors shadow-sm">
                            ✕ Batalkan & Ganti File
                        </button>
                    </form>
                </div>

                {{-- Summary Dashboard --}}
                <div
                    class="grid grid-cols-2 md:grid-cols-4 divide-x divide-y md:divide-y-0 divide-slate-100 border-b border-slate-100 bg-white">
                    @foreach ([['label' => 'Total Baris Data', 'value' => $preview['summary']['total'], 'color' => 'slate'], ['label' => 'Valid & Siap Masuk', 'value' => $preview['summary']['ok'], 'color' => 'emerald'], ['label' => 'Dilewati (Duplikat)', 'value' => $preview['summary']['skip'], 'color' => 'amber'], ['label' => 'Baris Error', 'value' => $preview['summary']['error'], 'color' => 'rose']] as $card)
                        @php
                            $isErrorCard = $card['color'] === 'rose' && $card['value'] > 0;
                        @endphp
                        <div
                            class="p-4 flex flex-col items-center justify-center text-center {{ $isErrorCard ? 'bg-rose-50/50' : '' }}">
                            <p
                                class="text-2xl font-black font-mono mb-0.5
                                {{ match ($card['color']) {
                                    'emerald' => 'text-emerald-500',
                                    'amber' => 'text-amber-500',
                                    'rose' => $card['value'] > 0 ? 'text-rose-600 animate-pulse' : 'text-slate-300',
                                    default => 'text-slate-700',
                                } }}">
                                {{ $card['value'] }}
                            </p>
                            <p
                                class="text-[10px] font-bold uppercase tracking-wider {{ $isErrorCard ? 'text-rose-800' : 'text-slate-400' }}">
                                {{ $card['label'] }}
                            </p>
                        </div>
                    @endforeach
                </div>

                {{-- Detail Table Data --}}
                <div class="max-h-72 overflow-y-auto bg-slate-50/30">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead class="sticky top-0 z-20">
                            <tr class="absolute inset-0 bg-white shadow-sm -z-10"></tr>
                            <tr>
                                <th
                                    class="px-4 py-2.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider w-12">
                                    Baris</th>
                                <th
                                    class="px-4 py-2.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider w-32">
                                    Status</th>
                                <th
                                    class="px-4 py-2.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider w-24">
                                    Tanggal</th>
                                <th
                                    class="px-4 py-2.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider w-40">
                                    Cabang</th>
                                <th
                                    class="px-4 py-2.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider w-24">
                                    Jenis</th>
                                <th
                                    class="px-4 py-2.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right w-28">
                                    Saldo Akhir</th>
                                <th class="px-4 py-2.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                    Keterangan / Error</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($preview['rows'] as $row)
                                <tr
                                    class="hover:bg-white transition-colors
                                    {{ match ($row['status']) {
                                        'ok' => '',
                                        'skip' => 'bg-amber-50/30',
                                        'error' => 'bg-rose-50/60',
                                        default => '',
                                    } }}">
                                    <td class="px-4 py-2 font-mono text-[10px] font-medium text-slate-400">
                                        #{{ $row['line'] }}</td>

                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold border
                                            {{ match ($row['status']) {
                                                'ok' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'skip' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'error' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                default => 'bg-slate-50 text-slate-600 border-slate-200',
                                            } }}">
                                            @if ($row['status'] == 'ok')
                                                <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Valid
                                            @elseif($row['status'] == 'skip')
                                                <span class="w-1 h-1 rounded-full bg-amber-500"></span> Skip
                                            @elseif($row['status'] == 'error')
                                                <span class="w-1 h-1 rounded-full bg-rose-500"></span> Error
                                            @else
                                                {{ $row['status'] }}
                                            @endif
                                        </span>
                                    </td>

                                    <td class="px-4 py-2 font-mono text-slate-700 whitespace-nowrap text-[10px]">
                                        {{ $row['tanggal_display'] }}</td>

                                    <td class="px-4 py-2">
                                        <div class="flex items-center gap-1.5">
                                            <span
                                                class="font-mono text-[10px] font-bold text-slate-500 bg-slate-100 px-1 rounded border border-slate-200">{{ $row['kode_cabang'] }}</span>
                                            @if ($row['cabang_nama'])
                                                <span
                                                    class="text-slate-700 font-medium truncate max-w-[100px] text-[10px]"
                                                    title="{{ $row['cabang_nama'] }}">{{ $row['cabang_nama'] }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-4 py-2 text-slate-700 font-medium whitespace-nowrap text-[10px]">
                                        {{ $row['jenis_label'] }}</td>

                                    <td class="px-4 py-2 text-right">
                                        <span
                                            class="font-mono font-bold text-[10px] {{ $row['saldo_akhir'] !== null ? 'text-slate-800' : 'text-slate-300' }}">
                                            {{ $row['saldo_akhir'] !== null ? number_format((int) $row['saldo_akhir'], 0, ',', '.') : '—' }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-2">
                                        <span
                                            class="text-[10px] font-medium {{ $row['status'] === 'error' ? 'text-rose-600 font-bold' : 'text-slate-500' }}">
                                            {{ $row['message'] ?? '—' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Action Footer / Final Confirmation --}}
                @php $hasError = $preview['summary']['error'] > 0; @endphp
                <div
                    class="px-5 py-4 border-t border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4 {{ $hasError ? 'bg-rose-50/80' : 'bg-white' }}">

                    <div class="text-xs">
                        @if ($hasError)
                            <div class="flex items-start gap-2">
                                <div
                                    class="w-7 h-7 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-rose-900">Terdapat <span
                                            class="text-rose-600 text-sm">{{ $preview['summary']['error'] }}</span>
                                        baris error!</p>
                                    <p class="text-rose-700 font-medium">Proses import <strong>dikunci</strong>.
                                        Silakan perbaiki file Excel Anda dan unggah ulang.</p>
                                </div>
                            </div>
                        @elseif ($preview['summary']['ok'] === 0)
                            <div
                                class="flex items-center gap-1.5 text-amber-700 font-medium bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Tidak ada data valid yang siap dimasukkan.
                            </div>
                        @else
                            <p class="font-bold text-slate-800">Semua data tervalidasi.</p>
                            <p class="text-slate-500 font-medium">Klik eksekusi untuk memasukkan data secara permanen.
                            </p>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('admin.import.confirm') }}" @submit="confirming = true"
                        class="w-full md:w-auto">
                        @csrf
                        <button type="submit"
                            :disabled="confirming || {{ $hasError || $preview['summary']['ok'] === 0 ? 'true' : 'false' }}"
                            class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-2.5
                               {{ $hasError ? 'bg-slate-200 text-slate-400' : 'bg-emerald-600 text-white hover:bg-emerald-700 shadow shadow-emerald-200 focus:ring-4 focus:ring-emerald-100' }}
                               text-xs font-bold rounded-lg transition-all disabled:cursor-not-allowed">

                            <svg x-show="confirming" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"
                                x-cloak>
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>

                            <svg x-show="!confirming" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>

                            <span
                                x-text="confirming ? 'Menyimpan Data...' : 'Eksekusi {{ $preview['summary']['ok'] }} Data'"></span>
                        </button>
                    </form>
                </div>
            </div>
        @endif


        {{-- ═══ RIWAYAT IMPORT LOGS ═══ --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Aktivitas Import Terakhir
                </h3>
            </div>

            @php
                $importLogs = \Spatie\Activitylog\Models\Activity::where('log_name', 'import')
                    ->with('causer')
                    ->latest()
                    ->take(10)
                    ->get();
            @endphp

            @if ($importLogs->isEmpty())
                <div class="py-8 flex flex-col items-center justify-center">
                    <div
                        class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center border border-slate-100 mb-2">
                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-slate-700">Belum Ada Riwayat Import</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Data riwayat akan muncul di sini setelah Anda
                        melakukan
                        import.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($importLogs as $log)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-5 py-3 whitespace-nowrap w-36">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-xs font-bold text-slate-700">{{ $log->created_at->format('d M Y') }}</span>
                                            <span
                                                class="text-[10px] font-mono text-slate-400 mt-0.5">{{ $log->created_at->format('H:i:s') }}
                                                WIB</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="text-xs font-medium text-slate-800">{{ $log->description }}</p>
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap text-right w-36">
                                        <div class="inline-flex items-center gap-1.5">
                                            <div
                                                class="w-5 h-5 rounded-full bg-slate-100 text-slate-500 font-bold text-[9px] flex items-center justify-center">
                                                {{ strtoupper(substr($log->causer?->name ?? 'A', 0, 1)) }}
                                            </div>
                                            <span
                                                class="text-xs font-semibold text-slate-600">{{ $log->causer?->name ?? 'System' }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>

</x-app-layout>
