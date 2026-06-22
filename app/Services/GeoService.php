<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use Illuminate\Support\Facades\Cache;

class GeoService
{
    /**
     * Get cached provinces for select dropdowns.
     * 
     * @return array
     */
    public static function provinces(): array
    {
        return Cache::remember('geo_provinces', 86400, function () {
            return Province::orderBy('name')->pluck('name', 'id')->toArray();
        });
    }

    /**
     * Get cached regencies for a specific province.
     * 
     * @param string|null $provinceId
     * @return array
     */
    public static function regencies(?string $provinceId): array
    {
        if (!$provinceId) {
            return [];
        }

        return Cache::remember("geo_regencies_{$provinceId}", 86400, function () use ($provinceId) {
            return Regency::where('province_id', $provinceId)->orderBy('name')->pluck('name', 'id')->toArray();
        });
    }

    /**
     * Get cached districts for a specific regency.
     * 
     * @param string|null $regencyId
     * @return array
     */
    public static function districts(?string $regencyId): array
    {
        if (!$regencyId) {
            return [];
        }

        return Cache::remember("geo_districts_{$regencyId}", 86400, function () use ($regencyId) {
            return District::where('regency_id', $regencyId)->orderBy('name')->pluck('name', 'id')->toArray();
        });
    }

    /**
     * Get cached villages for a specific district.
     * 
     * @param string|null $districtId
     * @return array
     */
    public static function villages(?string $districtId): array
    {
        if (!$districtId) {
            return [];
        }

        return Cache::remember("geo_villages_{$districtId}", 86400, function () use ($districtId) {
            return Village::where('district_id', $districtId)->orderBy('name')->pluck('name', 'id')->toArray();
        });
    }
}
