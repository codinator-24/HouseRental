<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HousePicture extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Explicitly defining, though Laravel might infer correctly.
     * @var string
     */
    protected $table = 'house_pictures';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'house_id',
        'image_url',
        'caption',
    ];

    /**
     * Get the house that owns the picture.
     */
    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }
}