<?php

namespace App\Models\Configuration\Branch;

use App\Models\Configuration\Company\Company;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Branch extends Model
{
    use BelongsToTenant, HasUlid;

    protected $table = 'branches';

    protected $fillable = [
        'company_id',
        'name',
        'short_name',
        'code',
        'address',
        'phone',
        'mobile',
        'email',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
