<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrasiController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->string('tab')->toString() ?: 'pending';

        $pending = User::with('cabang')
            ->where('registration_status', RegistrationStatus::Pending)
            ->where('is_active', false)
            ->orderBy('registered_at', 'asc')
            ->get();

        $history = User::with('cabang')
            ->whereIn('registration_status', [
                RegistrationStatus::Approved->value,
                RegistrationStatus::Rejected->value,
            ])
            ->whereNotNull('registered_at')
            ->orderBy('registered_at', 'desc')
            ->paginate(20);

        return view('admin.registrasi.index', [
            'title'    => 'Persetujuan Registrasi',
            'subtitle' => 'Kelola pendaftaran akun baru dari cabang',
            'pending'  => $pending,
            'history'  => $history,
            'tab'      => $tab,
        ]);
    }

    public function approve(User $user): RedirectResponse
    {
        abort_if(! $user->isPending(), 422, 'Akun ini bukan dalam status pending.');

        $user->update([
            'is_active'           => true,
            'registration_status' => RegistrationStatus::Approved,
            'catatan_penolakan'   => null,
        ]);

        activity('registrasi')
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log("Registrasi {$user->name} ({$user->nik}) disetujui");

        return redirect()->route('admin.registrasi.index')
            ->with('success', "Akun {$user->name} ({$user->nik}) berhasil disetujui. User kini dapat login.");
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        abort_if(! $user->isPending(), 422, 'Akun ini bukan dalam status pending.');

        $request->validate([
            'catatan_penolakan' => ['required', 'string', 'min:10', 'max:500'],
        ], [
            'catatan_penolakan.required' => 'Alasan penolakan wajib diisi.',
            'catatan_penolakan.min'      => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $user->update([
            'is_active'           => false,
            'registration_status' => RegistrationStatus::Rejected,
            'catatan_penolakan'   => trim($request->catatan_penolakan),
        ]);

        activity('registrasi')
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties(['alasan' => $request->catatan_penolakan])
            ->log("Registrasi {$user->name} ({$user->nik}) ditolak");

        return redirect()->route('admin.registrasi.index')
            ->with('success', "Pendaftaran {$user->name} berhasil ditolak.");
    }
}
