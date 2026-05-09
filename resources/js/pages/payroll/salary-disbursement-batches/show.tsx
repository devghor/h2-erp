import { BaseDialog } from '@/components/dialog/base-dialog';
import { Button } from '@/components/ui/button';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Textarea } from '@/components/ui/textarea';
import { breadcrumbItems } from '@/config/breadcrumbs';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import PayrollSalaryDisbursementBatchController from '@/actions/App/Http/Controllers/Payroll/PayrollSalaryDisbursementBatchController';
import { router } from '@inertiajs/react';
import { ChevronDown, ChevronRight, PlusCircle } from 'lucide-react';
import { useState } from 'react';
import { toast } from 'sonner';

interface BatchEmployee {
    id: number;
    user_id: number;
    payroll_employee_salary_profile_id: number | null;
    basic_amount: string;
    gross_amount: string;
    deduction_amount: string;
    net_amount: string;
    payment_mode: string;
    payment_status: string;
    remarks: string | null;
    employee: {
        employee_code: string | null;
        full_name: string | null;
        designation: { name: string } | null;
    } | null;
    items: {
        id: number;
        head_name: string;
        head_category: string;
        amount: string;
        is_adjustment: boolean;
    }[];
}

interface Batch {
    id: number;
    name: string;
    year: number;
    month: number;
    type: string;
    status: string;
    total_basic: string;
    total_gross: string;
    total_deduction: string;
    total_net: string;
    employee_count: number;
    remark: string | null;
    disbursement_date: string | null;
}

interface Props {
    batch: Batch;
    employees: BatchEmployee[];
}

const STATUS_COLORS: Record<string, string> = {
    generated: 'bg-gray-100 text-gray-700',
    processed: 'bg-blue-100 text-blue-700',
    sent_for_approval: 'bg-amber-100 text-amber-700',
    revert_from_approval: 'bg-orange-100 text-orange-700',
    sent_for_disbursement: 'bg-purple-100 text-purple-700',
    disbursed: 'bg-green-100 text-green-700',
};

const STATUS_LABELS: Record<string, string> = {
    generated: 'Draft',
    processed: 'Processed',
    sent_for_approval: 'Pending Approval',
    revert_from_approval: 'Reverted',
    sent_for_disbursement: 'Pending Disbursement',
    disbursed: 'Disbursed',
};

const PAYMENT_STATUS_COLORS: Record<string, string> = {
    pending: 'bg-yellow-100 text-yellow-700',
    paid: 'bg-green-100 text-green-700',
    failed: 'bg-red-100 text-red-700',
};

const CATEGORY_COLORS: Record<string, string> = {
    gross: 'text-blue-600',
    benefit: 'text-teal-600',
    deduction: 'text-red-600',
    adjustment: 'text-orange-600',
};

const HEAD_CATEGORY_LABELS: Record<string, string> = {
    gross: 'Gross',
    benefit: 'Benefit',
    deduction: 'Deduction',
    adjustment: 'Adjustment',
};

const fmt = (v: string | number) =>
    Number(v).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

