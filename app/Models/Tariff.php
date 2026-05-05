<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lp_tariff';

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
        'hometown_id',
        'destination_id',
        'weight',
        'category',
        'normal_price',
        'basic_price',
        'is_promo',
        'discount',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'normal_price' => 'decimal:2',
            'basic_price' => 'decimal:2',
            'discount' => 'decimal:2',
            'is_promo' => 'integer',
        ];
    }
}
