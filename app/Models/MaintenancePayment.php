<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenancePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_id',
        'user_id', // Landlord who made the payment
        'stripe_session_id',
        'amount',
        'currency',
        'status',
        'paid_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the maintenance request associated with this payment.
     */
    public function maintenance(): BelongsTo
    {
        return $this->belongsTo(Maintenance::class);
    }

    /**
     * Get the user (landlord) who made the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
