<?php

namespace App\Models\Configuration\Department;

use App\Models\Configuration\Division\Division;
use App\Models\Uam\User;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Department extends Model
{
    use HasUlid, BelongsToTenant;

    protected $table = 'departments';

    protected $fillable = [
        'name',
        'division_id',
        'description',
        'department_head_user_id',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function departmentHead()
    {
        return $this->belongsTo(User::class, 'department_head_user_id');
    }
}
