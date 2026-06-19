<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\CabangService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService   $userService,
        private readonly CabangService $cabangService,
    ) {}

    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->string('search')->toString(),
            'role'   => $request->string('role')->toString() ?: null,
        ];

        $users = $this->userService->list(15, $filters);

        return view('admin.user.index', [
            'title'        => 'Manajemen User',
            'subtitle'     => 'Kelola akun pengguna sistem',
            'users'        => $users,
            'roles'        => UserRole::cases(),
            'selectedRole' => $request->string('role')->toString(),
            'search'       => $request->string('search')->toString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.user.create', [
            'title'    => 'Tambah User',
            'subtitle' => 'Manajemen User',
            'roles'    => UserRole::cases(),
            'cabangs'  => $this->cabangService->getAllActive(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->userService->create($request->validated());

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('admin.user.edit', [
            'title'    => 'Edit User',
            'subtitle' => $user->nik . ' — ' . $user->name,
            'user'     => $user,
            'roles'    => UserRole::cases(),
            'cabangs'  => $this->cabangService->getAllActive(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->userService->update($user, $request->validated());

        return redirect()->route('admin.user.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function toggleActive(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.user.index')
                ->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $this->userService->toggleActive($user);
        $status = $user->fresh()->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.user.index')
            ->with('success', "User {$user->name} berhasil {$status}.");
    }

    public function resetPassword(User $user): RedirectResponse
    {
        $this->userService->resetPassword($user);

        return redirect()->route('admin.user.index')
            ->with('success', "Password {$user->name} berhasil direset ke: «" . UserService::DEFAULT_PASSWORD . "»");
    }

    public function bulkResetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $count = $this->userService->bulkResetPassword($request->user_ids);

        return redirect()->route('admin.user.index', $request->only(['search', 'role']))
            ->with('success', $count > 0
                ? "Password berhasil direset untuk {$count} user menjadi: «" . UserService::DEFAULT_PASSWORD . "»"
                : 'Tidak ada user yang sesuai untuk direset (Super Admin dikecualikan).');
    }

    public function bulkDeactivate(Request $request): RedirectResponse
    {
        $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $count = $this->userService->bulkDeactivate($request->user_ids);

        return redirect()->route('admin.user.index', $request->only(['search', 'role']))
            ->with('success', $count > 0
                ? "{$count} user berhasil dinonaktifkan."
                : 'Tidak ada user yang sesuai untuk dinonaktifkan (Super Admin dikecualikan).');
    }

    public function bulkActivate(Request $request): RedirectResponse
    {
        $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $count = $this->userService->bulkActivate($request->user_ids);

        return redirect()->route('admin.user.index', $request->only(['search', 'role']))
            ->with('success', $count > 0
                ? "{$count} user berhasil diaktifkan."
                : 'Tidak ada user yang sesuai untuk diaktifkan (Super Admin dikecualikan).');
    }
}
