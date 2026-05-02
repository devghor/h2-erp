<?php

namespace App\Models\Product;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Unit extends Model
{
    use HasFactory, BelongsToTenant, HasUlid;

    protected $table = 'product_units';

    protected $fillable = ['name', 'code', 'company_id'];
}
