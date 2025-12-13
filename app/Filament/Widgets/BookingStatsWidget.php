<?php

namespace App\Filament\Widgets;

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

        $pendingCount = Booking::query()->where('status', 'pending')->count();
        $holdCount = Booking::query()->where('status', 'hold')->count();
        $upcomingCount = Booking::query()
            ->whereDate('arrival', '>=', $today)
            ->whereIn('status', ['confirmed', 'hold'])
            ->count();
        $arrivalsThisWeek = Booking::query()
            ->whereBetween('arrival', [$today, $weekAhead])
            ->count();

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
                ->description('Para los próximos 7 días')
                ->color('success')
                ->icon('heroicon-o-calendar-days'),
            Stat::make('Estancias futuras', $upcomingCount)
                ->description('Confirmadas o en bloqueo')
                ->color('primary')
                ->icon('heroicon-o-home-modern'),
        ];
    }
}
