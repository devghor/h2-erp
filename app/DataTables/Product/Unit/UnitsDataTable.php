<?php

namespace App\DataTables\Product\Unit;

use App\DataTables\BaseDataTable;
use App\Models\Product\Unit;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class UnitsDataTable extends BaseDataTable
{
    protected bool $fastExcel = true;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', fn (Unit $u) => $u->created_at->format('Y-m-d H:i:s'))
            ->setRowId('id');
    }

    public function query(Unit $model): QueryBuilder
    {
        $query = $model->select(['id', 'name', 'code', 'created_at']);

        if ($name = request('name')) {
            $query->where('name', 'like', "%{$name}%");
        }

        if ($code = request('code')) {
            $query->where('code', 'like', "%{$code}%");
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
            Column::make('code')->title('Code'),
            Column::make('created_at')->title('Created At'),
        ];
    }

    protected function filename(): string
    {
        return 'ProductUnits_' . date('YmdHis');
    }
}
