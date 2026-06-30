<x-app-layout :title="$title" :subtitle="$subtitle">

    <div x-data="{
        showResetAllModal: false,
        showDeactivateAllModal: false,
        showActivateAllModal: false,
        showResetPasswordModal: false,
        showToggleModal: false,
        selectedUser: { action: '' },
        selectedUsers: [],
        selectAll: false,
        toggleAll() {
            if (this.selectAll) {
                this.selectedUsers = {{ $users->pluck('id')->toJson() }};
            } else {
                this.selectedUsers = [];
            }
        }
    }" x-init="$watch('selectedUsers', val => { selectAll = val.length === {{ $users->count() }} && val.length > 0 })" class="relative pb-24">

        {{-- ═══ TOOLBAR & FILTERS ═══ --}}
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">

            <form method="GET" action="{{ route('admin.user.index') }}"
                class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                {{-- Search Box --}}
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari NIK atau Nama..."
                        class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                </div>

                {{-- Role Filter --}}
                <div class="relative w-full sm:w-48">
                    <select name="role"
                        class="w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm appearance-none cursor-pointer">
                        <option value="">Semua Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->value }}" {{ $selectedRole === $role->value ? 'selected' : '' }}>
                                {{ $role->label() }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="submit"
                        class="w-full sm:w-auto px-5 py-2.5 bg-slate-900 text-white rounded-xl text-sm font-semibold hover:bg-slate-800 transition-colors shadow-sm whitespace-nowrap">
                        Terapkan
                    </button>
                    @if ($search || $selectedRole)
                        <a href="{{ route('admin.user.index') }}"
                            class="w-full sm:w-auto px-4 py-2.5 text-sm font-medium text-slate-500 hover:text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors text-center whitespace-nowrap">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <a href="{{ route('admin.user.create') }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-md shadow-indigo-200 hover:bg-indigo-700 transition-all flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Tambah User
            </a>
        </div>

        {{-- Info Filter Aktif --}}
        @if ($search || $selectedRole)
            <div class="mb-5 flex flex-wrap items-center gap-2 text-sm">
                <span class="text-slate-500 font-medium text-xs uppercase tracking-wider mr-1">Filter Aktif:</span>
                @if ($search)
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-semibold">
                        Pencarian: "{{ $search }}"
                    </span>
                @endif
                @if ($selectedRole)
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-semibold">
                        Role: {{ \App\Enums\UserRole::from($selectedRole)->label() }}
                    </span>
                @endif
            </div>
        @endif

        {{-- ═══ DATA TABLE ═══ --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden relative">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100">
                            <th class="px-6 py-4 w-12 text-center">
                                <input type="checkbox" x-model="selectAll" @change="toggleAll"
                                    class="w-4 h-4 rounded border-slate-500 text-indigo-600 focus:ring-indigo-500 cursor-pointer transition-colors">
                            </th>
                            <th class="px-4 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-16">No</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">User Info
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-40">Role
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Cabang</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-24">Status
                            </th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right w-40">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" :value="{{ $user->id }}" x-model.number="selectedUsers"
                                        class="w-4 h-4 rounded border-slate-400 text-indigo-600 focus:ring-indigo-500 cursor-pointer transition-colors">
                                </td>
                                <td class="px-4 py-4 text-slate-400 font-medium">
                                    {{ $users->firstItem() + $loop->index }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        {{-- Avatar Initials --}}
                                        <div
                                            class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center flex-shrink-0 border border-indigo-100">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p
                                                class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">
                                                {{ $user->name }}</p>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span
                                                    class="font-mono text-xs font-semibold text-slate-500">{{ $user->nik }}</span>
                                                @if ($user->email)
                                                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $roleColor = match ($user->role) {
                                            \App\Enums\UserRole::PicCabang => 'bg-sky-50 text-sky-700 border-sky-200',
                                            \App\Enums\UserRole::Akunting
                                                => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            \App\Enums\UserRole::KepalaOperasional
                                                => 'bg-amber-50 text-amber-700 border-amber-200',
                                            \App\Enums\UserRole::SuperAdmin
                                                => 'bg-rose-50 text-rose-700 border-rose-200',
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold border {{ $roleColor }}">
                                        {{ $user->roleLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($user->cabang)
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">{{ $user->cabang->kode_cabang }}</span>
                                            <span
                                                class="text-slate-700 font-medium">{{ $user->cabang->nama_cabang }}</span>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic text-xs">Pusat</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($user->is_active)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-slate-50 text-slate-600 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div
                                        class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">

                                        <a href="{{ route('admin.user.edit', $user) }}"
                                            class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors tooltip"
                                            title="Edit Profil">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>

                                        <button type="button"
                                            data-user="{{ json_encode(['id' => $user->id, 'name' => $user->name, 'action' => route('admin.user.reset-password', $user)]) }}"
                                            @click="selectedUser = JSON.parse($el.dataset.user); showResetPasswordModal = true"
                                            class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors tooltip"
                                            title="Reset Password">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                            </svg>
                                        </button>

                                        @if ($user->id !== auth()->id())
                                            <button type="button"
                                                data-user="{{ json_encode(['id' => $user->id, 'name' => $user->name, 'isActive' => $user->is_active, 'action' => route('admin.user.toggle', $user)]) }}"
                                                @click="selectedUser = JSON.parse($el.dataset.user); showToggleModal = true"
                                                class="p-2 text-slate-400 {{ $user->is_active ? 'hover:text-rose-600 hover:bg-rose-50' : 'hover:text-emerald-600 hover:bg-emerald-50' }} rounded-lg transition-colors tooltip"
                                                title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} User">
                                                @if ($user->is_active)
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @endif
                                            </button>
                                        @else
                                            <div class="w-8"></div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-slate-800 font-bold mb-1">User Tidak Ditemukan</h3>
                                        <p class="text-slate-500 text-sm">Tidak ada data pengguna yang cocok dengan
                                            filter pencarian Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($users->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $users->links() }}
                </div>
            @else
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <p class="text-sm font-medium text-slate-500">Total: <span
                            class="font-bold text-slate-800">{{ $users->total() }}</span> pengguna sistem</p>
                </div>
            @endif
        </div>


        {{-- ═══ FLOATING BULK ACTION BAR ═══ --}}
        <div x-show="selectedUsers.length > 0" x-cloak x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-y-full opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-full opacity-0"
            class="fixed bottom-8 left-1/2 -translate-x-1/2 z-40 bg-slate-900 border border-slate-700 shadow-2xl rounded-2xl px-5 py-3 flex items-center gap-5">

            <div class="flex items-center gap-3 text-white">
                <span class="flex items-center justify-center w-6 h-6 bg-indigo-500 rounded-full text-xs font-bold"
                    x-text="selectedUsers.length"></span>
                <span class="text-sm font-medium">User Terpilih</span>
            </div>

            <div class="w-px h-6 bg-slate-700"></div>

            <div class="flex items-center gap-2">
                <button type="button" @click="showResetAllModal = true"
                    class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-sm font-medium border border-slate-700 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    Reset PW
                </button>
                <button type="button" @click="showDeactivateAllModal = true"
                    class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-sm font-medium border border-slate-700 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    Nonaktifkan
                </button>
                <button type="button" @click="showActivateAllModal = true"
                    class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-sm font-medium border border-slate-700 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Aktifkan
                </button>
            </div>

            <button @click="selectAll = false; toggleAll()"
                class="ml-2 text-slate-400 hover:text-white p-1 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>


        {{-- ═══════════ MODAL: RESET PASSWORD SEMUA ═══════════ --}}
        <div x-show="showResetAllModal" x-cloak x-transition
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
            style="background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px);"
            x-on:keydown.escape.window="showResetAllModal = false">

            <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden relative border border-white/50"
                @click.stop x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-8">

                <div class="px-8 pt-8 pb-6 text-center">
                    <div
                        class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-inner">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Reset Password Massal</h3>
                    <p class="text-sm text-slate-500 mb-5">Password untuk <strong x-text="selectedUsers.length"
                            class="text-slate-800"></strong> user terpilih akan direset kembali menjadi default.</p>

                    <div
                        class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 text-center">
                        Password Default: <code
                            class="text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded font-mono">{{ \App\Services\UserService::DEFAULT_PASSWORD }}</code>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.user.bulk-reset-password') }}">
                    @csrf
                    <input type="hidden" name="search" value="{{ $search }}">
                    <input type="hidden" name="role" value="{{ $selectedRole }}">
                    <template x-for="id in selectedUsers" :key="id">
                        <input type="hidden" name="user_ids[]" :value="id">
                    </template>

                    <div class="px-8 pb-8 flex flex-col-reverse sm:flex-row items-center gap-3">
                        <button type="button" @click="showResetAllModal = false"
                            class="w-full sm:w-1/2 px-4 py-2.5 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300">
                            Batal
                        </button>
                        <button type="submit"
                            class="w-full sm:w-1/2 px-4 py-2.5 rounded-xl font-bold text-white bg-amber-500 hover:bg-amber-600 shadow-lg shadow-amber-200 transition-all focus:outline-none focus:ring-4 focus:ring-amber-100">
                            Ya, Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ═══════════ MODAL: NONAKTIFKAN SEMUA ═══════════ --}}
        <div x-show="showDeactivateAllModal" x-cloak x-transition
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
            style="background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px);"
            x-on:keydown.escape.window="showDeactivateAllModal = false">

            <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden relative border border-white/50"
                @click.stop x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-8">

                <div class="px-8 pt-8 pb-6 text-center">
                    <div
                        class="w-16 h-16 bg-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-inner">
                        <svg class="w-8 h-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Nonaktifkan User</h3>
                    <p class="text-sm text-slate-500 mb-5">Anda akan mencabut hak akses login untuk <strong
                            x-text="selectedUsers.length" class="text-slate-800"></strong> user yang dipilih secara
                        massal.</p>

                    <div
                        class="bg-rose-50 text-rose-700 border border-rose-100 rounded-xl px-4 py-3 text-xs font-semibold text-center flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Super Admin tidak akan terpengaruh aksi ini.
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.user.bulk-deactivate') }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="search" value="{{ $search }}">
                    <input type="hidden" name="role" value="{{ $selectedRole }}">
                    <template x-for="id in selectedUsers" :key="id">
                        <input type="hidden" name="user_ids[]" :value="id">
                    </template>

                    <div class="px-8 pb-8 flex flex-col-reverse sm:flex-row items-center gap-3">
                        <button type="button" @click="showDeactivateAllModal = false"
                            class="w-full sm:w-1/2 px-4 py-2.5 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300">
                            Batal
                        </button>
                        <button type="submit"
                            class="w-full sm:w-1/2 px-4 py-2.5 rounded-xl font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-lg shadow-rose-200 transition-all focus:outline-none focus:ring-4 focus:ring-rose-100">
                            Ya, Nonaktifkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ═══════════ MODAL: AKTIFKAN SEMUA ═══════════ --}}
        <div x-show="showActivateAllModal" x-cloak x-transition
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
            style="background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px);"
            x-on:keydown.escape.window="showActivateAllModal = false">

            <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden relative border border-white/50"
                @click.stop x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-8">

                <div class="px-8 pt-8 pb-6 text-center">
                    <div
                        class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-inner">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Aktifkan Kembali User</h3>
                    <p class="text-sm text-slate-500 mb-5">Anda akan memulihkan akses login untuk <strong
                            x-text="selectedUsers.length" class="text-slate-800"></strong> user yang telah dipilih.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.user.bulk-activate') }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="search" value="{{ $search }}">
                    <input type="hidden" name="role" value="{{ $selectedRole }}">
                    <template x-for="id in selectedUsers" :key="id">
                        <input type="hidden" name="user_ids[]" :value="id">
                    </template>

                    <div class="px-8 pb-8 flex flex-col-reverse sm:flex-row items-center gap-3">
                        <button type="button" @click="showActivateAllModal = false"
                            class="w-full sm:w-1/2 px-4 py-2.5 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300">
                            Batal
                        </button>
                        <button type="submit"
                            class="w-full sm:w-1/2 px-4 py-2.5 rounded-xl font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all focus:outline-none focus:ring-4 focus:ring-emerald-100">
                            Ya, Aktifkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ═══════════ MODAL: RESET PASSWORD ═══════════ --}}
        <div x-show="showResetPasswordModal" x-cloak x-transition
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
            style="background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px);"
            x-on:keydown.escape.window="showResetPasswordModal = false">

            <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden relative border border-white/50"
                @click.stop x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-8">

                <div class="px-8 pt-8 pb-6 text-center">
                    <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-inner">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Reset Password</h3>
                    <p class="text-sm text-slate-500 mb-5">Password untuk user <strong x-text="selectedUser?.name" class="text-slate-800"></strong> akan direset kembali menjadi default.</p>

                    <div class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 text-center">
                        Password Default: <code class="text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded font-mono">{{ \App\Services\UserService::DEFAULT_PASSWORD }}</code>
                    </div>
                </div>

                <form method="POST" :action="selectedUser?.action">
                    @csrf
                    <div class="px-8 pb-8 flex flex-col-reverse sm:flex-row items-center gap-3">
                        <button type="button" @click="showResetPasswordModal = false"
                            class="w-full sm:w-1/2 px-4 py-2.5 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300">
                            Batal
                        </button>
                        <button type="submit"
                            class="w-full sm:w-1/2 px-4 py-2.5 rounded-xl font-bold text-white bg-amber-500 hover:bg-amber-600 shadow-lg shadow-amber-200 transition-all focus:outline-none focus:ring-4 focus:ring-amber-100">
                            Ya, Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ═══════════ MODAL: AKTIFKAN / NONAKTIFKAN USER ═══════════ --}}
        <div x-show="showToggleModal" x-cloak x-transition
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
            style="background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px);"
            x-on:keydown.escape.window="showToggleModal = false">

            <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden relative border border-white/50"
                @click.stop x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-8">

                <div class="px-8 pt-8 pb-6 text-center">
                    <div x-show="selectedUser?.isActive" class="w-16 h-16 bg-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-inner">
                        <svg class="w-8 h-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <div x-show="!selectedUser?.isActive" class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-inner">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2" x-text="selectedUser?.isActive ? 'Nonaktifkan User' : 'Aktifkan User'"></h3>
                    <p class="text-sm text-slate-500 mb-5">
                        <span x-text="selectedUser?.isActive ? 'Nonaktifkan user ' + selectedUser?.name + '? User tidak dapat login sampai diaktifkan kembali.' : 'Aktifkan kembali user ' + selectedUser?.name + '? User dapat login kembali setelah diaktifkan.'"></span>
                    </p>
                </div>

                <form method="POST" :action="selectedUser?.action">
                    @csrf @method('PATCH')
                    <div class="px-8 pb-8 flex flex-col-reverse sm:flex-row items-center gap-3">
                        <button type="button" @click="showToggleModal = false"
                            class="w-full sm:w-1/2 px-4 py-2.5 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300">
                            Batal
                        </button>
                        <button type="submit"
                            :class="{
                                'bg-rose-600 hover:bg-rose-700 shadow-lg shadow-rose-200 focus:ring-rose-100': selectedUser?.isActive,
                                'bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-200 focus:ring-emerald-100': !selectedUser?.isActive
                            }"
                            class="w-full sm:w-1/2 px-4 py-2.5 rounded-xl font-bold text-white transition-all focus:outline-none focus:ring-4"
                            x-text="selectedUser?.isActive ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan'">
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-app-layout>
