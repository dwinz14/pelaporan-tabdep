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
        $users = $this->userService->list(15, [
            'search' => $request->string('search')->toString(),
            'role'   => $request->string('role')->toString() ?: null,
        ]);

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
}
