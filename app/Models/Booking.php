<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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
        'public_access_token',
        'portal_last_accessed_at',
    ];

    protected $casts = [
        'arrival' => 'date',
        'departure' => 'date',
        'terms_accepted_at' => 'datetime',
        'portal_last_accessed_at' => 'datetime',
        'status' => BookingStatus::class,
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (blank($booking->public_access_token)) {
                $booking->public_access_token = (string) Str::uuid();
            }
        });
    }

    public function guestEntries(): HasMany
    {
        return $this->hasMany(BookingGuest::class);
    }
}
