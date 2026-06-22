<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait HasLegacyOrModernTimestamp
{
    /**
     * Scope untuk filter rentang tanggal yang otomatis menyesuaikan
     * apakah created_at di model ini disimpan sebagai unix timestamp
     * integer (legacy) atau datetime Carbon (modern).
     *
     * Set property statis $usesIntegerTimestamp = true; di model legacy (ShipItem).
     */
    public function scopeCreatedBetween(Builder $query, Carbon $start, Carbon $end): Builder
    {
        $usesInteger = property_exists($this, 'usesIntegerTimestamp') && $this->usesIntegerTimestamp;

        return $query->whereBetween('created_at', $usesInteger
            ? [$start->timestamp, $end->timestamp]
            : [$start, $end]
        );
    }
}
