<?php

namespace App\Enums\Payroll;

enum PaymentStatusEnum: string
{
    case Pending = 'pending';
    case Paid    = 'paid';
    case Failed  = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Paid    => 'Paid',
            self::Failed  => 'Failed',
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
