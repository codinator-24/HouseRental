<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function floors(): HasMany // <-- Add this method
    {
        return $this->hasMany(Floor::class);
    }
}
