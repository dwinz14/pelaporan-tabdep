<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\NotificationService;
use App\Models\Cabang;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;


class RegisteredUserController extends Controller
{

    public function create(): View
    {
        $cabangs = Cabang::where('is_active', true)
            ->orderBy('kode_cabang')
            ->get();

        return view('auth.register', [
            'cabangs' => $cabangs,
        ]);
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        User::create([
            'nik'                 => strtoupper(trim($request->nik)),
            'name'                => trim($request->name),
            'email'               => $request->email ? trim($request->email) : null,
            'password'            => Hash::make($request->password),
            'id_cabang'           => $request->id_cabang,
            'role'                => UserRole::PicCabang,
            'is_active'           => false,
            'registration_status' => RegistrationStatus::Pending,
            'registered_at'       => now(),
        ]);

        app(NotificationService::class)->notifyRegistrasiBaru($user);
        return redirect()->route('register.pending');
    }
}
