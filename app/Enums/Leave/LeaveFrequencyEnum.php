<?php

namespace App\Enums\Leave;

enum LeaveFrequencyEnum: string
{
    case CalculateProportionally  = 'calculate_proportionally';
    case OnceAYear                = 'once_a_year';
    case OnceAServicePeriod       = 'once_a_service_period';
    case TwiceAServicePeriod      = 'twice_a_service_period';

    public function label(): string
    {
        return match ($this) {
            self::CalculateProportionally => 'Calculate Proportionally',
            self::OnceAYear               => 'Once in a Year',
            self::OnceAServicePeriod      => 'Once in a Service Period',
            self::TwiceAServicePeriod     => 'Twice in a Service Period',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn (self $case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases(),
        );
    }
}