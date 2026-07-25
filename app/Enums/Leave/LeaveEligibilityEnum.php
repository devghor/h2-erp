<?php

namespace App\Enums\Leave;

enum LeaveEligibilityEnum: string
{
    case FromJoining                       = 'from_joining';
    case AfterOneYearConfirmedService      = 'after_one_year_confirmed_service';
    case AfterConfirmationFromNextYearJanuary = 'after_confirmation_from_next_year_january';

    public function label(): string
    {
        return match ($this) {
            self::FromJoining                        => 'From Joining',
            self::AfterOneYearConfirmedService       => 'After One Year Confirmed Service',
            self::AfterConfirmationFromNextYearJanuary => 'After Confirmation From Next Year January 1st',
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