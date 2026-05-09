<?php

namespace App\Models\Product;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductItem extends Model
{
    use HasFactory, HasUlid;

    protected $table = 'product_product_items';

    protected $fillable = [
        'product_id',
        'item_product_id',
        'quantity',
        'unit_cost',
        'unit_price',
        'wastage_percent',
    ];

    protected $casts = [
        'quantity'        => 'decimal:4',
        'unit_cost'       => 'decimal:4',
        'unit_price'      => 'decimal:4',
        'wastage_percent' => 'decimal:4',
    ];

    public function getSubTotalAttribute(): float
    {
        return (float) $this->unit_price * (float) $this->quantity;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function itemProduct()
    {
        return $this->belongsTo(Product::class, 'item_product_id');
    }
}
