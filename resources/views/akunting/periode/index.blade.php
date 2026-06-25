<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Toolbar --}}
    <div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
        <form method="GET" action="{{ route('akunting.periode.index') }}" class="flex items-center gap-3">
            <div class="relative">
                <select name="tahun"
                    class="appearance-none px-3 py-2 pr-8 border border-[--color-border-default] rounded-lg text-sm bg-white text-[--color-text-primary] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Semua Tahun</option>
                    @foreach ($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="relative">
                <select name="status"
                    class="appearance-none px-3 py-2 pr-8 border border-[--color-border-default] rounded-lg text-sm bg-white text-[--color-text-primary] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Proses Verifikasi</option>
                    <option value="verified" {{ $status === 'verified' ? 'selected' : '' }}>Final</option>
                </select>
            </div>
            <button type="submit"
                class="btn-secondary text-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
            </button>
            @if ($tahun || $status)
                <a href="{{ route('akunting.periode.index') }}"
                    class="text-xs text-[--color-text-muted] hover:text-[--color-text-secondary] transition-colors font-medium">
                    ✕ Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table Card --}}
    <div class="bg-[--color-bg-surface] rounded-xl border border-[--color-border-default] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[--color-bg-subtle] border-b border-[--color-border-default]">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-[--color-text-muted] uppercase tracking-wider">Periode</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-[--color-text-muted] uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-[--color-text-muted] uppercase tracking-wider">Progress</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-[--color-text-muted] uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-[--color-text-muted] uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[--color-border-default]">
                    @forelse($periodes as $periode)
                        <tr class="{{ $periode->is_current ? 'bg-indigo-50/50' : 'hover:bg-[--color-bg-subtle]' }} transition-colors duration-150">
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <p class="font-semibold text-[--color-text-primary] text-sm">{{ $periode->nama_periode }}</p>
                                    @if ($periode->is_current)
                                        <span class="badge-success">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dot-pulse"></span>
                                            Aktif
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-xs font-mono text-[--color-text-secondary]">
                                {{ $periode->tanggal_akhir->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3.5">
                                @php
                                    $total = $periode->total_cabang ?? 0;
                                    $verified = $periode->total_verified ?? 0;
                                    $pct = $total > 0 ? round(($verified / $total) * 100) : 0;
                                @endphp
                                <div class="flex items-center gap-2.5">
                                    <div class="flex-1 min-w-[80px] bg-[--color-bg-inset] rounded-full h-2">
                                        <div class="{{ $pct == 100 ? 'bg-emerald-500' : 'bg-indigo-500' }} h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-xs text-[--color-text-muted] whitespace-nowrap font-mono">
                                        {{ $verified }}/{{ $total }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="{{ $periode->status_operasional->badgeClass() }}">
                                    {{ $periode->status_operasional->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <a href="{{ route('akunting.periode.show', $periode) }}"
                                    class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                    Buka
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-16 text-center">
                                <div class="w-14 h-14 bg-[--color-bg-inset] rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-7 h-7 text-[--color-text-muted]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-[--color-text-secondary] mb-1">Belum ada periode laporan</p>
                                <p class="text-xs text-[--color-text-muted]">Periode akan muncul setelah admin membuatnya.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($periodes->hasPages())
            <div class="px-4 py-3 border-t border-[--color-border-default] bg-[--color-bg-subtle] flex items-center justify-between">
                <p class="text-xs text-[--color-text-muted]">{{ $periodes->total() }} periode</p>
                {{ $periodes->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
