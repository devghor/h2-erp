<?php

namespace App\DataTables\Payroll;

use App\DataTables\BaseDataTable;
use App\Models\Payroll\PayrollSalaryDisbursementBatch;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class PayrollSalaryDisbursementBatchesDataTable extends BaseDataTable
{
    protected bool $fastExcel = true;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('type', fn (PayrollSalaryDisbursementBatch $b) => $b->type?->label() ?? '')
            ->editColumn('status', fn (PayrollSalaryDisbursementBatch $b) => $b->status?->label() ?? '')
            ->editColumn('disbursement_date', fn (PayrollSalaryDisbursementBatch $b) => $b->disbursement_date?->format('Y-m-d') ?? '')
            ->editColumn('created_at', fn (PayrollSalaryDisbursementBatch $b) => $b->created_at->format('Y-m-d H:i:s'))
            ->addColumn('period', fn (PayrollSalaryDisbursementBatch $b) => sprintf('%04d-%02d', $b->year, $b->month))
            ->setRowId('id');
    }

    public function query(PayrollSalaryDisbursementBatch $model): QueryBuilder
    {
        return $model->select([
            'id',
            'ulid',
            'name',
            'year',
            'month',
            'type',
            'status',
            'employee_count',
            'total_net',
            'disbursement_date',
            'created_at',
        ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title('Name'),
            Column::make('period')->title('Period'),
            Column::make('type')->title('Type'),
            Column::make('status')->title('Status'),
            Column::make('employee_count')->title('Employees'),
            Column::make('total_net')->title('Net Total'),
            Column::make('disbursement_date')->title('Disbursement Date'),
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
        return 'PayrollSalaryDisbursementBatches_' . date('YmdHis');
    }
}
