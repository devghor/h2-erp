<?php

namespace App\Models\Configuration\ApprovalLevel;

use App\Enums\Configuration\ApprovalLevelTypeEnum;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ApprovalLevel extends Model
{
    use HasUlid, BelongsToTenant;

    protected $table = 'approval_levels';

    protected $fillable = [
        'name',
        'type',
        'position_ids',
        'position_group_ids',
        'hrbp_ids',
    ];

    protected function casts(): array
    {
        return [
            'type' => ApprovalLevelTypeEnum::class,
            'position_ids' => 'array',
            'position_group_ids' => 'array',
            'hrbp_ids' => 'array',
        ];
    }
}
