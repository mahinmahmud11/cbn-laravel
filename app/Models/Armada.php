<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Armada extends Model
{
    use HasFactory;

    protected $table = 'armada';

    protected $fillable = [
        'nopol',
        'jenis',
        'merek',
        'warna',
        'tahun',
        'status',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
        ];
    }

    // ─── Relasi ──────────────────────────────────────────────────────────────

    public function suratJalan(): HasMany
    {
        return $this->hasMany(SuratJalan::class);
    }
}
