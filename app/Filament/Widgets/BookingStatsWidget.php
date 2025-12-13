<?php

namespace App\Filament\Widgets;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $weekAhead = $today->copy()->addWeek();

        $pendingCount = Booking::query()->where('status', BookingStatus::Pending->value)->count();
        $holdCount = Booking::query()->where('status', BookingStatus::Hold->value)->count();
        $arrivalsThisWeek = Booking::query()
            ->whereBetween('arrival', [$today, $weekAhead])
            ->whereIn('status', [BookingStatus::Confirmed->value, BookingStatus::InStay->value])
            ->count();
        $inStayCount = Booking::query()->where('status', BookingStatus::InStay->value)->count();

        return [
            Stat::make('Reservas pendientes', $pendingCount)
                ->description('A la espera de revisión')
                ->color('warning')
                ->icon('heroicon-o-bell-alert'),
            Stat::make('Bloqueos o pre-reservas', $holdCount)
                ->description('Fechas apartadas temporalmente')
                ->color('info')
                ->icon('heroicon-o-hand-raised'),
            Stat::make('Próximas llegadas', $arrivalsThisWeek)
                ->description('Confirmadas para los próximos 7 días')
                ->color('success')
                ->icon('heroicon-o-calendar-days'),
            Stat::make('Estancias en curso', $inStayCount)
                ->description('Check-ins ya realizados')
                ->color('primary')
                ->icon('heroicon-o-home-modern'),
        ];
    }
}
