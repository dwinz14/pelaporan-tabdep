<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Diterima — SI Stok Tab-Dep</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center font-sans antialiased">

    <div class="w-full max-w-md px-4 text-center">

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="bg-slate-900 px-8 py-6">
                <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center mx-auto">
                    <span class="text-white font-bold text-lg">SI</span>
                </div>
            </div>

            <div class="px-8 py-8">
                {{-- Success Icon --}}
                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-5">
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h2 class="text-xl font-bold text-gray-900 mb-2">Pendaftaran Berhasil Dikirim!</h2>
                <p class="text-gray-500 text-sm mb-6">
                    Akun Anda sedang menunggu persetujuan dari Super Admin.
                    Anda akan dapat login setelah akun disetujui.
                </p>

                {{-- Steps --}}
                <div class="text-left space-y-3 mb-7">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Data Anda telah diterima</p>
                            <p class="text-xs text-gray-400">Formulir pendaftaran berhasil disubmit</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div
                            class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Menunggu persetujuan Admin</p>
                            <p class="text-xs text-gray-400">Super Admin akan mereview pendaftaran Anda</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div
                            class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-400">Akun aktif & siap login</p>
                            <p class="text-xs text-gray-400">Setelah disetujui, Anda bisa login</p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('login') }}"
                    class="w-full flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600
                       text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Kembali ke Halaman Login
                </a>
            </div>
        </div>

        <p class="text-xs text-gray-400 mt-4">
            Butuh bantuan? Hubungi Super Admin atau IT Support kantor Anda.
        </p>
    </div>

</body>

</html>
