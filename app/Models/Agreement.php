<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Message; // Added for Message relationship
use App\Models\User; // Added for User type hinting

class Agreement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
        'signed_at',
        'expires_at',
        'rent_amount',
        'rent_frequency',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'signed_at' => 'datetime',
        'expires_at' => 'datetime',
        'rent_amount' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the messages for the agreement.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the tenant associated with this agreement.
     *
     * @return \App\Models\User
     */
    public function getTenantAttribute(): User
    {
        return $this->booking->tenant;
    }

    /**
     * Get the landlord associated with this agreement.
     *
     * @return \App\Models\User
     */
    public function getLandlordAttribute(): User
    {
        return $this->booking->house->landlord;
    }
}
