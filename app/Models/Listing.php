<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Listing extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'license_number',
        'address',
        'lat',
        'lng',
        'max_guests',
        'checkin_from',
        'checkout_until',
        'cover_image_path',
        'contract_owner_name',
        'contract_owner_document',
        'contract_owner_address',
        'contract_full_text',
        'cadastral_reference',
        'municipal_registry_code',
        'clm_registry_number',
        'nra_registry_number',
        'ccaa_license_code',
        'extra_beds',
        'cribs',
    ];
    public array $translatable = ['description'];

    public function amenities() { return $this->belongsToMany(Amenity::class); }
    public function bookings()  { return $this->hasMany(Booking::class); }

    public function seasons()    { return $this->hasMany(Season::class); }
    public function priceRules() { return $this->hasMany(PriceRule::class); }

    public function getCoverUrlAttribute(): ?string
    {
        if (! $this->cover_image_path) {
            return null;
        }

        return Storage::disk('public')->url($this->cover_image_path);
    }
}
