<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Breadcrumb + Info --}}
    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('pic.dashboard') }}" class="hover:text-indigo-600 transition-colors">
                Dashboard
            </a>
            <span>›</span>
            <span class="text-gray-900 font-medium">{{ $periode->nama_periode }}</span>
        </div>

        <div class="flex items-center gap-3">
            {{-- Status Periode --}}
            @if ($periode->isLocked())
                <span
                    class="flex items-center gap-1.5 text-xs bg-violet-100 text-violet-700 px-3 py-1.5 rounded-full font-medium">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                            clip-rule="evenodd" />
                    </svg>
                    Periode Sudah Final
                </span>
            @elseif($periode->is_current)
                <span
                    class="flex items-center gap-1.5 text-xs bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-full font-medium">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    Periode Aktif
                </span>
            @endif

            <span class="text-xs text-gray-400 font-mono">
                {{ $periode->tanggal_akhir->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </span>
        </div>
    </div>

    {{-- Info Box: Locked --}}
    @if ($periode->isLocked())
        <div class="mb-5 p-4 bg-violet-50 border border-violet-200 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 text-violet-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                    clip-rule="evenodd" />
            </svg>
            <div>
                <p class="text-sm font-semibold text-violet-800">Periode ini sudah diverifikasi final</p>
                <p class="text-xs text-violet-600 mt-0.5">
                    Data bersifat read-only. Tidak ada perubahan yang dapat dilakukan.
                </p>
            </div>
        </div>
    @endif

    {{-- Panduan --}}
    @if (!$periode->isLocked())
        <div class="mb-5 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-xs font-semibold text-blue-800 mb-1.5">📋 Cara Pencatatan</p>
            <ul class="text-xs text-blue-700 space-y-1">
                <li>• Perbarui angka setiap ada perubahan stok (penggunaan buku, penerimaan stok baru, dll.)</li>
                <li>• Klik <strong>Simpan Catatan</strong> untuk menyimpan perubahan — bisa dilakukan kapan saja</li>
                <li>• Klik <strong>Submit Laporan</strong> hanya saat waktu pelaporan wajib tiba</li>
                <li>• Saldo akhir dihitung otomatis — pastikan tidak bernilai negatif sebelum submit</li>
            </ul>
        </div>
    @endif

    {{-- Livewire Form Component --}}
    <livewire:pic.form-laporan :periode="$periode" />

</x-app-layout>
