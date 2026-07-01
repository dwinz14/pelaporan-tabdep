<x-app-layout :title="$title" :subtitle="$subtitle">
    @php
        $activeFilters = collect($filters)->filter(fn($value) => filled($value));
        $selectedUser = filled($filters['user_id'] ?? null)
            ? $users->firstWhere('id', (int) $filters['user_id'])
            : null;

        $logTone = function (?string $logName): array {
            return match ($logName) {
                'laporan' => [
                    'badge' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'icon' => 'bg-blue-50 text-blue-600 border-blue-100',
                    'dot' => 'bg-blue-500',
                ],
                'periode' => [
                    'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                    'icon' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                    'dot' => 'bg-indigo-500',
                ],
                'export' => [
                    'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'icon' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                    'dot' => 'bg-emerald-500',
                ],
                'user' => [
                    'badge' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'icon' => 'bg-amber-50 text-amber-600 border-amber-100',
                    'dot' => 'bg-amber-500',
                ],
                default => [
                    'badge' => 'bg-slate-100 text-slate-700 border-slate-200',
                    'icon' => 'bg-slate-100 text-slate-600 border-slate-200',
                    'dot' => 'bg-slate-400',
                ],
            };
        };

        $summaryCards = [
            [
                'label' => 'Total Log',
                'value' => number_format($totalLogs),
                'hint' => 'Seluruh aktivitas tercatat',
                'tone' => 'text-slate-900',
                'iconTone' => 'bg-slate-100 text-slate-600 border-slate-200',
                'icon' =>
                    'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z',
            ],
            [
                'label' => 'Hari Ini',
                'value' => number_format($todayLogs),
                'hint' => 'Aktivitas sejak 00:00',
                'tone' => 'text-indigo-700',
                'iconTone' => 'bg-indigo-50 text-indigo-600 border-indigo-200',
                'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
            ],
            [
                'label' => 'User Aktif',
                'value' => number_format($activeUsersToday),
                'hint' => 'Aktor unik hari ini',
                'tone' => 'text-emerald-700',
                'iconTone' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                'icon' =>
                    'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m10-4a4 4 0 11-8 0 4 4 0 018 0zm6 0a3 3 0 11-6 0 3 3 0 016 0zM9 10a3 3 0 11-6 0 3 3 0 016 0z',
            ],
            [
                'label' => 'Hasil Filter',
                'value' => number_format($logs->total()),
                'hint' => $activeFilters->isNotEmpty() ? 'Log sesuai filter aktif' : 'Filter belum diterapkan',
                'tone' => $activeFilters->isNotEmpty() ? 'text-sky-700' : 'text-slate-900',
                'iconTone' => $activeFilters->isNotEmpty()
                    ? 'bg-sky-50 text-sky-600 border-sky-200'
                    : 'bg-slate-100 text-slate-600 border-slate-200',
                'icon' =>
                    'M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6L14 13.5V19a1 1 0 01-1.45.89l-3-1.5A1 1 0 019 17.5v-4L3.2 4.6A1 1 0 013 4z',
            ],
        ];
    @endphp

    <div x-data="{ filtersOpen: true }" class="space-y-6">
        <section class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_320px]">
            <div class="relative overflow-hidden rounded-lg border border-[--color-border-default] bg-white shadow-sm">
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-slate-800 via-indigo-600 to-sky-500">
                </div>
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                        <div class="min-w-0">
                            <div class="mb-3 flex flex-wrap items-center gap-2">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-md border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Monitoring Aktif
                                </span>
                                @if ($latestActivityAt)
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-md border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                        Update terakhir {{ $latestActivityAt->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                            <h1
                                class="max-w-3xl text-2xl font-bold tracking-tight text-[--color-text-primary] sm:text-3xl">
                                Pantau jejak aktivitas sistem dengan lebih cepat.
                            </h1>
                            <p class="mt-2 max-w-2xl text-sm leading-6 text-[--color-text-muted]">
                                Gunakan halaman ini untuk melacak siapa melakukan apa, kapan aktivitas terjadi, dan
                                detail perubahan yang tersimpan pada audit trail.
                            </p>
                        </div>

                        <button type="button" @click="filtersOpen = !filtersOpen"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6L14 13.5V19a1 1 0 01-1.45.89l-3-1.5A1 1 0 019 17.5v-4L3.2 4.6A1 1 0 013 4z" />
                            </svg>
                            <span x-text="filtersOpen ? 'Sembunyikan Filter' : 'Tampilkan Filter'"></span>
                        </button>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                        @foreach ($summaryCards as $card)
                            <div class="rounded-lg border border-[--color-border-default] bg-[--color-bg-subtle] p-4">
                                <div
                                    class="mb-3 flex h-9 w-9 items-center justify-center rounded-lg border {{ $card['iconTone'] }}">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="{{ $card['icon'] }}" />
                                    </svg>
                                </div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-[--color-text-muted]">
                                    {{ $card['label'] }}</p>
                                <p class="mt-1 font-mono text-2xl font-bold tabular-nums {{ $card['tone'] }}">
                                    {{ $card['value'] }}</p>
                                <p class="mt-1 text-xs text-[--color-text-muted]">{{ $card['hint'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <aside class="rounded-lg border border-[--color-border-default] bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-[--color-text-muted]">Komposisi Log
                        </p>
                        <h2 class="mt-1 text-base font-bold text-[--color-text-primary]">Kategori teratas</h2>
                    </div>
                    <span
                        class="rounded-md border border-slate-200 bg-slate-50 px-2 py-1 font-mono text-xs font-bold text-slate-600">
                        {{ $logSummary->count() }}
                    </span>
                </div>

                <div class="mt-4 space-y-3">
                    @forelse ($logSummary as $name => $total)
                        @php
                            $tone = $logTone($name);
                            $percentage = $totalLogs > 0 ? min(100, round(($total / $totalLogs) * 100)) : 0;
                        @endphp
                        <div>
                            <div class="mb-1.5 flex items-center justify-between gap-3">
                                <div class="flex min-w-0 items-center gap-2">
                                    <span class="h-2 w-2 rounded-full {{ $tone['dot'] }}"></span>
                                    <span
                                        class="truncate text-xs font-bold uppercase tracking-wider text-slate-700">{{ $name ?: 'system' }}</span>
                                </div>
                                <span
                                    class="font-mono text-xs font-semibold text-slate-500">{{ number_format($total) }}</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full {{ $tone['dot'] }}"
                                    style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-lg border border-dashed border-slate-200 bg-slate-50 p-5 text-center">
                            <p class="text-sm font-semibold text-slate-700">Belum ada kategori</p>
                            <p class="mt-1 text-xs text-slate-500">Log akan muncul setelah aktivitas sistem tercatat.
                            </p>
                        </div>
                    @endforelse
                </div>
            </aside>
        </section>

        <section x-show="filtersOpen" x-transition
            class="rounded-lg border border-[--color-border-default] bg-white shadow-sm">
            <div class="border-b border-[--color-border-default] px-5 py-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-[--color-text-muted]">Filter Pencarian
                        </p>
                    </div>
                    @if ($activeFilters->isNotEmpty())
                        <a href="{{ route('admin.audit.index') }}"
                            class="inline-flex items-center justify-center gap-2 self-start rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 transition-colors hover:bg-slate-100 sm:self-auto">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Filter
                        </a>
                    @endif
                </div>
            </div>

            <form method="GET" action="{{ route('admin.audit.index') }}" class="p-5">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-slate-600">Kategori Log</label>
                        <select name="log_name"
                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Semua kategori</option>
                            @foreach ($logNames as $ln)
                                <option value="{{ $ln }}"
                                    {{ ($filters['log_name'] ?? '') === $ln ? 'selected' : '' }}>
                                    {{ ucfirst($ln) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-slate-600">Dari Tanggal</label>
                        <input type="date" name="dari" value="{{ $filters['dari'] ?? '' }}"
                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-slate-600">Sampai Tanggal</label>
                        <input type="date" name="sampai" value="{{ $filters['sampai'] ?? '' }}"
                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-slate-600">Keyword</label>
                        <div class="relative">
                            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="keyword" value="{{ $filters['keyword'] ?? '' }}"
                                placeholder="Cari deskripsi..."
                                class="w-full rounded-lg border border-slate-200 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-700 placeholder:text-slate-400 shadow-sm transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-wrap items-center gap-2">
                        @if ($activeFilters->isNotEmpty())
                            <span class="mr-1 text-xs font-bold uppercase tracking-wider text-slate-500">Filter
                                aktif:</span>
                            @if (filled($filters['log_name'] ?? null))
                                <span
                                    class="rounded-md border border-indigo-100 bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">Kategori:
                                    {{ $filters['log_name'] }}</span>
                            @endif
                            @if ($selectedUser)
                                <span
                                    class="rounded-md border border-indigo-100 bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">User:
                                    {{ $selectedUser->name }}</span>
                            @endif
                            @if (filled($filters['dari'] ?? null))
                                <span
                                    class="rounded-md border border-indigo-100 bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">Dari:
                                    {{ $filters['dari'] }}</span>
                            @endif
                            @if (filled($filters['sampai'] ?? null))
                                <span
                                    class="rounded-md border border-indigo-100 bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">Sampai:
                                    {{ $filters['sampai'] }}</span>
                            @endif
                            @if (filled($filters['keyword'] ?? null))
                                <span
                                    class="rounded-md border border-indigo-100 bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">Keyword:
                                    "{{ $filters['keyword'] }}"</span>
                            @endif
                        @else
                            <span class="text-xs text-[--color-text-muted]">Belum ada filter aktif. Menampilkan log
                                terbaru dari seluruh aktivitas.</span>
                        @endif
                    </div>

                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm shadow-indigo-200 transition-colors hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6L14 13.5V19a1 1 0 01-1.45.89l-3-1.5A1 1 0 019 17.5v-4L3.2 4.6A1 1 0 013 4z" />
                        </svg>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-lg border border-[--color-border-default] bg-white shadow-sm">
            <div
                class="flex flex-col gap-3 border-b border-[--color-border-default] px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-[--color-text-muted]">Log Aktivitas</p>
                    <h2 class="text-base font-bold text-[--color-text-primary]">Jejak audit terbaru</h2>
                </div>
                <span
                    class="self-start rounded-md border border-slate-200 bg-slate-50 px-3 py-1.5 font-mono text-xs font-bold text-slate-600 sm:self-auto">
                    {{ number_format($logs->total()) }} log
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[980px] text-sm">
                    <thead>
                        <tr class="border-b border-[--color-border-default] bg-[--color-bg-subtle]">
                            <th
                                class="w-44 px-5 py-3.5 text-left text-[10px] font-bold uppercase tracking-wider text-[--color-text-muted]">
                                Waktu</th>
                            <th
                                class="w-36 px-5 py-3.5 text-left text-[10px] font-bold uppercase tracking-wider text-[--color-text-muted]">
                                Kategori</th>
                            <th
                                class="px-5 py-3.5 text-left text-[10px] font-bold uppercase tracking-wider text-[--color-text-muted]">
                                Aktivitas</th>
                            <th
                                class="w-56 px-5 py-3.5 text-left text-[10px] font-bold uppercase tracking-wider text-[--color-text-muted]">
                                Aktor</th>
                            <th
                                class="w-32 px-5 py-3.5 text-right text-[10px] font-bold uppercase tracking-wider text-[--color-text-muted]">
                                Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[--color-border-default]">
                        @forelse($logs as $log)
                            @php
                                $tone = $logTone($log->log_name);
                                $properties = collect($log->properties);
                                $propertiesCount = $properties->count();
                                $subjectName = class_basename($log->subject_type ?? '');
                                $causerInitial = $log->causer ? strtoupper(substr($log->causer->name, 0, 1)) : 'S';
                            @endphp
                            <tr x-data="{ showProps: false }"
                                class="align-top transition-colors duration-150 hover:bg-[--color-bg-subtle]">
                                <td class="px-5 py-4">
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="mt-1 h-2.5 w-2.5 flex-shrink-0 rounded-full {{ $tone['dot'] }}"></span>
                                        <div>
                                            <p class="font-mono text-xs font-bold text-slate-700">
                                                {{ $log->created_at->format('d/m/Y') }}</p>
                                            <p class="mt-1 text-[11px] text-slate-400">
                                                {{ $log->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex rounded-md border px-2.5 py-1 text-xs font-bold uppercase tracking-wider {{ $tone['badge'] }}">
                                        {{ $log->log_name ?? 'system' }}
                                    </span>
                                    @if ($subjectName)
                                        <p class="mt-2 font-mono text-[11px] text-slate-400">{{ $subjectName }}
                                            #{{ $log->subject_id }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold leading-5 text-[--color-text-primary]">
                                        {{ $log->description }}</p>
                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-md border border-slate-200 bg-slate-50 px-2 py-1 text-[11px] font-semibold text-slate-500">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $propertiesCount }} detail
                                        </span>
                                        @if ($log->event)
                                            <span
                                                class="rounded-md border border-slate-200 bg-white px-2 py-1 font-mono text-[11px] font-semibold text-slate-500">{{ $log->event }}</span>
                                        @endif
                                    </div>

                                    @if ($propertiesCount > 0)
                                        <div x-show="showProps" x-transition x-cloak
                                            class="mt-3 rounded-lg border border-slate-200 bg-slate-950 p-3 text-xs text-slate-100 shadow-inner">
                                            <div class="mb-2 flex items-center justify-between gap-3">
                                                <p class="font-bold uppercase tracking-wider text-slate-300">Properties
                                                </p>
                                                <button type="button" @click="showProps = false"
                                                    class="rounded p-1 text-slate-400 transition-colors hover:bg-white/10 hover:text-white">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="max-h-56 space-y-2 overflow-auto pr-1 font-mono">
                                                @foreach ($properties as $key => $val)
                                                    <div class="rounded-md bg-white/5 p-2">
                                                        <p
                                                            class="mb-1 text-[10px] font-bold uppercase tracking-wider text-sky-300">
                                                            {{ $key }}</p>
                                                        <pre class="whitespace-pre-wrap break-words text-[11px] leading-5 text-slate-100">{{ is_array($val) || is_object($val) ? json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $val }}</pre>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg border {{ $log->causer ? 'border-indigo-100 bg-indigo-50 text-indigo-700' : 'border-slate-200 bg-slate-100 text-slate-600' }} font-bold">
                                            {{ $causerInitial }}
                                        </div>
                                        <div class="min-w-0">
                                            @if ($log->causer)
                                                <p class="truncate text-sm font-bold text-[--color-text-primary]">
                                                    {{ $log->causer->name }}</p>
                                                <p class="mt-0.5 font-mono text-xs text-slate-500">
                                                    {{ $log->causer->nik }}</p>
                                            @else
                                                <p class="text-sm font-bold text-[--color-text-primary]">Sistem</p>
                                                <p class="mt-0.5 text-xs text-slate-500">Otomatis</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @if ($propertiesCount > 0)
                                        <button type="button" @click="showProps = !showProps"
                                            class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-indigo-100 bg-indigo-50 px-3 py-2 text-xs font-bold text-indigo-700 transition-colors hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span x-text="showProps ? 'Tutup' : 'Buka'"></span>
                                        </button>
                                    @else
                                        <span class="text-xs text-slate-300">Tidak ada</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center">
                                    <div
                                        class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-300">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-[--color-text-primary]">Log tidak ditemukan</p>
                                    <p class="mt-1 text-sm text-[--color-text-muted]">Coba ubah filter atau reset
                                        pencarian untuk melihat aktivitas lainnya.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logs->hasPages())
                <div
                    class="flex flex-col gap-3 border-t border-[--color-border-default] bg-[--color-bg-subtle] px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs font-medium text-[--color-text-muted]">
                        Menampilkan <span class="font-bold text-slate-700">{{ $logs->firstItem() }}</span>-<span
                            class="font-bold text-slate-700">{{ $logs->lastItem() }}</span>
                        dari <span class="font-bold text-slate-700">{{ number_format($logs->total()) }}</span> log
                    </p>
                    {{ $logs->links() }}
                </div>
            @else
                <div class="border-t border-[--color-border-default] bg-[--color-bg-subtle] px-5 py-4">
                    <p class="text-xs font-medium text-[--color-text-muted]">
                        Total: <span class="font-bold text-slate-700">{{ number_format($logs->total()) }}</span> log
                    </p>
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
