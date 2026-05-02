<?php

namespace App\Models\Product;

use App\Enums\Media\MediaCollectionEnum;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Brand extends Model implements HasMedia
{
    use HasFactory, BelongsToTenant, InteractsWithMedia, HasUlid;

    protected $table = 'product_brands';

    protected $fillable = ['name', 'company_id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionEnum::ProductBrandLogo->value)
            ->singleFile();
    }
}
