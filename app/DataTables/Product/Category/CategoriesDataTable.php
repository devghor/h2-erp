<?php

namespace App\DataTables\Product\Category;

use App\DataTables\BaseDataTable;
use App\Enums\Media\MediaCollectionEnum;
use App\Models\Product\Category;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class CategoriesDataTable extends BaseDataTable
{
    protected bool $fastExcel = true;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('image_url', fn (Category $c) => $c->getFirstMediaUrl(MediaCollectionEnum::ProductCategoryImage->value))
            ->editColumn('created_at', fn (Category $c) => $c->created_at->format('Y-m-d H:i:s'))
            ->setRowId('id');
    }

    public function query(Category $model): QueryBuilder
    {
        $query = $model
            ->leftJoin('product_categories as parent_cat', 'product_categories.parent_id', '=', 'parent_cat.id')
            ->select([
                'product_categories.id',
                'product_categories.name',
                'product_categories.parent_id',
                'product_categories.created_at',
                'parent_cat.name as parent_name',
            ]);

        if ($name = request('name')) {
            $query->where('product_categories.name', 'like', "%{$name}%");
        }

        if ($parentName = request('parent_name')) {
            $query->where('parent_cat.name', 'like', "%{$parentName}%");
        }

        if ($from = request('created_at_from')) {
            $query->whereDate('product_categories.created_at', '>=', $from);
        }

        if ($to = request('created_at_to')) {
            $query->whereDate('product_categories.created_at', '<=', $to);
        }

        return $query;
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title('Name'),
            Column::computed('parent_name')->title('Parent Category'),
            Column::computed('image_url')->title('Image'),
            Column::make('created_at')->title('Created At'),
        ];
    }

    public function pdf()
    {
        return Pdf::loadView($this->printPreview, ['data' => $this->getDataForPrint()])
            ->setPaper('a4', 'landscape')
            ->download($this->getFilename() . '.pdf');
    }

    protected function filename(): string
    {
        return 'ProductCategories_' . date('YmdHis');
    }
}
