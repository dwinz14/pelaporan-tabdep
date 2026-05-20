<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Info Cabang --}}
    @if (auth()->user()->cabang)
        <div class="mb-5 flex items-center gap-4 p-4 bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="text-white font-bold text-sm">{{ auth()->user()->cabang->kode_cabang }}</span>
            </div>
            <div>
                <p class="font-semibold text-gray-900">{{ auth()->user()->cabang->nama_cabang }}</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ auth()->user()->name }} · {{ auth()->user()->nik }}
                </p>
            </div>
        </div>
    @endif

    {{-- Periode Aktif --}}
    @if ($periode)
        <div class="mb-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                <p class="text-sm font-semibold text-gray-900">{{ $periode->nama_periode }}</p>
                <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-medium">Aktif</span>
            </div>
            <p class="text-xs text-gray-400 font-mono">
                {{ $periode->tanggal_akhir->locale('id')->isoFormat('D MMMM Y') }}
            </p>
        </div>

        {{-- Status Cards --}}
        <div class="grid grid-cols-2 gap-4 mb-5">

            @php $tab = $laporanAktif->get('tabungan'); @endphp
            <div
                class="bg-white rounded-xl border {{ $tab?->status_verifikasi === \App\Enums\StatusVerifikasi::VerifiedAccounting ? 'border-emerald-300' : 'border-gray-200' }} p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">Tabungan</p>
                    </div>
                    @if ($tab)
                        <span
                            class="text-xs px-2 py-0.5 rounded-full font-medium {{ $tab->status_verifikasi->badgeClass() }}">
                            {{ $tab->status_verifikasi->label() }}
                        </span>
                    @else
                        <span class="text-xs text-gray-400">—</span>
                    @endif
                </div>
                @if ($tab)
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Saldo Awal</span>
                            <span
                                class="font-mono font-medium text-gray-700">{{ number_format($tab->saldo_awal) }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">+ Tambahan</span>
                            <span class="font-mono text-emerald-600">{{ number_format($tab->tambahan_stok) }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">− Digunakan</span>
                            <span class="font-mono text-red-500">{{ number_format($tab->jumlah_digunakan) }}</span>
                        </div>
                        <div class="pt-1.5 border-t border-gray-100 flex justify-between">
                            <span class="text-xs font-semibold text-gray-700">Saldo Akhir</span>
                            <span
                                class="font-mono font-bold text-gray-900">{{ number_format($tab->saldo_akhir) }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-gray-400">Belum ada data</p>
                @endif
            </div>

            @php $dep = $laporanAktif->get('deposito'); @endphp
            <div
                class="bg-white rounded-xl border {{ $dep?->status_verifikasi === \App\Enums\StatusVerifikasi::VerifiedAccounting ? 'border-emerald-300' : 'border-gray-200' }} p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-slate-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">Deposito</p>
                    </div>
                    @if ($dep)
                        <span
                            class="text-xs px-2 py-0.5 rounded-full font-medium {{ $dep->status_verifikasi->badgeClass() }}">
                            {{ $dep->status_verifikasi->label() }}
                        </span>
                    @else
                        <span class="text-xs text-gray-400">—</span>
                    @endif
                </div>
                @if ($dep)
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Saldo Awal</span>
                            <span
                                class="font-mono font-medium text-gray-700">{{ number_format($dep->saldo_awal) }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">+ Tambahan</span>
                            <span class="font-mono text-emerald-600">{{ number_format($dep->tambahan_stok) }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">− Digunakan</span>
                            <span class="font-mono text-red-500">{{ number_format($dep->jumlah_digunakan) }}</span>
                        </div>
                        <div class="pt-1.5 border-t border-gray-100 flex justify-between">
                            <span class="text-xs font-semibold text-gray-700">Saldo Akhir</span>
                            <span
                                class="font-mono font-bold text-gray-900">{{ number_format($dep->saldo_akhir) }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-gray-400">Belum ada data</p>
                @endif
            </div>
        </div>

        {{-- 2 CTA Buttons --}}
        <div class="grid grid-cols-2 gap-3 mb-6">
            <a href="{{ route('pic.pencatatan.index') }}"
                class="flex items-center gap-3 p-4 bg-white border-2 border-indigo-200 rounded-xl
                       hover:bg-indigo-50 hover:border-indigo-400 transition-all group">
                <div class="w-9 h-9 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-indigo-900">Catat Transaksi</p>
                    <p class="text-xs text-indigo-500">Input kejadian stok harian</p>
                </div>
            </a>

            <a href="{{ route('pic.laporan.edit', $periode) }}"
                class="flex items-center gap-3 p-4 bg-indigo-600 rounded-xl
                       hover:bg-indigo-700 transition-colors group">
                <div class="w-9 h-9 bg-indigo-500 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white">Input Laporan</p>
                    <p class="text-xs text-indigo-200">Pelaporan wajib periode ini</p>
                </div>
            </a>
        </div>
    @else
        <div class="mb-6 bg-white rounded-xl border border-gray-200 p-8 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-sm font-medium text-gray-500">Belum ada periode aktif</p>
            <p class="text-xs text-gray-400 mt-1">Hubungi Super Admin untuk generate periode laporan.</p>
        </div>
    @endif

    {{-- Riwayat Singkat --}}
    @if ($riwayatSingkat->isNotEmpty())
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-semibold text-gray-900">Riwayat Periode</h2>
            <a href="{{ route('pic.riwayat.index') }}"
                class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                Lihat semua →
            </a>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Periode</th>
                        <th
                            class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">
                            Tabungan</th>
                        <th
                            class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">
                            Deposito</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($riwayatSingkat as $p)
                        @php
                            $t = $p->laporans->firstWhere('jenis', \App\Enums\JenisLaporan::Tabungan);
                            $d = $p->laporans->firstWhere('jenis', \App\Enums\JenisLaporan::Deposito);
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <p class="text-xs font-medium text-gray-900">{{ $p->nama_periode }}</p>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span
                                        class="text-xs text-gray-400 font-mono">{{ $p->tanggal_akhir->format('d/m/Y') }}</span>
                                    <span
                                        class="text-xs {{ $p->status_operasional->badgeClass() }} px-1.5 py-0.5 rounded-full">
                                        {{ $p->status_operasional->label() }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if ($t)
                                    <p class="font-mono text-xs font-bold text-gray-800">
                                        {{ number_format($t->saldo_akhir) }}</p>
                                    <span
                                        class="text-xs {{ $t->status_verifikasi->badgeClass() }} px-1.5 py-0.5 rounded-full">
                                        {{ $t->status_verifikasi->label() }}
                                    </span>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if ($d)
                                    <p class="font-mono text-xs font-bold text-gray-800">
                                        {{ number_format($d->saldo_akhir) }}</p>
                                    <span
                                        class="text-xs {{ $d->status_verifikasi->badgeClass() }} px-1.5 py-0.5 rounded-full">
                                        {{ $d->status_verifikasi->label() }}
                                    </span>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-app-layout>
