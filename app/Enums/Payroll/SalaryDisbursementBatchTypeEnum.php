<?php

namespace App\Enums\Payroll;

enum SalaryDisbursementBatchTypeEnum: string
{
    case MonthlySalary = 'monthly_salary';
    case SpecialBatch  = 'special_batch';

    public function label(): string
    {
        return match ($this) {
            self::MonthlySalary => 'Monthly Salary',
            self::SpecialBatch  => 'Special Batch',
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
