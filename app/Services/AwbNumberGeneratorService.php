<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AwbSequence;
use Illuminate\Support\Facades\DB;

/**
 * Service untuk menghasilkan nomor AWB yang unik dan aman dari race condition.
 *
 * Format output: YYMM + 5 digit urut (total 9 karakter).
 * Contoh: "260600001" (bulan Juni 2026, resi pertama)
 *         "260699999" (bulan Juni 2026, resi ke-99.999)
 *
 * Mekanisme pengamanan (dua lapis):
 * 1. lockForUpdate() di dalam DB::transaction → mencegah dua request membaca
 *    nilai counter yang sama saat row sudah ada.
 * 2. Retry loop (maks 3x) → menutup edge case saat dua request datang bersamaan
 *    di momen row period belum eksis (awal bulan baru). Salah satu akan kalah
 *    create() dengan duplicate entry (23000), langsung retry — percobaan
 *    berikutnya row sudah ada dan lockForUpdate() bekerja normal.
 *
 * PENTING: Service ini bersifat agnostik terhadap tabel tujuan.
 * Dia hanya menghasilkan string nomor. Pemanggil (Resource/Controller) yang
 * bertanggung jawab menyimpan hasilnya ke kolom yang tepat.
 *
 * @see \App\Models\AwbSequence
 */
class AwbNumberGeneratorService
{
    /**
     * Hasilkan nomor AWB baru yang unik, atomik, dan bebas race condition.
     *
     * @return string Contoh: "260600001"
     * @throws \Illuminate\Database\QueryException Jika gagal setelah 3 percobaan
     * @throws \RuntimeException Fallback jika loop selesai tanpa return (seharusnya tidak terjadi)
     */
    public function generate(): string
    {
        $period = now()->format('ym'); // "2606" untuk Juni 2026

        for ($attempt = 0; $attempt < 3; $attempt++) {
            try {
                return DB::transaction(function () use ($period): string {
                    // Pisahkan lockForUpdate() dari firstOrCreate().
                    // lockForUpdate() hanya efektif pada row yang sudah ada —
                    // tidak bisa mengunci row yang belum eksis di database.
                    $sequence = AwbSequence::where('period', $period)
                        ->lockForUpdate()
                        ->first();

                    if (! $sequence) {
                        // Row belum ada (awal bulan baru). Buat dengan last_sequence = 0.
                        // Jika dua request bersamaan sampai di sini, salah satu akan
                        // dilempar QueryException (duplicate entry) → ditangkap di catch.
                        $sequence = AwbSequence::create([
                            'period'        => $period,
                            'last_sequence' => 0,
                        ]);
                    }

                    // increment() sudah sinkronkan nilai in-memory — tidak perlu refresh().
                    $sequence->increment('last_sequence');

                    return $this->format($period, $sequence->last_sequence);
                });
            } catch (\Illuminate\Database\QueryException $e) {
                // Kode 23000 = Integrity Constraint Violation (duplicate entry).
                // Ini hanya terjadi saat dua request bersamaan mencoba create() row yang sama.
                // Solusi: retry — di percobaan berikutnya row sudah ada, lockForUpdate() jalan normal.
                $isDuplicate = $e->getCode() === '23000';

                if ($isDuplicate && $attempt < 2) {
                    continue;
                }

                // Bukan duplicate entry, atau sudah 3x gagal — lempar ke pemanggil.
                throw $e;
            }
        }

        // Tidak akan tercapai secara normal, tapi dibutuhkan agar static analyzer tidak komplain.
        throw new \RuntimeException('AwbNumberGeneratorService: gagal menghasilkan nomor AWB setelah 3 percobaan.');
    }

    /**
     * Format period dan urutan menjadi string AWB 9 karakter.
     *
     * @param string $period  Format YYMM, contoh: "2606"
     * @param int    $seq     Nomor urut, contoh: 1
     * @return string         Contoh: "260600001"
     */
    private function format(string $period, int $seq): string
    {
        return sprintf('%s%05d', $period, $seq);
    }
}
