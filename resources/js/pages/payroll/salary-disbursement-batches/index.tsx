import DataTable from '@/components/data-table/data-table';
import { BaseDialog } from '@/components/dialog/base-dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { breadcrumbItems } from '@/config/breadcrumbs';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import PayrollSalaryDisbursementBatchController from '@/actions/App/Http/Controllers/Payroll/PayrollSalaryDisbursementBatchController';
import { router } from '@inertiajs/react';
import { Eye, Plus, Trash2 } from 'lucide-react';
import { useRef, useState } from 'react';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [breadcrumbItems.dashboard, breadcrumbItems.payrollSalaryDisbursementBatches];

const MONTHS = [
    { value: '1', label: 'January' },
    { value: '2', label: 'February' },
    { value: '3', label: 'March' },
    { value: '4', label: 'April' },
    { value: '5', label: 'May' },
    { value: '6', label: 'June' },
    { value: '7', label: 'July' },
    { value: '8', label: 'August' },
    { value: '9', label: 'September' },
    { value: '10', label: 'October' },
    { value: '11', label: 'November' },
    { value: '12', label: 'December' },
];

const currentYear = new Date().getFullYear();
const YEARS = Array.from({ length: 10 }, (_, i) => currentYear - 2 + i);

const defaultForm = {
    name: '',
    year: String(currentYear),
    month: String(new Date().getMonth() + 1),
    type: 'monthly_salary',
    remark: '',
};

const STATUS_COLORS: Record<string, string> = {
    Draft: 'bg-gray-100 text-gray-700',
    Processed: 'bg-blue-100 text-blue-700',
    'Pending Approval': 'bg-amber-100 text-amber-700',
    Reverted: 'bg-orange-100 text-orange-700',
    'Pending Disbursement': 'bg-purple-100 text-purple-700',
    Disbursed: 'bg-green-100 text-green-700',
};

const TYPE_COLORS: Record<string, string> = {
    'Monthly Salary': 'bg-blue-50 text-blue-600',
    'Special Batch': 'bg-violet-50 text-violet-600',
};

