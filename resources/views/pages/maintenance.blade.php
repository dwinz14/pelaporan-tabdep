<x-app-layout>
    <div class="min-h-[80vh] flex items-center justify-center bg-gray-50 px-6 py-12">
        <div class="max-w-2xl w-full bg-white border border-gray-200 rounded-2xl shadow-sm p-10 text-center">

            {{-- Icon --}}
            <div class="mx-auto flex items-center justify-center w-20 h-20 rounded-full bg-indigo-100 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-indigo-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M12 8v4m0 4h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3z" />
                </svg>
            </div>

            {{-- Title --}}
            <h1 class="text-3xl font-bold text-gray-900 mb-3">
                Halaman Belum Tersedia
            </h1>

            {{-- Description --}}
            <p class="text-gray-600 leading-relaxed mb-8">
                Maaf, halaman yang Anda akses saat ini masih dalam tahap perbaikan/pengembangan.
                Kami sedang menyiapkan fitur terbaik agar dapat segera digunakan.
            </p>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/') }}"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition duration-200">
                    Kembali ke Beranda
                </a>

                <button onclick="history.back()"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-100 transition duration-200">
                    Kembali
                </button>
            </div>

            {{-- Footer Note --}}
            <div class="mt-10 pt-6 border-t border-gray-100">
                <p class="text-sm text-gray-400">
                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
