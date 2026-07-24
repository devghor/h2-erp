<?php

namespace App\DataTables\Configuration\ApprovalFlow;

use App\DataTables\BaseDataTable;
use App\Models\Configuration\ApprovalFlow\ApprovalFlow;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class ApprovalFlowsDataTable extends BaseDataTable
{
    protected bool $fastExcel = true;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', fn (ApprovalFlow $f) => $f->created_at->format('Y-m-d H:i:s'))
            ->addColumn('type_label', fn (ApprovalFlow $f) => $f->type->label())
            ->addColumn('group_count', fn (ApprovalFlow $f) => $f->groups_count)
            ->setRowId('id');
    }

    public function query(ApprovalFlow $model): QueryBuilder
    {
        return $model->withCount('groups')->select(['id', 'name', 'type', 'created_at']);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title('Name'),
            Column::make('type_label')->title('Type'),
            Column::make('group_count')->title('Groups'),
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
        return 'ApprovalFlows_' . date('YmdHis');
    }
}
