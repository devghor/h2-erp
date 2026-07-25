import BulkDeleteButton from '@/components/bulk-delete-button';
import { MultiCombobox } from '@/components/multi-combobox';
import DataTable from '@/components/data-table/data-table';
import { RowActions } from '@/components/data-table/row-actions';
import { BaseDialog } from '@/components/dialog/base-dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { breadcrumbItems } from '@/config/breadcrumbs';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/react';
import axios from 'axios';
import { useRef, useState } from 'react';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [breadcrumbItems.dashboard, breadcrumbItems.leaveLeaveTypes];

interface Option {
    value: string;
    label: string;
}

interface LeaveTypeForm {
    id?: number;
    name: string;
    code: string;
    leave_count: number;
    max_balance: number;
    availability: string;
    frequency: string;
    eligibility: string;
    employee_type: number[];
    advanced_leave: boolean;
    document_required: boolean;
    carry_forward: boolean;
    allow_pending_leave: boolean;
}

const initialForm: LeaveTypeForm = {
    name: '',
    code: '',
    leave_count: 0,
    max_balance: 0,
    availability: '',
    frequency: '',
    eligibility: '',
    employee_type: [],
    advanced_leave: false,
    document_required: false,
    carry_forward: false,
    allow_pending_leave: false,
};

