<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }
}