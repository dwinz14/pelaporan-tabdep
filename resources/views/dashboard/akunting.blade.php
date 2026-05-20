<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Total Cabang</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalCabang }}</p>
        </div>
        <div class="bg-white rounded-lg border border-blue-200 bg-blue-50 p-5">
            <p class="text-xs text-blue-600 uppercase tracking-wide font-medium">Menunggu Verifikasi</p>
            <p class="text-2xl font-bold text-blue-700 mt-1">{{ $totalSubmitted }}</p>
            <p class="text-xs text-blue-500 mt-0.5">cabang, periode aktif</p>
        </div>
        <div class="bg-white rounded-lg border border-orange-200 bg-orange-50 p-5">
            <p class="text-xs text-orange-600 uppercase tracking-wide font-medium">Perlu Revisi</p>
            <p class="text-2xl font-bold text-orange-700 mt-1">{{ $totalRevisi }}</p>
            <p class="text-xs text-orange-500 mt-0.5">cabang, periode aktif</p>
        </div>
        <div class="bg-white rounded-lg border border-emerald-200 bg-emerald-50 p-5">
            <p class="text-xs text-emerald-600 uppercase tracking-wide font-medium">Terverifikasi</p>
            <p class="text-2xl font-bold text-emerald-700 mt-1">{{ $totalVerified }} / {{ $totalCabang }}</p>
            <p class="text-xs text-emerald-500 mt-0.5">periode aktif</p>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-5">

        {{-- Quick Access --}}
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
            <div class="space-y-2">
                @if ($periodeAktif)
                    <a href="{{ route('akunting.periode.show', $periodeAktif) }}"
                        class="flex items-center gap-3 p-3 border border-indigo-200 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                        <div class="w-8 h-8 bg-indigo-600 rounded flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-indigo-900">Verifikasi Sekarang</p>
                            <p class="text-xs text-indigo-600 truncate">{{ $periodeAktif->nama_periode }}</p>
                        </div>
                    </a>
                @endif
                <a href="{{ route('akunting.periode.index') }}"
                    class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-gray-700">Semua Periode</p>
                </a>
            </div>
        </div>

        {{-- 5 Periode Terbaru --}}
        <div class="md:col-span-2 bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-900">Periode Terbaru</h3>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Periode</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500">Progress</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 w-16">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($periodeRecent as $p)
                        @php
                            $total = $p->total_cabang ?? 0;
                            $verified = $p->total_verified ?? 0;
                            $pct = $total > 0 ? round(($verified / $total) * 100) : 0;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2.5">
                                <p class="text-xs font-medium text-gray-900">{{ $p->nama_periode }}</p>
                                <span
                                    class="text-xs {{ $p->status_operasional->badgeClass() }} px-1.5 py-0.5 rounded-full">
                                    {{ $p->status_operasional->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                                        <div class="{{ $pct == 100 ? 'bg-emerald-500' : 'bg-indigo-500' }} h-1.5 rounded-full"
                                            style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span
                                        class="text-xs text-gray-500 w-12 text-right">{{ $verified }}/{{ $total }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-2.5 text-right">
                                <a href="{{ route('akunting.periode.show', $p) }}"
                                    class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Buka</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-xs text-gray-400">Belum ada periode.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</x-app-layout>
