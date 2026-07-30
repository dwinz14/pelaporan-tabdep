<x-app-layout :title="$title" :subtitle="$subtitle">

    <div class="max-w-5xl mx-auto pb-12 space-y-6">

        {{-- ═══ HEADER & BREADCRUMB ═══ --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.user-monitor.index') }}"
                    class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 transition-all focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Audit Trail Pengguna</h2>
                    <div class="flex items-center gap-2 text-sm text-slate-500 font-medium mt-0.5">
                        <a href="{{ route('admin.user-monitor.index') }}"
                            class="hover:text-indigo-600 transition-colors">Monitoring User</a>
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-700">Detail Aktivitas</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ USER IDENTITY CARD (PREMIUM) ═══ --}}
        <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden shadow-xl relative">
            {{-- Decorative Background Glow --}}
            <div
                class="absolute right-0 top-0 w-64 h-full bg-gradient-to-l from-indigo-50 to-transparent opacity-60 pointer-events-none">
            </div>

            <div class="p-8 relative z-10">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">

                    {{-- Avatar --}}
                    @php
                        $roleColor = match ($user->role) {
                            \App\Enums\UserRole::PicCabang => 'bg-sky-50 text-sky-700 border-sky-200',
                            \App\Enums\UserRole::Akunting => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            \App\Enums\UserRole::KepalaOperasional => 'bg-amber-50 text-amber-700 border-amber-200',
                            \App\Enums\UserRole::SuperAdmin => 'bg-rose-50 text-rose-700 border-rose-200',
                            default => 'bg-slate-50 text-slate-600 border-slate-200',
                        };
                    @endphp
                    <div
                        class="w-20 h-20 bg-indigo-600 rounded-[1.25rem] flex items-center justify-center flex-shrink-0 shadow-lg shadow-indigo-200 rotate-3 transform hover:rotate-0 transition-transform duration-300 border-2 border-white ring-1 ring-slate-100">
                        <span class="text-white text-2xl font-black font-mono tracking-wider">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 text-center sm:text-left mt-2 sm:mt-0">
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $user->name }}</h3>

                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-x-4 gap-y-2 mt-3">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-600 font-mono shadow-sm">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                </svg>
                                {{ $user->nik }}
                            </span>

                            <span
                                class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold border {{ $roleColor }} shadow-sm uppercase tracking-wider">
                                {{ $user->roleLabel() }}
                            </span>

                            @if ($user->cabang)
                                <span class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-600">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Cabang {{ $user->cabang->nama_cabang }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ LIVEWIRE COMPONENT LOG DETAIL ═══ --}}
        @livewire('admin.user-log-detail', ['user' => $user])

    </div>
</x-app-layout>
