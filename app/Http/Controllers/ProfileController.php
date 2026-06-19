<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        return view('profile.index', [
            'title'    => 'Profil Saya',
            'subtitle' => 'Kelola informasi akun Anda',
            'user'     => auth()->user(),
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $oldData = [
            'name'  => $user->name,
            'email' => $user->email,
        ];

        $user->update([
            'name'  => trim($request->name),
            'email' => $request->email ? trim($request->email) : null,
        ]);

        activity('profile')
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties([
                'old' => $oldData,
                'new' => [
                    'name'  => $user->fresh()->name,
                    'email' => $user->fresh()->email,
                ],
            ])
            ->log("{$user->name} memperbarui data profil");

        return redirect()->route('profile.index')
            ->with('success_profile', 'Data profil berhasil diperbarui.');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        activity('profile')
            ->performedOn($user)
            ->causedBy($user)
            ->log("{$user->name} mengganti password");

        // Regenerate session agar session lama invalid setelah ganti password
        $request->session()->regenerate();

        return redirect()->route('profile.index')
            ->with('success_password', 'Password berhasil diperbarui. Silakan login ulang jika diperlukan.');
    }
}
