<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ImportTemplateExport;
use App\Http\Controllers\Controller;
use App\Services\ImportLaporanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    private const SESSION_KEY  = 'import_laporan_preview';
    private const TEMP_SUBDIR  = 'import_temp';

    public function __construct(
        private readonly ImportLaporanService $service,
    ) {}

    public function index(): View
    {
        $this->cleanTempFiles();

        return view('admin.import.index', [
            'title'    => 'Import Data Historis',
            'subtitle' => 'Import data pelaporan stok buku Tabungan dan Deposito',
            'preview'  => session(self::SESSION_KEY),
        ]);
    }

    public function downloadTemplate(): mixed
    {
        activity('import')
            ->causedBy(auth()->user())
            ->log('Mengunduh template import data historis');

        return Excel::download(new ImportTemplateExport(), 'Template_Import_Laporan_Historis.xlsx');
    }

    public function validateUpload(Request $request): RedirectResponse
    {
        $request->validate([
            'file_excel' => ['required', 'file', 'mimes:xlsx,xls', 'max:20480'], // 20MB
        ], [
            'file_excel.required' => 'File Excel wajib dipilih.',
            'file_excel.mimes'    => 'File harus berformat .xlsx atau .xls.',
            'file_excel.max'      => 'Ukuran file maksimal 20MB.',
        ]);

        $tempDir  = storage_path('app/' . self::TEMP_SUBDIR);
        if (! is_dir($tempDir)) mkdir($tempDir, 0755, true);

        $originalName = $request->file('file_excel')->getClientOriginalName();
        $tempName     = 'import_' . uniqid() . '.xlsx';
        $request->file('file_excel')->move($tempDir, $tempName);
        $tempPath = $tempDir . '/' . $tempName;

        try {
            $result = $this->service->parseAndValidate($tempPath);
        } catch (\Exception $e) {
            @unlink($tempPath);
            return redirect()->route('admin.import.index')
                ->with('import_error', 'Gagal membaca file: ' . $e->getMessage());
        }

        // File sudah cukup diparsing menjadi array — hapus file fisik
        @unlink($tempPath);

        if (! $result['sheet_found']) {
            return redirect()->route('admin.import.index')
                ->with('import_error', 'Sheet "Template Data" tidak ditemukan. Pastikan Anda menggunakan template resmi dan tidak mengubah nama sheet.');
        }

        if ($result['summary']['total'] === 0) {
            return redirect()->route('admin.import.index')
                ->with('import_error', 'Tidak ada data ditemukan pada sheet "Template Data". Pastikan data diisi mulai baris ke-2.');
        }

        session([
            self::SESSION_KEY => [
                'original_name' => $originalName,
                'rows'          => $result['rows'],
                'summary'       => $result['summary'],
                'validated_at'  => now()->toISOString(),
            ],
        ]);

        activity('import')
            ->causedBy(auth()->user())
            ->withProperties([
                'filename' => $originalName,
                'summary'  => $result['summary'],
            ])
            ->log("File diunggah & divalidasi: {$originalName} (Valid: {$result['summary']['ok']}, Dilewati: {$result['summary']['skip']}, Error: {$result['summary']['error']})");

        return redirect()->route('admin.import.index');
    }

    public function confirm(): RedirectResponse
    {
        $preview = session(self::SESSION_KEY);

        if (! $preview) {
            return redirect()->route('admin.import.index')
                ->with('import_error', 'Sesi pratinjau sudah kedaluwarsa. Silakan unggah ulang file.');
        }

        $okCount = count(array_filter($preview['rows'], fn($r) => $r['status'] === 'ok'));

        if ($okCount === 0) {
            return redirect()->route('admin.import.index')
                ->with('import_error', 'Tidak ada baris valid untuk diimport.');
        }

        activity('import')
            ->causedBy(auth()->user())
            ->withProperties(['filename' => $preview['original_name'], 'jumlah_baris' => $okCount])
            ->log("Import data historis dimulai dari: {$preview['original_name']} ({$okCount} baris)");

        $result = $this->service->commit($preview['rows'], auth()->user());

        session()->forget(self::SESSION_KEY);

        activity('import')
            ->causedBy(auth()->user())
            ->withProperties($result)
            ->log("Import selesai: {$result['created_laporan']} laporan dibuat, {$result['created_periode']} periode baru, {$result['skipped']} dilewati");

        return redirect()->route('admin.import.index')
            ->with('import_success', true)
            ->with('import_result', $result);
    }

    public function cancel(): RedirectResponse
    {
        session()->forget(self::SESSION_KEY);

        return redirect()->route('admin.import.index')
            ->with('import_info', 'Pratinjau dibatalkan. Tidak ada data yang disimpan.');
    }

    private function cleanTempFiles(): void
    {
        $dir = storage_path('app/' . self::TEMP_SUBDIR);
        if (! is_dir($dir)) return;

        foreach (glob($dir . '/*.xlsx') as $file) {
            if (filemtime($file) < time() - 3600) @unlink($file);
        }
    }
}
