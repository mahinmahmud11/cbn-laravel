<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'shipment_id',
        'amount',
        'status',
        'notes',
    ];

    /**
     * Get the agent (user) for the commission.
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get the shipment for the commission.
     */
    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }
}
