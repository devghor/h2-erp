<?php

namespace App\Models\Product;

use App\Enums\Media\MediaCollectionEnum;
use App\Traits\HasUserTracking;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Category extends Model implements HasMedia
{
    use BelongsToTenant, HasUlids, SoftDeletes, HasUserTracking, InteractsWithMedia;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'parent_category_id',
        'company_id',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_category_id');
    }

    public function registerAllMediaConversions(): void
    {
        $this->addMediaCollection(MediaCollectionEnum::ProductCategogy->value)->singleFile();
    }
}