export default function Index({
    employeeTypes,
    availabilityTypes,
    frequencyTypes,
    eligibilityTypes,
}: {
    employeeTypes: Option[];
    availabilityTypes: Option[];
    frequencyTypes: Option[];
    eligibilityTypes: Option[];
}) {
    const tableRef = useRef<{ refetch: () => void }>(null);
    const [open, setOpen] = useState(false);
    const [isEdit, setIsEdit] = useState(false);
    const [form, setForm] = useState<LeaveTypeForm>(initialForm);
    const [formErrors, setFormErrors] = useState<Record<string, string>>({});
    const [selectedIds, setSelectedIds] = useState<(string | number)[]>([]);

    const columns = [
        { accessorKey: 'id', header: 'ID', sortable: true, searchable: true },
        { accessorKey: 'name', header: 'Name', sortable: true, searchable: true },
        { accessorKey: 'code', header: 'Code', sortable: true, searchable: true },
        { accessorKey: 'leave_count', header: 'Leave Count', sortable: true },
        { accessorKey: 'max_balance', header: 'Max Balance', sortable: true },
        {
            accessorKey: 'availability',
            header: 'Availability',
            sortable: true,
            searchable: true,
            filterType: 'select' as const,
            filterOptions: availabilityTypes,
        },
        {
            accessorKey: 'frequency',
            header: 'Frequency',
            sortable: true,
            searchable: true,
            filterType: 'select' as const,
            filterOptions: frequencyTypes,
        },
        {
            accessorKey: 'eligibility',
            header: 'Eligibility',
            sortable: true,
            searchable: true,
            filterType: 'select' as const,
            filterOptions: eligibilityTypes,
        },
        { accessorKey: 'created_at', header: 'Created At', sortable: true },
        {
            accessorKey: 'actions',
            header: 'Actions',
            sortable: false,
            className: 'w-[60px] text-center',
            cell: ({ row }: any) => <RowActions onEdit={() => handleEdit(row)} onDelete={() => handleDelete(row.id)} />,
        },
    ];

    const handleOpenAdd = () => {
        setForm(initialForm);
        setIsEdit(false);
        setOpen(true);
        setFormErrors({});
    };

    const handleEdit = (row: any) => {
        setForm({
            id: row.id,
            name: row.name,
            code: row.code ?? '',
            leave_count: row.leave_count,
            max_balance: row.max_balance,
            availability: row.availability,
            frequency: row.frequency,
            eligibility: row.eligibility,
            employee_type: Array.isArray(row.employee_type) ? row.employee_type : [],
            advanced_leave: row.advanced_leave ?? false,
            document_required: row.document_required ?? false,
            carry_forward: row.carry_forward ?? false,
            allow_pending_leave: row.allow_pending_leave ?? false,
        });
        setIsEdit(true);
        setOpen(true);
        setFormErrors({});
    };

    const handleClose = () => {
        setOpen(false);
        setIsEdit(false);
    };

    const handleDelete = (id: number) => {
        router.delete(route('leave.leave-types.destroy', id), {
            onSuccess: () => tableRef.current?.refetch(),
        });
    };

    const handleBulkDelete = () => {
        if (selectedIds.length === 0) return;
        axios
            .delete(route('leave.leave-types.bulk-delete'), { data: { ids: selectedIds } })
            .then(() => {
                toast.success(`${selectedIds.length} leave type(s) deleted successfully`);
                tableRef.current?.refetch();
            })
            .catch(() => {
                toast.error('Error deleting selected leave types');
            });
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value, type } = e.target;
        setForm((prev) => ({ ...prev, [name]: type === 'number' ? Number(value) : value }));
    };

    const handleSelectChange = (name: string, value: string) => {
        setForm((prev) => ({ ...prev, [name]: value }));
    };

    const handleCheckboxChange = (name: string, checked: boolean | 'indeterminate') => {
        setForm((prev) => ({ ...prev, [name]: checked === true }));
    };

    const handleEmployeeTypeChange = (ids: number[]) => {
        setForm((prev) => ({ ...prev, employee_type: ids }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const data: Record<string, any> = {
            name: form.name,
            code: form.code,
            leave_count: form.leave_count,
            max_balance: form.max_balance,
            availability: form.availability,
            frequency: form.frequency,
            eligibility: form.eligibility,
            employee_type: form.employee_type,
            advanced_leave: form.advanced_leave,
            document_required: form.document_required,
            carry_forward: form.carry_forward,
            allow_pending_leave: form.allow_pending_leave,
        };
        if (isEdit && form.id) {
            router.put(route('leave.leave-types.update', form.id), data, {
                onSuccess: () => {
                    handleClose();
                    tableRef.current?.refetch();
                },
                onError: (errors) => {
                    setFormErrors(errors);
                },
            });
        } else {
            router.post(route('leave.leave-types.store'), data, {
                onSuccess: () => {
                    handleClose();
                    tableRef.current?.refetch();
                },
                onError: (errors) => {
                    setFormErrors(errors);
                },
            });
        }
    };

    const employeeTypeOptions = employeeTypes.map((t) => ({
        id: Number(t.value),
        label: t.label,
    }));

    return (
        <AppLayout title="Leave Types" breadcrumbs={breadcrumbs} actions={<Button onClick={handleOpenAdd}>Add Leave Type</Button>}>
            <DataTable
                ref={tableRef}
                columns={columns}
                dataUrl={route('leave.leave-types.index')}
                onSelectionChange={setSelectedIds}
                extraActions={<BulkDeleteButton selectedCount={selectedIds.length} onDelete={handleBulkDelete} />}
            />
            <BaseDialog
                open={open}
                onOpenChange={setOpen}
                title={isEdit ? 'Edit Leave Type' : 'Add Leave Type'}
                description={isEdit ? 'Update the details of the existing leave type.' : 'Fill in the details to create a new leave type.'}
                onSubmit={handleSubmit}
                onCancel={handleClose}
                submitLabel={isEdit ? 'Update' : 'Create'}
                className="max-w-2xl"
            >
                <div className="grid grid-cols-2 gap-4">
                    <div>
                        <Label htmlFor="name">Name</Label>
                        <Input name="name" value={form.name} onChange={handleChange} required />
                        {formErrors.name && <p className="text-sm text-red-500">{formErrors.name}</p>}
                    </div>
                    <div>
                        <Label htmlFor="code">Code</Label>
                        <Input name="code" value={form.code} onChange={handleChange} />
                        {formErrors.code && <p className="text-sm text-red-500">{formErrors.code}</p>}
                    </div>
                </div>
                <div className="grid grid-cols-2 gap-4">
                    <div>
                        <Label htmlFor="leave_count">Leave Count</Label>
                        <Input name="leave_count" type="number" min="0" value={form.leave_count} onChange={handleChange} required />
                        {formErrors.leave_count && <p className="text-sm text-red-500">{formErrors.leave_count}</p>}
                    </div>
                    <div>
                        <Label htmlFor="max_balance">Max Balance</Label>
                        <Input name="max_balance" type="number" min="0" value={form.max_balance} onChange={handleChange} required />
                        {formErrors.max_balance && <p className="text-sm text-red-500">{formErrors.max_balance}</p>}
                    </div>
                </div>
                <div className="grid grid-cols-2 gap-4">
                    <div>
                        <Label>Availability</Label>
                        <Select value={form.availability} onValueChange={(v) => handleSelectChange('availability', v)}>
                            <SelectTrigger>
                                <SelectValue placeholder="Select availability" />
                            </SelectTrigger>
                            <SelectContent>
                                {availabilityTypes.map((t) => (
                                    <SelectItem key={t.value} value={t.value}>
                                        {t.label}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        {formErrors.availability && <p className="text-sm text-red-500">{formErrors.availability}</p>}
                    </div>
                    <div>
                        <Label>Frequency</Label>
                        <Select value={form.frequency} onValueChange={(v) => handleSelectChange('frequency', v)}>
                            <SelectTrigger>
                                <SelectValue placeholder="Select frequency" />
                            </SelectTrigger>
                            <SelectContent>
                                {frequencyTypes.map((t) => (
                                    <SelectItem key={t.value} value={t.value}>
                                        {t.label}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        {formErrors.frequency && <p className="text-sm text-red-500">{formErrors.frequency}</p>}
                    </div>
                </div>
                <div>
                    <Label>Eligibility</Label>
                    <Select value={form.eligibility} onValueChange={(v) => handleSelectChange('eligibility', v)}>
                        <SelectTrigger>
                            <SelectValue placeholder="Select eligibility" />
                        </SelectTrigger>
                        <SelectContent>
                            {eligibilityTypes.map((t) => (
                                <SelectItem key={t.value} value={t.value}>
                                    {t.label}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    {formErrors.eligibility && <p className="text-sm text-red-500">{formErrors.eligibility}</p>}
                </div>
                <div>
                    <Label>Employee Type</Label>
                    <MultiCombobox options={employeeTypeOptions} value={form.employee_type} onChange={handleEmployeeTypeChange} placeholder="Select employee types..." />
                    {formErrors.employee_type && <p className="text-sm text-red-500">{formErrors.employee_type}</p>}
                </div>
                <div className="grid grid-cols-2 gap-4">
                    <div className="flex items-center gap-2">
                        <Checkbox id="advanced_leave" checked={form.advanced_leave} onCheckedChange={(c) => handleCheckboxChange('advanced_leave', c)} />
                        <Label htmlFor="advanced_leave">Advanced Leave</Label>
                    </div>
                    <div className="flex items-center gap-2">
                        <Checkbox id="document_required" checked={form.document_required} onCheckedChange={(c) => handleCheckboxChange('document_required', c)} />
                        <Label htmlFor="document_required">Document Required</Label>
                    </div>
                </div>
                <div className="grid grid-cols-2 gap-4">
                    <div className="flex items-center gap-2">
                        <Checkbox id="carry_forward" checked={form.carry_forward} onCheckedChange={(c) => handleCheckboxChange('carry_forward', c)} />
                        <Label htmlFor="carry_forward">Carry Forward</Label>
                    </div>
                    <div className="flex items-center gap-2">
                        <Checkbox id="allow_pending_leave" checked={form.allow_pending_leave} onCheckedChange={(c) => handleCheckboxChange('allow_pending_leave', c)} />
                        <Label htmlFor="allow_pending_leave">Allow Pending Leave</Label>
                    </div>
                </div>
            </BaseDialog>
        </AppLayout>
    );
}