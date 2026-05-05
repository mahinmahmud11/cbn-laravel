<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouteDestination extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lp_destination';

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
        'route_id',
        'route_code',
        'is_city',
        'district_code',
        'district_cbn',
        'is_local',
        'is_ok',
    ];

    /**
     * Get the district associated with the route.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_cbn', 'id');
    }
}
