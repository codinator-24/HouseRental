<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory; // It's good practice to add this if you might use factories

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'house_id',
        'tenant_id',
        'picture',
        'area_of_house',
        'description',
        'refund_amount',
        'status',
        'landlord_response',
    ];

    // Relationships (optional, but likely needed)
    public function house()
    {
        return $this->belongsTo(House::class);
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id'); // Assuming tenants are users
    }
}
