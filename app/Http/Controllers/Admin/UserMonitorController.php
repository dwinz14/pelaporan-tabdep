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
            'subtitle' => 'Pantau sesi aktif dan log aktivitas seluruh pengguna',
        ]);
    }
}
