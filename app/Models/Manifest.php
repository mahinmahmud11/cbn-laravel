<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manifest extends Model
{
    protected $fillable = [
        'manifest_number',
        'origin_agency_id',
        'destination_agency_id',
        'driver_name',
        'vehicle_plate',
        'status',
        'notes',
        'user_id',
    ];

    public function shipItems(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ShipItem::class, 'manifest_items', 'manifest_id', 'ship_item_id')->withTimestamps();
    }

    public function shipments(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Shipment::class, 'manifest_items', 'manifest_id', 'shipment_id')->withTimestamps();
    }

    public function originAgency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Agency::class, 'origin_agency_id');
    }

    public function destinationAgency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Agency::class, 'destination_agency_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
