<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\{Listing, Amenity, Booking, Season, PriceRule};

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1) Alojamiento
        $listing = Listing::create([
            'name'           => 'Villa Mila',
            'slug'           => Str::slug('Villa Mila'),
            'description'    => ['es' => 'Casa rural acogedora en Valdeganga, ideal familias.', 'en' => 'Cozy rural house in Valdeganga.'],
            'license_number' => 'VUT-CLM-000000',
            'address'        => 'Valdeganga (Albacete), Castilla-La Mancha',
            'lat'            => 39.1189,
            'lng'            => -1.6836,
            'max_guests'     => 6,
            'checkin_from'   => '16:00',
            'checkout_until' => '11:00',
        ]);

        // 2) Amenities
        $amenities = collect(['Wi-Fi', 'Piscina', 'Aire acondicionado', 'Barbacoa', 'Parking'])
            ->map(fn ($n) => Amenity::firstOrCreate(['name' => $n]));
        $listing->amenities()->sync($amenities->pluck('id'));

        // 3) Reserva de prueba
        Booking::create([
            'listing_id'    => $listing->id,
            'customer_name' => 'John Doe',
            'customer_email'=> 'john@example.com',
            'arrival'       => now()->addDays(10)->toDateString(),
            'departure'     => now()->addDays(13)->toDateString(),
            'guests'        => 2,
            'status'        => 'confirmed',
            'total'         => 270,
            'source'        => 'web',
        ]);

        // 4) Temporada de ejemplo
        $alta = Season::create([
            'listing_id' => $listing->id,
            'name'       => 'Alta (jul-ago)',
            'start_date' => now()->startOfYear()->addMonths(6)->startOfMonth(), // 1 julio aprox
            'end_date'   => now()->startOfYear()->addMonths(7)->endOfMonth(),   // 31 agosto aprox
        ]);

        // Global entre semana (season_id=null)
        PriceRule::create([
            'listing_id' => $listing->id,
            'season_id'  => null,
            'dow'        => null,
            'price_per_night' => 80,
            'min_nights' => 2,
            'cleaning_fee' => 30,
            'included_guests' => 2,
            'extra_guest_fee' => 15,
        ]);

        // Alta fines de semana
        foreach ([5,6] as $dow) {
            PriceRule::create([
                'listing_id' => $listing->id,
                'season_id'  => $alta->id,
                'dow'        => $dow,
                'price_per_night' => 120,
                'min_nights' => 3,
                'cleaning_fee' => 35,
                'included_guests' => 2,
                'extra_guest_fee' => 15,
            ]);
        }
    }
}
