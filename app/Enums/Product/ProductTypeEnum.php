<?php

namespace App\Enums\Product;

enum ProductTypeEnum: string
{
    case Standard = 'Standard';
    case Combo    = 'Combo';
    case Digital  = 'Digital';
    case Service  = 'Service';

    public function label(): string
    {
        return match ($this) {
            self::Standard => 'Standard',
            self::Combo    => 'Combo',
            self::Digital  => 'Digital',
            self::Service  => 'Service',
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
