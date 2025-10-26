<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id','season_id','dow','price_per_night','min_nights',
        'cleaning_fee','included_guests','extra_guest_fee','is_override'
    ];

    public function listing(){ return $this->belongsTo(Listing::class); }
    public function season() { return $this->belongsTo(Season::class); }
}
