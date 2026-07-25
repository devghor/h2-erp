<?php

namespace App\Enums\Employee;

enum EmployeeTypeEnum: int
{
    case Regular             = 1;
    case Contractual         = 2;
    case OnProbation         = 3;
    case Casual              = 4;
    case RegularContractual  = 5;
    case DailyContractual    = 6;
    case RegularCasual       = 7;

    public function label(): string
    {
        return match ($this) {
            self::Regular            => 'Regular',
            self::Contractual        => 'Contractual',
            self::OnProbation        => 'On Probation',
            self::Casual             => 'Casual',
            self::RegularContractual => 'Regular Contractual',
            self::DailyContractual   => 'Daily Contractual',
            self::RegularCasual      => 'Regular Casual',
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
