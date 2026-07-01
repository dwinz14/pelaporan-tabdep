<?php

namespace App\Services;

use App\Enums\JenisLaporan;
use App\Enums\StatusOperasional;
use App\Enums\StatusVerifikasi;
use App\Models\Cabang;
use App\Models\Laporan;
use App\Models\PeriodeLaporan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ImportLaporanService
{
    private const SHEET_NAME = 'Template Data';

    /**
     * Baca file Excel dan validasi seluruh baris.
     * Mengembalikan array baris ber-status: ok | skip | error.
     */
    public function parseAndValidate(string $filePath): array
    {
        $rawRows = $this->readSheet($filePath);

        if ($rawRows === null) {
            return [
                'sheet_found' => false,
                'rows'        => [],
                'summary'     => ['total' => 0, 'ok' => 0, 'skip' => 0, 'error' => 0],
            ];
        }

        $cabangMap = Cabang::all()->keyBy(fn($c) => strtoupper(trim($c->kode_cabang)));

        // ─── Tahap 1: Validasi field per baris ─────────────────
        $parsed = [];
        $seenInFile = []; // untuk deteksi duplikat dalam file: "tanggal|kode|jenis"

        foreach ($rawRows as $line => $row) {
            $result = $this->validateRow($row, $line, $cabangMap, $seenInFile);
            if ($result === null) continue; // baris kosong, skip diam-diam
            $parsed[] = $result;

            if ($result['status'] !== 'error') {
                $key = $result['tanggal']->format('Y-m-d') . '|' . $result['kode_cabang'] . '|' . $result['jenis'];
                $seenInFile[$key] = true;
            }
        }

        // ─── Tahap 2: Cek duplikat terhadap DB + hitung saldo berantai ─
        $valid = array_filter($parsed, fn($r) => $r['status'] === 'ok_pending');

        $grouped = [];
        foreach ($valid as $idx => $row) {
            $groupKey = $row['kode_cabang'] . '|' . $row['jenis'];
            $grouped[$groupKey][] = $idx;
        }

        foreach ($grouped as $indices) {
            // urutkan ascending berdasarkan tanggal
            usort($indices, fn($a, $b) => $valid[$a]['tanggal']->timestamp <=> $valid[$b]['tanggal']->timestamp);

            $sample      = $valid[$indices[0]];
            $cabang      = $cabangMap[$sample['kode_cabang']];
            $jenisEnum   = JenisLaporan::from($sample['jenis']);

            $runningSaldo = null;

            foreach ($indices as $idx) {
                $row = $valid[$idx];

                // Cek apakah sudah ada di DB
                $existingPeriode = PeriodeLaporan::where('tanggal_akhir', $row['tanggal']->format('Y-m-d'))->first();
                $existingLaporan = $existingPeriode
                    ? Laporan::where('id_periode', $existingPeriode->id)
                    ->where('id_cabang', $cabang->id)
                    ->where('jenis', $jenisEnum)
                    ->first()
                    : null;

                if ($existingLaporan) {
                    $parsed[$idx]['status']  = 'skip';
                    $parsed[$idx]['message'] = 'Sudah ada di sistem — dilewati (tidak ditimpa).';
                    $runningSaldo = $existingLaporan->saldo_akhir;
                    continue;
                }

                // Tentukan saldo awal
                if ($row['saldo_awal_input'] !== null) {
                    $saldoAwal = $row['saldo_awal_input'];
                } elseif ($runningSaldo !== null) {
                    $saldoAwal = $runningSaldo;
                } else {
                    // baseline dari DB: periode dengan tanggal < baris ini
                    $prevLaporan = Laporan::where('id_cabang', $cabang->id)
                        ->where('jenis', $jenisEnum)
                        ->whereHas('periode', fn($q) => $q->where('tanggal_akhir', '<', $row['tanggal']->format('Y-m-d')))
                        ->join('periode_laporans', 'laporans.id_periode', '=', 'periode_laporans.id')
                        ->orderBy('periode_laporans.tanggal_akhir', 'desc')
                        ->select('laporans.*')
                        ->first();

                    $saldoAwal = $prevLaporan?->saldo_akhir ?? 0;
                }

                $saldoAkhir = $saldoAwal + $row['tambahan'] - $row['digunakan'] - $row['rusak'] - $row['hilang'];

                if ($saldoAkhir < 0) {
                    $parsed[$idx]['status']  = 'error';
                    $parsed[$idx]['message'] = "Saldo akhir negatif ({$saldoAkhir}). Periksa kembali angka pada baris ini.";
                    // jangan lanjutkan chain dengan nilai negatif — hentikan warisan untuk baris setelahnya di grup ini
                    $runningSaldo = null;
                    continue;
                }

                $parsed[$idx]['status']      = 'ok';
                $parsed[$idx]['saldo_awal']  = $saldoAwal;
                $parsed[$idx]['saldo_akhir'] = $saldoAkhir;
                $parsed[$idx]['message']     = null;

                $runningSaldo = $saldoAkhir;
            }
        }

        // Bersihkan field internal yang tidak perlu ditampilkan
        $rows = array_map(function ($r) {
            unset($r['saldo_awal_input']);
            return $r;
        }, $parsed);

        $summary = [
            'total' => count($rows),
            'ok'    => count(array_filter($rows, fn($r) => $r['status'] === 'ok')),
            'skip'  => count(array_filter($rows, fn($r) => $r['status'] === 'skip')),
            'error' => count(array_filter($rows, fn($r) => $r['status'] === 'error')),
        ];

        return [
            'sheet_found' => true,
            'rows'        => array_values($rows),
            'summary'     => $summary,
        ];
    }

    /**
     * Eksekusi commit: buat PeriodeLaporan (jika belum ada) + Laporan untuk semua baris berstatus 'ok'.
     */
    public function commit(array $rows, User $actor): array
    {
        $okRows = array_values(array_filter($rows, fn($r) => $r['status'] === 'ok'));

        if (empty($okRows)) {
            return ['created_laporan' => 0, 'created_periode' => 0, 'skipped' => 0];
        }

        // Urutkan ascending berdasarkan tanggal agar periode dibuat berurutan
        usort(
            $okRows,
            fn($a, $b) =>
            Carbon::parse($a['tanggal_iso'])->timestamp <=> Carbon::parse($b['tanggal_iso'])->timestamp
        );

        $createdLaporan = 0;
        $createdPeriode = 0;
        $skipped        = 0;

        DB::transaction(function () use ($okRows, $actor, &$createdLaporan, &$createdPeriode, &$skipped) {
            $cabangMap = Cabang::all()->keyBy(fn($c) => strtoupper(trim($c->kode_cabang)));

            foreach ($okRows as $row) {
                $tanggal = Carbon::parse($row['tanggal_iso'])->format('Y-m-d');
                $cabang  = $cabangMap[$row['kode_cabang']] ?? null;

                if (! $cabang) {
                    $skipped++;
                    continue;
                }

                $periode = PeriodeLaporan::firstOrCreate(
                    ['tanggal_akhir' => $tanggal],
                    [
                        'nama_periode'               => 'Periode ' . Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM Y'),
                        'status_operasional'         => StatusOperasional::Verified,
                        'tgl_verifikasi_operasional' => now(),
                        'verified_by_operasional'    => $actor->id,
                        'is_current'                 => false,
                    ]
                );

                if ($periode->wasRecentlyCreated) $createdPeriode++;

                $jenisEnum = JenisLaporan::from($row['jenis']);

                $exists = Laporan::where('id_periode', $periode->id)
                    ->where('id_cabang', $cabang->id)
                    ->where('jenis', $jenisEnum)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                Laporan::create([
                    'id_cabang'               => $cabang->id,
                    'id_periode'              => $periode->id,
                    'jenis'                   => $jenisEnum,
                    'saldo_awal'              => $row['saldo_awal'],
                    'tambahan_stok'           => $row['tambahan'],
                    'jumlah_digunakan'        => $row['digunakan'],
                    'jml_dibatalkan_rusak'    => $row['rusak'],
                    'jml_dibatalkan_hilang'   => $row['hilang'],
                    'saldo_akhir'             => $row['saldo_akhir'],
                    'status_verifikasi'       => StatusVerifikasi::VerifiedAccounting,
                    'tgl_submit'              => now(),
                    'tgl_verifikasi_akunting' => now(),
                    'verified_by_akunting'    => $actor->id,
                ]);

                $createdLaporan++;
            }
        });

        return [
            'created_laporan' => $createdLaporan,
            'created_periode' => $createdPeriode,
            'skipped'         => $skipped,
        ];
    }

    // ─────────────────────────────────────────────────────────
    // PRIVATE: Membaca sheet
    // ─────────────────────────────────────────────────────────

    private function readSheet(string $filePath): ?array
    {
        $spreadsheet = IOFactory::load($filePath);

        $targetIndex = null;
        foreach ($spreadsheet->getSheetNames() as $index => $name) {
            if (strtolower(trim($name)) === strtolower(self::SHEET_NAME)) {
                $targetIndex = $index;
                break;
            }
        }

        if ($targetIndex === null) return null;

        $sheet      = $spreadsheet->getSheet($targetIndex);
        $highestRow = $sheet->getHighestDataRow();

        $rows = [];
        for ($r = 2; $r <= $highestRow; $r++) { // mulai baris 2 (lewati header)
            $rows[$r] = [
                'tanggal'    => $sheet->getCell("B{$r}")->getValue(),
                'kode'       => $sheet->getCell("C{$r}")->getValue(),
                'jenis'      => $sheet->getCell("D{$r}")->getValue(),
                'tambahan'   => $sheet->getCell("E{$r}")->getValue(),
                'digunakan'  => $sheet->getCell("F{$r}")->getValue(),
                'rusak'      => $sheet->getCell("G{$r}")->getValue(),
                'hilang'     => $sheet->getCell("H{$r}")->getValue(),
                'saldo_awal' => $sheet->getCell("I{$r}")->getValue(),
            ];
        }

        return $rows;
    }

    /**
     * Validasi satu baris mentah. Return null jika baris kosong (diabaikan).
     */
    private function validateRow(array $row, int $line, $cabangMap, array $seenInFile): ?array
    {
        $allEmpty = collect($row)->every(fn($v) => $v === null || trim((string) $v) === '');
        if ($allEmpty) return null;

        $errors = [];

        // ─── Tanggal ───
        $tanggal = $this->parseDateCell($row['tanggal']);
        if (! $tanggal) {
            $errors[] = 'Tanggal periode kosong atau format tidak dikenali (gunakan DD/MM/YYYY).';
        }

        // ─── Kode Cabang ───
        $kodeRaw = $row['kode'];
        $kode    = is_numeric($kodeRaw) ? (string)(int) $kodeRaw : strtoupper(trim((string) $kodeRaw));
        $cabang  = $cabangMap[$kode] ?? null;
        if (empty($kode)) {
            $errors[] = 'Kode cabang kosong.';
        } elseif (! $cabang) {
            $errors[] = "Kode cabang \"{$kode}\" tidak ditemukan di Master Cabang.";
        }

        // ─── Jenis ───
        $jenisRaw = strtolower(trim((string) $row['jenis']));
        $jenis    = match ($jenisRaw) {
            'tabungan' => 'tabungan',
            'deposito' => 'deposito',
            default    => null,
        };
        if (! $jenis) {
            $errors[] = 'Jenis harus diisi "Tabungan" atau "Deposito".';
        }

        // ─── Angka ───
        $tambahan = $this->parseNonNegativeInt($row['tambahan'], 'Tambahan Stok', $errors);
        $digunakan = $this->parseNonNegativeInt($row['digunakan'], 'Jumlah Digunakan', $errors);
        $rusak = $this->parseNonNegativeInt($row['rusak'], 'Dibatalkan (Rusak)', $errors);
        $hilang = $this->parseNonNegativeInt($row['hilang'], 'Dibatalkan (Hilang)', $errors);

        $saldoAwalInput = null;
        if ($row['saldo_awal'] !== null && trim((string) $row['saldo_awal']) !== '') {
            $saldoAwalInput = $this->parseNonNegativeInt($row['saldo_awal'], 'Saldo Awal', $errors);
        }

        // ─── Duplikat dalam file ───
        if ($tanggal && $cabang && $jenis) {
            $key = $tanggal->format('Y-m-d') . '|' . $kode . '|' . $jenis;
            if (isset($seenInFile[$key])) {
                $errors[] = 'Baris duplikat: kombinasi Tanggal + Kode Cabang + Jenis ini sudah muncul sebelumnya di file ini.';
            }
        }

        $base = [
            'line'             => $line,
            'tanggal'          => $tanggal,
            'tanggal_display'  => $tanggal?->format('d/m/Y') ?? (string) $row['tanggal'],
            'tanggal_iso'      => $tanggal?->format('Y-m-d'),
            'kode_cabang'      => $kode,
            'cabang_nama'      => $cabang?->nama_cabang,
            'jenis'            => $jenis,
            'jenis_label'      => $jenis ? ucfirst($jenis) : (string) $row['jenis'],
            'tambahan'         => $tambahan ?? 0,
            'digunakan'        => $digunakan ?? 0,
            'rusak'            => $rusak ?? 0,
            'hilang'           => $hilang ?? 0,
            'saldo_awal_input' => $saldoAwalInput,
            'saldo_awal'       => null,
            'saldo_akhir'      => null,
        ];

        if (! empty($errors)) {
            $base['status']  = 'error';
            $base['message'] = implode(' ', $errors);
            return $base;
        }

        $base['status']  = 'ok_pending'; // lolos validasi field, menunggu kalkulasi saldo & cek duplikat DB
        $base['message'] = null;
        return $base;
    }

    private function parseDateCell(mixed $value): ?Carbon
    {
        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value);
        }

        if (is_numeric($value)) {
            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value));
            } catch (\Exception) {
                return null;
            }
        }

        if (is_string($value) && trim($value) !== '') {
            try {
                // Coba format dd/mm/yyyy secara eksplisit dulu
                if (preg_match('#^(\d{1,2})/(\d{1,2})/(\d{4})$#', trim($value), $m)) {
                    return Carbon::createFromDate((int) $m[3], (int) $m[2], (int) $m[1]);
                }
                return Carbon::parse(trim($value));
            } catch (\Exception) {
                return null;
            }
        }

        return null;
    }

    private function parseNonNegativeInt(mixed $value, string $label, array &$errors): ?int
    {
        if ($value === null || trim((string) $value) === '') {
            return 0;
        }

        if (! is_numeric($value)) {
            $errors[] = "{$label} harus berupa angka.";
            return null;
        }

        $intVal = (int) round((float) $value);

        if ($intVal < 0) {
            $errors[] = "{$label} tidak boleh negatif.";
            return null;
        }

        return $intVal;
    }
}
