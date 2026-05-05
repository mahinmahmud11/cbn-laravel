<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Agency extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agency';

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
        'agency_name',
        'address',
        'country',
        'province',
        'city',
        'district',
        'village',
        'postal_code',
        'status',
        'leader',
        'phone_number',
        'email',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'integer',
            'updated_at' => 'integer',
            'postal_code' => 'integer',
        ];
    }

    /**
     * Get the users belonging to the agency.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'store_area', 'id');
    }

    /**
     * Get all shipments (ShipItem) made by the agency's users.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function shipItems(): HasManyThrough
    {
        return $this->hasManyThrough(
            ShipItem::class,
            User::class,
            'store_area', // Local key on users table (FK to agency)
            'user_entry', // Local key on ship_items table (FK to users)
            'id',         // Local key on agency table
            'id'          // Local key on users table
        );
    }

    /**
     * Get the province of the agency.
     */
    public function provinceRelation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Province::class, 'province', 'id');
    }

    /**
     * Get the city/regency of the agency.
     */
    public function cityRelation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Regency::class, 'city', 'id');
    }

    /**
     * Get the district of the agency.
     */
    public function districtRelation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(District::class, 'district', 'id');
    }
}
