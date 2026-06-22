<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'company_id',
        'customer_name',
        'customer_address',
        'total_amount',
        'paid_amount',
        'billing_status',
        'due_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function shipItems(): BelongsToMany
    {
        return $this->belongsToMany(ShipItem::class, 'invoice_items', 'invoice_id', 'ship_item_id')->withTimestamps();
    }

    public function shipments(): BelongsToMany
    {
        return $this->belongsToMany(Shipment::class, 'invoice_items', 'invoice_id', 'shipment_id')->withTimestamps();
    }

    public function getRemainingAmountAttribute(): float
    {
        return (float) $this->total_amount - (float) $this->paid_amount;
    }

    /**
     * Hitung ulang billing_status berdasarkan paid_amount vs total_amount.
     * Panggil ini setelah mengubah paid_amount, di dalam DB::transaction (Rule 9).
     * Tidak menimpa status 'dibatalkan' — itu status final manual.
     */
    public function syncBillingStatus(): void
    {
        if ($this->billing_status === 'dibatalkan') {
            return;
        }

        $this->billing_status = match (true) {
            (float) $this->paid_amount <= 0 => $this->billing_status === 'belum_ditagih' ? 'belum_ditagih' : 'sudah_ditagih',
            (float) $this->paid_amount < (float) $this->total_amount => 'sebagian_dibayar',
            default => 'lunas',
        };
    }
}
