<?php

declare(strict_types=1);

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use Notifiable, HasRoles;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * Indicates if the model should be timestamped.
     * Legacy Yii2 uses unix timestamp integers managed manually or via behaviors.
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
        'nip',
        'name',
        'nickname',
        'username',
        'password_hash',
        'email',
        'phone_number',
        'auth_key',
        'status',
        'created_at',
        'updated_at',
        'photo',
        'signature',
        'roleid',
        'store_area',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'integer',
            'created_at' => 'integer',
            'updated_at' => 'integer',
            'store_area' => 'integer',
        ];
    }

    /**
     * Get the name of the password attribute for the user.
     * This bridges Laravel Auth to read the legacy 'password_hash' column.
     *
     * @return string
     */
    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    /**
     * Determine if the user can access the given Filament panel.
     *
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // 10 represents STATUS_ACTIVE in the legacy Yii2 User model
        return $this->status === 10;
    }

    /**
     * Get the ship items entered by the user.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shipItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ShipItem::class, 'user_entry', 'id');
    }

    /**
     * Get the agency the user belongs to.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Agency::class, 'store_area', 'id');
    }
}
