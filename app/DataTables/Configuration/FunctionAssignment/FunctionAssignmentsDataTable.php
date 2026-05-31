<?php

namespace App\DataTables\Configuration\FunctionAssignment;

use App\DataTables\BaseDataTable;
use App\Models\Configuration\FunctionAssignment\FunctionAssignment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class FunctionAssignmentsDataTable extends BaseDataTable
{
    protected bool $fastExcel = true;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('type', fn (FunctionAssignment $row) => $row->type->value)
            ->addColumn('type_label', fn (FunctionAssignment $row) => $row->type->label())
            ->editColumn('created_at', fn (FunctionAssignment $row) => $row->created_at->format('Y-m-d H:i:s'))
            ->setRowId('id');
    }

    public function query(FunctionAssignment $model): QueryBuilder
    {
        return $model->select(['id', 'name', 'code', 'type', 'user_ids', 'description', 'created_at']);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title('Name'),
            Column::make('code')->title('Code'),
            Column::make('type_label')->title('Type'),
            Column::make('description')->title('Description'),
            Column::make('created_at')->title('Created At'),
        ];
    }

    public function pdf()
    {
        return Pdf::loadView($this->printPreview, ['data' => $this->getDataForPrint()])
            ->setPaper('a4', 'landscape')
            ->download($this->filename() . '.pdf');
    }

    protected function filename(): string
    {
        return 'FunctionAssignments_' . date('YmdHis');
    }
}
