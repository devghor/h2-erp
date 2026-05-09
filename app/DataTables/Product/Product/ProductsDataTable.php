<?php

namespace App\DataTables\Product\Product;

use App\DataTables\BaseDataTable;
use App\Enums\Media\MediaCollectionEnum;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class ProductsDataTable extends BaseDataTable
{
    protected bool $fastExcel = true;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('image_url', fn (Product $p) => $p->getFirstMediaUrl(MediaCollectionEnum::ProductImage->value))
            ->editColumn('type', fn (Product $p) => $p->type->label())
            ->editColumn('is_featured', fn (Product $p) => $p->is_featured ? 'Yes' : 'No')
            ->editColumn('created_at', fn (Product $p) => $p->created_at->format('Y-m-d H:i:s'))
            ->setRowId('id');
    }

    public function query(Product $model): QueryBuilder
    {
        $query = $model
            ->leftJoin('product_brands as pb', 'product_products.product_brand_id', '=', 'pb.id')
            ->leftJoin('product_categories as pc', 'product_products.product_category_id', '=', 'pc.id')
            ->select([
                'product_products.id',
                'product_products.name',
                'product_products.type',
                'product_products.code',
                'product_products.product_cost',
                'product_products.product_price',
                'product_products.is_featured',
                'product_products.created_at',
                'pb.name as brand_name',
                'pc.name as category_name',
            ]);

        if ($name = request('name')) {
            $query->where('product_products.name', 'like', "%{$name}%");
        }

        if ($type = request('type')) {
            $query->where('product_products.type', $type);
        }

        if ($brandId = request('brand_id')) {
            $query->where('product_products.product_brand_id', $brandId);
        }

        if ($categoryId = request('category_id')) {
            $query->where('product_products.product_category_id', $categoryId);
        }

        if ($from = request('created_at_from')) {
            $query->whereDate('product_products.created_at', '>=', $from);
        }

        if ($to = request('created_at_to')) {
            $query->whereDate('product_products.created_at', '<=', $to);
        }

        return $query;
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title('Name'),
            Column::computed('image_url')->title('Image'),
            Column::make('type')->title('Type'),
            Column::make('code')->title('Code'),
            Column::computed('brand_name')->title('Brand'),
            Column::computed('category_name')->title('Category'),
            Column::make('product_cost')->title('Cost'),
            Column::make('product_price')->title('Price'),
            Column::make('is_featured')->title('Featured'),
            Column::make('created_at')->title('Created At'),
        ];
    }

    protected function filename(): string
    {
        return 'Products_' . date('YmdHis');
    }
}
