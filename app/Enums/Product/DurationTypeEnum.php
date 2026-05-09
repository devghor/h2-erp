<?php

namespace App\Enums\Product;

enum DurationTypeEnum: string
{
    case Day   = 'Day';
    case Month = 'Month';
    case Year  = 'Year';

    public function label(): string
    {
        return match ($this) {
            self::Day   => 'Day',
            self::Month => 'Month',
            self::Year  => 'Year',
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
