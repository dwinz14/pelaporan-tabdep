<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Tabs --}}
    <div class="flex gap-1 mb-5 bg-gray-100 p-1 rounded-xl w-fit">
        <a href="{{ route('admin.registrasi.index', ['tab' => 'pending']) }}"
            class="flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-medium transition-all
                   {{ $tab === 'pending' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
            Menunggu Persetujuan
            @if ($pending->count() > 0)
                <span
                    class="w-5 h-5 bg-amber-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                    {{ $pending->count() }}
                </span>
            @endif
        </a>
        <a href="{{ route('admin.registrasi.index', ['tab' => 'history']) }}"
            class="flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-medium transition-all
                   {{ $tab === 'history' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
            Riwayat
        </a>
    </div>

    {{-- ═══ TAB: PENDING ═══ --}}
    @if ($tab === 'pending')

        @if ($pending->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-base font-semibold text-gray-700">Tidak ada pendaftaran yang menunggu</p>
                <p class="text-sm text-gray-400 mt-1">Semua pendaftaran sudah diproses.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($pending as $user)
                    <div x-data="{ showRejectModal: false }" class="bg-white rounded-xl border border-amber-200 overflow-hidden">

                        <div class="p-5">
                            <div class="flex items-start justify-between gap-4">

                                {{-- Avatar + Info --}}
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <span class="text-amber-700 font-bold text-lg">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs font-mono text-gray-500 mt-0.5">{{ $user->nik }}</p>
                                        @if ($user->email)
                                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                        @endif
                                        <div class="flex items-center gap-3 mt-2">
                                            @if ($user->cabang)
                                                <span
                                                    class="inline-flex items-center gap-1 text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-medium">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                                                    </svg>
                                                    {{ $user->cabang->kode_cabang }} — {{ $user->cabang->nama_cabang }}
                                                </span>
                                            @endif
                                            <span
                                                class="text-xs bg-sky-100 text-sky-700 px-2 py-0.5 rounded-full font-medium">
                                                PIC Cabang
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Waktu Daftar --}}
                                <div class="text-right flex-shrink-0">
                                    <p class="text-xs text-gray-400">Mendaftar</p>
                                    <p class="text-sm font-medium text-gray-700">
                                        {{ $user->registered_at->locale('id')->isoFormat('D MMM YYYY') }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        {{ $user->registered_at->locale('id')->isoFormat('HH:mm') }} WIB
                                    </p>
                                    <p class="text-xs text-amber-600 font-medium mt-1">
                                        {{ $user->registered_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center gap-3">
                                {{-- Approve --}}
                                <form method="POST" action="{{ route('admin.registrasi.approve', $user) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        onclick="return confirm('Setujui pendaftaran {{ $user->name }}?')"
                                        class="flex items-center gap-2 px-5 py-2 bg-emerald-600 text-white
                                               text-sm font-semibold rounded-lg hover:bg-emerald-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Setujui Pendaftaran
                                    </button>
                                </form>

                                {{-- Reject Button --}}
                                <button type="button" @click="showRejectModal = true"
                                    class="flex items-center gap-2 px-5 py-2 border-2 border-red-300 text-red-600
                                           text-sm font-semibold rounded-lg hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Tolak
                                </button>
                            </div>
                        </div>

                        {{-- ═══ MODAL TOLAK ═══ --}}
                        <div x-show="showRejectModal" x-transition
                            class="fixed inset-0 z-50 flex items-center justify-center p-4"
                            style="background: rgba(0,0,0,0.5);" x-on:keydown.escape.window="showRejectModal = false">

                            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md" @click.stop>

                                <div class="px-6 py-5 border-b border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">Tolak Pendaftaran</h3>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $user->name }}
                                                ({{ $user->nik }})</p>
                                        </div>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('admin.registrasi.reject', $user) }}">
                                    @csrf @method('PATCH')

                                    <div class="px-6 py-4">
                                        <div
                                            class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-xs text-red-700">
                                            Alasan penolakan akan ditampilkan kepada user saat mereka mencoba login.
                                            Tuliskan dengan jelas dan sopan.
                                        </div>

                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                            Alasan Penolakan <span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="catatan_penolakan" rows="4"
                                            placeholder="Contoh: NIK tidak ditemukan dalam data karyawan. Silakan hubungi HRD untuk verifikasi..."
                                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm
                                                   focus:outline-none focus:ring-2 focus:ring-red-500 resize-none
                                                   @error('catatan_penolakan') border-red-400 bg-red-50 @enderror">{{ old('catatan_penolakan') }}</textarea>
                                        @error('catatan_penolakan')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="text-xs text-gray-400 mt-1">Minimal 10 karakter</p>
                                    </div>

                                    <div class="px-6 pb-5 flex items-center justify-end gap-3">
                                        <button type="button" @click="showRejectModal = false"
                                            class="px-4 py-2 border border-gray-300 text-gray-700 text-sm
                                                   font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="px-5 py-2 bg-red-600 text-white text-sm font-semibold
                                                   rounded-lg hover:bg-red-700 transition-colors">
                                            Konfirmasi Tolak
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    @endif

    {{-- ═══ TAB: HISTORY ═══ --}}
    @if ($tab === 'history')

        @if ($history->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-10 text-center">
                <p class="text-sm text-gray-400">Belum ada riwayat registrasi.</p>
            </div>
        @else
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                User</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Cabang</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-36">
                                Mendaftar</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-36">
                                Status</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($history as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs font-mono text-gray-400">{{ $user->nik }}</p>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">
                                    @if ($user->cabang)
                                        <span
                                            class="font-mono text-gray-400 mr-1">{{ $user->cabang->kode_cabang }}</span>
                                        {{ $user->cabang->nama_cabang }}
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">
                                    {{ $user->registered_at->locale('id')->isoFormat('D MMM YYYY') }}
                                    <span class="text-gray-300">·</span>
                                    {{ $user->registered_at->format('H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $user->registration_status->badgeClass() }}">
                                        {{ $user->registration_status->label() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">
                                    @if ($user->isRejected() && $user->catatan_penolakan)
                                        <span
                                            class="text-red-600">{{ \Illuminate\Support\Str::limit($user->catatan_penolakan, 60) }}</span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($history->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200 flex items-center justify-between">
                        <p class="text-xs text-gray-500">{{ $history->total() }} riwayat</p>
                        {{ $history->links() }}
                    </div>
                @endif
            </div>
        @endif

    @endif

</x-app-layout>
