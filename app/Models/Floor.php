<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Floor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'house_id',
        'num_room',
        'bathroom',
    ];

    /**
     * Get the house that owns the floor.
     */
    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }
}