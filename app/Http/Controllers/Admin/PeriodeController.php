<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePeriodeRequest;
use App\Services\PeriodeLaporanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeriodeController extends Controller
{
    public function __construct(
        private readonly PeriodeLaporanService $service,
    ) {}

    public function index(Request $request): View
    {
        $periodes = $this->service->list(15, [
            'tahun'  => $request->integer('tahun') ?: null,
            'status' => $request->string('status')->toString() ?: null,
        ]);

        $tahunList = range(now()->year, 2021);

        return view('admin.periode.index', [
            'title'     => 'Manajemen Periode',
            'subtitle'  => 'Kelola periode pelaporan stok buku',
            'periodes'  => $periodes,
            'tahunList' => $tahunList,
            'tahun'     => $request->integer('tahun') ?: null,
            'status'    => $request->string('status')->toString(),
        ]);
    }

    public function create(): View
    {
        // Saran hari Jumat berikutnya
        $nextFriday = now()->next('Friday');

        return view('admin.periode.create', [
            'title'       => 'Generate Periode Baru',
            'subtitle'    => 'Manajemen Periode',
            'nextFriday'  => $nextFriday->format('Y-m-d'),
        ]);
    }

    public function store(StorePeriodeRequest $request): RedirectResponse
    {
        $periode = $this->service->generate($request->validated('tanggal_akhir'));

        return redirect()->route('admin.periode.index')
            ->with('success', "Periode \"{$periode->nama_periode}\" berhasil di-generate.");
    }
}
