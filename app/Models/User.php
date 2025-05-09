<?php

namespace App\Models;

// Add these if they are not already present, especially Authenticatable
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable; // Use the base User class
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // If using Sanctum

// Change class name from Tenant to User
// Make sure it extends Authenticatable for Auth functionality
class User extends Authenticatable // <-- Changed from Tenant
{
    // Keep HasFactory if you use factories
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    // protected $table = 'tenants'; // Remove or comment out this line if present
                                     // Laravel will infer 'users' from the class name 'User'

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'user_name',
        'first_phoneNumber',
        'second_phoneNumber',
        'email',
        'password',
        'role',
        'address',
        'picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token', // Standard hidden field for authentication
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime', // Standard cast for email verification
        'password' => 'hashed', // Use the 'hashed' cast for automatic hashing
    ];

    public function houses(): HasMany
    {
        return $this->hasMany(House::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
