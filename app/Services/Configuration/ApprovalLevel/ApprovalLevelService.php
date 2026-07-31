<?php

namespace App\Services\Configuration\ApprovalLevel;

use App\Enums\Configuration\ApprovalLevelTypeEnum;
use App\Models\Configuration\ApprovalLevel\ApprovalLevel;
use App\Services\Core\CoreService;
use Illuminate\Database\Eloquent\Model;

class ApprovalLevelService extends CoreService
{
    protected function model(): string
    {
        return ApprovalLevel::class;
    }

    public function create(array $data): Model
    {
        return $this->model->create($this->prepareData($data));
    }

    public function update(array $data, $id): Model
    {
        $level = $this->find($id);
        $level->update($this->prepareData($data));

        return $level;
    }

    private function prepareData(array $data): array
    {
        $type = $data['type'];

        $data['hrbp_ids'] = $type === ApprovalLevelTypeEnum::HRBP->value
            ? ($data['hrbp_ids'] ?? [])
            : null;

        $data['position_ids'] = $type === ApprovalLevelTypeEnum::Position->value
            ? ($data['position_ids'] ?? [])
            : null;

        $data['position_group_ids'] = $type === ApprovalLevelTypeEnum::PositionGroup->value
            ? ($data['position_group_ids'] ?? [])
            : null;

        return $data;
    }
}
