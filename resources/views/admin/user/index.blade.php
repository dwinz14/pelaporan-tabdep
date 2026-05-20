<x-app-layout :title="$title" :subtitle="$subtitle">

    {{-- Toolbar --}}
    <div class="flex items-center justify-between mb-5 gap-4 flex-wrap">
        <form method="GET" action="{{ route('admin.user.index') }}" class="flex gap-2 flex-wrap">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari NIK / nama..."
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-52 focus:outline-none focus:ring-2 focus:ring-indigo-500">

            <select name="role"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->value }}" {{ $selectedRole === $role->value ? 'selected' : '' }}>
                        {{ $role->label() }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                Filter
            </button>
            @if ($search || $selectedRole)
                <a href="{{ route('admin.user.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">✕
                    Reset</a>
            @endif
        </form>

        <a href="{{ route('admin.user.create') }}"
            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah User
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">No
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">
                        NIK</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-40">
                        Role</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cabang
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">
                        Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-48">
                        Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-gray-400 text-xs">
                            {{ $users->firstItem() + $loop->index }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-mono text-xs font-semibold text-gray-800">{{ $user->nik }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $user->name }}</p>
                            @if ($user->email)
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $roleColor = match ($user->role) {
                                    \App\Enums\UserRole::PicCabang => 'bg-sky-100 text-sky-800',
                                    \App\Enums\UserRole::Akunting => 'bg-emerald-100 text-emerald-800',
                                    \App\Enums\UserRole::KepalaOperasional => 'bg-amber-100 text-amber-800',
                                    \App\Enums\UserRole::SuperAdmin => 'bg-rose-100 text-rose-800',
                                };
                            @endphp
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $roleColor }}">
                                {{ $user->roleLabel() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-sm">
                            @if ($user->cabang)
                                <span
                                    class="font-mono text-xs mr-1 text-gray-400">{{ $user->cabang->kode_cabang }}</span>
                                {{ $user->cabang->nama_cabang }}
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if ($user->is_active)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.user.edit', $user) }}"
                                    class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>

                                {{-- Reset Password --}}
                                <form method="POST" action="{{ route('admin.user.reset-password', $user) }}"
                                    onsubmit="return confirm('Reset password {{ $user->name }} ke password default?')">
                                    @csrf
                                    <button type="submit"
                                        class="text-xs text-amber-600 hover:text-amber-800 font-medium">
                                        Reset PW
                                    </button>
                                </form>

                                {{-- Toggle Aktif --}}
                                @if ($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.user.toggle', $user) }}"
                                        onsubmit="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} user ini?')">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="text-xs font-medium {{ $user->is_active ? 'text-red-500 hover:text-red-700' : 'text-green-600 hover:text-green-800' }}">
                                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-gray-400 text-sm">
                            Tidak ada user ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if ($users->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 flex items-center justify-between">
                <p class="text-xs text-gray-500">
                    Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} user
                </p>
                {{ $users->links() }}
            </div>
        @else
            <div class="px-4 py-3 border-t border-gray-100">
                <p class="text-xs text-gray-400">Total: {{ $users->total() }} user</p>
            </div>
        @endif
    </div>

</x-app-layout>
