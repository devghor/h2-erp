<?php

namespace Tests\Feature\Configuration;

use App\Enums\Configuration\FunctionAssignment\FunctionTypeEnum;
use App\Models\Configuration\FunctionAssignment\FunctionAssignment;
use App\Models\Uam\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FunctionAssignmentTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsUser(): static
    {
        return $this->actingAs(User::factory()->create());
    }

    public function test_index_returns_success(): void
    {
        $this->actingAsUser()
            ->get(route('configuration.function-assignments.index'))
            ->assertOk();
    }

    public function test_store_creates_record(): void
    {
        $userId = User::factory()->create()->id;

        $this->actingAsUser()
            ->post(route('configuration.function-assignments.store'), [
                'name'        => 'HR Head Assignment',
                'code'        => 'HRA-001',
                'user_ids'    => [$userId],
                'description' => 'Head of HR role',
                'type'        => FunctionTypeEnum::HeadOfHR->value,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('function_assignments', [
            'name' => 'HR Head Assignment',
            'type' => FunctionTypeEnum::HeadOfHR->value,
        ]);
    }

    public function test_update_modifies_record(): void
    {
        $userId     = User::factory()->create()->id;
        $assignment = FunctionAssignment::create([
            'name'       => 'Old Name',
            'code'       => 'OLD-001',
            'user_ids'   => [$userId],
            'description'=> null,
            'type'       => FunctionTypeEnum::HeadOfHR->value,
            'company_id' => 1,
        ]);

        $this->actingAsUser()
            ->put(route('configuration.function-assignments.update', $assignment->id), [
                'name'        => 'New Name',
                'code'        => 'NEW-001',
                'user_ids'    => [$userId],
                'description' => 'Updated',
                'type'        => FunctionTypeEnum::HeadOfHR->value,
            ])
            ->assertRedirect(route('configuration.function-assignments.index'));

        $this->assertDatabaseHas('function_assignments', ['name' => 'New Name']);
    }

    public function test_destroy_removes_record(): void
    {
        $userId     = User::factory()->create()->id;
        $assignment = FunctionAssignment::create([
            'name'       => 'To Delete',
            'user_ids'   => [$userId],
            'type'       => FunctionTypeEnum::LeaveManager->value,
            'company_id' => 1,
        ]);

        $this->actingAsUser()
            ->delete(route('configuration.function-assignments.destroy', $assignment->id))
            ->assertRedirect(route('configuration.function-assignments.index'));

        $this->assertDatabaseMissing('function_assignments', ['id' => $assignment->id]);
    }

    public function test_bulk_delete_removes_multiple_records(): void
    {
        $userId = User::factory()->create()->id;
        $a1 = FunctionAssignment::create(['name'=>'A1','user_ids'=>[$userId],'type'=>FunctionTypeEnum::AttendanceManager->value,'company_id'=>1]);
        $a2 = FunctionAssignment::create(['name'=>'A2','user_ids'=>[$userId],'type'=>FunctionTypeEnum::PayrollManager->value,'company_id'=>1]);

        $this->actingAsUser()
            ->delete(route('configuration.function-assignments.bulk-delete'), ['ids' => [$a1->id, $a2->id]])
            ->assertOk()
            ->assertJson(['message' => 'Function assignments deleted successfully.']);

        $this->assertDatabaseMissing('function_assignments', ['id' => $a1->id]);
        $this->assertDatabaseMissing('function_assignments', ['id' => $a2->id]);
    }
}
