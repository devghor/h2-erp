import BulkDeleteButton from '@/components/bulk-delete-button';
import DataTable from '@/components/data-table/data-table';
import { RowActions } from '@/components/data-table/row-actions';
import { DatePicker } from '@/components/date-picker';
import { BaseDialog } from '@/components/dialog/base-dialog';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { breadcrumbItems } from '@/config/breadcrumbs';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/react';
import axios from 'axios';
import { useRef, useState } from 'react';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [breadcrumbItems.dashboard, breadcrumbItems.configurationShifts];

const weekdays = [
    { value: 0, label: 'Sun' },
    { value: 1, label: 'Mon' },
    { value: 2, label: 'Tue' },
    { value: 3, label: 'Wed' },
    { value: 4, label: 'Thu' },
    { value: 5, label: 'Fri' },
    { value: 6, label: 'Sat' },
];

const emptyForm = {
    id: undefined as number | undefined,
    name: '',
    working_days: [] as number[],
    weekends: [] as number[],
    start_time: '',
    end_time: '',
    special_working_dates: [] as string[],
    special_weekend_dates: [] as string[],
};

export default function Index() {
    const tableRef = useRef<{ refetch: () => void }>(null);
    const [open, setOpen] = useState(false);
    const [isEdit, setIsEdit] = useState(false);
    const [form, setForm] = useState({ ...emptyForm });
    const [formErrors, setFormErrors] = useState<Record<string, string>>({});
    const [selectedIds, setSelectedIds] = useState<(string | number)[]>([]);

    const columns = [
        { accessorKey: 'id', header: 'ID', sortable: true, searchable: true },
        { accessorKey: 'company', header: 'Company', sortable: true, searchable: true },
        { accessorKey: 'name', header: 'Name', sortable: true, searchable: true },
        {
            accessorKey: 'working_days',
            header: 'Working Days',
            sortable: false,
            searchable: false,
            cell: ({ row }: any) =>
                Array.isArray(row.working_days) ? row.working_days.map((d: number) => weekdays[d]?.label).join(', ') : (row.working_days ?? ''),
        },
        {
            accessorKey: 'weekends',
            header: 'Weekends',
            sortable: false,
            searchable: false,
            cell: ({ row }: any) =>
                Array.isArray(row.weekends) ? row.weekends.map((d: number) => weekdays[d]?.label).join(', ') : (row.weekends ?? ''),
        },
        { accessorKey: 'start_time', header: 'Start Time', sortable: true, searchable: false },
        { accessorKey: 'end_time', header: 'End Time', sortable: true, searchable: false },
        {
            accessorKey: 'special_working_dates',
            header: 'Special Working Dates',
            sortable: false,
            searchable: false,
            cell: ({ row }: any) =>
                Array.isArray(row.special_working_dates) ? row.special_working_dates.join(', ') : (row.special_working_dates ?? ''),
        },
        {
            accessorKey: 'special_weekend_dates',
            header: 'Special Weekend Dates',
            sortable: false,
            searchable: false,
            cell: ({ row }: any) =>
                Array.isArray(row.special_weekend_dates) ? row.special_weekend_dates.join(', ') : (row.special_weekend_dates ?? ''),
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
        setForm({ ...emptyForm });
        setIsEdit(false);
        setOpen(true);
        setFormErrors({});
    };

    const handleEdit = (row: any) => {
        setForm({
            id: row.id,
            name: row.name,
            working_days: row.working_days ?? [],
            weekends: row.weekends ?? [],
            start_time: row.start_time ? row.start_time.substring(0, 5) : '',
            end_time: row.end_time ? row.end_time.substring(0, 5) : '',
            special_working_dates: row.special_working_dates ?? [],
            special_weekend_dates: row.special_weekend_dates ?? [],
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
        router.delete(route('configuration.shifts.destroy', id), {
            onSuccess: () => tableRef.current?.refetch(),
        });
    };

    const handleBulkDelete = () => {
        if (selectedIds.length === 0) return;
        axios
            .delete(route('configuration.shifts.bulk-delete'), { data: { ids: selectedIds } })
            .then(() => {
                toast.success(`${selectedIds.length} shift(s) deleted successfully`);
                tableRef.current?.refetch();
            })
            .catch(() => {
                toast.error('Error deleting selected shifts');
            });
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value } = e.target;
        setForm((prev) => ({ ...prev, [name]: value }));
    };

    const toggleDay = (list: 'working_days' | 'weekends', value: number) => {
        setForm((prev) => {
            const current = prev[list];
            const next = current.includes(value) ? current.filter((v) => v !== value) : [...current, value];
            return { ...prev, [list]: next };
        });
    };

    const addDate = (list: 'special_working_dates' | 'special_weekend_dates') => {
        setForm((prev) => ({ ...prev, [list]: [...prev[list], ''] }));
    };

    const updateDate = (list: 'special_working_dates' | 'special_weekend_dates', index: number, value: string) => {
        setForm((prev) => {
            const next = [...prev[list]];
            next[index] = value;
            return { ...prev, [list]: next };
        });
    };

    const removeDate = (list: 'special_working_dates' | 'special_weekend_dates', index: number) => {
        setForm((prev) => {
            const next = [...prev[list]];
            next.splice(index, 1);
            return { ...prev, [list]: next };
        });
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const data: Record<string, any> = {
            name: form.name,
            working_days: form.working_days,
            weekends: form.weekends,
            start_time: form.start_time,
            end_time: form.end_time,
            special_working_dates: form.special_working_dates.filter(Boolean),
            special_weekend_dates: form.special_weekend_dates.filter(Boolean),
        };
        if (isEdit && form.id) {
            router.put(route('configuration.shifts.update', form.id), data, {
                onSuccess: () => {
                    handleClose();
                    tableRef.current?.refetch();
                },
                onError: (errors) => setFormErrors(errors),
            });
        } else {
            router.post(route('configuration.shifts.store'), data, {
                onSuccess: () => {
                    handleClose();
                    tableRef.current?.refetch();
                },
                onError: (errors) => setFormErrors(errors),
            });
        }
    };

    return (
        <AppLayout title="Shifts" breadcrumbs={breadcrumbs} actions={<Button onClick={handleOpenAdd}>Add Shift</Button>}>
            <DataTable
                ref={tableRef}
                columns={columns}
                dataUrl={route('configuration.shifts.index')}
                onSelectionChange={setSelectedIds}
                extraActions={<BulkDeleteButton selectedCount={selectedIds.length} onDelete={handleBulkDelete} />}
            />
            <BaseDialog
                open={open}
                onOpenChange={setOpen}
                title={isEdit ? 'Edit Shift' : 'Add Shift'}
                description={isEdit ? 'Update the details of the existing shift.' : 'Fill in the details to create a new shift.'}
                onSubmit={handleSubmit}
                onCancel={handleClose}
                submitLabel={isEdit ? 'Update' : 'Create'}
            >
                <div>
                    <Label htmlFor="name">Name</Label>
                    <Input name="name" value={form.name} onChange={handleChange} required />
                    {formErrors.name && <p className="text-sm text-red-500">{formErrors.name}</p>}
                </div>
                <div>
                    <Label>Working Days</Label>
                    <div className="flex flex-wrap gap-3">
                        {weekdays.map((day) => (
                            <label key={day.value} className="flex items-center gap-1 text-sm">
                                <Checkbox
                                    checked={form.working_days.includes(day.value)}
                                    onCheckedChange={() => toggleDay('working_days', day.value)}
                                />
                                {day.label}
                            </label>
                        ))}
                    </div>
                    {formErrors.working_days && <p className="text-sm text-red-500">{formErrors.working_days}</p>}
                </div>
                <div>
                    <Label>Weekends</Label>
                    <div className="flex flex-wrap gap-3">
                        {weekdays.map((day) => (
                            <label key={day.value} className="flex items-center gap-1 text-sm">
                                <Checkbox checked={form.weekends.includes(day.value)} onCheckedChange={() => toggleDay('weekends', day.value)} />
                                {day.label}
                            </label>
                        ))}
                    </div>
                    {formErrors.weekends && <p className="text-sm text-red-500">{formErrors.weekends}</p>}
                </div>
                <div className="grid grid-cols-2 gap-4">
                    <div>
                        <Label htmlFor="start_time">Start Time</Label>
                        <Input name="start_time" type="time" value={form.start_time} onChange={handleChange} required />
                        {formErrors.start_time && <p className="text-sm text-red-500">{formErrors.start_time}</p>}
                    </div>
                    <div>
                        <Label htmlFor="end_time">End Time</Label>
                        <Input name="end_time" type="time" value={form.end_time} onChange={handleChange} required />
                        {formErrors.end_time && <p className="text-sm text-red-500">{formErrors.end_time}</p>}
                    </div>
                </div>
                <div>
                    <div className="flex items-center justify-between">
                        <Label>Special Working Dates</Label>
                        <Button type="button" variant="outline" size="sm" onClick={() => addDate('special_working_dates')}>
                            Add Date
                        </Button>
                    </div>
                    <div className="mt-2 space-y-2">
                        {form.special_working_dates.map((date, index) => (
                            <div key={index} className="flex items-center gap-2">
                                <DatePicker value={date} onChange={(value) => updateDate('special_working_dates', index, value)} className="flex-1" />
                                <Button type="button" variant="destructive" size="sm" onClick={() => removeDate('special_working_dates', index)}>
                                    Remove
                                </Button>
                            </div>
                        ))}
                    </div>
                    {formErrors.special_working_dates && <p className="text-sm text-red-500">{formErrors.special_working_dates}</p>}
                </div>
                <div>
                    <div className="flex items-center justify-between">
                        <Label>Special Weekend Dates</Label>
                        <Button type="button" variant="outline" size="sm" onClick={() => addDate('special_weekend_dates')}>
                            Add Date
                        </Button>
                    </div>
                    <div className="mt-2 space-y-2">
                        {form.special_weekend_dates.map((date, index) => (
                            <div key={index} className="flex items-center gap-2">
                                <DatePicker value={date} onChange={(value) => updateDate('special_weekend_dates', index, value)} className="flex-1" />
                                <Button type="button" variant="destructive" size="sm" onClick={() => removeDate('special_weekend_dates', index)}>
                                    Remove
                                </Button>
                            </div>
                        ))}
                    </div>
                    {formErrors.special_weekend_dates && <p className="text-sm text-red-500">{formErrors.special_weekend_dates}</p>}
                </div>
            </BaseDialog>
        </AppLayout>
    );
}
