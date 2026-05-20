<?php

namespace App\Imports;

use App\Enums\JenisLaporan;
use App\Enums\StatusOperasional;
use App\Enums\StatusVerifikasi;
use App\Models\Cabang;
use App\Models\Laporan;
use App\Models\PeriodeLaporan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class HistorisImport implements WithMultipleSheets
{
    public array  $log     = [];
    public array  $errors  = [];
    public int    $created = 0;
    public int    $skipped = 0;

    private ?User $defaultAkunting;

    public function __construct()
    {
        // Cari user akunting pertama sebagai verified_by default
        $this->defaultAkunting = User::where('role', 'akunting')
            ->where('is_active', true)
            ->first();
    }

    public function sheets(): array
    {
        return [
            0 => new class($this) implements ToCollection {
                public function __construct(private HistorisImport $parent) {}
                public function collection(Collection $rows)
                {
                    $this->parent->processSheet($rows, JenisLaporan::Tabungan);
                }
            },
            1 => new class($this) implements ToCollection {
                public function __construct(private HistorisImport $parent) {}
                public function collection(Collection $rows)
                {
                    $this->parent->processSheet($rows, JenisLaporan::Deposito);
                }
            },
        ];
    }

    public function processSheet(Collection $rows, JenisLaporan $jenis): void
    {
        DB::transaction(function () use ($rows, $jenis) {
            // ── Deteksi kode cabang dari baris header ─────────
            // Cari baris yang berisi kode numerik cabang (101, 102, dst)
            $headerRow     = null;
            $headerRowIdx  = null;
            $periodeDates  = [];

            foreach ($rows as $idx => $row) {
                $rowArr = $row->toArray();

                // Header periode: baris yang cell pertamanya adalah tanggal/label periode
                // Data header cabang: baris yang mengandung kode cabang numerik
                $numericCount = collect($rowArr)->filter(
                    fn($v) =>
                    is_numeric($v) && $v >= 100 && $v <= 999
                )->count();

                if ($numericCount >= 3) {
                    $headerRow    = $rowArr;
                    $headerRowIdx = $idx;
                    break;
                }
            }

            if (! $headerRow) {
                $this->errors[] = "Sheet {$jenis->label()}: Baris header kode cabang tidak ditemukan.";
                return;
            }

            // Mapping: kolom index → kode_cabang
            $cabangMap = [];
            foreach ($headerRow as $colIdx => $val) {
                if (is_numeric($val) && $val >= 100 && $val <= 999) {
                    $cabangMap[$colIdx] = (string)(int)$val;
                }
            }

            if (empty($cabangMap)) {
                $this->errors[] = "Sheet {$jenis->label()}: Tidak ada kode cabang valid ditemukan di header.";
                return;
            }

            // ── Parse blok data per periode ───────────────────
            // Setelah header row, data diorganisir dalam blok:
            // [Tanggal Periode] [Saldo Awal] [Tambahan] [Digunakan] [Batal Rusak] [Batal Hilang] [Saldo Akhir]
            $dataRows    = $rows->slice($headerRowIdx + 1)->values();
            $periodeData = [];
            $current     = null;

            $fieldMap = [
                0 => 'saldo_awal',
                1 => 'tambahan_stok',
                2 => 'jumlah_digunakan',
                3 => 'jml_dibatalkan_rusak',
                4 => 'jml_dibatalkan_hilang',
                5 => 'saldo_akhir',
            ];

            $fieldCounter = 0;

            foreach ($dataRows as $row) {
                $rowArr = $row->toArray();
                $first  = trim((string)($rowArr[0] ?? ''));

                // Deteksi apakah ini baris tanggal periode
                // Cell pertama berisi string tanggal atau "Per DD MMMM YYYY"
                if ($this->isTanggalPeriode($first)) {
                    $tanggal = $this->parseTanggal($first);
                    if ($tanggal) {
                        $current      = $tanggal;
                        $fieldCounter = 0;
                        $periodeData[$current] = [];
                    }
                    continue;
                }

                // Baris data (saldo awal, tambahan, dst)
                if ($current && isset($fieldMap[$fieldCounter])) {
                    $field = $fieldMap[$fieldCounter];

                    foreach ($cabangMap as $colIdx => $kode) {
                        $val = $rowArr[$colIdx] ?? 0;
                        $periodeData[$current][$kode][$field] = max(0, (int)$val);
                    }

                    $fieldCounter++;
                }
            }

            // ── Insert ke database ────────────────────────────
            foreach ($periodeData as $tanggal => $cabangData) {
                if (empty($cabangData)) continue;

                // Cari atau buat periode
                $tgl     = Carbon::parse($tanggal);
                $periode = PeriodeLaporan::firstOrCreate(
                    ['tanggal_akhir' => $tgl->format('Y-m-d')],
                    [
                        'nama_periode'       => 'Periode ' . $tgl->locale('id')->isoFormat('D MMMM Y'),
                        'status_operasional' => StatusOperasional::Verified,
                        'tgl_verifikasi_operasional' => now(),
                        'verified_by_operasional'    => $this->defaultAkunting?->id,
                        'is_current'                 => false,
                    ]
                );

                foreach ($cabangData as $kode => $fields) {
                    $cabang = Cabang::where('kode_cabang', $kode)->first();

                    if (! $cabang) {
                        $this->errors[] = "Cabang kode {$kode} tidak ditemukan di database. Baris dilewati.";
                        $this->skipped++;
                        continue;
                    }

                    // Cek apakah laporan sudah ada
                    $exists = Laporan::where('id_cabang', $cabang->id)
                        ->where('id_periode', $periode->id)
                        ->where('jenis', $jenis)
                        ->exists();

                    if ($exists) {
                        $this->skipped++;
                        continue;
                    }

                    Laporan::create([
                        'id_cabang'              => $cabang->id,
                        'id_periode'             => $periode->id,
                        'jenis'                  => $jenis,
                        'saldo_awal'             => $fields['saldo_awal']            ?? 0,
                        'tambahan_stok'          => $fields['tambahan_stok']         ?? 0,
                        'jumlah_digunakan'       => $fields['jumlah_digunakan']      ?? 0,
                        'jml_dibatalkan_rusak'   => $fields['jml_dibatalkan_rusak']  ?? 0,
                        'jml_dibatalkan_hilang'  => $fields['jml_dibatalkan_hilang'] ?? 0,
                        'saldo_akhir'            => $fields['saldo_akhir']           ?? 0,
                        'status_verifikasi'      => StatusVerifikasi::VerifiedAccounting,
                        'tgl_submit'             => now(),
                        'tgl_verifikasi_akunting' => now(),
                        'verified_by_akunting'   => $this->defaultAkunting?->id,
                    ]);

                    $this->created++;
                }

                $this->log[] = "✓ Periode {$periode->nama_periode} ({$jenis->label()}): " . count($cabangData) . " cabang diproses.";
            }
        });
    }

    // ─── Helpers ──────────────────────────────────────────────

    private function isTanggalPeriode(string $val): bool
    {
        if (empty($val)) return false;

        // Pattern: "Per 24 April 2026" atau "24 April 2026" atau tanggal Excel
        if (preg_match('/\d{1,2}\s+\w+\s+\d{4}/', $val)) return true;
        if (preg_match('/per\s+\d{1,2}/i', $val)) return true;
        if (is_numeric($val) && $val > 40000 && $val < 50000) return true; // Excel date serial

        return false;
    }

    private function parseTanggal(string $val): ?string
    {
        try {
            // Hapus prefix "Per " atau "per "
            $clean = preg_replace('/^per\s+/i', '', trim($val));

            // Jika numeric (Excel date serial)
            if (is_numeric($val)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$val))
                    ->format('Y-m-d');
            }

            // Coba parse langsung
            Carbon::setLocale('id');
            $date = Carbon::parse($clean);
            return $date->format('Y-m-d');
        } catch (\Exception) {
            return null;
        }
    }
}
