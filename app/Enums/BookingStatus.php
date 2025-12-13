<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Pending = 'pending';
    case Hold = 'hold';
    case Confirmed = 'confirmed';
    case InStay = 'in_stay';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendiente',
            self::Hold => 'Bloqueo',
            self::Confirmed => 'Confirmada',
            self::InStay => 'En curso',
            self::Completed => 'Completada',
            self::Cancelled => 'Cancelada',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Hold => 'info',
            self::Confirmed => 'success',
            self::InStay => 'primary',
            self::Completed => 'gray',
            self::Cancelled => 'danger',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        $labels = [];

        foreach (self::cases() as $case) {
            $labels[$case->value] = $case->label();
        }

        return $labels;
    }

    /**
     * Statuses that should block availability on the calendar.
     *
     * @return array<string>
     */
    public static function blocking(): array
    {
        return [
            self::Hold->value,
            self::Confirmed->value,
            self::InStay->value,
        ];
    }
}
