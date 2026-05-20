<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Toolbar --}}
    <div class="flex items-center justify-between mb-5 gap-4 flex-wrap">
        <form method="GET" action="{{ route('admin.periode.index') }}" class="flex gap-2 flex-wrap">
            <select name="tahun"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Tahun</option>
                @foreach ($tahunList as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>

            <select name="status"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Status</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Proses Verifikasi</option>
                <option value="verified" {{ $status === 'verified' ? 'selected' : '' }}>Final</option>
            </select>

            <button type="submit"
                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                Filter
            </button>
            @if ($tahun || $status)
                <a href="{{ route('admin.periode.index') }}"
                    class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">✕ Reset</a>
            @endif
        </form>

        <a href="{{ route('admin.periode.create') }}"
            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Generate Periode
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama
                        Periode</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">
                        Tanggal</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-48">
                        Progress Verifikasi</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-36">
                        Status</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">
                        Aktif</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($periodes as $periode)
                    <tr class="{{ $periode->is_current ? 'bg-indigo-50' : 'hover:bg-gray-50' }} transition-colors">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-900">{{ $periode->nama_periode }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $periode->total_verified ?? 0 }} dari {{ $periode->total_cabang ?? 0 }} cabang
                                terverifikasi
                            </p>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs font-mono">
                            {{ $periode->tanggal_akhir->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $total = $periode->total_cabang ?? 0;
                                $verified = $periode->total_verified ?? 0;
                                $pct = $total > 0 ? round(($verified / $total) * 100) : 0;
                            @endphp
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-emerald-500 h-1.5 rounded-full transition-all"
                                        style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 w-8 text-right">{{ $pct }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $periode->status_operasional->badgeClass() }}">
                                {{ $periode->status_operasional->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if ($periode->is_current)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                                    ● Aktif
                                </span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-gray-400 text-sm">
                            Belum ada periode. Silakan <a href="{{ route('admin.periode.create') }}"
                                class="text-indigo-600 hover:underline">generate periode pertama</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($periodes->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 flex items-center justify-between">
                <p class="text-xs text-gray-500">
                    Menampilkan {{ $periodes->firstItem() }}–{{ $periodes->lastItem() }} dari
                    {{ $periodes->total() }} periode
                </p>
                {{ $periodes->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
