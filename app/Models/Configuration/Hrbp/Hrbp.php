<?php

namespace App\Models\Configuration\Hrbp;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Hrbp extends Model
{
    use HasUlid, BelongsToTenant;

    protected $table = 'hrbps';

    protected $fillable = [
        'name',
        'user_ids',
    ];

    protected function casts(): array
    {
        return [
            'user_ids' => 'array',
        ];
    }
}
