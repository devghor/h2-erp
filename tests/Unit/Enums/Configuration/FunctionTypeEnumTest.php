<?php

namespace Tests\Unit\Enums\Configuration;

use App\Enums\Configuration\FunctionAssignment\FunctionTypeEnum;
use PHPUnit\Framework\TestCase;

class FunctionTypeEnumTest extends TestCase
{
    public function test_has_five_cases(): void
    {
        $this->assertCount(5, FunctionTypeEnum::cases());
    }

    public function test_labels(): void
    {
        $this->assertSame('Head of HR', FunctionTypeEnum::HeadOfHR->label());
        $this->assertSame('Leave Manager', FunctionTypeEnum::LeaveManager->label());
        $this->assertSame('Attendance Manager', FunctionTypeEnum::AttendanceManager->label());
        $this->assertSame('Payroll Manager', FunctionTypeEnum::PayrollManager->label());
        $this->assertSame('Recruitment Manager', FunctionTypeEnum::RecruitmentManager->label());
    }

    public function test_options_structure(): void
    {
        $options = FunctionTypeEnum::options();
        $this->assertCount(5, $options);
        $this->assertArrayHasKey('value', $options[0]);
        $this->assertArrayHasKey('label', $options[0]);
        $this->assertSame(1, $options[0]['value']);
        $this->assertSame('Head of HR', $options[0]['label']);
    }

    public function test_integer_values(): void
    {
        $this->assertSame(1, FunctionTypeEnum::HeadOfHR->value);
        $this->assertSame(2, FunctionTypeEnum::LeaveManager->value);
        $this->assertSame(3, FunctionTypeEnum::AttendanceManager->value);
        $this->assertSame(4, FunctionTypeEnum::PayrollManager->value);
        $this->assertSame(5, FunctionTypeEnum::RecruitmentManager->value);
    }
}
