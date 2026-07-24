<?php

namespace App\Models\Configuration\ApprovalFlow;

use App\Models\Configuration\ApprovalLevel\ApprovalLevel;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ApprovalFlowGroupItem extends Model
{
    use HasUlid, BelongsToTenant;

    protected $table = 'approval_flow_group_items';

    protected $fillable = [
        'approval_flow_group_id',
        'approval_level_id',
        'sequence',
    ];

    public function approvalFlowGroup()
    {
        return $this->belongsTo(ApprovalFlowGroup::class);
    }

    public function approvalLevel()
    {
        return $this->belongsTo(ApprovalLevel::class);
    }
}
