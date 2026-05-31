<?php

namespace App\Models\Configuration\DeskGroup;

use App\Enums\Configuration\Desk\DeskGroupEnum;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class DeskGroup extends Model
{
    use HasUlid, BelongsToTenant;

    protected $table = 'desk_groups';

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
    ];

    protected $casts = [
        'type' => DeskGroupEnum::class,
    ];
}
