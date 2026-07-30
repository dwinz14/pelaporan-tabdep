<div wire:poll.60s="$refresh" class="space-y-6 relative pb-10" x-data="{
    showForceLogoutModal: false,
    showForceLogoutAllModal: false,
    selectedSessionId: '',
    selectedUserName: ''
}">

    {{-- ═══ FLASH MESSAGES (Modern Toast Style) ═══ --}}
    <div class="fixed top-4 right-4 z-[100] flex flex-col gap-2 w-full max-w-sm pointer-events-none">
        @if ($flashSuccess)
            <div x-data="{ show: true }" x-show="show" x-transition:enter="transform ease-out duration-300 transition"
                x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" x-init="setTimeout(() => show = false, 4000)"
                class="pointer-events-auto w-full bg-white border-l-4 border-emerald-500 rounded-xl shadow-xl overflow-hidden"
                role="alert">
                <div class="p-4 flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-bold text-slate-900">Berhasil</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $flashSuccess }}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="show = false"
                            class="bg-white rounded-md inline-flex text-slate-400 hover:text-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if ($flashError)
            <div x-data="{ show: true }" x-show="show" x-transition:enter="transform ease-out duration-300 transition"
                x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" x-init="setTimeout(() => show = false, 6000)"
                class="pointer-events-auto w-full bg-white border-l-4 border-rose-500 rounded-xl shadow-xl overflow-hidden"
                role="alert">
                <div class="p-4 flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-bold text-slate-900">Terjadi Kesalahan</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $flashError }}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="show = false"
                            class="bg-white rounded-md inline-flex text-slate-400 hover:text-slate-500 focus:outline-none focus:ring-2 focus:ring-rose-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- ═══ TOP COMMAND BAR ═══ --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">

        {{-- Live Indicator --}}
        <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm w-fit">
            <div class="relative flex h-3 w-3">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
            </div>
            <span class="text-sm font-bold text-slate-700 tracking-wide">LIVE</span>
            <div class="w-px h-4 bg-slate-300"></div>
            <span class="text-xs font-medium text-slate-500">Auto-refresh tiap 60 detik</span>
        </div>

        {{-- Danger Action --}}
        <div>
            <button type="button" x-on:click.prevent="showForceLogoutAllModal = true"
                class="flex items-center justify-center gap-2 px-5 py-2.5 bg-white border-2 border-rose-100 text-rose-600 text-sm font-bold rounded-xl hover:bg-rose-600 hover:text-white hover:border-rose-600 focus:ring-4 focus:ring-rose-100 transition-all shadow-sm flex-shrink-0 group">
                <svg class="w-4 h-4 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Force Logout Semua Sesi
            </button>
        </div>
    </div>

    {{-- ══════════════════════ DATA TABLE CONTAINER ══════════════════════ --}}
    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden relative">

        {{-- Filter Bar (Sleek Inline Design) --}}
        <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col lg:flex-row lg:items-center gap-4">

            <div class="flex-1">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Cari nama pengguna atau NIK..."
                        class="block w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <div class="w-full sm:w-48">
                    <select wire:model.live="role"
                        class="block w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow appearance-none cursor-pointer">
                        <option value="">Semua Role</option>
                        @foreach (\App\Enums\UserRole::cases() as $r)
                            <option value="{{ $r->value }}">{{ $r->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-56">
                    <select wire:model.live="cabang"
                        class="block w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow appearance-none cursor-pointer">
                        <option value="">Semua Lokasi / Cabang</option>
                        @foreach ($this->cabangs as $c)
                            <option value="{{ $c->id }}">{{ $c->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($search || $role || $cabang)
                    <button type="button" wire:click="resetFilters" title="Reset Pencarian"
                        class="px-4 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-800 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-colors flex items-center justify-center shadow-sm">
                        Reset
                    </button>
                @endif
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="overflow-x-auto min-h-[300px]">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-white border-b border-slate-100">
                        <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-80">Profil
                            Pengguna</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-48">Role &
                            Penempatan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-40">Status
                            Koneksi</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Detail Sesi
                            Aktif</th>
                        <th
                            class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right w-40">
                            AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($this->users as $user)
                        @php
                            $roleColor = match ($user->role) {
                                \App\Enums\UserRole::PicCabang => 'bg-sky-50 text-sky-700 border-sky-200',
                                \App\Enums\UserRole::Akunting => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                \App\Enums\UserRole::KepalaOperasional => 'bg-amber-50 text-amber-700 border-amber-200',
                                \App\Enums\UserRole::SuperAdmin => 'bg-rose-50 text-rose-700 border-rose-200',
                                default => 'bg-slate-50 text-slate-600 border-slate-200',
                            };
                            $isMe = $user->is_current ?? false;
                        @endphp
                        <tr class="transition-colors group hover:bg-slate-50 {{ $isMe ? 'bg-indigo-50/20' : '' }}">

                            {{-- Kolom: Profil Pengguna --}}
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="relative w-12 h-12 rounded-[1rem] flex items-center justify-center flex-shrink-0 font-bold text-white shadow-sm border border-slate-100
                                                {{ $isMe ? 'bg-indigo-600' : 'bg-slate-700' }}">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}

                                        {{-- Online/Offline Ping Dot --}}
                                        @if ($user->is_online)
                                            <span class="absolute -bottom-1 -right-1 flex h-4 w-4">
                                                <span
                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                <span
                                                    class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-white"></span>
                                            </span>
                                        @else
                                            <span
                                                class="absolute -bottom-1 -right-1 block w-4 h-4 bg-slate-300 border-2 border-white rounded-full"></span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-900 flex items-center gap-2 truncate">
                                            {{ $user->name }}
                                            @if ($isMe)
                                                <span
                                                    class="text-[10px] bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded border border-indigo-200 font-bold tracking-wider flex-shrink-0">
                                                    SAYA
                                                </span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-slate-500 font-mono mt-0.5 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                            </svg>
                                            {{ $user->nik }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom: Role & Penempatan --}}
                            <td class="px-6 py-4 align-middle">
                                <div class="flex flex-col items-start gap-1.5">
                                    <span
                                        class="inline-flex text-[11px] {{ $roleColor }} border px-2.5 py-1 rounded-md font-bold uppercase tracking-wider">
                                        {{ $user->roleLabel() }}
                                    </span>
                                    <span
                                        class="text-xs font-medium text-slate-600 flex items-center gap-1.5 truncate max-w-full">
                                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        @if ($user->cabang)
                                            {{ $user->cabang->nama_cabang }}
                                        @else
                                            Kantor Pusat
                                        @endif
                                    </span>
                                </div>
                            </td>

                            {{-- Kolom: Status Koneksi --}}
                            <td class="px-6 py-4 align-middle">
                                @if ($user->is_online)
                                    <span class="text-sm font-bold text-emerald-600">Online</span>
                                @else
                                    <span class="text-sm font-semibold text-slate-400">Offline</span>
                                @endif
                                <p class="text-xs text-slate-500 font-medium mt-0.5">
                                    @if ($user->is_online)
                                        Aktif {{ $user->last_seen ? $user->last_seen->diffForHumans() : 'baru saja' }}
                                    @else
                                        Terakhir:
                                        {{ $user->last_seen ? $user->last_seen->diffForHumans() : 'Belum Pernah' }}
                                    @endif
                                </p>
                            </td>

                            {{-- Kolom: Detail Sesi --}}
                            <td class="px-6 py-4 align-middle">
                                @if ($user->is_online && $user->session_id)
                                    <div class="flex flex-col gap-1.5">
                                        <span
                                            class="flex items-center gap-2 text-xs font-mono text-slate-600 bg-slate-50 border border-slate-100 px-2 py-1 rounded w-fit">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                            </svg>
                                            {{ $user->ip_address }}
                                        </span>
                                        <span class="flex items-center gap-2 text-xs font-medium text-slate-500">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ $user->browser }} &bull; {{ $user->os }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-xs font-medium text-slate-300 italic">Data sesi tidak
                                        tersedia</span>
                                @endif
                            </td>

                            {{-- Kolom: Tindakan --}}
                            <td class="px-8 py-4 align-middle text-right">
                                <div
                                    class="flex justify-end items-center gap-2 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">

                                    <a href="{{ route('admin.user-monitor.show', $user->id) }}"
                                        title="Lihat Riwayat Aktivitas"
                                        class="flex items-center justify-center p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition-all focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </a>

                                    @if ($user->is_online && !$isMe)
                                        <button type="button"
                                            x-on:click.prevent="selectedSessionId = '{{ $user->session_id }}'; selectedUserName = '{{ $user->name }}'; showForceLogoutModal = true"
                                            title="Cabut Akses Sesi Ini"
                                            class="flex items-center justify-center p-2 bg-white border border-rose-200 text-rose-500 rounded-xl hover:bg-rose-50 hover:text-rose-700 hover:border-rose-300 transition-all focus:ring-2 focus:ring-rose-500 focus:outline-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2.5"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center">
                                <div
                                    class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800 mb-1">Tidak Ditemukan Pengguna</h3>
                                <p class="text-sm text-slate-500">Coba ubah kata kunci atau filter pencarian Anda di
                                    atas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($this->users->hasPages())
            <div class="px-8 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $this->users->links() }}
            </div>
        @endif
    </div>

    {{-- ═══ CONFIRM MODAL — FORCE LOGOUT INDIVIDUAL ═══ --}}
    <div x-show="showForceLogoutModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showForceLogoutModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6"
            @keydown.escape.window="showForceLogoutModal = false">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center flex-shrink-0 mt-1">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-bold text-slate-900">Konfirmasi Force Logout</h3>
                    <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                        PERINGATAN! Sesi pengguna <span class="font-bold text-rose-600"
                            x-text="selectedUserName"></span> akan dihentikan paksa. Lanjutkan?
                    </p>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="showForceLogoutModal = false"
                    class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors focus:ring-2 focus:ring-slate-300">
                    Batal
                </button>
                <button type="button" @click="$wire.forceLogout(selectedSessionId); showForceLogoutModal = false"
                    class="px-5 py-2.5 text-sm font-bold text-white bg-rose-600 rounded-xl hover:bg-rose-700 transition-colors focus:ring-4 focus:ring-rose-200">
                    Ya, Force Logout
                </button>
            </div>
        </div>
    </div>

    {{-- ═══ CONFIRM MODAL — FORCE LOGOUT ALL ═══ --}}
    <div x-show="showForceLogoutAllModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showForceLogoutAllModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6"
            @keydown.escape.window="showForceLogoutAllModal = false">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center flex-shrink-0 mt-1">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-bold text-slate-900">Konfirmasi Force Logout Semua</h3>
                    <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                        PERINGATAN! Anda akan mengeluarkan (Logout) secara paksa <span class="font-bold">SEMUA</span>
                        pengguna yang sedang aktif, <span class="font-bold">KECUALI</span> Anda sendiri. Lanjutkan?
                    </p>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="showForceLogoutAllModal = false"
                    class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors focus:ring-2 focus:ring-slate-300">
                    Batal
                </button>
                <button type="button" @click="$wire.forceLogoutAll(); showForceLogoutAllModal = false"
                    class="px-5 py-2.5 text-sm font-bold text-white bg-rose-600 rounded-xl hover:bg-rose-700 transition-colors focus:ring-4 focus:ring-rose-200">
                    Ya, Force Logout Semua
                </button>
            </div>
        </div>
    </div>

</div>
