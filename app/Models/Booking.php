<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Review; // Added for Review relationship

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'house_id', // got from houses table
        'tenant_id', // got from users table
        'status',
        'message',
        'month_duration', // e.g., duration in month
    ];

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'month_duration' => 'integer',
    ];

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    /**
     * Get the review associated with the booking.
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class, 'booking_id', 'id');
    }

    /**
     * Check if the booking is completed and its end date has passed.
     * Assumes 'end_date' is a field on the booking model.
     *
     * @return bool
     */
    public function isCompletedAndPast(): bool
    {
        if ($this->status !== 'completed' || !isset($this->created_at) || !isset($this->month_duration)) {
            return false;
        }

        // Calculate the end date based on created_at and month_duration
        $endDate = $this->created_at->copy()->addMonths($this->month_duration);
        return $endDate->isPast();
    }
}
