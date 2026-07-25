<?php

namespace App\Enums\Leave;

enum LeaveAvailabilityEnum: string
{
    case Always              = 'always';
    case AfterHrApproval     = 'after_hr_approval';

    public function label(): string
    {
        return match ($this) {
            self::Always           => 'Always',
            self::AfterHrApproval  => 'After HR Approval',
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