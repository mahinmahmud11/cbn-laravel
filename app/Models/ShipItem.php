<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShipItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ship_items';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'awb',
        'sender_name',
        'recipient_name',
        'dimension',
        'pieces',
        'kilogram',
        'price',
        'package_type',
        'categories',
        'fragile',
        'notes',
        'status',
        'created_at',
        'user_entry',
        'updated_at',
        'user_update',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'pieces' => 'integer',
            'kilogram' => 'integer',
            'price' => 'decimal:2',
            'created_at' => 'integer',
            'updated_at' => 'integer',
            'user_entry' => 'integer',
            'user_update' => 'integer',
        ];
    }

    /**
     * Get the statuses for the ship item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shipStatuses(): HasMany
    {
        return $this->hasMany(ShipStatus::class, 'item_id', 'id');
    }

    /**
     * Get the user who entered the ship item.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_entry', 'id');
    }
}
