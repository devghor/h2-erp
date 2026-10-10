<?php

namespace App\Models\Configuration\PositionGroup;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class PositionGroup extends Model
{
    use BelongsToTenant, HasUlid;

    protected $table = 'position_groups';

    protected $fillable = [
        'name',
        'code',
        'description',
    ];
}
