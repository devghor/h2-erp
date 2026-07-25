<?php

namespace App\Enums\Configuration;

enum ApprovalLevelTypeEnum: string
{
    case DivisionHead = 'DivisionHead';
    case HOD          = 'HOD';
    case LineManager  = 'LineManager';
    case HRBP         = 'HRBP';
    case Position     = 'Position';

    public function label(): string
    {
        return match ($this) {
            self::DivisionHead => 'Division Head',
            self::HOD          => 'Head of Department',
            self::LineManager  => 'Line Manager',
            self::HRBP         => 'HRBP',
            self::Position     => 'Position',
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
