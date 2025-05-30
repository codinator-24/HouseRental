<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'house_id',
        'reported_user_id',
        'reason_category',
        'description',
        'status',
    ];

    /**
     * Get the user who submitted the report.
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the house that was reported.
     */
    public function house()
    {
        return $this->belongsTo(House::class);
    }

    /**
     * Get the user (landlord) who was reported.
     */
    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }
}
