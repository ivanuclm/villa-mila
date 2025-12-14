<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingGuest extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'first_name',
        'first_surname',
        'second_surname',
        'full_name',
        'document_type',
        'document_number',
        'document_support_number',
        'nationality',
        'birth_country',
        'birthdate',
        'is_minor',
        'kinship',
        'gender',
        'email',
        'phone',
        'signature_path',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'is_minor' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (BookingGuest $guest) {
            if (blank($guest->full_name)) {
                $parts = array_filter([
                    $guest->first_name,
                    $guest->first_surname,
                    $guest->second_surname,
                ]);
                if ($parts) {
                    $guest->full_name = implode(' ', $parts);
                }
            }
        });
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
