<?php

namespace App\Services\Product\Product;

use App\Enums\Media\MediaCollectionEnum;
use App\Enums\Product\ProductTypeEnum;
use App\Models\Product\Product;
use App\Models\Product\ProductItem;
use App\Services\Core\CoreService;
use Illuminate\Database\Eloquent\Model;

class ProductService extends CoreService
{
    protected function model(): string
    {
        return Product::class;
    }

    public function create(array $data): Model
    {
        $image      = $data['product_image'] ?? null;
        $comboItems = $data['combo_items'] ?? [];
        unset($data['product_image'], $data['combo_items']);

        $product = $this->model->create($data);

        if ($image) {
            $product->addMedia($image)->toMediaCollection(MediaCollectionEnum::ProductImage->value);
        }

        $this->syncComboItems($product, $comboItems);

        return $product;
    }

    public function update(array $data, $id): Model
    {
        $image      = $data['product_image'] ?? null;
        $comboItems = $data['combo_items'] ?? [];
        unset($data['product_image'], $data['combo_items']);

        $product = $this->find($id);
        $product->update($data);

        if ($image) {
            $product->clearMediaCollection(MediaCollectionEnum::ProductImage->value);
            $product->addMedia($image)->toMediaCollection(MediaCollectionEnum::ProductImage->value);
        }

        $this->syncComboItems($product->fresh(), $comboItems);

        return $product;
    }

    private function syncComboItems(Product $product, array $items): void
    {
        if ($product->type !== ProductTypeEnum::Combo) {
            $product->comboItems()->delete();

            return;
        }

        $incomingIds = array_column($items, 'item_product_id');

        $product->comboItems()
            ->whereNotIn('item_product_id', $incomingIds)
            ->delete();

        foreach ($items as $item) {
            ProductItem::updateOrCreate(
                [
                    'product_id'      => $product->id,
                    'item_product_id' => $item['item_product_id'],
                ],
                [
                    'quantity'        => $item['quantity'] ?? 1,
                    'unit_cost'       => $item['unit_cost'] ?? null,
                    'unit_price'      => $item['unit_price'] ?? null,
                    'wastage_percent' => $item['wastage_percent'] ?? 0,
                ]
            );
        }
    }
}
