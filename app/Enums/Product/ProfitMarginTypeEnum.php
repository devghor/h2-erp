<?php

namespace App\Enums\Product;

enum ProfitMarginTypeEnum: string
{
    case Percentage = 'Percentage';
    case Flat       = 'Flat';

    public function label(): string
    {
        return match ($this) {
            self::Percentage => 'Percentage',
            self::Flat       => 'Flat',
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
