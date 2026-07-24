<?php

namespace App\Enums\Configuration;

enum ApprovalFlowTypeEnum: string
{
    case Leave      = 'Leave';
    case Attendance = 'Attendance';

    public function label(): string
    {
        return match ($this) {
            self::Leave      => 'Leave',
            self::Attendance => 'Attendance',
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
