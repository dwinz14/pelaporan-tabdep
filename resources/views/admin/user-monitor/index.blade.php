<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Cek session driver --}}
    @if (config('session.driver') !== 'database')
        <div class="mb-5 p-4 bg-amber-50 border border-amber-300 rounded-xl flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
            <div>
                <p class="text-sm font-semibold text-amber-800">Fitur Sesi Aktif Tidak Tersedia</p>
                <p class="text-xs text-amber-700 mt-0.5">
                    Session driver saat ini: <code
                        class="bg-amber-100 px-1 rounded">{{ config('session.driver') }}</code>.
                    Fitur monitoring sesi aktif membutuhkan <code
                        class="bg-amber-100 px-1 rounded">SESSION_DRIVER=database</code> di file .env.
                    Jalankan <code class="bg-amber-100 px-1 rounded">php artisan session:table && php artisan
                        migrate</code> jika belum.
                </p>
            </div>
        </div>
    @endif

    <livewire:admin.user-monitor />

</x-app-layout>
