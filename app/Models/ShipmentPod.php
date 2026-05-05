<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentPod extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ship_status_id',
        'file_path',
    ];

    /**
     * Get the ship status associated with the POD.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shipStatus(): BelongsTo
    {
        return $this->belongsTo(ShipStatus::class, 'ship_status_id', 'id');
    }
}
