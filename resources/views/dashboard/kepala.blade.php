<x-app-layout :title="$title" :subtitle="$subtitle">

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div
            class="bg-white rounded-lg border {{ $siapVerifikasi > 0 ? 'border-amber-300 bg-amber-50' : 'border-gray-200' }} p-5">
            <p
                class="text-xs {{ $siapVerifikasi > 0 ? 'text-amber-600' : 'text-gray-500' }} uppercase tracking-wide font-medium">
                Siap Verifikasi Final
            </p>
            <p class="text-3xl font-bold {{ $siapVerifikasi > 0 ? 'text-amber-700' : 'text-gray-400' }} mt-1">
                {{ $siapVerifikasi }}
            </p>
            <p class="text-xs {{ $siapVerifikasi > 0 ? 'text-amber-500' : 'text-gray-400' }} mt-0.5">periode menunggu
                persetujuan</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Belum Lengkap</p>
            <p class="text-3xl font-bold text-gray-400 mt-1">{{ $belumLengkap }}</p>
            <p class="text-xs text-gray-400 mt-0.5">periode belum semua cabang verified</p>
        </div>
        <div class="bg-white rounded-lg border border-emerald-200 bg-emerald-50 p-5">
            <p class="text-xs text-emerald-600 uppercase tracking-wide font-medium">Total Sudah Final</p>
            <p class="text-3xl font-bold text-emerald-700 mt-1">{{ $totalFinal }}</p>
            <p class="text-xs text-emerald-500 mt-0.5">periode terverifikasi</p>
        </div>
    </div>

    @if ($siapVerifikasi > 0)
        <div class="mb-5 p-4 bg-amber-50 border border-amber-200 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <p class="text-sm font-semibold text-amber-800">
                    Ada {{ $siapVerifikasi }} periode menunggu verifikasi final Anda.
                </p>
                <p class="text-xs text-amber-600 mt-0.5">Semua cabang sudah diverifikasi oleh akunting.</p>
            </div>
            <a href="{{ route('kepala.periode.index') }}"
                class="ml-auto px-4 py-2 bg-amber-600 text-white text-xs font-semibold rounded-lg hover:bg-amber-700 transition-colors flex-shrink-0">
                Verifikasi Sekarang →
            </a>
        </div>
    @endif

    <div class="bg-white rounded-lg border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">Menu</h3>
        <a href="{{ route('kepala.periode.index') }}"
            class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors max-w-sm">
            <div class="w-9 h-9 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900">Halaman Verifikasi Final</p>
                <p class="text-xs text-gray-500">{{ $siapVerifikasi }} periode siap difinalisasi</p>
            </div>
        </a>
    </div>

</x-app-layout>
