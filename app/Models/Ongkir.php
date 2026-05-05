<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ongkir extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'harga_ongkir';

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
        'district_id',
        'weight',
        'reg_price',
        'ons_price',
        'is_enabled',
        'destination_id',
        'reg_pack',
        'boss_pack',
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
            'reg_price' => 'decimal:2',
            'ons_price' => 'decimal:2',
            'reg_pack' => 'decimal:2',
            'boss_pack' => 'decimal:2',
            'is_enabled' => 'integer',
        ];
    }
}
