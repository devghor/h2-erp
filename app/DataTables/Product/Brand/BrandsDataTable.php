<?php

namespace App\DataTables\Product\Brand;

use App\DataTables\BaseDataTable;
use App\Enums\Media\MediaCollectionEnum;
use App\Models\Product\Brand;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class BrandsDataTable extends BaseDataTable
{
    protected bool $fastExcel = true;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('logo_url', fn (Brand $b) => $b->getFirstMediaUrl(MediaCollectionEnum::ProductBrandLogo->value))
            ->editColumn('created_at', fn (Brand $b) => $b->created_at->format('Y-m-d H:i:s'))
            ->setRowId('id');
    }

    public function query(Brand $model): QueryBuilder
    {
        $query = $model->select(['id', 'name', 'created_at']);

        if ($name = request('name')) {
            $query->where('name', 'like', "%{$name}%");
        }

        if ($from = request('created_at_from')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = request('created_at_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        return $query;
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title('Name'),
            Column::computed('logo_url')->title('Logo'),
            Column::make('created_at')->title('Created At'),
        ];
    }

    protected function filename(): string
    {
        return 'ProductBrands_' . date('YmdHis');
    }
}
