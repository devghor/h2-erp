<?php

namespace App\Http\Resources\Product\Category;

use App\Enums\Media\MediaCollectionEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'parent_id'   => $this->parent_id,
            'parent_name' => $this->parent?->name,
            'image_url'   => $this->getFirstMediaUrl(MediaCollectionEnum::ProductCategoryImage->value),
            'created_at'  => $this->created_at,
        ];
    }
}
