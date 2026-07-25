<?php

namespace App\DataTables\Configuration\Position;

use App\DataTables\BaseDataTable;
use App\Models\Configuration\Position\Position;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class PositionsDataTable extends BaseDataTable
{
    protected bool $fastExcel = true;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', fn (Position $p) => $p->created_at->format('Y-m-d H:i:s'))
            ->addColumn('branch_name', fn (Position $p) => $p->branch?->name)
            ->addColumn('division_name', fn (Position $p) => $p->division?->name)
            ->addColumn('department_name', fn (Position $p) => $p->department?->name)
            ->addColumn('position_group_name', fn (Position $p) => $p->positionGroup?->name)
            ->addColumn('parent_name', fn (Position $p) => $p->parent?->name)
            ->setRowId('id');
    }

    public function query(Position $model): QueryBuilder
    {
        $query = $model
            ->with(['branch', 'division', 'department', 'positionGroup', 'parent'])
            ->select(['id', 'name', 'code', 'description', 'parent_id', 'branch_id', 'division_id', 'department_id', 'position_group_id', 'created_at']);

        if ($branchId = request('branch_id')) {
            $query->where('branch_id', $branchId);
        }
        if ($divisionId = request('division_id')) {
            $query->where('division_id', $divisionId);
        }
        if ($departmentId = request('department_id')) {
            $query->where('department_id', $departmentId);
        }
        if ($positionGroupId = request('position_group_filter')) {
            $query->where('position_group_id', $positionGroupId);
        }
        if ($parentId = request('parent_position_filter')) {
            $query->where('parent_id', $parentId);
        }

        return $query;
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title('Name'),
            Column::make('code')->title('Code'),
            Column::make('branch_name')->title('Branch'),
            Column::make('division_name')->title('Division'),
            Column::make('department_name')->title('Department'),
            Column::make('position_group_name')->title('Position Group'),
            Column::make('parent_name')->title('Parent Position'),
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
        return 'Positions_' . date('YmdHis');
    }
}
