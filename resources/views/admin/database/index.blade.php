<x-app-layout :title="$title" :subtitle="$subtitle">
    <div x-data="{
        restoreModalOpen: {{ session('open_restore_modal') ? 'true' : 'false' }},
        confirmText: '',
        get canConfirm() { return this.confirmText === 'RESTORE' }
    }">

        {{-- ═══ FLASH MESSAGES ═══ --}}
        @if (session('db_success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 8000)"
                class="mb-5 p-4 bg-emerald-50 border border-emerald-300 rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <p class="text-sm text-emerald-800">{{ session('db_success') }}</p>
            </div>
        @endif

        @if (session('db_error'))
            <div class="mb-5 p-4 bg-red-50 border border-red-300 rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <p class="text-sm text-red-800">{{ session('db_error') }}</p>
            </div>
        @endif

        {{-- Peringatan jika proc_open tidak tersedia --}}
        @if (!$canRunProcess)
            <div class="mb-5 p-4 bg-amber-50 border border-amber-300 rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-amber-800">Fitur Backup/Restore Tidak Tersedia</p>
                    <p class="text-xs text-amber-700 mt-0.5">Fungsi <code>proc_open</code> dinonaktifkan di konfigurasi
                        PHP server ini. Hubungi administrator server untuk mengaktifkannya.</p>
                </div>
            </div>
        @endif

        {{-- ═══ GRID ATAS: STATUS + INFO DATABASE ═══ --}}
        <div class="grid md:grid-cols-2 gap-5 mb-5">

            {{-- Koneksi Database --}}
            <div
                class="bg-white rounded-xl border overflow-hidden
                    {{ $connection['connected'] ? 'border-emerald-200' : 'border-red-200' }}">
                <div
                    class="px-5 py-3.5 border-b {{ $connection['connected'] ? 'bg-emerald-50 border-emerald-100' : 'bg-red-50 border-red-100' }} flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span
                            class="w-2 h-2 rounded-full {{ $connection['connected'] ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></span>
                        <p
                            class="text-sm font-semibold {{ $connection['connected'] ? 'text-emerald-800' : 'text-red-800' }}">
                            {{ $connection['connected'] ? 'Terhubung' : 'Koneksi Gagal' }}
                        </p>
                    </div>
                    <span
                        class="text-xs {{ $connection['connected'] ? 'text-emerald-600' : 'text-red-600' }} font-mono uppercase">
                        {{ config('database.default') }}
                    </span>
                </div>
                <div class="px-5 py-4 space-y-2.5">
                    @if ($connection['connected'])
                        @foreach ([['label' => 'Database', 'value' => $connection['database']], ['label' => 'Host', 'value' => $connection['host'] . ':' . $connection['port']]] as $item)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 text-xs">{{ $item['label'] }}</span>
                                <span class="font-mono text-xs font-semibold text-gray-800">{{ $item['value'] }}</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-xs text-red-700 font-mono">{{ $connection['error'] }}</p>
                    @endif

                    {{-- Availabilitas Tool --}}
                    <div class="pt-2 border-t border-gray-100 space-y-1.5">
                        @foreach ([['label' => 'mysqldump', 'ok' => $mysqldumpFound], ['label' => 'mysql', 'ok' => $mysqlFound]] as $tool)
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500 font-mono">{{ $tool['label'] }}</span>
                                @if ($tool['ok'])
                                    <span
                                        class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-medium">✓
                                        Tersedia</span>
                                @else
                                    <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-medium">✕
                                        Tidak Ditemukan</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Info Database --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3.5 border-b border-gray-100 bg-gray-50">
                    <p class="text-sm font-semibold text-gray-800">Informasi Database</p>
                </div>
                @if ($dbInfo['success'])
                    <div class="px-5 py-4 space-y-2.5">
                        @foreach ([['label' => 'Versi MySQL', 'value' => $dbInfo['version']], ['label' => 'Ukuran DB', 'value' => $dbInfo['size_human']], ['label' => 'Jumlah Tabel', 'value' => $dbInfo['tables'] . ' tabel'], ['label' => 'Character Set', 'value' => $dbInfo['charset']], ['label' => 'Collation', 'value' => $dbInfo['collation']]] as $item)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-xs text-gray-500">{{ $item['label'] }}</span>
                                <span class="font-mono text-xs font-semibold text-gray-800">{{ $item['value'] }}</span>
                            </div>
                        @endforeach

                        {{-- Disk Usage --}}
                        <div class="pt-2 border-t border-gray-100">
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="text-gray-500">Ruang Disk Tersedia</span>
                                <span
                                    class="font-mono font-semibold {{ $diskCheck['sufficient'] ? 'text-emerald-700' : 'text-red-600' }}">
                                    {{ $diskInfo['free_human'] }} bebas
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full transition-all
                                        {{ $diskInfo['used_percent'] > 85 ? 'bg-red-500' : ($diskInfo['used_percent'] > 70 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                    style="width: {{ min($diskInfo['used_percent'], 100) }}%"></div>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">{{ $diskInfo['used_percent'] }}% digunakan dari
                                {{ $diskInfo['total_human'] }}</p>
                        </div>
                    </div>
                @else
                    <div class="px-5 py-4">
                        <p class="text-xs text-red-600 font-mono">{{ $dbInfo['error'] }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ═══ GRID BAWAH: BACKUP + RESTORE ═══ --}}
        <div class="grid md:grid-cols-5 gap-5 mb-5">

            {{-- Kolom Backup (3/5) --}}
            <div class="md:col-span-3 space-y-4">

                {{-- Create Backup --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">Buat Backup</h3>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Export seluruh database ke file .sql
                            </p>
                        </div>
                        @if (!$diskCheck['sufficient'])
                            <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-lg font-medium">
                                ⚠ Disk hampir penuh
                            </span>
                        @endif
                    </div>
                    <div class="px-5 py-4">
                        @if (!$diskCheck['sufficient'])
                            <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-lg text-xs text-red-700">
                                Ruang disk tidak cukup. Tersedia: <strong>{{ $diskCheck['free'] }}</strong>,
                                dibutuhkan: <strong>{{ $diskCheck['required'] }}</strong>.
                                Hapus backup lama terlebih dahulu.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.database.backup') }}" x-data="{ loading: false }"
                            @submit="loading = true">
                            @csrf
                            <button type="submit"
                                :disabled="loading ||
                                    {{ !$canRunProcess || !$mysqldumpFound || !$diskCheck['sufficient'] ? 'true' : 'false' }}"
                                class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold
                                   rounded-lg hover:bg-indigo-700 transition-colors
                                   disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"
                                    x-cloak>
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                </svg>
                                <span x-text="loading ? 'Membuat backup...' : 'Buat Backup Sekarang'"></span>
                            </button>
                            <p class="text-xs text-gray-400 mt-2">
                                Proses mungkin memakan waktu beberapa menit tergantung ukuran database.
                            </p>
                        </form>
                    </div>
                </div>

                {{-- Daftar Backup --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-900">Daftar Backup</h3>
                        <span class="text-xs text-gray-400">{{ count($backups) }} file</span>
                    </div>

                    @if (empty($backups))
                        <div class="px-5 py-10 text-center">
                            <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            <p class="text-sm text-gray-400">Belum ada file backup.</p>
                            <p class="text-xs text-gray-400 mt-1">Buat backup pertama dengan tombol di atas.</p>
                        </div>
                    @else
                        <div class="divide-y divide-gray-50">
                            @foreach ($backups as $backup)
                                <div
                                    class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition-colors group">
                                    <div
                                        class="w-9 h-9 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-mono font-semibold text-gray-800 truncate">
                                            {{ $backup['filename'] }}
                                        </p>
                                        <div class="flex items-center gap-3 mt-0.5">
                                            <span class="text-xs text-gray-400">{{ $backup['size_human'] }}</span>
                                            <span class="text-xs text-gray-400">
                                                {{ $backup['created_at']->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                                            </span>
                                            <span class="text-xs text-gray-300">
                                                {{ $backup['created_at']->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                                        {{-- Download --}}
                                        <a href="{{ route('admin.database.download', $backup['filename']) }}"
                                            class="flex items-center gap-1 text-xs text-indigo-600 hover:text-indigo-800 font-medium px-2 py-1 rounded hover:bg-indigo-50 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Unduh
                                        </a>

                                        {{-- Delete --}}
                                        <form method="POST"
                                            action="{{ route('admin.database.delete-backup', $backup['filename']) }}"
                                            onsubmit="return confirm('Hapus backup {{ $backup['filename'] }}? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="flex items-center gap-1 text-xs text-red-500 hover:text-red-700 font-medium px-2 py-1 rounded hover:bg-red-50 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Kolom Restore (2/5) --}}
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl border border-red-200 overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-red-100 bg-red-50">
                        <h3 class="text-sm font-semibold text-red-900">Restore Database</h3>
                        <p class="text-xs text-red-600 mt-0.5">Operasi destruktif — hati-hati</p>
                    </div>

                    <div class="px-5 py-4">
                        {{-- Warning Box --}}
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-xs font-semibold text-red-800 mb-1.5">⚠ Perhatian Serius:</p>
                            <ul class="text-xs text-red-700 space-y-1">
                                <li>• Semua data yang ada akan <strong>ditimpa dan hilang permanen</strong></li>
                                <li>• Database akan diganti dengan isi file SQL yang diupload</li>
                                <li>• <strong>Buat backup terlebih dahulu</strong> sebelum restore</li>
                                <li>• Proses tidak dapat dibatalkan setelah dimulai</li>
                            </ul>
                        </div>

                        @if (!$canRunProcess || !$mysqlFound)
                            <div
                                class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-500 text-center">
                                Fitur restore tidak tersedia di server ini.
                            </div>
                        @else
                            <form method="POST" action="{{ route('admin.database.restore.upload') }}"
                                enctype="multipart/form-data" x-data="{ filename: '', loading: false }" @submit="loading = true">
                                @csrf

                                <div class="border-2 border-dashed border-gray-200 rounded-xl p-5 text-center
                                        hover:border-red-300 transition-colors cursor-pointer mb-3"
                                    @click="$refs.sqlInput.click()">
                                    <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p x-show="!filename" class="text-xs text-gray-400">Klik atau drag & drop file
                                        .sql</p>
                                    <p x-show="filename" class="text-xs font-semibold text-red-700" x-text="filename"
                                        x-cloak></p>
                                    <p class="text-xs text-gray-400 mt-1">Maksimal 512 MB</p>
                                </div>

                                <input type="file" name="sql_file" accept=".sql" x-ref="sqlInput"
                                    class="hidden" @change="filename = $event.target.files[0]?.name ?? ''">

                                @error('sql_file')
                                    <p class="mb-2 text-xs text-red-600">{{ $message }}</p>
                                @enderror

                                <button type="submit" :disabled="!filename || loading"
                                    class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white text-sm font-semibold
                                       rounded-lg hover:bg-red-700 transition-colors
                                       disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none"
                                        viewBox="0 0 24 24" x-cloak>
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                    </svg>
                                    <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <span x-text="loading ? 'Mengvalidasi...' : 'Upload & Lanjutkan'"></span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ TABEL DETAIL DATABASE ═══ --}}
        @if ($dbInfo['success'] && !empty($dbInfo['table_list']))
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Detail Tabel Database</h3>
                    <span class="text-xs text-gray-400">Top {{ count($dbInfo['table_list']) }} tabel terbesar</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th
                                    class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Nama Tabel</th>
                                <th
                                    class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">
                                    Est. Rows</th>
                                <th
                                    class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">
                                    Ukuran</th>
                                <th
                                    class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">
                                    Engine</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($dbInfo['table_list'] as $table)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-2.5">
                                        <span class="font-mono text-xs text-gray-800">{{ $table->table_name }}</span>
                                    </td>
                                    <td class="px-4 py-2.5 text-right text-xs font-mono text-gray-600">
                                        {{ number_format($table->table_rows) }}
                                    </td>
                                    <td class="px-4 py-2.5 text-right text-xs font-mono text-gray-600">
                                        {{ $table->size_kb >= 1024 ? round($table->size_kb / 1024, 2) . ' MB' : $table->size_kb . ' KB' }}
                                    </td>
                                    <td class="px-4 py-2.5 text-center">
                                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-mono">
                                            {{ $table->engine }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif


        {{-- ═══ MODAL KONFIRMASI RESTORE ═══ --}}
        <div x-show="restoreModalOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="background: rgba(0,0,0,0.6);" x-on:keydown.escape.window="restoreModalOpen = false">

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden" @click.stop>

                {{-- Header --}}
                <div class="bg-red-600 px-6 py-5">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-lg">Konfirmasi Restore Database</h3>
                            <p class="text-red-200 text-xs mt-0.5">Operasi ini tidak dapat dibatalkan</p>
                        </div>
                    </div>
                </div>

                @if ($restorePending)
                    <div class="px-6 py-5 space-y-4">

                        {{-- File Info --}}
                        <div class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl">
                            <div
                                class="w-9 h-9 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-gray-900 truncate">
                                    {{ $restorePending['original_name'] }}</p>
                                <p class="text-xs text-gray-400">Ukuran: {{ $restorePending['size_human'] }}</p>
                            </div>
                        </div>

                        {{-- Checklist Konsekuensi --}}
                        <div class="space-y-2">
                            @foreach (['Seluruh data yang ada saat ini akan DIHAPUS dan diganti', 'Semua tabel database akan di-drop dan dibuat ulang', 'Proses tidak bisa dihentikan setelah dimulai', 'Pastikan file backup berasal dari sistem yang sama'] as $item)
                                <div class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <p class="text-xs text-gray-700">{{ $item }}</p>
                                </div>
                            @endforeach
                        </div>

                        {{-- Validasi Errors --}}
                        @error('restore_confirm')
                            <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-xs text-red-700 font-medium">
                                {{ $message }}
                            </div>
                        @enderror

                        {{-- Ketik RESTORE --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Untuk melanjutkan, ketik <code
                                    class="bg-red-100 text-red-700 px-1 py-0.5 rounded font-bold">RESTORE</code>:
                            </label>
                            <input type="text" x-model="confirmText" placeholder="Ketik RESTORE di sini..."
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-mono
                               focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-400">Case-sensitive. Harus persis
                                <strong>RESTORE</strong>.</p>
                        </div>
                    </div>

                    <div class="px-6 pb-5 flex items-center justify-between gap-3">
                        {{-- Batal --}}
                        <form method="POST" action="{{ route('admin.database.restore.dismiss') }}"
                            class="flex items-center gap-2">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                Batalkan
                            </button>
                        </form>

                        {{-- Konfirmasi --}}
                        <form method="POST" action="{{ route('admin.database.restore.confirm') }}">
                            @csrf
                            <input type="hidden" name="confirmation" :value="confirmText">
                            <button type="submit" :disabled="!canConfirm"
                                class="flex items-center gap-2 px-5 py-2 bg-red-600 text-white text-sm font-bold rounded-lg
                               hover:bg-red-700 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Ya, Restore Database
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>