export default function Index() {
    const tableRef = useRef<{ refetch: () => void }>(null);
    const [open, setOpen] = useState(false);
    const [form, setForm] = useState({ ...defaultForm });
    const [formErrors, setFormErrors] = useState<Record<string, string>>({});

    const autoName = () => {
        const monthName = MONTHS.find((m) => m.value === form.month)?.label ?? '';
        return `${monthName} ${form.year} ${form.type === 'special_batch' ? 'Special Batch' : 'Monthly Salary'}`;
    };

    const handleOpen = () => {
        const month = String(new Date().getMonth() + 1);
        const year = String(currentYear);
        const updated = { ...defaultForm, year, month };
        const monthName = MONTHS.find((m) => m.value === month)?.label ?? '';
        updated.name = `${monthName} ${year} Monthly Salary`;
        setForm(updated);
        setFormErrors({});
        setOpen(true);
    };

    const handleFieldChange = (field: string, value: string) => {
        setForm((prev) => {
            const next = { ...prev, [field]: value };
            if (['month', 'year', 'type'].includes(field)) {
                const monthName = MONTHS.find((m) => m.value === next.month)?.label ?? '';
                next.name = `${monthName} ${next.year} ${next.type === 'special_batch' ? 'Special Batch' : 'Monthly Salary'}`;
            }
            return next;
        });
    };

    const handleSubmit = () => {
        router.post(
            PayrollSalaryDisbursementBatchController.store.url(),
            { ...form, year: Number(form.year), month: Number(form.month) },
            {
                onError: (errors) => setFormErrors(errors),
                onSuccess: () => {
                    setOpen(false);
                    toast.success('Batch created and employees generated.');
                },
            },
        );
    };

    const handleDelete = (id: number) => {
        router.delete(PayrollSalaryDisbursementBatchController.destroy.url(id), {
            onSuccess: () => {
                tableRef.current?.refetch();
                toast.success('Batch deleted.');
            },
            onError: () => toast.error('Cannot delete this batch.'),
        });
    };

    const columns = [
        { accessorKey: 'id', header: 'ID', sortable: true },
        { accessorKey: 'name', header: 'Name', sortable: true, searchable: true },
        { accessorKey: 'period', header: 'Period', sortable: false },
        {
            accessorKey: 'type',
            header: 'Type',
            sortable: false,
            cell: ({ row }: any) => {
                const label: string = row.type ?? '';
                const cls = TYPE_COLORS[label] ?? 'bg-gray-100 text-gray-600';
                return <span className={`inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ${cls}`}>{label}</span>;
            },
        },
        {
            accessorKey: 'status',
            header: 'Status',
            sortable: false,
            cell: ({ row }: any) => {
                const label: string = row.status ?? '';
                const cls = STATUS_COLORS[label] ?? 'bg-gray-100 text-gray-600';
                return <span className={`inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ${cls}`}>{label}</span>;
            },
        },
        { accessorKey: 'employee_count', header: 'Employees', sortable: true },
        { accessorKey: 'total_net', header: 'Net Total', sortable: true },
        { accessorKey: 'disbursement_date', header: 'Disbursement Date', sortable: true },
        {
            accessorKey: 'actions',
            header: 'Actions',
            sortable: false,
            className: 'w-[100px] text-center',
            cell: ({ row }: any) => (
                <div className="flex items-center justify-center gap-1">
                    <button
                        className="inline-flex items-center gap-1 rounded px-2 py-1 text-xs text-blue-600 hover:bg-blue-50"
                        onClick={() => router.visit(PayrollSalaryDisbursementBatchController.show.url(row.id))}
                        title="View"
                    >
                        <Eye className="h-3.5 w-3.5" />
                        View
                    </button>
                    {row.status === 'Draft' && (
                        <button
                            className="inline-flex items-center gap-1 rounded px-2 py-1 text-xs text-red-600 hover:bg-red-50"
                            onClick={() => {
                                if (confirm('Delete this batch?')) handleDelete(row.id);
                            }}
                            title="Delete"
                        >
                            <Trash2 className="h-3.5 w-3.5" />
                        </button>
                    )}
                </div>
            ),
        },
    ];

    return (
        <AppLayout
            title="Salary Disbursement"
            breadcrumbs={breadcrumbs}
            actions={
                <Button onClick={handleOpen}>
                    <Plus className="mr-1 h-4 w-4" />
                    Create Batch
                </Button>
            }
        >
            <DataTable ref={tableRef} columns={columns} dataUrl={PayrollSalaryDisbursementBatchController.index.url()} />

            <BaseDialog
                open={open}
                onOpenChange={setOpen}
                title="Create Salary Disbursement Batch"
                description="A batch will be generated from all active employee salary profiles."
                onSubmit={handleSubmit}
                submitLabel="Generate Batch"
                className="max-w-lg"
            >
                <div className="grid gap-4">
                    <div className="grid grid-cols-2 gap-4">
                        <div className="space-y-1">
                            <Label>Year</Label>
                            <Select value={form.year} onValueChange={(v) => handleFieldChange('year', v)}>
                                <SelectTrigger>
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    {YEARS.map((y) => (
                                        <SelectItem key={y} value={String(y)}>
                                            {y}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                        <div className="space-y-1">
                            <Label>Month</Label>
                            <Select value={form.month} onValueChange={(v) => handleFieldChange('month', v)}>
                                <SelectTrigger>
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    {MONTHS.map((m) => (
                                        <SelectItem key={m.value} value={m.value}>
                                            {m.label}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                    </div>

                    <div className="space-y-1">
                        <Label>Type</Label>
                        <Select value={form.type} onValueChange={(v) => handleFieldChange('type', v)}>
                            <SelectTrigger>
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="monthly_salary">Monthly Salary</SelectItem>
                                <SelectItem value="special_batch">Special Batch</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <div className="space-y-1">
                        <Label>Name</Label>
                        <Input value={form.name} onChange={(e) => setForm((p) => ({ ...p, name: e.target.value }))} />
                        {formErrors.name && <p className="text-destructive text-xs">{formErrors.name}</p>}
                    </div>

                    <div className="space-y-1">
                        <Label>Remark</Label>
                        <Textarea
                            value={form.remark}
                            onChange={(e) => setForm((p) => ({ ...p, remark: e.target.value }))}
                            rows={2}
                        />
                    </div>
                </div>
            </BaseDialog>
        </AppLayout>
    );
}
