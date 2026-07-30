<?php

namespace App\Http\Controllers\Akunting;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Services\MonitoringService;
use Illuminate\View\View;

class MonitoringController extends Controller
{
    public function __construct(
        private readonly MonitoringService $service,
    ) {}

    public function index(): View
    {
        return view('akunting.monitoring.index', [
            'title'     => 'Monitoring Stok Buku',
            'subtitle'  => 'Pantau pergerakan stok buku tabungan & deposito seluruh cabang',
            'total'     => $this->service->getTotalAgregat(),
            'cabangs'   => $this->service->getAllCabangOverview(),
            'updatedAt' => now()->locale('id')->isoFormat('D MMMM Y, HH:mm:ss'),
        ]);
    }

    public function show(Cabang $cabang): View
    {
        abort_if(! $cabang->is_active, 404, 'Cabang tidak aktif.');

        $detail = $this->service->getCabangDetail($cabang);

        return view('akunting.monitoring.show', [
            'title'    => 'Monitoring — ' . $cabang->nama_cabang,
            'subtitle' => 'Kode ' . $cabang->kode_cabang . ' · Pergerakan stok real-time',
            'detail'   => $detail,
        ]);
    }
}