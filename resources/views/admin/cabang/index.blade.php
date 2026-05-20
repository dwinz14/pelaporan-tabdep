<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Toolbar --}}
    <div class="flex items-center justify-between mb-5 gap-4">
        <form method="GET" action="{{ route('admin.cabang.index') }}" class="flex gap-2">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari kode / nama cabang..."
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-64 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <button type="submit"
                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                Cari
            </button>
            @if ($search)
                <a href="{{ route('admin.cabang.index') }}"
                    class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">✕ Reset</a>
            @endif
        </form>

        <a href="{{ route('admin.cabang.create') }}"
            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Cabang
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">
                        Kode</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama
                        Cabang</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Alamat
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">
                        Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">
                        Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($cabangs as $cabang)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-gray-400 text-xs">
                            {{ $cabangs->firstItem() + $loop->index }}
                        </td>
                        <td class="px-4 py-3">
                            <span
                                class="font-mono font-semibold text-gray-900 text-sm">{{ $cabang->kode_cabang }}</span>
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $cabang->nama_cabang }}</td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $cabang->alamat ? \Illuminate\Support\Str::limit($cabang->alamat, 50) : '—' }}
                        </td>
                        <td class="px-4 py-3">
                            @if ($cabang->is_active)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.cabang.edit', $cabang) }}"
                                    class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>

                                <form method="POST" action="{{ route('admin.cabang.toggle', $cabang) }}"
                                    onsubmit="return confirm('{{ $cabang->is_active ? 'Nonaktifkan' : 'Aktifkan' }} cabang ini?')">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="text-xs font-medium {{ $cabang->is_active ? 'text-red-500 hover:text-red-700' : 'text-green-600 hover:text-green-800' }}">
                                        {{ $cabang->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-gray-400 text-sm">
                            @if ($search)
                                Tidak ada cabang yang cocok dengan pencarian <strong>"{{ $search }}"</strong>.
                            @else
                                Belum ada data cabang.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if ($cabangs->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 flex items-center justify-between">
                <p class="text-xs text-gray-500">
                    Menampilkan {{ $cabangs->firstItem() }}–{{ $cabangs->lastItem() }} dari {{ $cabangs->total() }}
                    cabang
                </p>
                {{ $cabangs->links() }}
            </div>
        @else
            <div class="px-4 py-3 border-t border-gray-100">
                <p class="text-xs text-gray-400">Total: {{ $cabangs->total() }} cabang</p>
            </div>
        @endif
    </div>

</x-app-layout>
