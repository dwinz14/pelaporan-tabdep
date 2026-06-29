<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePeriodeRequest;
use App\Models\PeriodeLaporan;
use App\Services\PeriodeLaporanService;
use Carbon\Carbon;
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
        $suggestion = $this->service->getSuggestedNextPeriode();

        return view('admin.periode.index', [
            'title'     => 'Manajemen Periode',
            'subtitle'  => 'Kelola periode pelaporan stok buku',
            'periodes'  => $periodes,
            'tahunList' => $tahunList,
            'tahun'     => $request->integer('tahun') ?: null,
            'status'    => $request->string('status')->toString(),
            'suggestion' => $suggestion,
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

    /**
     * Generate periode dari saran
     */
    public function generateSuggested(Request $request): RedirectResponse
    {
        $request->validate([
            'tanggal_akhir' => [
                'required',
                'date',
                'unique:periode_laporans,tanggal_akhir',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->dayOfWeek !== Carbon::FRIDAY) {
                        $fail('Tanggal periode harus hari Jumat.');
                    }
                },
            ],
        ]);

        // Verifikasi bahwa tanggal yang dikirim memang adalah saran yang valid
        $suggestion = $this->service->getSuggestedNextPeriode();

        if (! $suggestion || $suggestion['tanggal'] !== $request->tanggal_akhir) {
            return redirect()->route('admin.periode.index')
                ->with('error', 'Saran periode tidak valid atau sudah tidak berlaku.');
        }

        $periode = $this->service->generate($request->tanggal_akhir);

        return redirect()->route('admin.periode.index')
            ->with('success', "Periode \"{$periode->nama_periode}\" berhasil di-generate dari saran otomatis.");
    }

    /**
     * Detail periode: pivot data laporan semua cabang — read-only untuk admin.
     */
    public function show(PeriodeLaporan $periode): View
    {
        return view('admin.periode.show', [
            'title'    => $periode->nama_periode,
            'subtitle' => 'Detail laporan seluruh cabang',
            'periode'  => $periode,
        ]);
    }
}
