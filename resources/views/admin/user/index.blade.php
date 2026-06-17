<x-app-layout :title="$title" :subtitle="$subtitle">

    <div x-data="{ showResetAllModal: false, showDeactivateAllModal: false }">

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
                    <a href="{{ route('admin.user.index') }}"
                        class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">✕ Reset</a>
                @endif
            </form>

            <div class="flex items-center gap-2">

                {{-- Bulk: Reset Password Semua --}}
                <button type="button" @click="showResetAllModal = true"
                    {{ $bulkAffectedCount === 0 ? 'disabled' : '' }}
                    class="flex items-center gap-1.5 px-3 py-2 border text-xs font-semibold rounded-lg transition-colors
                       {{ $bulkAffectedCount === 0
                           ? 'border-gray-200 text-gray-300 cursor-not-allowed'
                           : 'border-amber-300 text-amber-700 hover:bg-amber-50' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset Password Semua
                    <span
                        class="px-1.5 py-0.5 rounded-full {{ $bulkAffectedCount === 0 ? 'bg-gray-100 text-gray-400' : 'bg-amber-100 text-amber-800' }}">
                        {{ $bulkAffectedCount }}
                    </span>
                </button>

                {{-- Bulk: Nonaktifkan Semua --}}
                <button type="button" @click="showDeactivateAllModal = true"
                    {{ $bulkAffectedCount === 0 ? 'disabled' : '' }}
                    class="flex items-center gap-1.5 px-3 py-2 border text-xs font-semibold rounded-lg transition-colors
                       {{ $bulkAffectedCount === 0
                           ? 'border-gray-200 text-gray-300 cursor-not-allowed'
                           : 'border-red-300 text-red-600 hover:bg-red-50' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    Nonaktifkan Semua
                    <span
                        class="px-1.5 py-0.5 rounded-full {{ $bulkAffectedCount === 0 ? 'bg-gray-100 text-gray-400' : 'bg-red-100 text-red-700' }}">
                        {{ $bulkAffectedCount }}
                    </span>
                </button>

                <a href="{{ route('admin.user.create') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah User
                </a>
            </div>
        </div>

        {{-- Info Filter Aktif --}}
        @if ($search || $selectedRole)
            <div
                class="mb-4 p-3 bg-indigo-50 border border-indigo-200 rounded-lg flex items-center gap-2 text-xs text-indigo-700">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter aktif:
                @if ($search)
                    <span
                        class="bg-white px-2 py-0.5 rounded-full border border-indigo-200 font-medium">"{{ $search }}"</span>
                @endif
                @if ($selectedRole)
                    <span class="bg-white px-2 py-0.5 rounded-full border border-indigo-200 font-medium">
                        {{ \App\Enums\UserRole::from($selectedRole)->label() }}
                    </span>
                @endif
                — aksi massal di bawah akan mengikuti filter ini.
            </div>
        @endif

        {{-- Table --}}
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">
                            No</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">
                            NIK</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Nama</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-40">
                            Role</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Cabang</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">
                            Status</th>
                        <th
                            class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-48">
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

                                    <form method="POST" action="{{ route('admin.user.reset-password', $user) }}"
                                        onsubmit="return confirm('Reset password {{ $user->name }} ke password default?')">
                                        @csrf
                                        <button type="submit"
                                            class="text-xs text-amber-600 hover:text-amber-800 font-medium">
                                            Reset PW
                                        </button>
                                    </form>

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

            @if ($users->hasPages())
                <div class="px-4 py-3 border-t border-gray-200 flex items-center justify-between">
                    <p class="text-xs text-gray-500">
                        Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }}
                        user
                    </p>
                    {{ $users->links() }}
                </div>
            @else
                <div class="px-4 py-3 border-t border-gray-100">
                    <p class="text-xs text-gray-400">Total: {{ $users->total() }} user</p>
                </div>
            @endif
        </div>


        {{-- ═══════════ MODAL: RESET PASSWORD SEMUA ═══════════ --}}
        <div x-show="showResetAllModal" x-cloak x-transition
            class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5);"
            x-on:keydown.escape.window="showResetAllModal = false">

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" @click.stop>

                <div class="px-6 py-5 border-b border-gray-100">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Reset Password Semua User</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Berdasarkan filter yang sedang aktif</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4">
                    {{-- Scope Filter --}}
                    <div class="flex flex-wrap gap-1.5 mb-3">
                        @if ($search)
                            <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full">Pencarian:
                                "{{ $search }}"</span>
                        @endif
                        @if ($selectedRole)
                            <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full">
                                Role: {{ \App\Enums\UserRole::from($selectedRole)->label() }}
                            </span>
                        @endif
                        @if (!$search && !$selectedRole)
                            <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full">Semua user (tanpa
                                filter)</span>
                        @endif
                    </div>

                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-800">
                        Password <strong>{{ $bulkAffectedCount }} user</strong> akan direset menjadi:
                        <code class="bg-white px-2 py-0.5 rounded font-mono font-semibold border border-amber-200">
                            {{ \App\Services\UserService::DEFAULT_PASSWORD }}
                        </code>
                    </div>

                    <p class="text-xs text-gray-400 mt-3">
                        Super Admin selalu dikecualikan dari aksi ini, apapun filter yang aktif.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.user.bulk-reset-password') }}">
                    @csrf
                    <input type="hidden" name="search" value="{{ $search }}">
                    <input type="hidden" name="role" value="{{ $selectedRole }}">

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                        <button type="button" @click="showResetAllModal = false"
                            class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-100 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2 bg-amber-600 text-white text-sm font-semibold rounded-lg hover:bg-amber-700 transition-colors">
                            Ya, Reset {{ $bulkAffectedCount }} Password
                        </button>
                    </div>
                </form>

            </div>
        </div>


        {{-- ═══════════ MODAL: NONAKTIFKAN SEMUA ═══════════ --}}
        <div x-show="showDeactivateAllModal" x-cloak x-transition
            class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5);"
            x-on:keydown.escape.window="showDeactivateAllModal = false">

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" @click.stop>

                <div class="px-6 py-5 border-b border-gray-100">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Nonaktifkan Semua User</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Berdasarkan filter yang sedang aktif</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <div class="flex flex-wrap gap-1.5 mb-3">
                        @if ($search)
                            <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full">Pencarian:
                                "{{ $search }}"</span>
                        @endif
                        @if ($selectedRole)
                            <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full">
                                Role: {{ \App\Enums\UserRole::from($selectedRole)->label() }}
                            </span>
                        @endif
                        @if (!$search && !$selectedRole)
                            <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full">Semua user (tanpa
                                filter)</span>
                        @endif
                    </div>

                    <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">
                        <strong>{{ $bulkAffectedCount }} user</strong> akan dinonaktifkan dan tidak dapat login
                        sampai diaktifkan kembali secara manual.
                    </div>

                    <p class="text-xs text-gray-400 mt-3">
                        Super Admin (termasuk akun Anda) selalu dikecualikan dari aksi ini, apapun filter yang aktif.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.user.bulk-deactivate') }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="search" value="{{ $search }}">
                    <input type="hidden" name="role" value="{{ $selectedRole }}">

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                        <button type="button" @click="showDeactivateAllModal = false"
                            class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-100 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                            Ya, Nonaktifkan {{ $bulkAffectedCount }} User
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

</x-app-layout>
