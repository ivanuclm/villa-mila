<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'customer_name',
        'customer_first_name',
        'customer_first_surname',
        'customer_second_surname',
        'customer_email',
        'customer_phone',
        'customer_document_type',
        'customer_document_number',
        'customer_document_support_number',
        'customer_birthdate',
        'customer_birth_country',
        'customer_address_street',
        'customer_address_number',
        'customer_address_city',
        'customer_address_province',
        'customer_address_country',
        'arrival',
        'departure',
        'guests',
        'status',
        'total',
        'payment_method',
        'payment_received_at',
        'payment_notes',
        'operations_checklist',
        'contract_document_path',
        'source',
        'notes',
        'terms_accepted_at',
        'public_access_token',
        'portal_last_accessed_at',
    ];

    protected $casts = [
        'arrival' => 'date',
        'departure' => 'date',
        'customer_birthdate' => 'date',
        'terms_accepted_at' => 'datetime',
        'portal_last_accessed_at' => 'datetime',
        'payment_received_at' => 'datetime',
        'status' => BookingStatus::class,
        'operations_checklist' => 'array',
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

    public function getContractDocumentUrlAttribute(): ?string
    {
        if (! $this->contract_document_path) {
            return null;
        }

        return Storage::disk('public')->url($this->contract_document_path);
    }
}
