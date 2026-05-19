<?php

namespace App\DataTables\Configuration\Desk;

use App\DataTables\BaseDataTable;
use App\Models\Configuration\Desk\Desk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class DesksDataTable extends BaseDataTable
{
    protected bool $fastExcel = true;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', fn (Desk $d) => $d->created_at->format('Y-m-d H:i:s'))
            ->editColumn('desk_group', fn (Desk $d) => $d->desk_group?->label())
            ->addColumn('branch_name', fn (Desk $d) => $d->branch?->name)
            ->addColumn('division_name', fn (Desk $d) => $d->division?->name)
            ->addColumn('department_name', fn (Desk $d) => $d->department?->name)
            ->setRowId('id');
    }

    public function query(Desk $model): QueryBuilder
    {
        $query = $model
            ->with(['branch', 'division', 'department'])
            ->select(['id', 'name', 'description', 'branch_id', 'division_id', 'department_id', 'desk_group', 'created_at']);

        if ($branchId = request('branch_id')) {
            $query->where('branch_id', $branchId);
        }
        if ($divisionId = request('division_id')) {
            $query->where('division_id', $divisionId);
        }
        if ($departmentId = request('department_id')) {
            $query->where('department_id', $departmentId);
        }
        if ($deskGroup = request('desk_group_filter')) {
            $query->where('desk_group', $deskGroup);
        }

        return $query;
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title('Name'),
            Column::make('branch_name')->title('Branch'),
            Column::make('division_name')->title('Division'),
            Column::make('department_name')->title('Department'),
            Column::make('desk_group')->title('Desk Group'),
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
        return 'Desks_' . date('YmdHis');
    }
}
