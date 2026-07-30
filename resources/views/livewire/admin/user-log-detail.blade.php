<div class="space-y-6">

    {{-- ═══ FILTER PANEL (SLEEK INLINE) ═══ --}}
    <div
        class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">

        <div
            class="flex items-center gap-2 text-sm font-bold text-slate-500 uppercase tracking-wider pl-2 w-full md:w-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            Filter Riwayat:
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 w-full flex-1">
            {{-- Category Filter --}}
            <select wire:model.live="selectedLogName"
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach ($this->logNames as $ln)
                    <option value="{{ $ln }}">{{ ucfirst($ln ?? 'Lainnya') }}</option>
                @endforeach
            </select>

            {{-- Start Date --}}
            <div class="relative">
                <label
                    class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Mulai</label>
                <input type="date" wire:model.live="logDari"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all cursor-pointer">
            </div>

            {{-- End Date --}}
            <div class="relative">
                <label
                    class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Akhir</label>
                <input type="date" wire:model.live="logSampai"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all cursor-pointer">
            </div>
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto">
            @if ($selectedLogName || $logDari || $logSampai)
                <button type="button" wire:click="resetFilters"
                    class="w-full md:w-auto px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-800 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors text-center whitespace-nowrap shadow-sm">
                    Reset Filter
                </button>
            @endif
        </div>
    </div>


    {{-- ═══ LOG FEED / THREAD LIST ═══ --}}
    <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden shadow-xl">

        <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-800">Linimasa Aktivitas</h3>
            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-xs font-bold shadow-sm">
                {{ $this->logs->total() }} Data Ditemukan
            </span>
        </div>

        @if ($this->logs->isEmpty())
            <div class="py-16 text-center px-4">
                <div
                    class="w-20 h-20 bg-slate-50 rounded-full border border-slate-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Tidak Ada Aktivitas Tercatat</h3>
                <p class="text-sm text-slate-500 max-w-sm mx-auto">Riwayat pengguna ini masih kosong atau tidak ada data
                    yang cocok dengan filter tanggal/kategori yang Anda pilih.</p>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach ($this->logs as $log)
                    @php
                        // Menentukan warna dan ikon berdasarkan kategori log
                        $catStyle = match ($log->log_name) {
                            'laporan' => [
                                'bg' => 'bg-blue-100 text-blue-700',
                                'border' => 'border-blue-200',
                                'icon' =>
                                    'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                            ],
                            'periode' => [
                                'bg' => 'bg-indigo-100 text-indigo-700',
                                'border' => 'border-indigo-200',
                                'icon' =>
                                    'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                            ],
                            'export' => [
                                'bg' => 'bg-emerald-100 text-emerald-700',
                                'border' => 'border-emerald-200',
                                'icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4',
                            ],
                            'import' => [
                                'bg' => 'bg-teal-100 text-teal-700',
                                'border' => 'border-teal-200',
                                'icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12',
                            ],
                            'database' => [
                                'bg' => 'bg-slate-100 text-slate-700',
                                'border' => 'border-slate-200',
                                'icon' =>
                                    'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4',
                            ],
                            'monitoring' => [
                                'bg' => 'bg-rose-100 text-rose-700',
                                'border' => 'border-rose-200',
                                'icon' =>
                                    'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
                            ],
                            'pencatatan' => [
                                'bg' => 'bg-cyan-100 text-cyan-700',
                                'border' => 'border-cyan-200',
                                'icon' =>
                                    'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                            ],
                            'registrasi' => [
                                'bg' => 'bg-amber-100 text-amber-700',
                                'border' => 'border-amber-200',
                                'icon' =>
                                    'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',
                            ],
                            'profile' => [
                                'bg' => 'bg-pink-100 text-pink-700',
                                'border' => 'border-pink-200',
                                'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                            ],
                            'user' => [
                                'bg' => 'bg-violet-100 text-violet-700',
                                'border' => 'border-violet-200',
                                'icon' =>
                                    'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                            ],
                            default => [
                                'bg' => 'bg-slate-100 text-slate-600',
                                'border' => 'border-slate-200',
                                'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                            ],
                        };
                        $hasProps = $log->properties && $log->properties->isNotEmpty();
                    @endphp

                    <div x-data="{ open: false }" class="p-6 sm:px-8 hover:bg-slate-50/50 transition-colors group">
                        <div class="flex flex-col sm:flex-row sm:items-start gap-4 sm:gap-6">

                            {{-- Info Sisi Kiri (Desktop) / Atas (Mobile) --}}
                            <div
                                class="flex items-center sm:flex-col sm:items-end gap-3 sm:gap-1 sm:w-36 flex-shrink-0 pt-1">
                                <span
                                    class="text-sm font-bold text-slate-700">{{ $log->created_at->format('d M Y') }}</span>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-xs font-mono text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded">{{ $log->created_at->format('H:i:s') }}</span>
                                </div>
                            </div>

                            {{-- Divider (Desktop Only) --}}
                            <div class="hidden sm:flex flex-col items-center flex-shrink-0 pt-1.5">
                                <div class="w-3 h-3 rounded-full {{ $catStyle['bg'] }} ring-4 ring-white"></div>
                            </div>

                            {{-- Main Content --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $catStyle['bg'] }} border {{ $catStyle['border'] }} mb-2 shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="{{ $catStyle['icon'] }}" />
                                            </svg>
                                            {{ $log->log_name ?? 'Lainnya' }}
                                        </span>
                                        <p class="text-base text-slate-800 font-semibold leading-snug">
                                            {{ $log->description }}</p>
                                        <p class="text-xs text-slate-400 mt-1 italic">
                                            {{ $log->created_at->diffForHumans() }}</p>
                                    </div>

                                    @if ($hasProps)
                                        <button type="button" @click="open = !open"
                                            class="flex-shrink-0 flex items-center justify-center gap-1.5 px-3 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 rounded-lg transition-colors focus:ring-2 focus:ring-indigo-300 focus:outline-none">
                                            <span x-text="open ? 'Tutup Detail' : 'Lihat Detail'"
                                                class="hidden sm:inline"></span>
                                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>

                                {{-- ═══ EXPANDABLE PROPERTIES (DIFF VIEWER) ═══ --}}
                                @if ($hasProps)
                                    <div x-show="open" x-collapse x-cloak class="mt-4">
                                        <div
                                            class="bg-slate-900 rounded-2xl p-5 border border-slate-800 shadow-inner overflow-hidden">

                                            {{-- Metadata non-diff (jika ada) --}}
                                            @php $hasOtherProps = false; @endphp
                                            <div class="space-y-2 mb-4">
                                                @foreach ($log->properties as $key => $val)
                                                    @if (!in_array($key, ['old', 'new']))
                                                        @php $hasOtherProps = true; @endphp
                                                        <div
                                                            class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4 text-xs font-mono">
                                                            <span
                                                                class="text-indigo-400 w-32 flex-shrink-0">"{{ $key }}"
                                                                :</span>
                                                            <span
                                                                class="text-slate-300 break-all bg-slate-800/50 px-2 py-0.5 rounded">{{ is_array($val) ? json_encode($val, JSON_UNESCAPED_UNICODE) : $val }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>

                                            @if ($hasOtherProps && ($log->properties->has('old') || $log->properties->has('new')))
                                                <div class="border-t border-slate-700/50 my-4"></div>
                                            @endif

                                            {{-- Diff Viewer (Side-by-side on lg screens) --}}
                                            @if ($log->properties->has('old') || $log->properties->has('new'))
                                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                                                    {{-- Old Values (Deleted/Changed) --}}
                                                    @if ($log->properties->has('old'))
                                                        <div
                                                            class="bg-rose-950/30 rounded-xl border border-rose-900/50 p-4">
                                                            <div
                                                                class="flex items-center gap-2 mb-3 border-b border-rose-900/50 pb-2">
                                                                <div
                                                                    class="w-5 h-5 rounded bg-rose-500/20 flex items-center justify-center text-rose-400">
                                                                    <svg class="w-3.5 h-3.5" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="3"
                                                                            d="M20 12H4" />
                                                                    </svg>
                                                                </div>
                                                                <h4
                                                                    class="text-[11px] uppercase font-bold tracking-widest text-rose-400">
                                                                    Data Sebelumnya</h4>
                                                            </div>
                                                            <div class="space-y-2">
                                                                @foreach ($log->properties['old'] ?? [] as $k => $v)
                                                                    <div
                                                                        class="flex flex-col font-mono text-[11px] leading-relaxed">
                                                                        <span
                                                                            class="text-rose-500/80 font-bold mb-0.5">{{ $k }}
                                                                            :</span>
                                                                        <span
                                                                            class="text-rose-200 bg-rose-500/10 px-2 py-1 rounded break-all">{{ is_array($v) ? json_encode($v) : $v }}</span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif

                                                    {{-- New Values (Added/Updated) --}}
                                                    @if ($log->properties->has('new'))
                                                        <div
                                                            class="bg-emerald-950/30 rounded-xl border border-emerald-900/50 p-4">
                                                            <div
                                                                class="flex items-center gap-2 mb-3 border-b border-emerald-900/50 pb-2">
                                                                <div
                                                                    class="w-5 h-5 rounded bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                                                                    <svg class="w-3.5 h-3.5" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="3"
                                                                            d="M12 4v16m8-8H4" />
                                                                    </svg>
                                                                </div>
                                                                <h4
                                                                    class="text-[11px] uppercase font-bold tracking-widest text-emerald-400">
                                                                    Data Pembaruan</h4>
                                                            </div>
                                                            <div class="space-y-2">
                                                                @foreach ($log->properties['new'] ?? [] as $k => $v)
                                                                    <div
                                                                        class="flex flex-col font-mono text-[11px] leading-relaxed">
                                                                        <span
                                                                            class="text-emerald-500/80 font-bold mb-0.5">{{ $k }}
                                                                            :</span>
                                                                        <span
                                                                            class="text-emerald-200 bg-emerald-500/10 px-2 py-1 rounded break-all">{{ is_array($v) ? json_encode($v) : $v }}</span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($this->logs->hasPages())
                <div class="px-8 py-5 border-t border-slate-100 bg-slate-50/50">
                    {{ $this->logs->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
