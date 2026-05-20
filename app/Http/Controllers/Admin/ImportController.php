<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\HistorisImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function index(): View
    {
        return view('admin.import.index', [
            'title'    => 'Import Data Historis',
            'subtitle' => 'Upload file Excel laporan periode sebelumnya',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file_excel' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:10240', // 10MB
            ],
        ], [
            'file_excel.required' => 'File Excel wajib dipilih.',
            'file_excel.mimes'    => 'File harus berformat .xlsx atau .xls.',
            'file_excel.max'      => 'Ukuran file maksimal 10MB.',
        ]);

        try {
            $import = new HistorisImport();
            Excel::import($import, $request->file('file_excel'));

            activity('import')
                ->causedBy(auth()->user())
                ->withProperties([
                    'created' => $import->created,
                    'skipped' => $import->skipped,
                    'errors'  => count($import->errors),
                ])
                ->log("Import data historis: {$import->created} laporan dibuat, {$import->skipped} dilewati");

            $message = "Import selesai: {$import->created} laporan berhasil diimport, {$import->skipped} dilewati.";

            if (! empty($import->errors)) {
                $message .= ' Ada ' . count($import->errors) . ' peringatan.';
            }

            return redirect()->route('admin.import.index')
                ->with('import_success', true)
                ->with('import_created', $import->created)
                ->with('import_skipped', $import->skipped)
                ->with('import_log', $import->log)
                ->with('import_errors', $import->errors);
        } catch (\Exception $e) {
            return redirect()->route('admin.import.index')
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }
}
