<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'address',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'postal_code',
        'type',
        'company_id',
        'created_by',
    ];

    /**
     * Get the province for the customer.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    /**
     * Get the regency for the customer.
     */
    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class, 'regency_id', 'id');
    }

    /**
     * Get the district for the customer.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    /**
     * Get the village for the customer.
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'village_id', 'id');
    }

    /**
     * Get the user that created the customer.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the shipments where this customer is the sender.
     */
    public function shipmentsSender(): HasMany
    {
        return $this->hasMany(Shipment::class, 'customer_id', 'id');
    }

    /**
     * Get the company for the customer.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
