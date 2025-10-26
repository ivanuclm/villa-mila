<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Listing extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia;

    protected $fillable = ['name','slug','description','license_number','address','lat','lng','max_guests','checkin_from','checkout_until'];
    public array $translatable = ['description'];

    public function amenities() { return $this->belongsToMany(Amenity::class); }
    public function bookings()  { return $this->hasMany(Booking::class); }

    public function seasons()    { return $this->hasMany(Season::class); }
    public function priceRules() { return $this->hasMany(PriceRule::class); }
}
