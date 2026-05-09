<?php

namespace App\Services\Payroll;

use App\Enums\Payroll\PaymentStatusEnum;
use App\Enums\Payroll\SalaryDisbursementBatchStatusEnum;
use App\Enums\Payroll\SalaryHeadCategoryEnum;
use App\Models\Payroll\PayrollEmployeeSalaryProfile;
use App\Models\Payroll\PayrollSalaryDisbursementBatch;
use App\Models\Payroll\PayrollSalaryDisbursementBatchEmployee;
use App\Services\Core\CoreService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PayrollSalaryDisbursementBatchService extends CoreService
{
    protected function model(): string
    {
        return PayrollSalaryDisbursementBatch::class;
    }

    public function generateBatch(array $data): PayrollSalaryDisbursementBatch
    {
        return DB::transaction(function () use ($data) {
            $batch = PayrollSalaryDisbursementBatch::create([
                'name'       => $data['name'],
                'year'       => $data['year'],
                'month'      => $data['month'],
                'type'       => $data['type'],
                'remark'     => $data['remark'] ?? null,
                'status'     => SalaryDisbursementBatchStatusEnum::Generated->value,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            $profileQuery = PayrollEmployeeSalaryProfile::where('is_active', true)
                ->with(['items.salaryHead', 'employee.designation']);

            if (!empty($data['user_ids'])) {
                $profileQuery->whereIn('user_id', $data['user_ids']);
            }

            $profiles = $profileQuery->get();

            $totalBasic      = 0;
            $totalGross      = 0;
            $totalDeduction  = 0;
            $totalNet        = 0;
            $employeeCount   = 0;

            foreach ($profiles as $profile) {
                $itemsToCreate  = [];
                $grossAmount    = 0;
                $deductionAmount = 0;
                $basicAmount    = (float) $profile->basic_amount;

                foreach ($profile->items as $profileItem) {
                    $head = $profileItem->salaryHead;
                    if (!$head) {
                        continue;
                    }

                    $amount = $head->is_basic_linked
                        ? round($basicAmount * ($head->basic_ratio / 100), 2)
                        : (float) $profileItem->amount;

                    $category = $head->category instanceof SalaryHeadCategoryEnum
                        ? $head->category
                        : SalaryHeadCategoryEnum::from($head->category);

                    if (in_array($category, [SalaryHeadCategoryEnum::Gross, SalaryHeadCategoryEnum::Benefit])) {
                        $grossAmount += $amount;
                    } else {
                        $deductionAmount += $amount;
                    }

                    $itemsToCreate[] = [
                        'payroll_salary_head_id' => $head->id,
                        'head_name'              => $head->name,
                        'head_category'          => $category->value,
                        'amount'                 => $amount,
                        'is_adjustment'          => false,
                        'created_by'             => Auth::id(),
                        'updated_by'             => Auth::id(),
                    ];
                }

                $netAmount = $grossAmount - $deductionAmount;

                $batchEmployee = $batch->employees()->create([
                    'user_id'                            => $profile->user_id,
                    'payroll_employee_salary_profile_id' => $profile->id,
                    'basic_amount'                       => $basicAmount,
                    'gross_amount'                       => round($grossAmount, 2),
                    'deduction_amount'                   => round($deductionAmount, 2),
                    'net_amount'                         => round($netAmount, 2),
                    'payment_mode'                       => 'bank',
                    'payment_status'                     => PaymentStatusEnum::Pending->value,
                    'created_by'                         => Auth::id(),
                    'updated_by'                         => Auth::id(),
                ]);

                $batchEmployee->items()->createMany($itemsToCreate);

                $totalBasic     += $basicAmount;
                $totalGross     += $grossAmount;
                $totalDeduction += $deductionAmount;
                $totalNet       += $netAmount;
                $employeeCount++;
            }

            $batch->update([
                'total_basic'      => round($totalBasic, 2),
                'total_gross'      => round($totalGross, 2),
                'total_deduction'  => round($totalDeduction, 2),
                'total_net'        => round($totalNet, 2),
                'employee_count'   => $employeeCount,
            ]);

            return $batch;
        });
    }

    public function process(string $id): PayrollSalaryDisbursementBatch
    {
        $batch = PayrollSalaryDisbursementBatch::findOrFail($id);
        $batch->update([
            'status'     => SalaryDisbursementBatchStatusEnum::Processed->value,
            'updated_by' => Auth::id(),
        ]);

        return $batch;
    }

    public function sendForApproval(string $id): PayrollSalaryDisbursementBatch
    {
        $batch = PayrollSalaryDisbursementBatch::findOrFail($id);
        $batch->update([
            'status'       => SalaryDisbursementBatchStatusEnum::SentForApproval->value,
            'submitted_by' => Auth::id(),
            'submitted_at' => now(),
            'updated_by'   => Auth::id(),
        ]);

        return $batch;
    }

    public function revertFromApproval(string $id): PayrollSalaryDisbursementBatch
    {
        $batch = PayrollSalaryDisbursementBatch::findOrFail($id);
        $batch->update([
            'status'     => SalaryDisbursementBatchStatusEnum::RevertFromApproval->value,
            'updated_by' => Auth::id(),
        ]);

        return $batch;
    }

    public function sendForDisbursement(string $id): PayrollSalaryDisbursementBatch
    {
        $batch = PayrollSalaryDisbursementBatch::findOrFail($id);
        $batch->update([
            'status'      => SalaryDisbursementBatchStatusEnum::SentForDisbursement->value,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'updated_by'  => Auth::id(),
        ]);

        return $batch;
    }

    public function disburse(string $id, string $disbursementDate): PayrollSalaryDisbursementBatch
    {
        return DB::transaction(function () use ($id, $disbursementDate) {
            $batch = PayrollSalaryDisbursementBatch::findOrFail($id);
            $batch->update([
                'status'            => SalaryDisbursementBatchStatusEnum::Disbursed->value,
                'disbursed_by'      => Auth::id(),
                'disbursed_at'      => now(),
                'disbursement_date' => $disbursementDate,
                'updated_by'        => Auth::id(),
            ]);

            PayrollSalaryDisbursementBatchEmployee::where('payroll_salary_disbursement_batch_id', $batch->id)
                ->update([
                    'payment_status' => PaymentStatusEnum::Paid->value,
                    'paid_at'        => now(),
                    'updated_by'     => Auth::id(),
                ]);

            return $batch;
        });
    }

    public function addAdjustment(string $batchId, string $empId, array $data): void
    {
        DB::transaction(function () use ($batchId, $empId, $data) {
            $batchEmployee = PayrollSalaryDisbursementBatchEmployee::where('id', $empId)
                ->where('payroll_salary_disbursement_batch_id', $batchId)
                ->firstOrFail();

            $batchEmployee->items()->create([
                'payroll_salary_head_id' => null,
                'head_name'              => $data['head_name'],
                'head_category'          => $data['head_category'],
                'amount'                 => $data['amount'],
                'is_adjustment'          => true,
                'created_by'             => Auth::id(),
                'updated_by'             => Auth::id(),
            ]);

            $category = $data['head_category'];
            $amount   = (float) $data['amount'];

            if (in_array($category, ['gross', 'benefit'])) {
                $batchEmployee->increment('gross_amount', $amount);
            } else {
                $batchEmployee->increment('deduction_amount', $amount);
            }

            $net = (float) $batchEmployee->fresh()->gross_amount - (float) $batchEmployee->fresh()->deduction_amount;
            $batchEmployee->update(['net_amount' => round($net, 2), 'updated_by' => Auth::id()]);

            $batch = $batchEmployee->batch;
            $batch->update([
                'total_gross'      => $batch->employees()->sum('gross_amount'),
                'total_deduction'  => $batch->employees()->sum('deduction_amount'),
                'total_net'        => $batch->employees()->sum('net_amount'),
                'updated_by'       => Auth::id(),
            ]);
        });
    }

    public function getBatchWithEmployees(string $id): PayrollSalaryDisbursementBatch
    {
        return PayrollSalaryDisbursementBatch::with([
            'employees' => fn ($q) => $q->with([
                'employee.designation',
                'items' => fn ($q) => $q->orderBy('is_adjustment')->orderBy('id'),
            ]),
        ])->findOrFail($id);
    }
}
