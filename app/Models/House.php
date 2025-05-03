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
        'first_address',
        'second_address',
        'city',
        'location_url',
        'property_type',
        'num_room',
        'num_floor',
        'square_footage',
        'rent_amount',
        'status',
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
}
