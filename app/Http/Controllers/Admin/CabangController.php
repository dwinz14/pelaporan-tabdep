<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCabangRequest;
use App\Http\Requests\Admin\UpdateCabangRequest;
use App\Models\Cabang;
use App\Services\CabangService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CabangController extends Controller
{
    public function __construct(
        private readonly CabangService $service,
    ) {}

    public function index(Request $request): View
    {
        $cabangs = $this->service->list(
            perPage: 15,
            search: $request->string('search')->toString(),
        );

        return view('admin.cabang.index', [
            'title'   => 'Manajemen Cabang',
            'subtitle' => 'Kelola data kantor cabang',
            'cabangs' => $cabangs,
            'search'  => $request->string('search')->toString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.cabang.create', [
            'title'    => 'Tambah Cabang',
            'subtitle' => 'Manajemen Cabang',
        ]);
    }

    public function store(StoreCabangRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.cabang.index')
            ->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function edit(Cabang $cabang): View
    {
        return view('admin.cabang.edit', [
            'title'    => 'Edit Cabang',
            'subtitle' => $cabang->kode_cabang . ' — ' . $cabang->nama_cabang,
            'cabang'   => $cabang,
        ]);
    }

    public function update(UpdateCabangRequest $request, Cabang $cabang): RedirectResponse
    {
        $this->service->update($cabang, $request->validated());

        return redirect()->route('admin.cabang.index')
            ->with('success', 'Data cabang berhasil diperbarui.');
    }

    public function toggleActive(Cabang $cabang): RedirectResponse
    {
        $this->service->toggleActive($cabang);

        $updated = $cabang->fresh();
        $status  = $updated->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.cabang.index')
            ->with('success', "Cabang {$cabang->nama_cabang} berhasil {$status}.");
    }
}