export default function Show({ batch, employees }: Props) {
    const breadcrumbs: BreadcrumbItem[] = [
        breadcrumbItems.dashboard,
        breadcrumbItems.payrollSalaryDisbursementBatches,
        { title: batch.name, href: PayrollSalaryDisbursementBatchController.show.url(batch.id) },
    ];

    const [expandedRows, setExpandedRows] = useState<Set<number>>(new Set());
    const [disburseOpen, setDisburseOpen] = useState(false);
    const [disbursementDate, setDisbursementDate] = useState('');
    const [adjustmentOpen, setAdjustmentOpen] = useState(false);
    const [adjustmentEmpId, setAdjustmentEmpId] = useState<number | null>(null);
    const [adjustment, setAdjustment] = useState({ head_name: '', head_category: 'deduction', amount: '' });
    const [adjustmentErrors, setAdjustmentErrors] = useState<Record<string, string>>({});

    const toggleRow = (id: number) => {
        setExpandedRows((prev) => {
            const next = new Set(prev);
            next.has(id) ? next.delete(id) : next.add(id);
            return next;
        });
    };

    const postTransition = (url: string, data?: Record<string, any>) => {
        router.post(url, data ?? {}, {
            onSuccess: () => toast.success('Status updated.'),
            onError: () => toast.error('Action failed.'),
        });
    };

    const handleProcess = () => postTransition(PayrollSalaryDisbursementBatchController.process.url(batch.id));
    const handleSendForApproval = () => postTransition(PayrollSalaryDisbursementBatchController.sendForApproval.url(batch.id));
    const handleRevert = () => postTransition(PayrollSalaryDisbursementBatchController.revertFromApproval.url(batch.id));
    const handleSendForDisbursement = () => postTransition(PayrollSalaryDisbursementBatchController.sendForDisbursement.url(batch.id));

    const handleDisburse = () => {
        router.post(
            PayrollSalaryDisbursementBatchController.disburse.url(batch.id),
            { disbursement_date: disbursementDate },
            {
                onSuccess: () => {
                    setDisburseOpen(false);
                    toast.success('Batch disbursed. All employees marked as paid.');
                },
                onError: (e) => toast.error(e.disbursement_date ?? 'Disbursement failed.'),
            },
        );
    };

    const openAdjustment = (empId: number) => {
        setAdjustmentEmpId(empId);
        setAdjustment({ head_name: '', head_category: 'deduction', amount: '' });
        setAdjustmentErrors({});
        setAdjustmentOpen(true);
    };

    const handleAddAdjustment = () => {
        if (!adjustmentEmpId) return;
        router.post(
            PayrollSalaryDisbursementBatchController.storeAdjustment.url({ id: batch.id, empId: adjustmentEmpId }),
            adjustment,
            {
                onSuccess: () => {
                    setAdjustmentOpen(false);
                    toast.success('Adjustment added.');
                },
                onError: (e) => setAdjustmentErrors(e),
            },
        );
    };

    const status = batch.status;
    const isGenerated = status === 'generated';

    const actionButtons = (
        <div className="flex flex-wrap gap-2">
            {status === 'generated' && (
                <Button onClick={handleProcess}>Process Batch</Button>
            )}
            {status === 'processed' && (
                <Button onClick={handleSendForApproval}>Send for Approval</Button>
            )}
            {status === 'sent_for_approval' && (
                <>
                    <Button variant="outline" onClick={handleRevert}>Revert</Button>
                    <Button onClick={handleSendForDisbursement}>Send for Disbursement</Button>
                </>
            )}
            {status === 'revert_from_approval' && (
                <Button onClick={handleSendForDisbursement}>Send for Disbursement</Button>
            )}
            {status === 'sent_for_disbursement' && (
                <Button onClick={() => setDisburseOpen(true)}>Disburse</Button>
            )}
        </div>
    );

    const statusLabel = STATUS_LABELS[status] ?? status;
    const statusCls = STATUS_COLORS[status] ?? 'bg-gray-100 text-gray-700';

    return (
        <AppLayout title={batch.name} breadcrumbs={breadcrumbs} actions={actionButtons}>
            {/* Header badges */}
            <div className="mb-4 flex flex-wrap items-center gap-2">
                <span className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${statusCls}`}>
                    {statusLabel}
                </span>
                <span className="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-600">
                    {batch.type === 'monthly_salary' ? 'Monthly Salary' : 'Special Batch'}
                </span>
                <span className="text-muted-foreground text-sm">
                    {new Date(batch.year, batch.month - 1).toLocaleString('default', { month: 'long', year: 'numeric' })}
                </span>
            </div>

            {/* Summary cards */}
            <div className="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
                {[
                    { label: 'Total Basic', value: batch.total_basic },
                    { label: 'Total Gross', value: batch.total_gross },
                    { label: 'Total Deduction', value: batch.total_deduction },
                    { label: 'Total Net', value: batch.total_net },
                ].map(({ label, value }) => (
                    <div key={label} className="rounded-lg border bg-white p-4 shadow-sm dark:bg-gray-950">
                        <p className="text-muted-foreground text-xs font-medium">{label}</p>
                        <p className="mt-1 text-xl font-semibold">{fmt(value)}</p>
                    </div>
                ))}
            </div>

            {/* Employee count + remark */}
            <div className="mb-4 flex flex-wrap items-center justify-between gap-2">
                <p className="text-muted-foreground text-sm">
                    <span className="font-medium text-foreground">{batch.employee_count}</span> employees
                    {batch.disbursement_date && (
                        <> &middot; Disbursed on <span className="font-medium text-foreground">{batch.disbursement_date}</span></>
                    )}
                </p>
                {batch.remark && <p className="text-muted-foreground max-w-md text-sm italic">{batch.remark}</p>}
            </div>

            {/* Employee table */}
            <div className="rounded-lg border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead className="w-8" />
                            <TableHead>Code</TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Designation</TableHead>
                            <TableHead className="text-right">Basic</TableHead>
                            <TableHead className="text-right">Gross</TableHead>
                            <TableHead className="text-right">Deduction</TableHead>
                            <TableHead className="text-right">Net</TableHead>
                            <TableHead>Mode</TableHead>
                            <TableHead>Payment</TableHead>
                            {isGenerated && <TableHead />}
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {employees.map((emp) => {
                            const expanded = expandedRows.has(emp.id);
                            const paymentCls = PAYMENT_STATUS_COLORS[emp.payment_status] ?? 'bg-gray-100 text-gray-600';
                            return (
                                <>
                                    <TableRow key={emp.id} className="cursor-pointer hover:bg-muted/30">
                                        <TableCell>
                                            <button
                                                className="text-muted-foreground hover:text-foreground"
                                                onClick={() => toggleRow(emp.id)}
                                            >
                                                {expanded ? (
                                                    <ChevronDown className="h-4 w-4" />
                                                ) : (
                                                    <ChevronRight className="h-4 w-4" />
                                                )}
                                            </button>
                                        </TableCell>
                                        <TableCell className="text-sm">{emp.employee?.employee_code ?? '—'}</TableCell>
                                        <TableCell className="text-sm font-medium">{emp.employee?.full_name ?? '—'}</TableCell>
                                        <TableCell className="text-sm">{emp.employee?.designation?.name ?? '—'}</TableCell>
                                        <TableCell className="text-right text-sm">{fmt(emp.basic_amount)}</TableCell>
                                        <TableCell className="text-right text-sm">{fmt(emp.gross_amount)}</TableCell>
                                        <TableCell className="text-right text-sm">{fmt(emp.deduction_amount)}</TableCell>
                                        <TableCell className="text-right text-sm font-semibold">{fmt(emp.net_amount)}</TableCell>
                                        <TableCell className="text-sm capitalize">{emp.payment_mode}</TableCell>
                                        <TableCell>
                                            <span className={`inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium capitalize ${paymentCls}`}>
                                                {emp.payment_status}
                                            </span>
                                        </TableCell>
                                        {isGenerated && (
                                            <TableCell>
                                                <button
                                                    className="inline-flex items-center gap-1 rounded px-2 py-1 text-xs text-blue-600 hover:bg-blue-50"
                                                    onClick={() => openAdjustment(emp.id)}
                                                >
                                                    <PlusCircle className="h-3.5 w-3.5" />
                                                    Adjust
                                                </button>
                                            </TableCell>
                                        )}
                                    </TableRow>

                                    {expanded && (
                                        <TableRow key={`${emp.id}-items`} className="bg-muted/20">
                                            <TableCell colSpan={isGenerated ? 11 : 10} className="px-8 py-3">
                                                <table className="w-full text-sm">
                                                    <thead>
                                                        <tr className="text-muted-foreground border-b text-xs">
                                                            <th className="py-1 text-left font-medium">Head Name</th>
                                                            <th className="py-1 text-left font-medium">Category</th>
                                                            <th className="py-1 text-right font-medium">Amount</th>
                                                            <th className="py-1 text-left font-medium">Note</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {emp.items.map((item) => (
                                                            <tr key={item.id} className="border-b last:border-0">
                                                                <td className="py-1">{item.head_name}</td>
                                                                <td className={`py-1 ${CATEGORY_COLORS[item.head_category] ?? ''}`}>
                                                                    {HEAD_CATEGORY_LABELS[item.head_category] ?? item.head_category}
                                                                </td>
                                                                <td className="py-1 text-right">{fmt(item.amount)}</td>
                                                                <td className="py-1">
                                                                    {item.is_adjustment && (
                                                                        <span className="rounded bg-orange-100 px-1.5 py-0.5 text-xs text-orange-600">
                                                                            Adjustment
                                                                        </span>
                                                                    )}
                                                                </td>
                                                            </tr>
                                                        ))}
                                                    </tbody>
                                                </table>
                                            </TableCell>
                                        </TableRow>
                                    )}
                                </>
                            );
                        })}

                        {employees.length === 0 && (
                            <TableRow>
                                <TableCell colSpan={isGenerated ? 11 : 10} className="text-muted-foreground py-8 text-center text-sm">
                                    No employees in this batch.
                                </TableCell>
                            </TableRow>
                        )}
                    </TableBody>
                </Table>
            </div>

            {/* Disburse dialog */}
            <BaseDialog
                open={disburseOpen}
                onOpenChange={setDisburseOpen}
                title="Disburse Batch"
                description="Enter the actual payment date to mark this batch as disbursed."
                onSubmit={handleDisburse}
                submitLabel="Disburse"
            >
                <div className="space-y-1">
                    <Label>Disbursement Date</Label>
                    <Input type="date" value={disbursementDate} onChange={(e) => setDisbursementDate(e.target.value)} />
                </div>
            </BaseDialog>

            {/* Adjustment dialog */}
            <BaseDialog
                open={adjustmentOpen}
                onOpenChange={setAdjustmentOpen}
                title="Add Adjustment"
                description="Add a one-time adjustment (bonus or ad-hoc deduction) for this employee."
                onSubmit={handleAddAdjustment}
                submitLabel="Add"
            >
                <div className="grid gap-4">
                    <div className="space-y-1">
                        <Label>Head Name</Label>
                        <Input
                            value={adjustment.head_name}
                            onChange={(e) => setAdjustment((p) => ({ ...p, head_name: e.target.value }))}
                            placeholder="e.g. Festival Bonus"
                        />
                        {adjustmentErrors.head_name && <p className="text-destructive text-xs">{adjustmentErrors.head_name}</p>}
                    </div>
                    <div className="space-y-1">
                        <Label>Category</Label>
                        <Select
                            value={adjustment.head_category}
                            onValueChange={(v) => setAdjustment((p) => ({ ...p, head_category: v }))}
                        >
                            <SelectTrigger>
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="gross">Gross</SelectItem>
                                <SelectItem value="benefit">Benefit</SelectItem>
                                <SelectItem value="deduction">Deduction</SelectItem>
                                <SelectItem value="adjustment">Adjustment</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div className="space-y-1">
                        <Label>Amount</Label>
                        <Input
                            type="number"
                            min="0"
                            step="0.01"
                            value={adjustment.amount}
                            onChange={(e) => setAdjustment((p) => ({ ...p, amount: e.target.value }))}
                        />
                        {adjustmentErrors.amount && <p className="text-destructive text-xs">{adjustmentErrors.amount}</p>}
                    </div>
                </div>
            </BaseDialog>
        </AppLayout>
    );
}
