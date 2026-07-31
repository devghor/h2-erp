<?php

namespace App\Models\Configuration\Hrbp;

use App\Models\Uam\User;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Hrbp extends Model
{
    use HasUlid, BelongsToTenant;

    protected $table = 'hrbps';

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
