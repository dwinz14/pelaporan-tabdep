<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class AuditController extends Controller
{
    public function index(Request $request): View
    {
        $query = Activity::with('causer')
            ->latest();

        // Filter: log_name
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->string('log_name')->toString());
        }

        // Filter: causer (user)
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->integer('user_id'))
                ->where('causer_type', 'App\\Models\\User');
        }

        // Filter: tanggal mulai
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->string('dari')->toString());
        }

        // Filter: tanggal selesai
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->string('sampai')->toString());
        }

        // Filter: keyword deskripsi
        if ($request->filled('keyword')) {
            $query->where('description', 'like', '%' . $request->string('keyword') . '%');
        }

        $logs  = $query->paginate(25)->withQueryString();
        $users = User::orderBy('name')->get(['id', 'name', 'nik']);

        $logNames = Activity::select('log_name')
            ->distinct()
            ->orderBy('log_name')
            ->pluck('log_name');

        return view('admin.audit.index', [
            'title'    => 'Audit Trail',
            'subtitle' => 'Log aktivitas seluruh pengguna sistem',
            'logs'     => $logs,
            'users'    => $users,
            'logNames' => $logNames,
            'filters'  => $request->only(['log_name', 'user_id', 'dari', 'sampai', 'keyword']),
        ]);
    }
}
