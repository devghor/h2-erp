import BulkDeleteButton from '@/components/bulk-delete-button';
import DataTable from '@/components/data-table/data-table';
import { RowActions } from '@/components/data-table/row-actions';
import { BaseDialog } from '@/components/dialog/base-dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { MultiUserCombobox } from '@/components/multi-user-combobox';
import { breadcrumbItems } from '@/config/breadcrumbs';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/react';
import axios from 'axios';
import { useRef, useState } from 'react';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [breadcrumbItems.dashboard, breadcrumbItems.configurationHrbps];

type User = { id: number; name: string; username: string; email: string };

export default function Index({ users }: { users: User[] }) {
    const tableRef = useRef<{ refetch: () => void }>(null);
    const [open, setOpen] = useState(false);
    const [isEdit, setIsEdit] = useState(false);
    const [form, setForm] = useState({
        id: undefined as number | undefined,
        name: '',
        user_ids: [] as number[],
    });
    const [formErrors, setFormErrors] = useState<Record<string, string>>({});
    const [selectedIds, setSelectedIds] = useState<(string | number)[]>([]);

    const columns = [
        { accessorKey: 'id', header: 'ID', sortable: true, searchable: true },
        { accessorKey: 'name', header: 'Name', sortable: true, searchable: true },
        { accessorKey: 'user_names', header: 'Users', sortable: false, searchable: false },
        { accessorKey: 'user_count', header: 'User Count', sortable: false, searchable: false },
        { accessorKey: 'created_at', header: 'Created At', sortable: true },
        {
            accessorKey: 'actions',
            header: 'Actions',
            sortable: false,
            className: 'w-[60px] text-center',
            cell: ({ row }: any) => <RowActions onEdit={() => handleEdit(row)} onDelete={() => handleDelete(row.id)} />,
        },
    ];

    const emptyForm = () => ({
        id: undefined as number | undefined,
        name: '',
        user_ids: [] as number[],
    });

    const handleOpenAdd = () => {
        setForm(emptyForm());
        setIsEdit(false);
        setOpen(true);
        setFormErrors({});
    };

    const handleEdit = (row: any) => {
        setForm({
            id: row.id,
            name: row.name ?? '',
            user_ids: row.user_ids ?? [],
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
        router.delete(route('configuration.hrbps.destroy', id), {
            onSuccess: () => tableRef.current?.refetch(),
        });
    };

    const handleBulkDelete = () => {
        if (selectedIds.length === 0) return;
        axios
            .delete(route('configuration.hrbps.bulk-delete'), { data: { ids: selectedIds } })
            .then(() => {
                toast.success(`${selectedIds.length} HRBP(s) deleted successfully`);
                tableRef.current?.refetch();
            })
            .catch(() => {
                toast.error('Error deleting selected HRBPs');
            });
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value } = e.target;
        setForm((prev) => ({ ...prev, [name]: value }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const data = {
            name: form.name,
            user_ids: form.user_ids,
        };
        if (isEdit && form.id) {
            router.put(route('configuration.hrbps.update', form.id), data, {
                onSuccess: () => {
                    handleClose();
                    tableRef.current?.refetch();
                },
                onError: (errors) => setFormErrors(errors),
            });
        } else {
            router.post(route('configuration.hrbps.store'), data, {
                onSuccess: () => {
                    handleClose();
                    tableRef.current?.refetch();
                },
                onError: (errors) => setFormErrors(errors),
            });
        }
    };

    return (
        <AppLayout title="HRBP" breadcrumbs={breadcrumbs} actions={<Button onClick={handleOpenAdd}>Add HRBP</Button>}>
            <DataTable
                ref={tableRef}
                columns={columns}
                dataUrl={route('configuration.hrbps.index')}
                onSelectionChange={setSelectedIds}
                extraActions={<BulkDeleteButton selectedCount={selectedIds.length} onDelete={handleBulkDelete} />}
            />
            <BaseDialog
                open={open}
                onOpenChange={setOpen}
                title={isEdit ? 'Edit HRBP' : 'Add HRBP'}
                description={isEdit ? 'Update the details of the existing HRBP.' : 'Fill in the details to create a new HRBP.'}
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
                    <Label>Users</Label>
                    <MultiUserCombobox users={users} value={form.user_ids} onChange={(user_ids) => setForm((prev) => ({ ...prev, user_ids }))} />
                    {formErrors.user_ids && <p className="text-sm text-red-500">{formErrors.user_ids}</p>}
                </div>
            </BaseDialog>
        </AppLayout>
    );
}
