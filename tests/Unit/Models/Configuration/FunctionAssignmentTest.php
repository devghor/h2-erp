<?php

namespace Tests\Unit\Models\Configuration;

use App\Enums\Configuration\FunctionAssignment\FunctionTypeEnum;
use App\Models\Configuration\FunctionAssignment\FunctionAssignment;
use App\Traits\HasUlid;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Tests\TestCase;

class FunctionAssignmentTest extends TestCase
{
    public function test_uses_has_ulid_trait(): void
    {
        $this->assertContains(HasUlid::class, class_uses_recursive(FunctionAssignment::class));
    }

    public function test_uses_belongs_to_tenant_trait(): void
    {
        $this->assertContains(BelongsToTenant::class, class_uses_recursive(FunctionAssignment::class));
    }

    public function test_fillable_fields(): void
    {
        $model = new FunctionAssignment();
        $this->assertSame(['name', 'code', 'user_ids', 'description', 'type'], $model->getFillable());
    }

    public function test_casts_user_ids_to_array(): void
    {
        $casts = (new FunctionAssignment())->getCasts();
        $this->assertArrayHasKey('user_ids', $casts);
        $this->assertSame('array', $casts['user_ids']);
    }

    public function test_casts_type_to_function_type_enum(): void
    {
        $casts = (new FunctionAssignment())->getCasts();
        $this->assertArrayHasKey('type', $casts);
        $this->assertSame(FunctionTypeEnum::class, $casts['type']);
    }

    public function test_table_name(): void
    {
        $this->assertSame('function_assignments', (new FunctionAssignment())->getTable());
    }
}
