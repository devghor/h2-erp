<?php

namespace App\Models\Configuration\ApprovalLevel;

use App\Enums\Configuration\ApprovalLevelTypeEnum;
use App\Models\Configuration\Hrbp\Hrbp;
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
        'hrbp_id',
    ];

    protected function casts(): array
    {
        return [
            'type' => ApprovalLevelTypeEnum::class,
            'position_ids' => 'array',
        ];
    }

    public function hrbp()
    {
        return $this->belongsTo(Hrbp::class);
    }
}
