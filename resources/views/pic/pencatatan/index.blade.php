<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Breadcrumb & Info --}}
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('pic.dashboard') }}" class="hover:text-indigo-600 transition-colors">
                Dashboard
            </a>
            <span>›</span>
            <span class="text-gray-900 font-medium">Catat Transaksi</span>
        </div>

        {{-- Info Tooltip using Alpine --}}
        <div x-data="{ tooltip: false }" class="relative z-20">
            <button @mouseenter="tooltip = true" @mouseleave="tooltip = false" @click="tooltip = !tooltip" 
                class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-full hover:bg-indigo-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Panduan
            </button>

            <div x-show="tooltip" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-1"
                 style="display: none;"
                 class="absolute right-0 top-full mt-2 w-80 p-4 bg-white border border-gray-200 rounded-xl shadow-xl">
                <p class="text-xs font-semibold text-gray-800 mb-2">Tentang Fitur Pencatatan</p>
                <ul class="text-xs text-gray-600 space-y-1.5">
                    <li class="flex items-start gap-1.5"><span class="text-indigo-500 mt-0.5">•</span> <span>Catat setiap transaksi buku tabungan/deposito saat kejadian (seperti catatan harian)</span></li>
                    <li class="flex items-start gap-1.5"><span class="text-indigo-500 mt-0.5">•</span> <span>Satu entri = satu kejadian spesifik</span></li>
                    <li class="flex items-start gap-1.5"><span class="text-indigo-500 mt-0.5">•</span> <span>Data dapat disinkronkan ke laporan wajib otomatis</span></li>
                    <li class="flex items-start gap-1.5"><span class="text-indigo-500 mt-0.5">•</span> <span>Pencatatan bisa diedit/dihapus selama periode belum disubmit</span></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Livewire Component --}}
    <livewire:pic.pencatatan-manager />

</x-app-layout>
