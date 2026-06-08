<?php

namespace App\Http\Middleware;

use App\Enums\RegistrationStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Akun menunggu approval
        if ($user->registration_status === RegistrationStatus::Pending) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'nik' => 'Akun Anda masih menunggu persetujuan Super Admin.',
            ]);
        }

        // Akun ditolak
        if ($user->registration_status === RegistrationStatus::Rejected) {
            Auth::logout();
            $catatan = $user->catatan_penolakan
                ? " Alasan: {$user->catatan_penolakan}"
                : '';
            return redirect()->route('login')->withErrors([
                'nik' => "Pendaftaran Anda telah ditolak.{$catatan}",
            ]);
        }

        // Akun nonaktif oleh admin
        if (! $user->is_active) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'nik' => 'Akun Anda telah dinonaktifkan. Hubungi Super Admin.',
            ]);
        }

        return $next($request);
    }
}
