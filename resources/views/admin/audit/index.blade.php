<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Filter --}}
    <div class="bg-white rounded-lg border border-gray-200 p-4 mb-5">
        <form method="GET" action="{{ route('admin.audit.index') }}" class="grid grid-cols-2 md:grid-cols-5 gap-3">

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Kategori Log</label>
                <select name="log_name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua</option>
                    @foreach ($logNames as $ln)
                        <option value="{{ $ln }}" {{ ($filters['log_name'] ?? '') === $ln ? 'selected' : '' }}>
                            {{ ucfirst($ln) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">User</label>
                <select name="user_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua User</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}"
                            {{ ($filters['user_id'] ?? '') == $u->id ? 'selected' : '' }}>
                            {{ $u->nik }} — {{ $u->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ $filters['dari'] ?? '' }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ $filters['sampai'] ?? '' }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Keyword</label>
                <input type="text" name="keyword" value="{{ $filters['keyword'] ?? '' }}"
                    placeholder="Cari deskripsi..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="md:col-span-5 flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    Filter
                </button>
                @if (array_filter($filters))
                    <a href="{{ route('admin.audit.index') }}"
                        class="px-4 py-2 border border-gray-300 text-gray-600 text-xs rounded-lg hover:bg-gray-50 transition-colors">
                        ✕ Reset Filter
                    </a>
                    <span class="text-xs text-gray-400 self-center ml-2">
                        {{ $logs->total() }} log ditemukan
                    </span>
                @endif
            </div>
        </form>
    </div>

    {{-- Log Table --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-36">
                        Waktu</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">
                        Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Deskripsi</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-40">
                        Dilakukan Oleh</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">
                        Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 transition-colors" x-data="{ showProps: false }">
                        <td class="px-4 py-3">
                            <p class="text-xs font-mono text-gray-700">
                                {{ $log->created_at->format('d/m/Y') }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $log->created_at->format('H:i:s') }}
                            </p>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $badgeColor = match ($log->log_name) {
                                    'laporan' => 'bg-blue-100 text-blue-700',
                                    'periode' => 'bg-indigo-100 text-indigo-700',
                                    'export' => 'bg-emerald-100 text-emerald-700',
                                    default => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                                {{ $log->log_name ?? '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-800">
                            {{ $log->description }}
                        </td>
                        <td class="px-4 py-3">
                            @if ($log->causer)
                                <p class="text-xs font-medium text-gray-800">{{ $log->causer->name }}</p>
                                <p class="text-xs font-mono text-gray-400">{{ $log->causer->nik }}</p>
                            @else
                                <span class="text-xs text-gray-400">Sistem</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if ($log->properties && $log->properties->isNotEmpty())
                                <button type="button" @click="showProps = !showProps"
                                    class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                    <span x-text="showProps ? 'Tutup' : 'Lihat'"></span>
                                </button>
                                <div x-show="showProps" x-transition
                                    class="mt-2 p-2 bg-gray-50 rounded text-xs font-mono text-gray-600 border border-gray-200 max-w-xs overflow-x-auto">
                                    @foreach ($log->properties as $key => $val)
                                        <div><span class="font-semibold">{{ $key }}:</span>
                                            {{ is_array($val) ? json_encode($val) : $val }}</div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-400">
                            Tidak ada log aktivitas yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($logs->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 flex items-center justify-between">
                <p class="text-xs text-gray-500">
                    Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} log
                </p>
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
