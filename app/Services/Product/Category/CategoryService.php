<?php

namespace App\Services\Product\Category;

use App\Enums\Media\MediaCollectionEnum;
use App\Models\Product\Category;
use App\Services\Core\CoreService;
use Illuminate\Database\Eloquent\Model;

class CategoryService extends CoreService
{
    protected function model(): string
    {
        return Category::class;
    }

    public function create(array $data): Model
    {
        $image = $data['image'] ?? null;
        unset($data['image']);

        $category = $this->model->create($data);

        if ($image) {
            $category->addMedia($image)->toMediaCollection(MediaCollectionEnum::ProductCategoryImage->value);
        }

        return $category;
    }

    public function update(array $data, $id): Model
    {
        $image = $data['image'] ?? null;
        unset($data['image']);

        $category = $this->find($id);
        $category->update($data);

        if ($image) {
            $category->clearMediaCollection(MediaCollectionEnum::ProductCategoryImage->value);
            $category->addMedia($image)->toMediaCollection(MediaCollectionEnum::ProductCategoryImage->value);
        }

        return $category;
    }
}
