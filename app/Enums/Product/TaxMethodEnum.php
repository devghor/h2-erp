<?php

namespace App\Enums\Product;

enum TaxMethodEnum: string
{
    case Exclusive = 'Exclusive';
    case Inclusive = 'Inclusive';

    public function label(): string
    {
        return match ($this) {
            self::Exclusive => 'Exclusive',
            self::Inclusive => 'Inclusive',
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
