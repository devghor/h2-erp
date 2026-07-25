<?php

namespace App\Models\Configuration\Position;

use App\Models\Configuration\Branch\Branch;
use App\Models\Configuration\Department\Department;
use App\Models\Configuration\Division\Division;
use App\Models\Configuration\PositionGroup\PositionGroup;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Position extends Model
{
    use HasUlid, BelongsToTenant;

    protected $table = 'positions';

    protected $fillable = [
        'name',
        'code',
        'parent_id',
        'description',
        'branch_id',
        'division_id',
        'department_id',
        'position_group_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Position::class, 'parent_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function positionGroup()
    {
        return $this->belongsTo(PositionGroup::class);
    }
}
