<?php

namespace App\Models\Product;

use App\Enums\Media\MediaCollectionEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Category extends Model implements HasMedia
{
    use HasFactory, BelongsToTenant, InteractsWithMedia;

    protected $table = 'product_categories';

    protected $fillable = ['name', 'parent_id', 'company_id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionEnum::ProductCategoryImage->value)
            ->singleFile();
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
