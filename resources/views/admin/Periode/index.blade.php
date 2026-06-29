<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- ═══ BANNER SARAN PERIODE ═══ --}}
    @if ($suggestion)
        <div x-data="{ dismissed: false }" x-show="!dismissed" x-transition
            class="mb-5 rounded-xl border overflow-hidden
                    {{ $suggestion['is_overdue'] ? 'border-red-300 bg-red-50' : 'border-indigo-300 bg-indigo-50' }}">

            <div class="px-5 py-4 flex items-start gap-4">

                {{-- Icon --}}
                <div
                    class="w-10 h-10 flex-shrink-0 rounded-xl flex items-center justify-center
                            {{ $suggestion['is_overdue'] ? 'bg-red-100' : 'bg-indigo-100' }}">
                    @if ($suggestion['is_overdue'])
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    @endif
                </div>

                {{-- Teks --}}
                <div class="flex-1 min-w-0">
                    @if ($suggestion['is_overdue'])
                        <p class="text-sm font-bold text-red-800">
                            ⚠ Periode berikutnya sudah melewati jadwal!
                        </p>
                        <p class="text-xs text-red-700 mt-0.5">
                            Berdasarkan pola 2 mingguan dari <strong>{{ $suggestion['prev_periode'] }}</strong>,
                            periode <strong>{{ $suggestion['nama_periode'] }}</strong>
                            (<strong>{{ $suggestion['display'] }}</strong>) seharusnya sudah dibuka
                            {{ $suggestion['days_diff'] }} hari yang lalu.
                        </p>
                    @else
                        <p class="text-sm font-bold text-indigo-800">
                            Saran: Generate Periode Berikutnya
                        </p>
                        <p class="text-xs text-indigo-700 mt-0.5">
                            Berdasarkan pola 2 mingguan dari <strong>{{ $suggestion['prev_periode'] }}</strong>,
                            apakah Anda ingin generate <strong>{{ $suggestion['nama_periode'] }}</strong>
                            untuk tanggal <strong>{{ $suggestion['display'] }}</strong>?
                        </p>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    {{-- Tombol Generate (form POST ke route khusus) --}}
                    <form method="POST" action="{{ route('admin.periode.generate-suggested') }}">
                        @csrf
                        <input type="hidden" name="tanggal_akhir" value="{{ $suggestion['tanggal'] }}">
                        <button type="submit"
                            class="flex items-center gap-1.5 px-4 py-2
                                   {{ $suggestion['is_overdue'] ? 'bg-red-600 hover:bg-red-700' : 'bg-indigo-600 hover:bg-indigo-700' }}
                                   text-white text-sm font-semibold rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Ya, Generate Sekarang
                        </button>
                    </form>

                    {{-- Dismiss (hanya UI, tidak ada server call) --}}
                    <button type="button" @click="dismissed = true" title="Tutup saran ini"
                        class="p-2 rounded-lg transition-colors
                               {{ $suggestion['is_overdue']
                                   ? 'text-red-400 hover:text-red-600 hover:bg-red-100'
                                   : 'text-indigo-400 hover:text-indigo-600 hover:bg-indigo-100' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Strip bawah --}}
            <div class="h-0.5 {{ $suggestion['is_overdue'] ? 'bg-red-400' : 'bg-indigo-400' }}"></div>
        </div>
    @endif

    {{-- Toolbar --}}
    <div class="flex items-center justify-between mb-5 gap-4 flex-wrap">
        <form method="GET" action="{{ route('admin.periode.index') }}" class="flex gap-2 flex-wrap">
            <select name="tahun"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Tahun</option>
                @foreach ($tahunList as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}
                    </option>
                @endforeach
            </select>
            <select name="status"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Status</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Proses Verifikasi</option>
                <option value="verified" {{ $status === 'verified' ? 'selected' : '' }}>Final</option>
            </select>
            <button type="submit"
                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                Filter
            </button>
            @if ($tahun || $status)
                <a href="{{ route('admin.periode.index') }}"
                    class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">✕ Reset</a>
            @endif
        </form>

        <a href="{{ route('admin.periode.create') }}"
            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium
                   rounded-lg hover:bg-indigo-700 transition-colors">
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
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Nama Periode
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">
                        Tanggal
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-56">
                        Progress Verifikasi
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-40">
                        Status Operasional
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">
                        Aktif
                    </th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($periodes as $periode)
                    <tr class="{{ $periode->is_current ? 'bg-indigo-50' : 'hover:bg-gray-50' }} transition-colors">

                        {{-- Nama Periode --}}
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-900">{{ $periode->nama_periode }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $periode->total_verified ?? 0 }} / {{ $periode->total_cabang ?? 0 }} cabang
                                diverifikasi
                            </p>
                        </td>

                        {{-- Tanggal --}}
                        <td class="px-4 py-3 text-xs font-mono text-gray-600">
                            {{ $periode->tanggal_akhir->format('d/m/Y') }}
                        </td>

                        {{-- Progress Bar --}}
                        <td class="px-4 py-3">
                            @php
                                $total = $periode->total_cabang ?? 0;
                                $verified = $periode->total_verified ?? 0;
                                $pct = $total > 0 ? round(($verified / $total) * 100) : 0;
                            @endphp
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                                    <div class="{{ $pct === 100 ? 'bg-emerald-500' : 'bg-indigo-500' }} h-1.5 rounded-full transition-all"
                                        style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 w-8 text-right">{{ $pct }}%</span>
                            </div>
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3">
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                         {{ $periode->status_operasional->badgeClass() }}">
                                {{ $periode->status_operasional->label() }}
                            </span>
                        </td>

                        {{-- Is Current --}}
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

                        {{-- Aksi --}}
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.periode.show', $periode) }}"
                                class="inline-flex items-center gap-1 text-xs text-indigo-600
                                       hover:text-indigo-800 font-semibold transition-colors">
                                Lihat Data
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-gray-400 text-sm">
                            Belum ada periode.
                            <a href="{{ route('admin.periode.create') }}" class="text-indigo-600 hover:underline">
                                Generate periode pertama
                            </a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($periodes->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 flex items-center justify-between">
                <p class="text-xs text-gray-500">
                    Menampilkan {{ $periodes->firstItem() }}–{{ $periodes->lastItem() }}
                    dari {{ $periodes->total() }} periode
                </p>
                {{ $periodes->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
