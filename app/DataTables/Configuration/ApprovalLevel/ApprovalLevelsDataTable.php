<?php

namespace App\DataTables\Configuration\ApprovalLevel;

use App\DataTables\BaseDataTable;
use App\Models\Configuration\ApprovalLevel\ApprovalLevel;
use App\Models\Configuration\Hrbp\Hrbp;
use App\Models\Configuration\Position\Position;
use App\Models\Configuration\PositionGroup\PositionGroup;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class ApprovalLevelsDataTable extends BaseDataTable
{
    protected bool $fastExcel = true;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $positionNames = Position::pluck('name', 'id');
        $positionGroupNames = PositionGroup::pluck('name', 'id');
        $hrbpLabels = Hrbp::with('user')->get()->mapWithKeys(
            fn (Hrbp $h) => [$h->id => $h->user ? "{$h->user->name} ({$h->user->email})" : "HRBP #{$h->id}"]
        );

        return (new EloquentDataTable($query))
            ->editColumn('created_at', fn (ApprovalLevel $l) => $l->created_at->format('Y-m-d H:i:s'))
            ->addColumn('type_label', fn (ApprovalLevel $l) => $l->type->label())
            ->addColumn('hrbp_names', fn (ApprovalLevel $l) => collect($l->hrbp_ids ?? [])->map(fn ($id) => $hrbpLabels->get($id))->filter()->implode(', '))
            ->addColumn('position_names', fn (ApprovalLevel $l) => collect($l->position_ids ?? [])->map(fn ($id) => $positionNames->get($id))->filter()->implode(', '))
            ->addColumn('position_group_names', fn (ApprovalLevel $l) => collect($l->position_group_ids ?? [])->map(fn ($id) => $positionGroupNames->get($id))->filter()->implode(', '))
            ->setRowId('id');
    }

    public function query(ApprovalLevel $model): QueryBuilder
    {
        return $model->select(['id', 'name', 'type', 'position_ids', 'position_group_ids', 'hrbp_ids', 'created_at']);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title('Name'),
            Column::make('type_label')->title('Type'),
            Column::make('hrbp_names')->title('HRBP'),
            Column::make('position_names')->title('Positions'),
            Column::make('position_group_names')->title('Position Groups'),
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
        return 'ApprovalLevels_' . date('YmdHis');
    }
}
