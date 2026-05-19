<?php

namespace App\Enums\Configuration\Desk;

enum DeskGroupEnum: int
{
    case Ceo = 1;
    case DyCeo = 2;
    case Hod = 3;
    case DivisionHead = 4;
    case BelowDivisionHead = 5;
    case BelowHod = 6;

    public function label(): string
    {
        return match ($this) {
            self::Ceo => 'CEO',
            self::DyCeo => 'Dy. CEO',
            self::Hod => 'HOD',
            self::DivisionHead => 'Division Head',
            self::BelowDivisionHead => 'Below Division Head',
            self::BelowHod => 'Below HOD',
        };
    }

    public static function options(): array
    {
        return array_map(fn($case) => ['value' => $case->value, 'label' => $case->label()], self::cases());
    }
}
