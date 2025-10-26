<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Season extends Model
{
    use HasFactory;

    protected $fillable = ['listing_id','name','start_date','end_date'];
    protected $casts = ['start_date'=>'date','end_date'=>'date'];

    public function listing()   { return $this->belongsTo(Listing::class); }
    public function priceRules(){ return $this->hasMany(PriceRule::class); }
}
