<?php

namespace App\Models\Configuration\Shift;

use App\Models\Configuration\Company\Company;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Shift extends Model
{
    use HasUlid, BelongsToTenant;

    protected $table = 'shifts';

    protected $fillable = [
        'company_id',
        'name',
        'working_days',
        'weekends',
        'start_time',
        'end_time',
        'special_working_dates',
        'special_weekend_dates',
    ];

    protected $casts = [
        'working_days' => 'array',
        'weekends' => 'array',
        'special_working_dates' => 'array',
        'special_weekend_dates' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
