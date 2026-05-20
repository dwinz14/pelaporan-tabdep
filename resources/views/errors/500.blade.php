<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Server Error</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center font-sans antialiased">
    <div class="text-center px-6">
        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h1 class="text-5xl font-bold text-gray-900 mb-2">500</h1>
        <h2 class="text-lg font-semibold text-gray-700 mb-3">Terjadi Kesalahan Server</h2>
        <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">
            Server mengalami kendala. Silakan coba lagi atau hubungi administrator.
        </p>
        <a href="/"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium
                   rounded-lg hover:bg-indigo-700 transition-colors">
            ← Kembali ke Beranda
        </a>
    </div>
</body>

</html>
