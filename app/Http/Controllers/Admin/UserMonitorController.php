<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class UserMonitorController extends Controller
{
    public function index(): View
    {
        return view('admin.user-monitor.index', [
            'title'    => 'Monitoring User',
            'subtitle' => 'Pantau sesi aktif dan aktivitas seluruh pengguna',
        ]);
    }

    public function show(\App\Models\User $user): View
    {
        return view('admin.user-monitor.show', [
            'title'    => 'Detail Log Aktivitas',
            'subtitle' => 'Log aktivitas untuk user: ' . $user->name,
            'user'     => $user,
        ]);
    }
}
