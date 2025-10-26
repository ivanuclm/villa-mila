<?php

namespace App\Services;

use App\Models\{Listing, PriceRule};
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class BookingPriceService
{
    public function quote(Listing $listing, string|\DateTimeInterface $arrival, string|\DateTimeInterface $departure, int $guests = 2): array
    {
        $start = $arrival instanceof \DateTimeInterface ? Carbon::instance($arrival) : Carbon::parse($arrival);
        $end   = $departure instanceof \DateTimeInterface ? Carbon::instance($departure) : Carbon::parse($departure);

        $period   = CarbonPeriod::create($start, $end->copy()->subDay()); // una iteración por noche
        $nights   = 0; $subtotal = 0; $cleaning = 0;

        foreach ($period as $date) {
            $nights++;
            $rule = $this->matchRule($listing, $date);

            $price = $rule?->price_per_night ?? 0;
            $extra = max(0, $guests - ($rule?->included_guests ?? 2)) * ($rule?->extra_guest_fee ?? 0);

            $subtotal += $price + $extra;
            $cleaning  = max($cleaning, $rule?->cleaning_fee ?? 0);
        }

        return [
            'nights'   => $nights,
            'subtotal' => round($subtotal, 2),
            'cleaning' => round($cleaning, 2),
            'total'    => round($subtotal + $cleaning, 2),
        ];
    }

    protected function matchRule(Listing $listing, Carbon $date): ?PriceRule
    {
        $dow = (int) $date->format('N'); // 1..7 (L..D)

        return $listing->priceRules()
            ->where(function ($q) use ($date) {
                $q->whereNull('season_id')
                  ->orWhereHas('season', fn ($s) =>
                      $s->whereDate('start_date', '<=', $date)
                        ->whereDate('end_date',   '>=', $date)
                  );
            })
            ->where(function ($q) use ($dow) {
                $q->whereNull('dow')->orWhere('dow', $dow);
            })
            ->orderByDesc('is_override')  // 1º overrides
            // ->orderByDesc('season_id')    // 2º con temporada
            ->orderByRaw('CASE WHEN season_id IS NULL THEN 1 ELSE 0 END ASC')
            // 3) luego las que SÍ fijan día de semana (dow), null al final
            ->orderByRaw('CASE WHEN dow IS NULL THEN 1 ELSE 0 END ASC')
            // 4) (opcional) como último desempate, la más cara
            ->orderByDesc('price_per_night')
            ->first();
    }
}
