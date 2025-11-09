<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'arrival',
        'departure',
        'guests',
        'status',
        'total',
        'source',
        'notes',
        'terms_accepted_at',
    ];

    protected $casts = [
        'arrival'=>'date',
        'departure'=>'date',
        'terms_accepted_at'=>'datetime',
    ];
    
    public function listing() { return $this->belongsTo(Listing::class); }
}
