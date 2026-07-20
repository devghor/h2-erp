<?php

namespace App\DataTables\Configuration\Department;

use App\DataTables\BaseDataTable;
use App\Models\Configuration\Department\Department;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class DepartmentsDataTable extends BaseDataTable
{
    protected bool $fastExcel = true;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('division', fn (Department $d) => $d->division?->name ?? '')
            ->addColumn('departmentHead', fn (Department $d) => $d->departmentHead?->name ?? '')
            ->editColumn('created_at', fn (Department $d) => $d->created_at->format('Y-m-d H:i:s'))
            ->setRowId('id');
    }

    public function query(Department $model): QueryBuilder
    {
        return $model->with(['division', 'departmentHead'])
            ->select(['id', 'name', 'division_id', 'description', 'department_head_user_id', 'created_at']);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title('Name'),
            Column::computed('division')->title('Division'),
            Column::computed('departmentHead')->title('Department Head'),
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
        return 'Departments_' . date('YmdHis');
    }
}
