<?php

namespace App\Http\Controllers\Payroll;

use App\DataTables\Payroll\PayrollSalaryDisbursementBatchesDataTable;
use App\Enums\Payroll\SalaryDisbursementBatchStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\DisbursePayrollSalaryDisbursementBatchRequest;
use App\Http\Requests\Payroll\StorePayrollSalaryDisbursementBatchRequest;
use App\Http\Requests\Payroll\UpdatePayrollSalaryDisbursementBatchRequest;
use App\Models\Payroll\PayrollSalaryDisbursementBatch;
use App\Services\Payroll\PayrollSalaryDisbursementBatchService;
use Inertia\Inertia;

class PayrollSalaryDisbursementBatchController extends Controller
{
    public function __construct(
        private PayrollSalaryDisbursementBatchService $service,
    ) {}

    public function index(PayrollSalaryDisbursementBatchesDataTable $dataTable)
    {
        return $dataTable->renderInertia('payroll/salary-disbursement-batches/index');
    }

    public function show(string $id)
    {
        $batch = $this->service->getBatchWithEmployees($id);

        return Inertia::render('payroll/salary-disbursement-batches/show', [
            'batch'     => $batch,
            'employees' => $batch->employees,
        ]);
    }

    public function store(StorePayrollSalaryDisbursementBatchRequest $request)
    {
        $batch = $this->service->generateBatch($request->validated());

        return redirect()
            ->route('payroll.salary-disbursement-batches.show', $batch->id)
            ->with('success', 'Salary disbursement batch generated successfully.');
    }

    public function update(UpdatePayrollSalaryDisbursementBatchRequest $request, string $id)
    {
        $batch = PayrollSalaryDisbursementBatch::findOrFail($id);
        $batch->update([
            'name'       => $request->validated('name'),
            'remark'     => $request->validated('remark'),
            'updated_by' => auth()->id(),
        ]);

        return redirect()
            ->route('payroll.salary-disbursement-batches.show', $id)
            ->with('success', 'Batch updated successfully.');
    }

    public function destroy(string $id)
    {
        $batch = PayrollSalaryDisbursementBatch::findOrFail($id);

        abort_if(
            $batch->status !== SalaryDisbursementBatchStatusEnum::Generated,
            403,
            'Only draft batches can be deleted.'
        );

        $batch->delete();

        return redirect()
            ->route('payroll.salary-disbursement-batches.index')
            ->with('success', 'Batch deleted successfully.');
    }

    public function process(string $id)
    {
        $this->service->process($id);

        return redirect()
            ->route('payroll.salary-disbursement-batches.show', $id)
            ->with('success', 'Batch marked as processed.');
    }

    public function sendForApproval(string $id)
    {
        $this->service->sendForApproval($id);

        return redirect()
            ->route('payroll.salary-disbursement-batches.show', $id)
            ->with('success', 'Batch sent for approval.');
    }

    public function revertFromApproval(string $id)
    {
        $this->service->revertFromApproval($id);

        return redirect()
            ->route('payroll.salary-disbursement-batches.show', $id)
            ->with('success', 'Batch reverted from approval.');
    }

    public function sendForDisbursement(string $id)
    {
        $this->service->sendForDisbursement($id);

        return redirect()
            ->route('payroll.salary-disbursement-batches.show', $id)
            ->with('success', 'Batch sent for disbursement.');
    }

    public function disburse(DisbursePayrollSalaryDisbursementBatchRequest $request, string $id)
    {
        $this->service->disburse($id, $request->validated('disbursement_date'));

        return redirect()
            ->route('payroll.salary-disbursement-batches.show', $id)
            ->with('success', 'Batch disbursed successfully. All employees marked as paid.');
    }

    public function storeAdjustment(\Illuminate\Http\Request $request, string $id, string $empId)
    {
        $data = $request->validate([
            'head_name'     => ['required', 'string', 'max:255'],
            'head_category' => ['required', 'string', 'in:gross,benefit,deduction,adjustment'],
            'amount'        => ['required', 'numeric', 'min:0'],
        ]);

        $this->service->addAdjustment($id, $empId, $data);

        return redirect()
            ->route('payroll.salary-disbursement-batches.show', $id)
            ->with('success', 'Adjustment added successfully.');
    }
}
