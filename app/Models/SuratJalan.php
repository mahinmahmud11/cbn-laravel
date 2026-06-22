<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SuratJalan extends Model
{
    use HasFactory;

    protected $table = 'surat_jalan';

    protected $fillable = [
        'nomor',
        'armada_id',
        'driver_name',
        'driver_phone',
        'tanggal_kirim',
        'status',
        'catatan',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kirim' => 'date',
        ];
    }

    // ─── Relasi ──────────────────────────────────────────────────────────────

    public function armada(): BelongsTo
    {
        return $this->belongsTo(Armada::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function shipments(): BelongsToMany
    {
        return $this->belongsToMany(
            Shipment::class,
            'surat_jalan_shipments',
            'surat_jalan_id',
            'shipment_id'
        );
    }
}
