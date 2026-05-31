<?php

namespace App\Models\Configuration\FunctionAssignment;

use App\Enums\Configuration\FunctionAssignment\FunctionTypeEnum;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class FunctionAssignment extends Model
{
    use HasUlid, BelongsToTenant;

    protected $table = 'function_assignments';

    protected $fillable = [
        'name',
        'code',
        'user_ids',
        'description',
        'type',
        'company_id',
    ];

    protected $casts = [
        'user_ids' => 'array',
        'type'     => FunctionTypeEnum::class,
    ];
}
