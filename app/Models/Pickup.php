<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pickup extends Model
{
    protected $fillable = [
        'pickup_number',
        'customer_name',
        'customer_phone',
        'pickup_address',
        'pickup_time',
        'estimated_items',
        'status',
        'courier_id',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'pickup_time' => 'datetime',
    ];

    public function courier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
