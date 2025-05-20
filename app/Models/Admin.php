<?php
namespace App\Models;
 
 use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Import Authenticatable
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // If you plan to use Sanctum for API authentication
 

class Admin extends Authenticatable // Extend Authenticatable
 {
    use HasFactory;
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'admins'; // Explicitly define the table name
    protected $fillable = [
        'full_name',
        'user_name',
        'phoneNumber',
        'email',
        'password',
        'picture',
    ];

    protected $hidden = [
        'password',
    ];
 }
