<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_name',
        'customer_address',
        'total_amount',
        'status',
        'due_date',
        'notes',
    ];

    public function shipItems(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ShipItem::class, 'invoice_items', 'invoice_id', 'ship_item_id')->withTimestamps();
    }
}
