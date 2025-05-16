<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;

/**
 * App\Models\User
 *
 * @property string $id
 * @property string $full_name
 * @property string $user_name
 * @property string $first_phoneNumber
 * @property string|null $second_phoneNumber
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string|null $address
 * @property string|null $picture
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $readNotifications
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $unreadNotifications
 * @property-read int|null $notifications_count
 * @property-read int|null $read_notifications_count
 * @property-read int|null $unread_notifications_count
 * 
 * @method DatabaseNotificationCollection notifications()
 * @method DatabaseNotificationCollection unreadNotifications()
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens; 
    
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