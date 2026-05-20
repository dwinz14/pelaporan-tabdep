<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PencatatanController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        abort_if(! $user->id_cabang, 403, 'Akun Anda tidak terdaftar di cabang manapun.');

        return view('pic.pencatatan.index', [
            'title'    => 'Catat Transaksi',
            'subtitle' => 'Pencatatan stok buku ' . ($user->cabang?->nama_cabang ?? ''),
        ]);
    }
}
