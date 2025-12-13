<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\Widget;

class BookingsCalendarWidget extends Widget
{
    protected string $view = 'filament.widgets.bookings-calendar-widget';

    protected int|string|array $columnSpan = 'full';

    public array $weeks = [];

    public string $monthLabel = '';

    public function mount(): void
    {
        $this->buildCalendar();
    }

    protected function buildCalendar(): void
    {
        $month = Carbon::now()->startOfMonth();
        $this->monthLabel = $month->translatedFormat('F Y');

        $start = $month->copy()->startOfWeek(Carbon::MONDAY);
        $end = $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $bookings = Booking::query()
            ->with('listing')
            ->whereDate('departure', '>=', $start)
            ->whereDate('arrival', '<=', $end)
            ->get();

        $calendarMap = [];

        foreach ($bookings as $booking) {
            $period = CarbonPeriod::create(
                $booking->arrival,
                $booking->departure?->copy()->subDay() ?? $booking->arrival
            );

            foreach ($period as $date) {
                $calendarMap[$date->toDateString()][] = [
                    'name' => $booking->customer_name,
                    'status' => $booking->status,
                    'label' => match ($booking->status) {
                        'pending' => 'Pendiente',
                        'hold' => 'Bloqueo',
                        'confirmed' => 'Confirmada',
                        'cancelled' => 'Cancelada',
                        default => $booking->status,
                    },
                    'listing' => $booking->listing?->name,
                ];
            }
        }

        $cursor = $start->copy();
        $weeks = [];

        while ($cursor <= $end) {
            $week = [];

            for ($i = 0; $i < 7; $i++) {
                $dateKey = $cursor->toDateString();
                $week[] = [
                    'date' => $cursor->copy(),
                    'inMonth' => $cursor->month === $month->month,
                    'bookings' => $calendarMap[$dateKey] ?? [],
                ];
                $cursor->addDay();
            }

            $weeks[] = $week;
        }

        $this->weeks = $weeks;
    }
}