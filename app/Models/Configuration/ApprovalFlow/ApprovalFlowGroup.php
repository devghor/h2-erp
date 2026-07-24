<?php

namespace App\Models\Configuration\ApprovalFlow;

use App\Models\Configuration\PositionGroup\PositionGroup;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ApprovalFlowGroup extends Model
{
    use HasUlid, BelongsToTenant;

    protected $table = 'approval_flow_groups';

    protected $fillable = [
        'approval_flow_id',
        'position_group_id',
    ];

    public function approvalFlow()
    {
        return $this->belongsTo(ApprovalFlow::class);
    }

    public function positionGroup()
    {
        return $this->belongsTo(PositionGroup::class);
    }

    public function items()
    {
        return $this->hasMany(ApprovalFlowGroupItem::class)->orderBy('sequence');
    }
}
