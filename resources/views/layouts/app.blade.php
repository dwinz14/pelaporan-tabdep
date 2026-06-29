<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css'])
    @livewireStyles
    <style>
        /* Sidebar gradient dark */
        .sidebar-gradient {
            background: linear-gradient(180deg, #0f172a 0%, #0e1629 50%, #0b1120 100%);
        }

        /* Subtle noise texture overlay */
        .sidebar-gradient::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
            border-radius: inherit;
        }

        /* Header glassmorphism */
        .header-glass {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Smooth page content fade-in */
        @keyframes pageIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-animate {
            animation: pageIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Flash message slide-in */
        @keyframes flashIn {
            from {
                opacity: 0;
                transform: translateY(-8px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .flash-animate {
            animation: flashIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Flash dismiss */
        .flash-out {
            animation: flashOut 0.25s ease forwards;
        }

        @keyframes flashOut {
            to {
                opacity: 0;
                transform: translateY(-6px);
                max-height: 0;
                padding: 0;
                margin: 0;
            }
        }

        /* User avatar ring glow on hover */
        .avatar-wrap:hover .avatar-ring {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.5);
        }

        /* Logo shimmer animation */
        @keyframes logoShimmer {

            0%,
            100% {
                background-position: -200% center;
            }

            50% {
                background-position: 200% center;
            }
        }

        /* Logout button hover */
        .logout-btn:hover svg {
            transform: translateX(2px);
        }

        .logout-btn svg {
            transition: transform 0.2s ease;
        }
    </style>
</head>

<body style="background-color: var(--color-bg-canvas);" class="font-sans antialiased h-full">

    <div class="flex h-screen overflow-hidden">

        {{-- ═══════════════════════════════════
             SIDEBAR
        ═══════════════════════════════════ --}}
        <aside
            class="sidebar-gradient relative w-64 flex flex-col flex-shrink-0 border-r border-white/[0.06] shadow-2xl shadow-black/40 z-20">

            {{-- ── Logo & App Name ── --}}
            <div class="h-16 flex items-center px-5 border-b border-white/[0.06] flex-shrink-0">
                {{-- Logo badge --}}
                <div class="relative w-10 h-10 flex-shrink-0 mr-3">
                    <div
                        class="flex items-center justify-center w-10 h-10 rounded-2xl
               bg-white/10 backdrop-blur-xl border border-white/20
               shadow-lg shadow-black/20">

                        <x-application-logo class="w-6 h-6 text-white" />

                    </div>

                    {{-- Status dot --}}
                    <span
                        class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5
               bg-emerald-400 rounded-full border-2 border-slate-900 animate-pulse">
                    </span>
                </div>
                <div class="min-w-0">
                    <p class="text-white text-sm font-semibold leading-snug tracking-tight">TabDep-Hub</p>
                    <p class="text-slate-400 text-[11px] leading-tight mt-0.5 truncate">Manajemen Pelaporan Stok</p>
                </div>
            </div>

            {{-- ── Navigation ── --}}
            <nav class="flex-1 px-3 py-3 overflow-y-auto space-y-0.5 scrollbar-thin" id="sidebar-nav">

                @php $user = auth()->user(); @endphp

                {{-- ── PIC Cabang ── --}}
                @if ($user->isPicCabang())
                    <x-sidebar-item route="pic.dashboard" icon="grid" label="Dashboard" />

                    <x-sidebar-divider label="Pencatatan" />
                    <x-sidebar-item route="pic.pencatatan.index" icon="clipboard" label="Catat Transaksi"
                        activeOn="pic.pencatatan.*" />

                    <x-sidebar-divider label="Laporan" />
                    @if (isset($currentPeriode) && $currentPeriode)
                        {{-- Input Laporan aktif --}}
                        <a href="{{ route('pic.laporan.edit', $currentPeriode) }}"
                            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 relative overflow-hidden
                                   {{ request()->routeIs('pic.laporan.*')
                                       ? 'bg-gradient-to-r from-indigo-500/90 to-indigo-600 text-white font-semibold shadow-lg shadow-indigo-500/20'
                                       : 'text-slate-400 font-medium hover:text-white hover:bg-white/5' }}">
                            @if (request()->routeIs('pic.laporan.*'))
                                <span
                                    class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] h-5 bg-white/70 rounded-r-full"></span>
                            @endif
                            <span
                                class="w-7 h-7 flex items-center justify-center rounded-lg flex-shrink-0 transition-all duration-200
                                         {{ request()->routeIs('pic.laporan.*') ? 'bg-white/20' : 'bg-white/[0.04] group-hover:bg-white/10' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <span class="truncate">Input Laporan</span>
                            {{-- Live indicator --}}
                            <span class="ml-auto flex items-center gap-1 flex-shrink-0">
                                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                                <span
                                    class="text-[10px] text-emerald-400 font-semibold hidden group-hover:inline">Live</span>
                            </span>
                        </a>
                    @else
                        {{-- Input Laporan disabled --}}
                        <span
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 cursor-not-allowed opacity-40 select-none">
                            <span
                                class="w-7 h-7 flex items-center justify-center rounded-lg bg-white/[0.03] flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <span class="truncate">Input Laporan</span>
                            <span class="ml-auto flex-shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                        </span>
                    @endif

                    <x-sidebar-divider label="Riwayat" />
                    <x-sidebar-item route="pic.riwayat.index" icon="clock" label="Riwayat Laporan"
                        activeOn="pic.riwayat.*" />
                @endif

                {{-- ── Akunting ── --}}
                @if ($user->isAkunting())
                    <x-sidebar-item route="akunting.dashboard" icon="grid" label="Dashboard" />
                    <x-sidebar-divider label="Verifikasi" />
                    <x-sidebar-item route="akunting.periode.index" icon="calendar" label="Daftar Periode"
                        activeOn="akunting.periode.*" />
                @endif

                {{-- ── Kepala Operasional ── --}}
                @if ($user->isKepalaOperasional())
                    <x-sidebar-item route="kepala.dashboard" icon="grid" label="Dashboard" />
                    <x-sidebar-divider label="Persetujuan" />
                    <x-sidebar-item route="kepala.periode.index" icon="shield-check" label="Verifikasi Final"
                        activeOn="kepala.periode.*" />
                @endif

                {{-- ── Super Admin ── --}}
                @if ($user->isSuperAdmin())
                    <x-sidebar-item route="admin.dashboard" icon="grid" label="Dashboard" />
                    <x-sidebar-divider label="Master Data" />
                    <x-sidebar-item route="admin.cabang.index" icon="office-building" label="Manajemen Cabang"
                        activeOn="admin.cabang.*" />
                    <x-sidebar-item route="admin.user.index" icon="users" label="Manajemen User"
                        activeOn="admin.user.*" />
                    <x-sidebar-item route="admin.registrasi.index" icon="users" label="Registrasi User"
                        activeOn="admin.registrasi.*" :badge="$pendingCount > 0 ? $pendingCount : null" />
                    <x-sidebar-divider label="Operasional" />
                    <x-sidebar-item route="admin.periode.index" icon="calendar" label="Manajemen Periode"
                        activeOn="admin.periode.*" />
                    <x-sidebar-item route="admin.import.index" icon="upload" label="Import Data"
                        activeOn="admin.import.*" />
                    <x-sidebar-divider label="Sistem" />
                    <x-sidebar-item route="admin.audit.index" icon="document-text" label="Audit Trail"
                        activeOn="admin.audit.*" />
                    <x-sidebar-item route="admin.database.index" icon="database" label="Database"
                        activeOn="admin.database.*" />
                @endif

            </nav>

            {{-- ── User Profile & Logout ── --}}
            <div class="border-t border-slate-800 p-4">
                <a href="{{ route('profile.index') }}"
                    class="flex items-center gap-3 mb-3 px-2 py-1.5 rounded-lg
               hover:bg-slate-800 transition-colors group">
                    <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p
                            class="text-white text-xs font-semibold truncate group-hover:text-indigo-300 transition-colors">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-slate-400 text-xs truncate font-medium">{{ auth()->user()->roleLabel() }}</p>
                    </div>
                    <svg class="w-3 h-3 text-slate-600 group-hover:text-slate-400 flex-shrink-0" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2 px-3 py-1.5 text-xs text-slate-400
                   hover:text-white hover:bg-slate-800 rounded transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>

        </aside>

        {{-- ═══════════════════════════════════
             MAIN CONTENT AREA
        ═══════════════════════════════════ --}}
        <div class="flex-1 flex flex-col overflow-hidden" style="background-color: var(--color-bg-canvas);">

            {{-- ── Top Header ── --}}
            <header
                class="header-glass h-16 border-b border-[--color-border-default] flex items-center px-6 flex-shrink-0 shadow-sm z-10">
                {{-- Page Title --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2.5">
                        {{-- Colored accent bar --}}
                        <span
                            class="w-1 h-5 bg-gradient-to-b from-indigo-500 to-indigo-600 rounded-full flex-shrink-0"></span>
                        <div>
                            <h1 class="text-sm font-semibold text-[--color-text-primary] leading-snug">
                                {{ $title ?? 'Dashboard' }}
                            </h1>
                            @isset($subtitle)
                                <p class="text-[11px] text-[--color-text-muted] leading-snug mt-0.5">{{ $subtitle }}
                                </p>
                            @endisset
                        </div>
                    </div>
                </div>

                {{-- Right section --}}
                <div class="flex items-center gap-3">
                    {{-- Date badge --}}
                    <div
                        class="hidden sm:flex items-center gap-1.5 text-xs text-[--color-text-muted] bg-[--color-bg-subtle] px-3 py-1.5 rounded-lg border border-[--color-border-default]">
                        <svg class="w-3.5 h-3.5 text-[--color-text-muted]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ now()->isoFormat('D MMM Y') }}</span>
                    </div>

                    {{-- Cabang badge (PIC only) --}}
                    @if (auth()->user()->isPicCabang() && auth()->user()->cabang)
                        <div
                            class="flex items-center gap-1.5 text-xs bg-indigo-50 text-indigo-700 border border-indigo-200 px-3 py-1.5 rounded-lg font-medium">
                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                            </svg>
                            <span class="font-mono font-bold">{{ auth()->user()->cabang->kode_cabang }}</span>
                            <span class="text-indigo-400">—</span>
                            <span>{{ auth()->user()->cabang->nama_cabang }}</span>
                        </div>
                    @endif

                    {{-- Notification bell (visual only) --}}
                    <livewire:notification-bell />
                </div>
            </header>

            {{-- ── Page Content ── --}}
            <main class="flex-1 overflow-auto p-6 page-animate">

                {{-- Flash: Success --}}
                @if (session('success'))
                    <div id="flash-success"
                        class="flash-animate mb-5 flex items-start gap-3 p-4 bg-white border border-emerald-200 text-slate-700 text-sm rounded-xl shadow-sm shadow-emerald-100/50"
                        role="alert">
                        <span class="flex-shrink-0 w-7 h-7 flex items-center justify-center bg-emerald-100 rounded-lg">
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-emerald-700 text-xs uppercase tracking-wide mb-0.5">Berhasil
                            </p>
                            <p class="text-slate-600">{{ session('success') }}</p>
                        </div>
                        <button onclick="dismissFlash('flash-success')"
                            class="flex-shrink-0 text-slate-400 hover:text-slate-600 transition-colors p-0.5 rounded-md hover:bg-slate-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                {{-- Flash: Error --}}
                @if (session('error'))
                    <div id="flash-error"
                        class="flash-animate mb-5 flex items-start gap-3 p-4 bg-white border border-red-200 text-slate-700 text-sm rounded-xl shadow-sm shadow-red-100/50"
                        role="alert">
                        <span class="flex-shrink-0 w-7 h-7 flex items-center justify-center bg-red-100 rounded-lg">
                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-red-700 text-xs uppercase tracking-wide mb-0.5">Terjadi
                                Kesalahan</p>
                            <p class="text-slate-600">{{ session('error') }}</p>
                        </div>
                        <button onclick="dismissFlash('flash-error')"
                            class="flex-shrink-0 text-slate-400 hover:text-slate-600 transition-colors p-0.5 rounded-md hover:bg-slate-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                {{-- Flash: Warning --}}
                @if (session('warning'))
                    <div id="flash-warning"
                        class="flash-animate mb-5 flex items-start gap-3 p-4 bg-white border border-amber-200 text-slate-700 text-sm rounded-xl shadow-sm shadow-amber-100/50"
                        role="alert">
                        <span class="flex-shrink-0 w-7 h-7 flex items-center justify-center bg-amber-100 rounded-lg">
                            <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-amber-700 text-xs uppercase tracking-wide mb-0.5">Perhatian
                            </p>
                            <p class="text-slate-600">{{ session('warning') }}</p>
                        </div>
                        <button onclick="dismissFlash('flash-warning')"
                            class="flex-shrink-0 text-slate-400 hover:text-slate-600 transition-colors p-0.5 rounded-md hover:bg-slate-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                {{-- Slot Content --}}
                {{ $slot }}

            </main>

        </div>
    </div>

    @livewireScripts

    <script>
        /**
         * Dismiss flash messages with smooth animation
         */
        function dismissFlash(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.style.transition =
                'opacity 0.25s ease, transform 0.25s ease, max-height 0.3s ease, margin 0.3s ease, padding 0.3s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-6px)';
            el.style.overflow = 'hidden';
            setTimeout(() => {
                el.style.maxHeight = '0';
                el.style.paddingTop = '0';
                el.style.paddingBottom = '0';
                el.style.marginBottom = '0';
            }, 200);
            setTimeout(() => el.remove(), 500);
        }

        /**
         * Auto-dismiss flash messages after 5 seconds
         */
        document.addEventListener('DOMContentLoaded', () => {
            ['flash-success', 'flash-error', 'flash-warning'].forEach(id => {
                const el = document.getElementById(id);
                if (el) setTimeout(() => dismissFlash(id), 5000);
            });
        });
    </script>

</body>

</html>
