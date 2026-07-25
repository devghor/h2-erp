<?php

namespace App\DataTables\Configuration\Shift;

use App\DataTables\BaseDataTable;
use App\Models\Configuration\Shift\Shift;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class ShiftsDataTable extends BaseDataTable
{
    protected bool $fastExcel = true;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('company', fn(Shift $shift) => $shift->company?->name ?? '')
            ->editColumn('created_at', fn(Shift $shift) => $shift->created_at->format('Y-m-d H:i:s'))
            ->setRowId('id');
    }

    public function query(Shift $model): QueryBuilder
    {
        return $model->with('company')->select([
            'id',
            'company_id',
            'name',
            'working_days',
            'weekends',
            'start_time',
            'end_time',
            'special_working_dates',
            'special_weekend_dates',
            'created_at',
        ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::computed('company')->title('Company'),
            Column::make('name')->title('Name'),
            Column::make('working_days')->title('Working Days'),
            Column::make('weekends')->title('Weekends'),
            Column::make('start_time')->title('Start Time'),
            Column::make('end_time')->title('End Time'),
            Column::make('special_working_dates')->title('Special Working Dates'),
            Column::make('special_weekend_dates')->title('Special Weekend Dates'),
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
        return 'Shifts_' . date('YmdHis');
    }
}
