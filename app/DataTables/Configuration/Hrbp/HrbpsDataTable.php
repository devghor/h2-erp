<?php

namespace App\DataTables\Configuration\Hrbp;

use App\DataTables\BaseDataTable;
use App\Models\Configuration\Hrbp\Hrbp;
use App\Models\Uam\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class HrbpsDataTable extends BaseDataTable
{
    protected bool $fastExcel = true;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $userNames = User::pluck('name', 'id');

        return (new EloquentDataTable($query))
            ->editColumn('created_at', fn (Hrbp $h) => $h->created_at->format('Y-m-d H:i:s'))
            ->addColumn('user_names', fn (Hrbp $h) => collect($h->user_ids ?? [])->map(fn ($id) => $userNames->get($id))->filter()->implode(', '))
            ->addColumn('user_count', fn (Hrbp $h) => count($h->user_ids ?? []))
            ->setRowId('id');
    }

    public function query(Hrbp $model): QueryBuilder
    {
        return $model->select(['id', 'name', 'user_ids', 'created_at']);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title('Name'),
            Column::make('user_names')->title('Users'),
            Column::make('user_count')->title('User Count'),
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
