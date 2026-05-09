<?php

namespace App\Enums\Payroll;

enum SalaryDisbursementBatchStatusEnum: string
{
    case Generated           = 'generated';
    case Processed           = 'processed';
    case SentForApproval     = 'sent_for_approval';
    case RevertFromApproval  = 'revert_from_approval';
    case SentForDisbursement = 'sent_for_disbursement';
    case Disbursed           = 'disbursed';

    public function label(): string
    {
        return match ($this) {
            self::Generated           => 'Draft',
            self::Processed           => 'Processed',
            self::SentForApproval     => 'Pending Approval',
            self::RevertFromApproval  => 'Reverted',
            self::SentForDisbursement => 'Pending Disbursement',
            self::Disbursed           => 'Disbursed',
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
