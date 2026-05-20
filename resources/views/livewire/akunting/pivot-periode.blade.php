<div>

    {{-- ═══ FLASH MESSAGES ═══ --}}
    @if ($flashSuccess)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
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
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 text-sm rounded-lg flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
            {{ $flashError }}
        </div>
    @endif

    {{-- ═══ PROGRESS STATS ═══ --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-5">
        <div class="bg-white rounded-lg border border-gray-200 p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $this->progress['total'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total Cabang</p>
        </div>
        <div class="bg-white rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-center">
            <p class="text-2xl font-bold text-emerald-700">{{ $this->progress['verified'] }}</p>
            <p class="text-xs text-emerald-600 mt-0.5">Terverifikasi</p>
        </div>
        <div class="bg-white rounded-lg border border-blue-200 bg-blue-50 p-4 text-center">
            <p class="text-2xl font-bold text-blue-700">{{ $this->progress['submitted'] }}</p>
            <p class="text-xs text-blue-600 mt-0.5">Menunggu Verifikasi</p>
        </div>
        <div class="bg-white rounded-lg border border-orange-200 bg-orange-50 p-4 text-center">
            <p class="text-2xl font-bold text-orange-700">{{ $this->progress['revision'] }}</p>
            <p class="text-xs text-orange-600 mt-0.5">Perlu Revisi</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4 text-center">
            <p class="text-2xl font-bold text-gray-500">{{ $this->progress['draft'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Draft</p>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div class="bg-white rounded-lg border border-gray-200 p-4 mb-5">
        <div class="flex items-center justify-between mb-2">
            <p class="text-sm font-medium text-gray-700">
                Progress Verifikasi Akunting
            </p>
            <p
                class="text-sm font-semibold {{ $this->progress['pct'] === 100 ? 'text-emerald-600' : 'text-gray-600' }}">
                {{ $this->progress['verified'] }} / {{ $this->progress['total'] }} cabang
                ({{ $this->progress['pct'] }}%)
            </p>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="h-2.5 rounded-full transition-all duration-500
                        {{ $this->progress['pct'] === 100 ? 'bg-emerald-500' : 'bg-indigo-500' }}"
                style="width: {{ $this->progress['pct'] }}%"></div>
        </div>
        @if ($this->progress['pct'] === 100 && !$periode->isLocked())
            <p class="text-xs text-emerald-600 mt-2 font-medium">
                ✅ Semua cabang terverifikasi. Periode ini siap untuk verifikasi final oleh Kepala Operasional.
            </p>
        @endif
    </div>

    {{-- ═══ PIVOT TABLE ═══ --}}
    @if ($periode->isLocked())
        <div
            class="mb-4 p-3 bg-violet-50 border border-violet-200 rounded-lg text-xs text-violet-800 flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                    clip-rule="evenodd" />
            </svg>
            <strong>Periode ini sudah diverifikasi final.</strong> Data bersifat read-only dan tidak dapat diubah.
        </div>
    @endif

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" style="min-width: 1200px;">
                <thead>
                    {{-- Header baris 1: Grup --}}
                    <tr class="bg-gray-800 text-white">
                        <th rowspan="2"
                            class="px-3 py-2.5 text-left text-xs font-semibold border-r border-gray-700 w-36 align-middle">
                            Kantor Cabang
                        </th>
                        <th colspan="7"
                            class="px-3 py-2 text-center text-xs font-semibold border-r border-gray-700 bg-indigo-800">
                            TABUNGAN
                        </th>
                        <th colspan="7" class="px-3 py-2 text-center text-xs font-semibold bg-slate-700">
                            DEPOSITO
                        </th>
                    </tr>
                    {{-- Header baris 2: Kolom detail --}}
                    <tr class="bg-gray-100 text-gray-600">
                        @foreach (['Saldo Awal', 'Tambahan', 'Digunakan', 'Batal Rusak', 'Batal Hilang', 'Saldo Akhir', 'Status / Aksi'] as $col)
                            <th
                                class="px-3 py-2 text-center text-xs font-semibold whitespace-nowrap
                                       {{ $loop->last ? 'border-r border-gray-300 w-28' : 'border-r border-gray-200' }}">
                                {{ $col }}
                            </th>
                        @endforeach
                        @foreach (['Saldo Awal', 'Tambahan', 'Digunakan', 'Batal Rusak', 'Batal Hilang', 'Saldo Akhir', 'Status / Aksi'] as $col)
                            <th
                                class="px-3 py-2 text-center text-xs font-semibold whitespace-nowrap
                                       {{ !$loop->last ? 'border-r border-gray-200' : '' }}">
                                {{ $col }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($this->pivotData as $row)
                        @php
                            $tab = $row['tabungan'];
                            $dep = $row['deposito'];
                            $cabang = $row['cabang'];

                            // Warna baris berdasarkan status keseluruhan
                            $tabStatus = $tab?->status_verifikasi;
                            $depStatus = $dep?->status_verifikasi;

                            $rowClass = 'hover:bg-gray-50';
                            if (
                                $tabStatus === \App\Enums\StatusVerifikasi::VerifiedAccounting &&
                                $depStatus === \App\Enums\StatusVerifikasi::VerifiedAccounting
                            ) {
                                $rowClass = 'bg-emerald-50 hover:bg-emerald-100';
                            } elseif (
                                $tabStatus === \App\Enums\StatusVerifikasi::RevisionRequested ||
                                $depStatus === \App\Enums\StatusVerifikasi::RevisionRequested
                            ) {
                                $rowClass = 'bg-orange-50 hover:bg-orange-100';
                            } elseif (
                                $tabStatus === \App\Enums\StatusVerifikasi::Submitted ||
                                $depStatus === \App\Enums\StatusVerifikasi::Submitted
                            ) {
                                $rowClass = 'bg-blue-50 hover:bg-blue-100';
                            }
                        @endphp
                        <tr class="{{ $rowClass }} transition-colors">
                            {{-- Nama Cabang --}}
                            <td class="px-3 py-3 border-r border-gray-200">
                                <p class="font-semibold text-gray-900 font-mono text-xs">{{ $cabang->kode_cabang }}</p>
                                <p class="text-gray-600 text-xs leading-tight">{{ $cabang->nama_cabang }}</p>
                            </td>

                            {{-- TABUNGAN --}}
                            @if ($tab)
                                <td class="px-3 py-3 text-center font-mono text-xs border-r border-gray-100">
                                    {{ number_format($tab->saldo_awal) }}</td>
                                <td class="px-3 py-3 text-center font-mono text-xs border-r border-gray-100">
                                    {{ number_format($tab->tambahan_stok) }}</td>
                                <td class="px-3 py-3 text-center font-mono text-xs border-r border-gray-100">
                                    {{ number_format($tab->jumlah_digunakan) }}</td>
                                <td class="px-3 py-3 text-center font-mono text-xs border-r border-gray-100">
                                    {{ number_format($tab->jml_dibatalkan_rusak) }}</td>
                                <td class="px-3 py-3 text-center font-mono text-xs border-r border-gray-100">
                                    {{ number_format($tab->jml_dibatalkan_hilang) }}</td>
                                <td class="px-3 py-3 text-center border-r border-gray-100">
                                    <span
                                        class="font-mono font-bold text-xs {{ $tab->saldo_akhir == 0 ? 'text-gray-400' : 'text-gray-900' }}">
                                        {{ number_format($tab->saldo_akhir) }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 border-r border-gray-200">
                                    @include('livewire.akunting._action-cell', ['laporan' => $tab])
                                </td>
                            @else
                                <td colspan="7"
                                    class="px-3 py-3 text-center text-gray-300 text-xs border-r border-gray-200">Belum
                                    ada data</td>
                            @endif

                            {{-- DEPOSITO --}}
                            @if ($dep)
                                <td class="px-3 py-3 text-center font-mono text-xs border-r border-gray-100">
                                    {{ number_format($dep->saldo_awal) }}</td>
                                <td class="px-3 py-3 text-center font-mono text-xs border-r border-gray-100">
                                    {{ number_format($dep->tambahan_stok) }}</td>
                                <td class="px-3 py-3 text-center font-mono text-xs border-r border-gray-100">
                                    {{ number_format($dep->jumlah_digunakan) }}</td>
                                <td class="px-3 py-3 text-center font-mono text-xs border-r border-gray-100">
                                    {{ number_format($dep->jml_dibatalkan_rusak) }}</td>
                                <td class="px-3 py-3 text-center font-mono text-xs border-r border-gray-100">
                                    {{ number_format($dep->jml_dibatalkan_hilang) }}</td>
                                <td class="px-3 py-3 text-center border-r border-gray-100">
                                    <span
                                        class="font-mono font-bold text-xs {{ $dep->saldo_akhir == 0 ? 'text-gray-400' : 'text-gray-900' }}">
                                        {{ number_format($dep->saldo_akhir) }}
                                    </span>
                                </td>
                                <td class="px-3 py-3">
                                    @include('livewire.akunting._action-cell', ['laporan' => $dep])
                                </td>
                            @else
                                <td colspan="7" class="px-3 py-3 text-center text-gray-300 text-xs">Belum ada data
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══ MODAL REVISI ═══ --}}
    @if ($showRevisiModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-data
            x-on:keydown.escape.window="$wire.closeModal()">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md" @click.stop>

                {{-- Modal Header --}}
                <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">Minta Revisi Laporan</h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $revisiJenis }} · {{ $revisiCabang }}</p>
                    </div>
                    <button type="button" wire:click="closeModal"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="px-5 py-4">
                    <div class="mb-3 p-3 bg-orange-50 border border-orange-200 rounded-lg text-xs text-orange-800">
                        Catatan ini akan ditampilkan kepada PIC cabang saat mereka membuka form laporan.
                        Tuliskan dengan jelas apa yang perlu diperbaiki.
                    </div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Catatan Revisi <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="catatanRevisi" rows="4"
                        placeholder="Contoh: Saldo akhir tidak sesuai. Jumlah digunakan harap dicek kembali sesuai bukti pemakaian bulan ini..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                               focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none
                               @error('catatanRevisi') border-red-400 bg-red-50 @enderror">
                    </textarea>
                    @error('catatanRevisi')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400 mt-1">Minimal 10 karakter.</p>
                </div>

                {{-- Modal Footer --}}
                <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" wire:click="closeModal"
                        class="px-4 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="button" wire:click="submitRevisi" wire:loading.attr="disabled"
                        class="px-4 py-2 bg-orange-500 text-white text-sm font-medium rounded-lg
                               hover:bg-orange-600 transition-colors disabled:opacity-60">
                        <span wire:loading wire:target="submitRevisi">Mengirim...</span>
                        <span wire:loading.remove wire:target="submitRevisi">Kirim Permintaan Revisi</span>
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
