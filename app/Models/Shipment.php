<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\Concerns\HasLegacyOrModernTimestamp;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Shipment extends Model
{
    use HasFactory, HasLegacyOrModernTimestamp, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['tracking_number', 'current_status', 'price', 'recipient_name', 'customer_name'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    protected $fillable = [
        'tracking_number',
        'customer_name',
        'sender_phone',
        'recipient_name',
        'recipient_phone',
        'origin_city',
        'destination_city',
        'origin_district_id',
        'destination_district_id',
        'pieces',
        'weight_kg',
        'dimension',
        'package_type',
        'package_content',
        'price',
        'current_status',
        'notes',
        'created_by',
        'sender_name',
        'sender_address',
        'origin_province_id',
        'origin_regency_id',
        'origin_village_id',
        'origin_postal_code',
        'receiver_address',
        'destination_province_id',
        'destination_regency_id',
        'destination_village_id',
        'destination_postal_code',
        'is_fragile',
        'customer_id',
        'base_price',
        'packing_fee',
        'insurance_fee',
        'discount_amount',
    ];

    protected function casts(): array
    {
        return [
            'pieces'    => 'integer',
            'weight_kg' => 'decimal:2',
            'price'     => 'decimal:2',
        ];
    }

    // ─── Relasi ──────────────────────────────────────────────────────────────

    public function trackingHistories(): HasMany
    {
        return $this->hasMany(TrackingHistory::class);
    }

    public function activities(): MorphMany
    {
        return $this->activitiesAsSubject();
    }

    public function agentCommissions(): HasMany
    {
        return $this->hasMany(AgentCommission::class);
    }

    public function originDistrict(): BelongsTo
    {
        return $this->belongsTo(District::class, 'origin_district_id', 'id');
    }

    public function destinationDistrict(): BelongsTo
    {
        return $this->belongsTo(District::class, 'destination_district_id', 'id');
    }

    public function originProvince(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'origin_province_id', 'id');
    }

    public function originRegency(): BelongsTo
    {
        return $this->belongsTo(Regency::class, 'origin_regency_id', 'id');
    }

    public function originVillage(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'origin_village_id', 'id');
    }

    public function destinationProvince(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'destination_province_id', 'id');
    }

    public function destinationRegency(): BelongsTo
    {
        return $this->belongsTo(Regency::class, 'destination_regency_id', 'id');
    }

    public function destinationVillage(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'destination_village_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function suratJalans(): BelongsToMany
    {
        return $this->belongsToMany(
            SuratJalan::class,
            'surat_jalan_shipments',
            'shipment_id',
            'surat_jalan_id'
        );
    }
}
