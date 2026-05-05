<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipStatus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ship_status';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public const STATUS_WAITING = 'waiting';
    public const STATUS_PROCESS = 'progress';
    public const STATUS_DONE = 'done';
    public const STATUS_CANCEL = 'cancelled';
    public const STATUS_RETURN = 'return';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_id',
        'track',
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
            'item_id' => 'integer',
            'created_at' => 'integer',
            'updated_at' => 'integer',
            'user_entry' => 'integer',
            'user_update' => 'integer',
        ];
    }

    /**
     * Get the ship item that owns the status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shipItem(): BelongsTo
    {
        return $this->belongsTo(ShipItem::class, 'item_id', 'id');
    }

    /**
     * Get the POD associated with the ship status.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pod(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ShipmentPod::class, 'ship_status_id', 'id');
    }
}
