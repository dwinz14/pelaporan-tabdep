<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Cabang Aktif</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalCabang }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Total User</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalUser }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Total Periode</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalPeriode }}</p>
        </div>
        <div class="bg-white rounded-lg border border-indigo-200 bg-indigo-50 p-5">
            <p class="text-xs text-indigo-600 uppercase tracking-wide font-medium">Periode Aktif</p>
            @if ($periodeAktif)
                <p class="text-sm font-bold text-indigo-900 mt-1 leading-tight">{{ $periodeAktif->nama_periode }}</p>
            @else
                <p class="text-sm font-bold text-gray-400 mt-1">Belum ada</p>
            @endif
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-5">

        {{-- Quick Actions --}}
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Aksi Cepat</h3>
            <div class="space-y-2">
                @foreach ([['route' => 'admin.cabang.index', 'label' => 'Kelola Cabang', 'sub' => $totalCabang . ' cabang aktif'], ['route' => 'admin.user.index', 'label' => 'Kelola User', 'sub' => $totalUser . ' user aktif'], ['route' => 'admin.periode.create', 'label' => 'Generate Periode', 'sub' => 'Buat periode laporan baru'], ['route' => 'admin.audit.index', 'label' => 'Audit Trail', 'sub' => 'Log aktivitas sistem']] as $item)
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div>
                            <p class="text-xs font-medium text-gray-800">{{ $item['label'] }}</p>
                            <p class="text-xs text-gray-400">{{ $item['sub'] }}</p>
                        </div>
                        <span class="text-gray-300 text-sm">›</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Saldo Terkini per Cabang --}}
        <div class="md:col-span-2 bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Saldo Terkini per Cabang</h3>
                <p class="text-xs text-gray-400">Periode terakhir</p>
            </div>
            @if ($saldoTerkini->isEmpty())
                <div class="p-8 text-center text-xs text-gray-400">Belum ada data laporan.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Cabang</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">Saldo Tabungan</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">Saldo Deposito</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($saldoTerkini as $row)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2.5">
                                        <span
                                            class="font-mono text-xs font-semibold text-gray-500 mr-1">{{ $row['cabang']->kode_cabang }}</span>
                                        <span class="text-xs text-gray-700">{{ $row['cabang']->nama_cabang }}</span>
                                    </td>
                                    <td class="px-4 py-2.5 text-right">
                                        <span class="font-mono text-xs font-semibold text-gray-900">
                                            {{ number_format($row['saldo_tab']) }}
                                        </span>
                                        @if ($row['status_tab'])
                                            <span
                                                class="ml-1 text-xs {{ $row['status_tab']->badgeClass() }} px-1 py-0.5 rounded">
                                                {{ $row['status_tab']->label() }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2.5 text-right">
                                        <span class="font-mono text-xs font-semibold text-gray-900">
                                            {{ number_format($row['saldo_dep']) }}
                                        </span>
                                        @if ($row['status_dep'])
                                            <span
                                                class="ml-1 text-xs {{ $row['status_dep']->badgeClass() }} px-1 py-0.5 rounded">
                                                {{ $row['status_dep']->label() }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>

</x-app-layout>
