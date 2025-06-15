<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'agreement_id',
        'amount',
        'payment_method',
        'status',
        'paid_at',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2', // Ensures 'amount' is treated as a decimal with 2 places
    ];

    /**
     * Get the agreement that owns the payment.
     */
    public function agreement(): BelongsTo
    {
        return $this->belongsTo(Agreement::class);
    }

    /**
     * Get the maintenance request that owns the payment (if applicable).
     */
    public function maintenance(): BelongsTo
    {
        return $this->belongsTo(Maintenance::class);
    }

    /**
     * Get the user associated with the payment (e.g., payer or payee).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
