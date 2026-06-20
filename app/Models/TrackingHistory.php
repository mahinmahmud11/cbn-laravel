<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackingHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'status',
        'location',
        'description',
        'photo_proof',
        'updated_by',
    ];

    /**
     * Get the shipment that owns the tracking history.
     */
    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Get the user who updated this tracking record.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
