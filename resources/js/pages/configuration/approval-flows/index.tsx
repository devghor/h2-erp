import BulkDeleteButton from '@/components/bulk-delete-button';
import DataTable from '@/components/data-table/data-table';
import { RowActions } from '@/components/data-table/row-actions';
import { BaseDialog } from '@/components/dialog/base-dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { breadcrumbItems } from '@/config/breadcrumbs';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/react';
import axios from 'axios';
import { useRef, useState } from 'react';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [breadcrumbItems.dashboard, breadcrumbItems.configurationApprovalFlows];

type TypeOption = { value: string; label: string };

export default function Index({ typeOptions }: { typeOptions: TypeOption[] }) {
    const tableRef = useRef<{ refetch: () => void }>(null);
    const [open, setOpen] = useState(false);
    const [form, setForm] = useState({ name: '', type: '' });
    const [formErrors, setFormErrors] = useState<Record<string, string>>({});
    const [selectedIds, setSelectedIds] = useState<(string | number)[]>([]);

    const goToShow = (id: number) => router.get(route('configuration.approval-flows.show', id));

    const columns = [
        { accessorKey: 'id', header: 'ID', sortable: true, searchable: true },
        {
            accessorKey: 'name',
            header: 'Name',
            sortable: true,
            searchable: true,
            cell: ({ row }: any) => (
                <button type="button" className="text-primary hover:underline" onClick={() => goToShow(row.id)}>
                    {row.name}
                </button>
            ),
        },
        { accessorKey: 'type_label', header: 'Type', sortable: false, searchable: false },
        { accessorKey: 'group_count', header: 'Groups', sortable: false, searchable: false },
        { accessorKey: 'created_at', header: 'Created At', sortable: true },
        {
            accessorKey: 'actions',
            header: 'Actions',
            sortable: false,
            className: 'w-[60px] text-center',
            cell: ({ row }: any) => <RowActions onEdit={() => goToShow(row.id)} onDelete={() => handleDelete(row.id)} />,
        },
    ];

    const handleOpenAdd = () => {
        setForm({ name: '', type: '' });
        setOpen(true);
        setFormErrors({});
    };

    const handleClose = () => {
        setOpen(false);
    };

    const handleDelete = (id: number) => {
        router.delete(route('configuration.approval-flows.destroy', id), {
            onSuccess: () => tableRef.current?.refetch(),
        });
    };

    const handleBulkDelete = () => {
        if (selectedIds.length === 0) return;
        axios
            .delete(route('configuration.approval-flows.bulk-delete'), { data: { ids: selectedIds } })
            .then(() => {
                toast.success(`${selectedIds.length} approval flow(s) deleted successfully`);
                tableRef.current?.refetch();
            })
            .catch(() => {
                toast.error('Error deleting selected approval flows');
            });
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value } = e.target;
        setForm((prev) => ({ ...prev, [name]: value }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        router.post(route('configuration.approval-flows.store'), form, {
            onSuccess: () => {
                handleClose();
                tableRef.current?.refetch();
            },
            onError: (errors) => setFormErrors(errors),
        });
    };

    return (
        <AppLayout title="Approval Flows" breadcrumbs={breadcrumbs} actions={<Button onClick={handleOpenAdd}>Add Approval Flow</Button>}>
            <DataTable
                ref={tableRef}
                columns={columns}
                dataUrl={route('configuration.approval-flows.index')}
                onSelectionChange={setSelectedIds}
                extraActions={<BulkDeleteButton selectedCount={selectedIds.length} onDelete={handleBulkDelete} />}
            />
            <BaseDialog
                open={open}
                onOpenChange={setOpen}
                title="Add Approval Flow"
                description="Fill in the details to create a new approval flow."
                onSubmit={handleSubmit}
                onCancel={handleClose}
                submitLabel="Create"
            >
                <div>
                    <Label htmlFor="name">Name</Label>
                    <Input name="name" value={form.name} onChange={handleChange} required />
                    {formErrors.name && <p className="text-sm text-red-500">{formErrors.name}</p>}
                </div>
                <div>
                    <Label htmlFor="type">Type</Label>
                    <Select value={form.type} onValueChange={(value) => setForm((prev) => ({ ...prev, type: value }))}>
                        <SelectTrigger>
                            <SelectValue placeholder="Select a type" />
                        </SelectTrigger>
                        <SelectContent>
                            {typeOptions.map((option) => (
                                <SelectItem key={option.value} value={option.value}>
                                    {option.label}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    {formErrors.type && <p className="text-sm text-red-500">{formErrors.type}</p>}
                </div>
            </BaseDialog>
        </AppLayout>
    );
}
