<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_number',
        'customer_name',
        'recipient_name',
        'recipient_phone',
        'origin_city',
        'destination_city',
        'current_status',
    ];

    /**
     * Get the tracking histories for the shipment.
     */
    public function trackingHistories(): HasMany
    {
        return $this->hasMany(TrackingHistory::class);
    }

    /**
     * Get the agent commissions for the shipment.
     */
    public function agentCommissions(): HasMany
    {
        return $this->hasMany(AgentCommission::class);
    }
}
