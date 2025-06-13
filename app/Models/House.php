<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Review; // Added for Review relationship
use App\Models\Booking; // Added for Booking relationship
use App\Models\Message; // Added for Message relationship

class House extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'landlord_id',
        'title',
        'description',
        'neighborhood',
        'second_address',
        'city',
        'location_url',
        'property_type',
        'square_footage',
        'rent_amount',
        'status',
        'latitude',
        'longitude',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'disagree', // Or 'pending_approval', 'inactive', etc. as you prefer
    ];

    /**
     * Get the landlord (user) that owns the house.
     */
    public function landlord(): BelongsTo
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

     /**
     * Get the pictures for the house.
     */
    public function pictures(): HasMany
    {
        return $this->hasMany(HousePicture::class);
    }

    public function floors(): HasMany
    {
        return $this->hasMany(Floor::class);
    }

    /**
     * Accessor for the total number of floors.
     *
     * @return int
     */
    public function getNumFloorAttribute(): int
    {
        // Check if the floors relationship is loaded to avoid N+1
        if ($this->relationLoaded('floors')) {
            return $this->floors->count();
        }
        // If not loaded, load it and count
        return $this->floors()->count();
    }

    /**
     * Accessor for the total number of rooms.
     *
     * @return int
     */
    public function getNumRoomAttribute(): int
    {
        // Check if the floors relationship is loaded
        if ($this->relationLoaded('floors')) {
            return $this->floors->sum('num_room');
        }
        // If not loaded, load it and sum
        // This could still be an N+1 if floors() itself isn't constrained
        // but for sum, it's often handled efficiently by Eloquent.
        // For optimal performance, ensure 'floors' is eager loaded.
        return $this->floors()->sum('num_room');
    }

    /**
     * The users that have favorited this house.
     */
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites', 'house_id', 'user_id')->withTimestamps();
    }

    /**
     * Get the reviews for the house.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the bookings for the house.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Accessor for the average rating of the house.
     *
     * @return float
     */
    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->where('is_approved', true)->avg('rating'), 1) ?? 0.0;
    }

    /**
     * Accessor for the total number of approved reviews for the house.
     *
     * @return int
     */
    public function getTotalApprovedReviewsAttribute(): int
    {
        return $this->reviews()->where('is_approved', true)->count();
    }

    /**
     * Check if a user can review this house for a specific completed booking.
     * This is a basic check; more robust logic might involve checking booking status and dates.
     *
     * @param User $user
     * @param Booking $booking
     * @return bool
     */
    public function canBeReviewedBy(User $user, Booking $booking): bool
    {
        // Ensure the booking belongs to the user and this house
        if ($booking->tenant_id !== $user->id || $booking->house_id !== $this->id) {
            return false;
        }

        // Use the Booking model's method to check if it's completed and past its end date
        if (!$booking->isCompletedAndPast()) {
            return false;
        }

        // Check if the user has already reviewed this specific booking
        return !$this->reviews()->where('user_id', $user->id)->where('booking_id', $booking->id)->exists();
    }

    /**
     * Get the inquiry messages for the house.
     * These are messages not tied to a specific agreement.
     */
    public function inquiryMessages(): HasMany
    {
        return $this->hasMany(Message::class)->whereNull('agreement_id');
    }
}
