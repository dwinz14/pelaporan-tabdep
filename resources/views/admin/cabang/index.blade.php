<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- ═══ HEADER & PAGE INFO ═══ --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-950 tracking-tight">Kelola Data Kantor Cabang</h1>
            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
                <span
                    class="inline-flex items-center justify-center px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md font-bold font-mono border border-slate-200/60 shadow-xs">
                    {{ $cabangs->total() }}
                </span>
                Total cabang terdaftar di dalam sistem
            </p>
        </div>

        {{-- Primary Action --}}
        <a href="{{ route('admin.cabang.create') }}"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-md shadow-indigo-100 focus:outline-none focus:ring-4 focus:ring-indigo-500/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Cabang
        </a>
    </div>

    {{-- ═══ FILTER & SEARCH BAR ═══ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4 mb-6">
        <form method="GET" action="{{ route('admin.cabang.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div
                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Cari kode atau nama cabang..."
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 text-xs font-medium text-slate-800 placeholder-slate-400 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all">
            </div>

            <div class="flex items-center gap-2 self-end sm:self-auto">
                <button type="submit"
                    class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition-colors focus:outline-none focus:ring-4 focus:ring-slate-200">
                    Cari Data
                </button>
                @if ($search)
                    <a href="{{ route('admin.cabang.index') }}"
                        class="inline-flex items-center gap-1 px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">
                        ✕ Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ═══ MAIN DATA TABLE ═══ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200">
                        <th
                            class="px-6 py-4 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest w-12 text-center">
                            No</th>
                        <th class="px-6 py-4 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest w-36">
                            Kode Cabang</th>
                        <th class="px-6 py-4 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">Nama
                            Cabang</th>
                        <th class="px-6 py-4 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">Alamat
                        </th>
                        <th
                            class="px-6 py-4 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest w-32 text-center">
                            Status</th>
                        <th
                            class="px-6 py-4 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest w-48 text-right">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($cabangs as $index => $cabang)
                        <tr class="hover:bg-slate-50/50 transition-colors duration-150 group">
                            {{-- No --}}
                            <td class="px-6 py-4.5 text-center text-xs font-mono font-medium text-slate-400">
                                {{ $cabangs->firstItem() + $index }}
                            </td>

                            {{-- Kode Cabang --}}
                            <td class="px-6 py-4.5 align-middle">
                                <span
                                    class="inline-flex items-center justify-center px-2.5 py-1 bg-indigo-50 border border-indigo-100 text-indigo-700 rounded-lg text-xs font-bold font-mono shadow-2xs">
                                    {{ $cabang->kode_cabang }}
                                </span>
                            </td>

                            {{-- Nama Cabang --}}
                            <td class="px-6 py-4.5 align-middle">
                                <p
                                    class="text-xs font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">
                                    {{ $cabang->nama_cabang }}
                                </p>
                            </td>

                            {{-- Kontak & Alamat --}}
                            <td class="px-6 py-4.5 align-middle">
                                <div class="max-w-md">
                                    <div class="flex items-center gap-1 text-slate-400 mb-1">
                                    </div>
                                    <p
                                        class="text-xs text-slate-500 line-clamp-1 group-hover:line-clamp-none transition-all duration-300">
                                        {{ $cabang->alamat ?: 'Tidak ada alamat terdaftar' }}
                                    </p>
                                </div>
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4.5 text-center align-middle">
                                @if ($cabang->is_active)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 border border-emerald-200 text-emerald-700 shadow-3xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-50 border border-slate-200 text-slate-400 shadow-3xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4.5 align-middle text-right">
                                <div class="inline-flex items-center gap-3">
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.cabang.edit', $cabang) }}"
                                        class="inline-flex items-center gap-1 text-xs font-bold text-slate-600 hover:text-indigo-600 bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 hover:border-indigo-100 px-3 py-1.5 rounded-lg transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h14a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>

                                    {{-- Toggle Status --}}
                                    <form action="{{ route('admin.cabang.toggle', $cabang) }}" method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin mengubah status keaktifan cabang ini?')">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center text-xs font-bold px-3 py-1.5 rounded-lg border transition-all {{ $cabang->is_active ? 'text-rose-600 bg-rose-50 border-rose-100 hover:bg-rose-100' : 'text-emerald-600 bg-emerald-50 border-emerald-100 hover:bg-emerald-100' }}">
                                            {{ $cabang->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- Empty State Terintegrasi --}}
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="max-w-md mx-auto">
                                    <div
                                        class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-800 mb-1">
                                        @if ($search)
                                            Cabang Tidak Ditemukan
                                        @else
                                            Belum Ada Data Cabang
                                        @endif
                                    </h3>
                                    <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                                        @if ($search)
                                            Tidak ada hasil yang cocok dengan kata kunci
                                            <strong>"{{ $search }}"</strong>. Periksa kembali ejaan Anda.
                                        @else
                                            Silakan tambahkan data cabang baru untuk memulai pencatatan dan pengelolaan
                                            operasional.
                                        @endif
                                    </p>
                                    @if ($search)
                                        <a href="{{ route('admin.cabang.index') }}"
                                            class="inline-flex px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition-colors">
                                            Tampilkan Semua Cabang
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ═══ PAGINATION & FOOTER ═══ --}}
        @if ($cabangs->hasPages())
            <div
                class="px-6 py-4 bg-slate-50/50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs font-medium text-slate-500">
                    Menampilkan <span class="font-bold text-slate-800">{{ $cabangs->firstItem() }}</span>–<span
                        class="font-bold text-slate-800">{{ $cabangs->lastItem() }}</span> dari <span
                        class="font-bold text-slate-800">{{ $cabangs->total() }}</span> cabang
                </p>
                <div class="pagination-custom-container">
                    {{ $cabangs->links() }}
                </div>
            </div>
        @else
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-200/60">
                <p class="text-xs font-medium text-slate-400">Total: {{ $cabangs->total() }} cabang terdaftar</p>
            </div>
        @endif
    </div>

</x-app-layout>
