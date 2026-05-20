<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-5">
        <a href="{{ route('pic.dashboard') }}" class="hover:text-indigo-600 transition-colors">
            Dashboard
        </a>
        <span>›</span>
        <span class="text-gray-900 font-medium">Catat Transaksi</span>
    </div>

    {{-- Info Box --}}
    <div class="mb-5 p-4 bg-blue-50 border border-blue-200 rounded-xl">
        <p class="text-xs font-semibold text-blue-800 mb-1.5">📝 Tentang Fitur Pencatatan</p>
        <ul class="text-xs text-blue-700 space-y-1">
            <li>• Catat setiap transaksi buku tabungan/deposito saat kejadian — seperti catatan harian</li>
            <li>• Satu entri = satu kejadian spesifik (misal: 5 buku tabungan digunakan hari ini)</li>
            <li>• Data pencatatan bisa disinkronkan ke form laporan wajib secara otomatis</li>
            <li>• Pencatatan dapat diedit/dihapus selama laporan periode tersebut belum disubmit</li>
        </ul>
    </div>

    {{-- Livewire Component --}}
    <livewire:pic.pencatatan-manager />

</x-app-layout>
