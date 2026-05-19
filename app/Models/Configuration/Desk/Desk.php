<?php

namespace App\Models\Configuration\Desk;

use App\Enums\Configuration\Desk\DeskGroupEnum;
use App\Models\Configuration\Branch\Branch;
use App\Models\Configuration\Department\Department;
use App\Models\Configuration\Division\Division;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Desk extends Model
{
    use HasUlid, BelongsToTenant;

    protected $table = 'desks';

    protected $fillable = [
        'name',
        'parent_id',
        'description',
        'branch_id',
        'division_id',
        'department_id',
        'desk_group',
    ];

    protected $casts = [
        'desk_group' => DeskGroupEnum::class,
    ];

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
}
