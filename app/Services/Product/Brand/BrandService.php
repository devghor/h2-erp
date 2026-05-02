<?php

namespace App\Services\Product\Brand;

use App\Enums\Media\MediaCollectionEnum;
use App\Models\Product\Brand;
use App\Services\Core\CoreService;
use Illuminate\Database\Eloquent\Model;

class BrandService extends CoreService
{
    protected function model(): string
    {
        return Brand::class;
    }

    public function create(array $data): Model
    {
        $logo = $data['logo'] ?? null;
        unset($data['logo']);

        $brand = $this->model->create($data);

        if ($logo) {
            $brand->addMedia($logo)->toMediaCollection(MediaCollectionEnum::ProductBrandLogo->value);
        }

        return $brand;
    }

    public function update(array $data, $id): Model
    {
        $logo = $data['logo'] ?? null;
        unset($data['logo']);

        $brand = $this->find($id);
        $brand->update($data);

        if ($logo) {
            $brand->clearMediaCollection(MediaCollectionEnum::ProductBrandLogo->value);
            $brand->addMedia($logo)->toMediaCollection(MediaCollectionEnum::ProductBrandLogo->value);
        }

        return $brand;
    }
}
