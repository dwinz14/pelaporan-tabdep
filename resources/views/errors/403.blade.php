<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center font-sans antialiased">
    <div class="text-center px-6">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
        </div>
        <h1 class="text-5xl font-bold text-gray-900 mb-2">403</h1>
        <h2 class="text-lg font-semibold text-gray-700 mb-3">Akses Ditolak</h2>
        <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">
            {{ $exception?->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}
        </p>
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium
                   rounded-lg hover:bg-indigo-700 transition-colors">
            ← Kembali
        </a>
    </div>
</body>

</html>
