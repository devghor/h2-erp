<?php

namespace App\DataTables\Configuration\Hrbp;

use App\DataTables\BaseDataTable;
use App\Models\Configuration\Hrbp\Hrbp;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class HrbpsDataTable extends BaseDataTable
{
    protected bool $fastExcel = true;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', fn (Hrbp $h) => $h->created_at->format('Y-m-d H:i:s'))
            ->addColumn('user_name', fn (Hrbp $h) => $h->user?->name)
            ->setRowId('id');
    }

    public function query(Hrbp $model): QueryBuilder
    {
        return $model->with('user')->select(['id', 'user_id', 'created_at']);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('user_name')->title('User'),
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
        return 'Hrbps_' . date('YmdHis');
    }
}
