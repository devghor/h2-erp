<?php

namespace App\DataTables\Leave\LeaveType;

use App\DataTables\BaseDataTable;
use App\Enums\Leave\LeaveAvailabilityEnum;
use App\Enums\Leave\LeaveEligibilityEnum;
use App\Enums\Leave\LeaveFrequencyEnum;
use App\Models\Leave\LeaveType\LeaveType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class LeaveTypesDataTable extends BaseDataTable
{
    protected bool $fastExcel = true;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('availability', fn (LeaveType $lt) => LeaveAvailabilityEnum::tryFrom($lt->availability)?->label() ?? $lt->availability)
            ->editColumn('frequency', fn (LeaveType $lt) => LeaveFrequencyEnum::tryFrom($lt->frequency)?->label() ?? $lt->frequency)
            ->editColumn('eligibility', fn (LeaveType $lt) => LeaveEligibilityEnum::tryFrom($lt->eligibility)?->label() ?? $lt->eligibility)
            ->editColumn('created_at', fn (LeaveType $lt) => $lt->created_at->format('Y-m-d H:i:s'))
            ->setRowId('id');
    }

    public function query(LeaveType $model): QueryBuilder
    {
        return $model->select([
            'id', 'name', 'code', 'leave_count', 'max_balance', 'availability',
            'frequency', 'eligibility', 'employee_type', 'advanced_leave',
            'document_required', 'carry_forward', 'allow_pending_leave', 'created_at',
        ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title('Name'),
            Column::make('code')->title('Code'),
            Column::make('leave_count')->title('Leave Count'),
            Column::make('max_balance')->title('Max Balance'),
            Column::make('availability')->title('Availability'),
            Column::make('frequency')->title('Frequency'),
            Column::make('eligibility')->title('Eligibility'),
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
        return 'LeaveTypes_' . date('YmdHis');
    }
}