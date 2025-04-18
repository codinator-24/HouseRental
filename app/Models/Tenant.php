<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'fullName',
        'email',
        'password',
        'address',
        'contactNo',
        'userTitle',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
