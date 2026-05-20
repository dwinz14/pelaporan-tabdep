<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Periode Siap Verifikasi Final --}}
    <h2 class="text-sm font-semibold text-gray-900 mb-3">
        Menunggu Verifikasi Final
    </h2>

    @if ($periodeSiap->isEmpty())
        <div class="bg-white rounded-lg border border-gray-200 p-8 text-center mb-6">
            <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-gray-500">Tidak ada periode yang menunggu verifikasi final.</p>
            <p class="text-xs text-gray-400 mt-1">Periode akan muncul di sini setelah semua cabang diverifikasi oleh
                akunting.</p>
        </div>
    @else
        <div class="space-y-3 mb-6">
            @foreach ($periodeSiap as $periode)
                @php
                    $total = $periode->total_cabang ?? 0;
                    $verified = $periode->total_verified ?? 0;
                    $siap = $total > 0 && $total === $verified;
                @endphp
                <div
                    class="bg-white rounded-lg border {{ $siap ? 'border-emerald-200 bg-emerald-50' : 'border-gray-200' }} p-4">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-semibold text-gray-900">{{ $periode->nama_periode }}</h3>
                                @if ($siap)
                                    <span
                                        class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-medium">
                                        ✓ Siap Verifikasi Final
                                    </span>
                                @else
                                    <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">
                                        {{ $verified }}/{{ $total }} cabang terverifikasi
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500">
                                {{ $periode->tanggal_akhir->locale('id')->isoFormat('D MMMM Y') }}
                            </p>

                            {{-- Progress --}}
                            @php $pct = $total > 0 ? round($verified / $total * 100) : 0; @endphp
                            <div class="mt-2 flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-1.5" style="max-width: 200px">
                                    <div class="{{ $siap ? 'bg-emerald-500' : 'bg-indigo-500' }} h-1.5 rounded-full"
                                        style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500">{{ $pct }}%</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('kepala.periode.show', $periode) }}"
                                class="px-3 py-1.5 border border-gray-300 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                Review
                            </a>
                            @if ($siap)
                                <form method="POST" action="{{ route('kepala.periode.finalize', $periode) }}"
                                    onsubmit="return confirm('Verifikasi final periode ini? Semua data akan dikunci dan tidak dapat diubah.')">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-emerald-600 text-white text-xs font-semibold rounded-lg hover:bg-emerald-700 transition-colors">
                                        ✓ Verifikasi Final
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Riwayat Periode Sudah Final --}}
    @if ($periodeSelesai->isNotEmpty())
        <h2 class="text-sm font-semibold text-gray-900 mb-3">Riwayat Periode Sudah Final</h2>
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Periode</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">
                            Tanggal Akhir</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Tanggal Final</th>
                        <th
                            class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($periodeSelesai as $periode)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $periode->nama_periode }}</td>
                            <td class="px-4 py-3 text-xs font-mono text-gray-500">
                                {{ $periode->tanggal_akhir->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-xs text-gray-500">
                                {{ $periode->tgl_verifikasi_operasional?->locale('id')->isoFormat('D MMM Y, HH:mm') ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('kepala.periode.show', $periode) }}"
                                    class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                    Lihat →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-app-layout>
