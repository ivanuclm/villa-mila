<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['listing_id','customer_name','customer_email','arrival','departure','guests','status','total','source'];

    protected $casts = ['arrival'=>'date','departure'=>'date'];
    
    public function listing() { return $this->belongsTo(Listing::class); }
}
