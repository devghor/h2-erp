<?php

namespace App\Models\Product;

use App\Enums\Media\MediaCollectionEnum;
use App\Enums\Product\BarcodeSymbologyEnum;
use App\Enums\Product\DurationTypeEnum;
use App\Enums\Product\ProfitMarginTypeEnum;
use App\Enums\Product\ProductTaxEnum;
use App\Enums\Product\ProductTypeEnum;
use App\Enums\Product\TaxMethodEnum;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Product extends Model implements HasMedia
{
    use BelongsToTenant, HasFactory, HasUlid, InteractsWithMedia;

    protected $table = 'product_products';

    protected $fillable = [
        'name',
        'type',
        'code',
        'barcode_symbology',
        'product_brand_id',
        'product_category_id',
        'product_unit_id',
        'product_sale_unit_id',
        'product_purchase_unit_id',
        'product_cost',
        'profit_margin_type',
        'profit_margin',
        'product_price',
        'wholesale_price',
        'daily_sale_objective',
        'product_tax',
        'tax_method',
        'warranty_value',
        'warranty_duration_type',
        'guarantee_value',
        'guarantee_duration_type',
        'is_featured',
        'has_batch_and_expire_date',
        'has_imei_or_serial_no',
        'has_promotional_price',
        'embedded_barcode',
        'product_details',
        'company_id',
    ];

    protected $casts = [
        'type'                      => ProductTypeEnum::class,
        'barcode_symbology'         => BarcodeSymbologyEnum::class,
        'profit_margin_type'        => ProfitMarginTypeEnum::class,
        'product_tax'               => ProductTaxEnum::class,
        'tax_method'                => TaxMethodEnum::class,
        'warranty_duration_type'    => DurationTypeEnum::class,
        'guarantee_duration_type'   => DurationTypeEnum::class,
        'is_featured'               => 'boolean',
        'has_batch_and_expire_date' => 'boolean',
        'has_imei_or_serial_no'     => 'boolean',
        'has_promotional_price'     => 'boolean',
        'product_cost'              => 'decimal:4',
        'profit_margin'             => 'decimal:4',
        'product_price'             => 'decimal:4',
        'wholesale_price'           => 'decimal:4',
        'daily_sale_objective'      => 'decimal:4',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionEnum::ProductImage->value)->singleFile();
    }

    public function getImageUrlAttribute(): string
    {
        return $this->getFirstMediaUrl(MediaCollectionEnum::ProductImage->value);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'product_brand_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'product_category_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'product_unit_id');
    }

    public function saleUnit()
    {
        return $this->belongsTo(Unit::class, 'product_sale_unit_id');
    }

    public function purchaseUnit()
    {
        return $this->belongsTo(Unit::class, 'product_purchase_unit_id');
    }

    public function comboItems()
    {
        return $this->hasMany(ProductItem::class, 'product_id');
    }
}
