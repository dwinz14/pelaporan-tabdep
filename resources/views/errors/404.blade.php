<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center font-sans antialiased">
    <div class="text-center px-6">
        <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h1 class="text-5xl font-bold text-gray-900 mb-2">404</h1>
        <h2 class="text-lg font-semibold text-gray-700 mb-3">Halaman Tidak Ditemukan</h2>
        <p class="text-sm text-gray-500 mb-6">
            Halaman yang Anda cari tidak ada atau telah dipindahkan.
        </p>
        @auth
            <a href="{{ route(auth()->user()->dashboardRoute()) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium
                       rounded-lg hover:bg-indigo-700 transition-colors">
                ← Kembali ke Dashboard
            </a>
        @else
            <a href="{{ route('login') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium
                       rounded-lg hover:bg-indigo-700 transition-colors">
                ← Ke Halaman Login
            </a>
        @endauth
    </div>
</body>

</html>
