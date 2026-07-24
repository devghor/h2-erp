<?php

namespace App\Services\Configuration\ApprovalFlow;

use App\Models\Configuration\ApprovalFlow\ApprovalFlowGroup;
use App\Services\Core\CoreService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApprovalFlowGroupService extends CoreService
{
    protected function model(): string
    {
        return ApprovalFlowGroup::class;
    }

    public function create(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $group = $this->model->create([
                'approval_flow_id' => $data['approval_flow_id'],
                'position_group_id' => $data['position_group_id'],
            ]);

            $this->syncItems($group, $data['items']);

            return $group;
        });
    }

    public function update(array $data, $id): Model
    {
        return DB::transaction(function () use ($data, $id) {
            $group = $this->find($id);
            $group->update(['position_group_id' => $data['position_group_id']]);

            $this->syncItems($group, $data['items']);

            return $group;
        });
    }

    private function syncItems(ApprovalFlowGroup $group, array $items): void
    {
        $group->items()->delete();

        foreach ($items as $item) {
            $group->items()->create([
                'approval_level_id' => $item['approval_level_id'],
                'sequence' => $item['sequence'],
            ]);
        }
    }
}
