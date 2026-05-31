<?php

namespace App\Enums\Configuration\FunctionAssignment;

enum FunctionTypeEnum: int
{
    case HeadOfHR           = 1;
    case LeaveManager       = 2;
    case AttendanceManager  = 3;
    case PayrollManager     = 4;
    case RecruitmentManager = 5;

    public function label(): string
    {
        return match ($this) {
            self::HeadOfHR           => 'Head of HR',
            self::LeaveManager       => 'Leave Manager',
            self::AttendanceManager  => 'Attendance Manager',
            self::PayrollManager     => 'Payroll Manager',
            self::RecruitmentManager => 'Recruitment Manager',
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
