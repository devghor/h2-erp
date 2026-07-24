<?php

namespace App\Models\Configuration\ApprovalFlow;

use App\Enums\Configuration\ApprovalFlowTypeEnum;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ApprovalFlow extends Model
{
    use HasUlid, BelongsToTenant;

    protected $table = 'approval_flows';

    protected $fillable = [
        'name',
        'type',
    ];

    protected function casts(): array
    {
        return [
            'type' => ApprovalFlowTypeEnum::class,
        ];
    }

    public function groups()
    {
        return $this->hasMany(ApprovalFlowGroup::class);
    }
}
