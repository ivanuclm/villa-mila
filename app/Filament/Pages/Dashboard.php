<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BookingStatsWidget;
use App\Filament\Widgets\BookingsCalendarWidget;
use App\Filament\Widgets\UpcomingBookingsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Escritorio';

    protected static ?string $title = 'Resumen general';

    public function getWidgets(): array
    {
        return [
            BookingStatsWidget::class,
            BookingsCalendarWidget::class,
            UpcomingBookingsWidget::class,
        ];
    }
}
