<?php

namespace App\Http\Requests\Product\Product;

use App\Enums\Product\BarcodeSymbologyEnum;
use App\Enums\Product\DurationTypeEnum;
use App\Enums\Product\ProfitMarginTypeEnum;
use App\Enums\Product\ProductTaxEnum;
use App\Enums\Product\ProductTypeEnum;
use App\Enums\Product\TaxMethodEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                          => ['required', 'string', 'max:255'],
            'type'                          => ['required', new Enum(ProductTypeEnum::class)],
            'code'                          => ['nullable', 'string', 'max:100'],
            'barcode_symbology'             => ['nullable', new Enum(BarcodeSymbologyEnum::class)],
            'product_brand_id'              => ['nullable', 'exists:product_brands,id'],
            'product_category_id'           => ['nullable', 'exists:product_categories,id'],
            'product_unit_id'               => ['nullable', 'exists:product_units,id'],
            'product_sale_unit_id'          => ['nullable', 'exists:product_units,id'],
            'product_purchase_unit_id'      => ['nullable', 'exists:product_units,id'],
            'product_cost'                  => ['nullable', 'numeric', 'min:0'],
            'profit_margin_type'            => ['nullable', new Enum(ProfitMarginTypeEnum::class)],
            'profit_margin'                 => ['nullable', 'numeric', 'min:0'],
            'product_price'                 => ['nullable', 'numeric', 'min:0'],
            'wholesale_price'               => ['nullable', 'numeric', 'min:0'],
            'daily_sale_objective'          => ['nullable', 'numeric', 'min:0'],
            'product_tax'                   => ['nullable', new Enum(ProductTaxEnum::class)],
            'tax_method'                    => ['nullable', new Enum(TaxMethodEnum::class)],
            'warranty_value'                => ['nullable', 'integer', 'min:0'],
            'warranty_duration_type'        => ['nullable', new Enum(DurationTypeEnum::class)],
            'guarantee_value'               => ['nullable', 'integer', 'min:0'],
            'guarantee_duration_type'       => ['nullable', new Enum(DurationTypeEnum::class)],
            'is_featured'                   => ['nullable', 'boolean'],
            'has_batch_and_expire_date'     => ['nullable', 'boolean'],
            'has_imei_or_serial_no'         => ['nullable', 'boolean'],
            'has_promotional_price'         => ['nullable', 'boolean'],
            'embedded_barcode'              => ['nullable', 'string'],
            'product_image'                 => ['nullable', 'image', 'max:2048'],
            'product_details'               => ['nullable', 'string'],
            'combo_items'                   => ['nullable', 'array'],
            'combo_items.*.item_product_id' => ['required_with:combo_items', 'exists:product_products,id'],
            'combo_items.*.quantity'        => ['required_with:combo_items', 'numeric', 'min:0.0001'],
            'combo_items.*.unit_cost'       => ['nullable', 'numeric', 'min:0'],
            'combo_items.*.unit_price'      => ['nullable', 'numeric', 'min:0'],
            'combo_items.*.wastage_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
