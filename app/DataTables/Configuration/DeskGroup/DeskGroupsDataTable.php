<?php

namespace App\DataTables\Configuration\DeskGroup;

use App\DataTables\BaseDataTable;
use App\Models\Configuration\DeskGroup\DeskGroup;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class DeskGroupsDataTable extends BaseDataTable
{
    protected bool $fastExcel = true;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', fn (DeskGroup $dg) => $dg->created_at->format('Y-m-d H:i:s'))
            ->editColumn('type', fn (DeskGroup $dg) => $dg->type?->label())
            ->addColumn('type_value', fn (DeskGroup $dg) => $dg->type?->value)
            ->setRowId('id');
    }

    public function query(DeskGroup $model): QueryBuilder
    {
        return $model->select(['id', 'name', 'code', 'description', 'type', 'created_at']);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title('Name'),
            Column::make('code')->title('Code'),
            Column::make('type')->title('Type'),
            Column::make('description')->title('Description'),
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
        return 'DeskGroups_' . date('YmdHis');
    }
}
