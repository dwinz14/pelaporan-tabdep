<?php

namespace App\Http\Controllers\Pic;

use App\Models\Laporan;
use App\Models\PeriodeLaporan;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function edit(PeriodeLaporan $periode): View
    {
        $user = auth()->user();

        abort_if(! $user->id_cabang, 403, 'Akun Anda tidak terdaftar di cabang manapun.');

        abort_if(
            ! Laporan::where('id_periode', $periode->id)
                ->where('id_cabang', $user->id_cabang)
                ->exists(),
            404,
            'Laporan tidak ditemukan untuk periode ini.'
        );

        return view('pic.laporan.edit', [
            'title'    => 'Catat Laporan',
            'subtitle' => $periode->nama_periode,
            'periode'  => $periode,
        ]);
    }

    public function riwayat(): View
    {
        $user = auth()->user();

        $periodes = PeriodeLaporan::whereHas(
            'laporans',
            fn($q) =>
            $q->where('id_cabang', $user->id_cabang)
        )
            ->orderBy('tanggal_akhir', 'desc')
            ->with([
                'laporans' => fn($q) =>
                $q->where('id_cabang', $user->id_cabang)
            ])
            ->paginate(15);

        return view('pic.riwayat.index', [
            'title'    => 'Riwayat Laporan',
            'subtitle' => 'Semua periode laporan ' . ($user->cabang?->nama_cabang ?? ''),
            'periodes' => $periodes,
        ]);
    }
}
