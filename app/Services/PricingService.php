<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tariff;
use App\Models\RouteDestination;
use Illuminate\Support\Collection;

/**
 * Service untuk menangani logika perhitungan tarif (Pricing Engine).
 * Diporting dari logika legacy Yii2 dengan penyesuaian arsitektur Laravel modern.
 */
class PricingService
{
    /**
     * Pembagi volumetrik standar (Cm3 ke Kg).
     * Biasanya 6000 untuk darat/laut, 5000 untuk udara.
     */
    private const VOLUMETRIC_DIVISOR = 6000;

    /**
     * Batas toleransi pembulatan berat (Kg).
     * Jika desimal > 0.3, maka dibulatkan ke atas.
     */
    private const ROUNDING_THRESHOLD = 0.3;

    /**
     * Menghitung ongkos kirim berdasarkan parameter pengiriman.
     *
     * @param string $originDistrictId ID Kecamatan Asal (district_cbn)
     * @param string $destinationDistrictId ID Kecamatan Tujuan (district_cbn)
     * @param float $actualWeight Berat aktual paket (Kg)
     * @param float|null $length Panjang (Cm)
     * @param float|null $width Lebar (Cm)
     * @param float|null $height Tinggi (Cm)
     * @return Collection Kumpulan tarif yang tersedia
     */
    public function calculate(
        string $originDistrictId,
        string $destinationDistrictId,
        float $actualWeight,
        ?float $length = null,
        ?float $width = null,
        ?float $height = null
    ): Collection {
        // 1. Kalkulasi Berat Volumetrik
        $volumetricWeight = 0.0;
        if ($length && $width && $height) {
            $volumetricWeight = ($length * $width * $height) / self::VOLUMETRIC_DIVISOR;
        }

        // 2. Tentukan Berat Tagihan (Billable Weight)
        $weightToCharge = max($actualWeight, $volumetricWeight);
        $billableWeight = $this->applyRounding($weightToCharge);

        // 3. Resolusi Rute Legacy
        $originRoute = RouteDestination::where('district_cbn', $originDistrictId)->first();
        $destRoute = RouteDestination::where('district_cbn', $destinationDistrictId)->first();

        if (!$originRoute || !$destRoute) {
            return collect();
        }

        // 4. Pengambilan Data Tarif Legacy (lp_tariff)
        $tariffs = Tariff::where('hometown_id', $originRoute->district_code)
            ->where('destination_id', $destRoute->district_code)
            ->where('normal_price', '>', 0)
            ->get();

        // 5. Kalkulasi Biaya Akhir
        return $tariffs->map(function (Tariff $tariff) use ($billableWeight) {
            $basePrice = (float) $tariff->normal_price;
            $totalPrice = $basePrice * $billableWeight;

            // Logika Diskon/Promo (jika ada di legacy)
            if ($tariff->is_promo && $tariff->discount > 0) {
                $totalPrice = max(0, $totalPrice - (float) $tariff->discount);
            }

            return [
                'service' => $tariff->category,
                'billable_weight' => $billableWeight,
                'volumetric_weight' => round($volumetricWeight, 2),
                'actual_weight' => $actualWeight,
                'price_per_kg' => $basePrice,
                'total_fee' => $totalPrice,
                'is_promo' => (bool) $tariff->is_promo,
            ];
        });
    }

    /**
     * Menerapkan logika pembulatan berat sesuai standar operasional CBN.
     */
    private function applyRounding(float $weight): float
    {
        if ($weight <= 0) {
            return 0.0;
        }

        $base = floor($weight);
        $decimal = $weight - $base;

        if ($decimal > self::ROUNDING_THRESHOLD) {
            return ceil($weight);
        }

        return max(1.0, $base);
    }
}
