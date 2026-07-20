<?php

namespace App\Models\Configuration\Division;

use App\Models\Uam\User;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Division extends Model
{
    use HasUlid, BelongsToTenant;

    protected $table = 'divisions';

    protected $fillable = [
        'name',
        'description',
        'division_head_user_id',
    ];

    public function divisionHead()
    {
        return $this->belongsTo(User::class, 'division_head_user_id');
    }
}
