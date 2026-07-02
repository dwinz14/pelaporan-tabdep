<div wire:poll.60s="$refresh">

    {{-- ═══ FLASH ═══ --}}
    @if ($flashSuccess)
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
            class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            {{ $flashSuccess }}
        </div>
    @endif

    @if ($flashError)
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
            class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 text-sm rounded-lg flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
            {{ $flashError }}
        </div>
    @endif

    {{-- ═══ TABS ═══ --}}
    <div class="flex gap-1 mb-5 bg-gray-100 p-1 rounded-xl w-fit">
        <button type="button" wire:click="$set('activeTab', 'sessions')"
            class="flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-medium transition-all
                   {{ $activeTab === 'sessions' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Sesi Aktif
            @if ($this->sessionStats['total'] > 0)
                <span class="px-1.5 py-0.5 text-xs rounded-full font-semibold bg-emerald-100 text-emerald-700">
                    {{ $this->sessionStats['total'] }}
                </span>
            @endif
        </button>

        <button type="button" wire:click="$set('activeTab', 'logs')"
            class="flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-medium transition-all
                   {{ $activeTab === 'logs' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            Log Aktivitas
        </button>
    </div>


    {{-- ══════════════════════ TAB: SESI AKTIF ══════════════════════ --}}
    @if ($activeTab === 'sessions')

        {{-- Stats Row --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
            <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $this->sessionStats['total'] }}</p>
                    <p class="text-xs text-gray-500">Sesi Aktif</p>
                </div>
            </div>

            @foreach ($this->sessionStats['by_role'] as $role => $count)
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $count }}</p>
                    <p class="text-xs text-gray-500">{{ $role }}</p>
                </div>
            @endforeach
        </div>

        {{-- Toolbar --}}
        <div class="flex items-center justify-between mb-3 flex-wrap gap-3">
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                        clip-rule="evenodd" />
                </svg>
                Auto-refresh setiap 60 detik
            </div>

            @if ($this->sessionStats['total'] > 1)
                <button type="button" wire:click="forceLogoutAll"
                    wire:confirm="Logout SEMUA user selain Anda? Tindakan ini tidak dapat dibatalkan."
                    class="flex items-center gap-1.5 px-3 py-1.5 border border-red-300 text-red-600 text-xs
                           font-semibold rounded-lg hover:bg-red-50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Force Logout Semua User
                </button>
            @endif
        </div>

        {{-- Session Cards --}}
        @if ($this->activeSessions->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-10 text-center">
                <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-500">Tidak ada sesi aktif saat ini</p>
                <p class="text-xs text-gray-400 mt-1">Selain sesi Anda sendiri</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($this->activeSessions as $session)
                    @php
                        $roleColor = match ($session->user?->role) {
                            \App\Enums\UserRole::PicCabang => 'bg-sky-100 text-sky-800',
                            \App\Enums\UserRole::Akunting => 'bg-emerald-100 text-emerald-800',
                            \App\Enums\UserRole::KepalaOperasional => 'bg-amber-100 text-amber-800',
                            \App\Enums\UserRole::SuperAdmin => 'bg-rose-100 text-rose-800',
                            default => 'bg-gray-100 text-gray-600',
                        };
                    @endphp

                    <div
                        class="bg-white rounded-xl border {{ $session->is_current ? 'border-indigo-300 ring-1 ring-indigo-200' : 'border-gray-200' }} overflow-hidden">
                        <div class="flex items-start gap-4 p-4">

                            {{-- Avatar --}}
                            <div
                                class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 font-bold text-white
                                        {{ $session->is_current ? 'bg-indigo-600' : 'bg-gray-700' }}">
                                {{ strtoupper(substr($session->user?->name ?? '?', 0, 2)) }}
                            </div>

                            {{-- Info Utama --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-semibold text-gray-900 text-sm">
                                        {{ $session->user?->name ?? 'Unknown' }}</p>
                                    @if ($session->is_current)
                                        <span
                                            class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-semibold">
                                            ● Sesi Anda
                                        </span>
                                    @endif
                                    <span class="text-xs {{ $roleColor }} px-2 py-0.5 rounded-full font-medium">
                                        {{ $session->user?->roleLabel() ?? '—' }}
                                    </span>
                                    @if ($session->user?->cabang)
                                        <span class="text-xs text-gray-500">
                                            {{ $session->user->cabang->kode_cabang }} —
                                            {{ $session->user->cabang->nama_cabang }}
                                        </span>
                                    @endif
                                </div>

                                <p class="text-xs font-mono text-gray-400 mt-0.5">{{ $session->user?->nik ?? '—' }}</p>

                                {{-- Detail Teknis --}}
                                <div class="flex items-center gap-4 mt-2 flex-wrap">
                                    <span class="flex items-center gap-1 text-xs text-gray-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                        </svg>
                                        {{ $session->ip_address }}
                                    </span>
                                    <span class="flex items-center gap-1 text-xs text-gray-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ $session->browser }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $session->os }}</span>
                                    <span class="flex items-center gap-1 text-xs text-gray-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Aktif {{ $session->last_activity->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            {{-- Action --}}
                            <div class="flex-shrink-0">
                                @if (!$session->is_current)
                                    <button type="button" wire:click="forceLogout('{{ $session->session_id }}')"
                                        wire:confirm="Force logout sesi {{ $session->user?->name }}?"
                                        wire:loading.attr="disabled"
                                        class="flex items-center gap-1.5 px-3 py-2 border border-red-300 text-red-600
                                               text-xs font-semibold rounded-lg hover:bg-red-50 transition-colors
                                               disabled:opacity-60">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Force Logout
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400 italic">—</span>
                                @endif
                            </div>

                        </div>

                        {{-- User Agent Detail (collapsible) --}}
                        <div x-data="{ open: false }" class="border-t border-gray-50">
                            <button type="button" @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-1.5
                                       text-xs text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors">
                                <span>Lihat detail user agent</span>
                                <svg :class="open ? 'rotate-180' : ''" class="w-3.5 h-3.5 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="px-4 pb-3">
                                <p
                                    class="text-xs font-mono text-gray-500 break-all bg-gray-50 rounded p-2 border border-gray-100">
                                    {{ $session->user_agent }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    @endif


    {{-- ══════════════════════ TAB: LOG AKTIVITAS ══════════════════════ --}}
    @if ($activeTab === 'logs')

        <div class="grid md:grid-cols-3 gap-5">

            {{-- Kolom Kiri: Pilih User + Stats --}}
            <div class="md:col-span-1 space-y-4">

                {{-- Pilih User --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                        <p class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Pilih User</p>
                    </div>
                    <div class="p-3">
                        <select wire:model.live="selectedUserId"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">— Semua User —</option>
                            @foreach ($this->allUsers as $u)
                                <option value="{{ $u->id }}">
                                    {{ $u->name }} ({{ $u->nik }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Info User Terpilih --}}
                    @if ($this->selectedUser)
                        @php
                            $roleColor = match ($this->selectedUser->role) {
                                \App\Enums\UserRole::PicCabang => 'bg-sky-100 text-sky-800',
                                \App\Enums\UserRole::Akunting => 'bg-emerald-100 text-emerald-800',
                                \App\Enums\UserRole::KepalaOperasional => 'bg-amber-100 text-amber-800',
                                \App\Enums\UserRole::SuperAdmin => 'bg-rose-100 text-rose-800',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <div class="px-3 pb-3 pt-0">
                            <div class="flex items-center gap-3 p-3 bg-indigo-50 rounded-lg border border-indigo-100">
                                <div
                                    class="w-9 h-9 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-xs font-bold">
                                        {{ strtoupper(substr($this->selectedUser->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-gray-900 truncate">
                                        {{ $this->selectedUser->name }}
                                    </p>
                                    <p class="text-xs font-mono text-gray-400">{{ $this->selectedUser->nik }}</p>
                                    <span
                                        class="inline-flex mt-0.5 text-xs {{ $roleColor }} px-1.5 py-0.5 rounded-full font-medium">
                                        {{ $this->selectedUser->roleLabel() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Stats User Terpilih --}}
                @if ($this->selectedUser && $this->userActivityStats)
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                            <p class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Statistik Aktivitas
                            </p>
                        </div>
                        <div class="divide-y divide-gray-50">
                            <div class="px-4 py-3 flex justify-between items-center">
                                <span class="text-xs text-gray-500">Total Log</span>
                                <span
                                    class="text-sm font-bold text-gray-900">{{ number_format($this->userActivityStats['total']) }}</span>
                            </div>
                            <div class="px-4 py-3 flex justify-between items-center">
                                <span class="text-xs text-gray-500">30 Hari Terakhir</span>
                                <span
                                    class="text-sm font-bold text-indigo-600">{{ number_format($this->userActivityStats['last_30']) }}</span>
                            </div>
                            @if ($this->userActivityStats['last_activity'])
                                <div class="px-4 py-3">
                                    <span class="text-xs text-gray-500 block mb-0.5">Aktivitas Terakhir</span>
                                    <span class="text-xs font-medium text-gray-700">
                                        {{ $this->userActivityStats['last_activity']->created_at->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                                    </span>
                                    <span class="text-xs text-gray-400 block">
                                        {{ $this->userActivityStats['last_activity']->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Breakdown by Category --}}
                        @if ($this->userActivityStats['by_category']->isNotEmpty())
                            <div class="px-4 py-3 border-t border-gray-100">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Per
                                    Kategori (30 hr)</p>
                                <div class="space-y-1.5">
                                    @foreach ($this->userActivityStats['by_category'] as $cat => $count)
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 flex items-center gap-2">
                                                <span
                                                    class="text-xs text-gray-600 font-mono">{{ $cat ?? 'default' }}</span>
                                            </div>
                                            <span
                                                class="text-xs font-semibold text-gray-800">{{ $count }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Kolom Kanan: Filter + Tabel Log --}}
            <div class="md:col-span-2 space-y-4">

                {{-- Filter Log --}}
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Kategori</label>
                            <select wire:model.live="selectedLogName"
                                class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg text-xs
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Semua</option>
                                @foreach ($this->logNames as $ln)
                                    <option value="{{ $ln }}">{{ ucfirst($ln ?? 'default') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Dari</label>
                            <input type="date" wire:model.live="logDari"
                                class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg text-xs
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Sampai</label>
                            <input type="date" wire:model.live="logSampai"
                                class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg text-xs
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="flex items-end">
                            <button type="button" wire:click="resetLogFilter"
                                class="w-full px-3 py-1.5 border border-gray-300 text-gray-600 text-xs rounded-lg
                                       hover:bg-gray-50 transition-colors">
                                Reset Filter
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Tabel Log --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    @php $logs = $this->userLogs; @endphp

                    <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-700">
                            {{ number_format($logs->total()) }} log
                            @if ($this->selectedUser)
                                dari <strong>{{ $this->selectedUser->name }}</strong>
                            @else
                                dari semua user
                            @endif
                        </p>
                        <p class="text-xs text-gray-400">
                            Halaman {{ $logs->currentPage() }} / {{ $logs->lastPage() }}
                        </p>
                    </div>

                    @if ($logs->isEmpty())
                        <div class="py-10 text-center">
                            <p class="text-sm text-gray-400">Tidak ada log ditemukan.</p>
                        </div>
                    @else
                        <div class="divide-y divide-gray-50">
                            @foreach ($logs as $log)
                                <div x-data="{ open: false }" class="px-4 py-3 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start gap-3">

                                        {{-- Category Badge --}}
                                        <span
                                            class="flex-shrink-0 mt-0.5 inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                                                     {{ match ($log->log_name) {
                                                         'laporan' => 'bg-blue-100 text-blue-700',
                                                         'periode' => 'bg-indigo-100 text-indigo-700',
                                                         'export' => 'bg-emerald-100 text-emerald-700',
                                                         'import' => 'bg-teal-100 text-teal-700',
                                                         'database' => 'bg-gray-100 text-gray-700',
                                                         'monitoring' => 'bg-red-100 text-red-700',
                                                         'pencatatan' => 'bg-cyan-100 text-cyan-700',
                                                         'registrasi' => 'bg-amber-100 text-amber-700',
                                                         'profile' => 'bg-pink-100 text-pink-700',
                                                         'user' => 'bg-violet-100 text-violet-700',
                                                         default => 'bg-gray-100 text-gray-600',
                                                     } }}">
                                            {{ $log->log_name ?? 'default' }}
                                        </span>

                                        {{-- Content --}}
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-gray-800 leading-snug">{{ $log->description }}</p>
                                            <div class="flex items-center gap-3 mt-1 flex-wrap">
                                                {{-- User (jika menampilkan semua user) --}}
                                                @if (!$this->selectedUser && $log->causer)
                                                    <span class="text-xs text-indigo-600 font-medium">
                                                        {{ $log->causer->name }}
                                                    </span>
                                                @endif
                                                <span class="text-xs text-gray-400">
                                                    {{ $log->created_at->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                                                </span>
                                                <span class="text-xs text-gray-300">
                                                    {{ $log->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Toggle Properties --}}
                                        @if ($log->properties && $log->properties->isNotEmpty())
                                            <button type="button" @click="open = !open"
                                                class="text-xs text-gray-400 hover:text-indigo-600 transition-colors flex-shrink-0">
                                                <svg :class="open ? 'rotate-180' : ''"
                                                    class="w-4 h-4 transition-transform" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Properties Panel --}}
                                    @if ($log->properties && $log->properties->isNotEmpty())
                                        <div x-show="open" x-collapse class="mt-2 ml-16">
                                            <div class="bg-gray-50 rounded-lg p-2.5 border border-gray-200">
                                                @foreach ($log->properties as $key => $val)
                                                    @if (!in_array($key, ['old', 'new']))
                                                        <div class="flex items-start gap-2 text-xs">
                                                            <span
                                                                class="font-mono font-semibold text-gray-500 flex-shrink-0 w-28">{{ $key }}:</span>
                                                            <span
                                                                class="text-gray-700 break-all">{{ is_array($val) ? json_encode($val, JSON_UNESCAPED_UNICODE) : $val }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach

                                                {{-- Old/New diff khusus --}}
                                                @if ($log->properties->has('old') || $log->properties->has('new'))
                                                    <div
                                                        class="grid grid-cols-2 gap-2 mt-1.5 border-t border-gray-200 pt-1.5">
                                                        @if ($log->properties->has('old'))
                                                            <div>
                                                                <p class="text-xs font-semibold text-gray-400 mb-1">
                                                                    Sebelum</p>
                                                                @foreach ($log->properties['old'] ?? [] as $k => $v)
                                                                    <p class="text-xs font-mono text-gray-500">
                                                                        {{ $k }}: {{ $v }}</p>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                        @if ($log->properties->has('new'))
                                                            <div>
                                                                <p class="text-xs font-semibold text-emerald-600 mb-1">
                                                                    Sesudah</p>
                                                                @foreach ($log->properties['new'] ?? [] as $k => $v)
                                                                    <p class="text-xs font-mono text-emerald-700">
                                                                        {{ $k }}: {{ $v }}</p>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        @if ($logs->lastPage() > 1)
                            <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
                                <button type="button" wire:click="previousPage"
                                    :disabled="{{ $logs->currentPage() }} === 1"
                                    class="flex items-center gap-1 px-3 py-1.5 text-xs border border-gray-300 rounded-lg
                                           text-gray-600 hover:bg-gray-50 transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                                    {{ $logs->currentPage() === 1 ? 'disabled' : '' }}>
                                    ← Sebelumnya
                                </button>
                                <span class="text-xs text-gray-500">
                                    {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }}
                                </span>
                                <button type="button" wire:click="nextPage({{ $logs->lastPage() }})"
                                    {{ $logs->currentPage() === $logs->lastPage() ? 'disabled' : '' }}
                                    class="flex items-center gap-1 px-3 py-1.5 text-xs border border-gray-300 rounded-lg
                                           text-gray-600 hover:bg-gray-50 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                                    Berikutnya →
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>
