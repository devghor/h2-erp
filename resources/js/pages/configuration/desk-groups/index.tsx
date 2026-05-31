import DataTable from '@/components/data-table/data-table';
import { RowActions } from '@/components/data-table/row-actions';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import BulkDeleteButton from '@/components/bulk-delete-button';
import { BaseDialog } from '@/components/dialog/base-dialog';
import { breadcrumbItems } from '@/config/breadcrumbs';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/react';
import axios from 'axios';
import { useRef, useState } from 'react';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [breadcrumbItems.dashboard, breadcrumbItems.configurationDeskGroups];

type DeskGroupType = { value: number; label: string };

export default function Index({ deskGroupTypes }: { deskGroupTypes: DeskGroupType[] }) {
    const tableRef = useRef<{ refetch: () => void }>(null);
    const [open, setOpen] = useState(false);
    const [isEdit, setIsEdit] = useState(false);
    const [form, setForm] = useState({
        id: undefined as number | undefined,
        name: '',
        code: '',
        description: '',
        type: '',
    });
    const [formErrors, setFormErrors] = useState<Record<string, string>>({});
    const [selectedIds, setSelectedIds] = useState<(string | number)[]>([]);

    const columns = [
        { accessorKey: 'id', header: 'ID', sortable: true, searchable: true },
        { accessorKey: 'name', header: 'Name', sortable: true, searchable: true },
        { accessorKey: 'code', header: 'Code', sortable: true, searchable: true },
        { accessorKey: 'type', header: 'Type', sortable: true },
        { accessorKey: 'description', header: 'Description', sortable: true, searchable: true },
        { accessorKey: 'created_at', header: 'Created At', sortable: true },
        {
            accessorKey: 'actions',
            header: 'Actions',
            sortable: false,
            className: 'w-[60px] text-center',
            cell: ({ row }: any) => (
                <RowActions onEdit={() => handleEdit(row)} onDelete={() => handleDelete(row.id)} />
            ),
        },
    ];

    const emptyForm = () => ({
        id: undefined as number | undefined,
        name: '',
        code: '',
        description: '',
        type: '',
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
            code: row.code ?? '',
            description: row.description ?? '',
            type: row.type_value != null ? String(row.type_value) : '',
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
        router.delete(route('configuration.desk-groups.destroy', id), {
            onSuccess: () => tableRef.current?.refetch(),
        });
    };

    const handleBulkDelete = () => {
        if (selectedIds.length === 0) return;
        axios
            .delete(route('configuration.desk-groups.bulk-delete'), { data: { ids: selectedIds } })
            .then(() => {
                toast.success(`${selectedIds.length} desk group(s) deleted successfully`);
                tableRef.current?.refetch();
            })
            .catch(() => {
                toast.error('Error deleting selected desk groups');
            });
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value } = e.target;
        setForm((prev) => ({ ...prev, [name]: value }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const data: Record<string, any> = {
            name: form.name,
            code: form.code || null,
            description: form.description || null,
            type: form.type ? Number(form.type) : null,
        };
        if (isEdit && form.id) {
            router.put(route('configuration.desk-groups.update', form.id), data, {
                onSuccess: () => {
                    handleClose();
                    tableRef.current?.refetch();
                },
                onError: (errors) => setFormErrors(errors),
            });
        } else {
            router.post(route('configuration.desk-groups.store'), data, {
                onSuccess: () => {
                    handleClose();
                    tableRef.current?.refetch();
                },
                onError: (errors) => setFormErrors(errors),
            });
        }
    };

    return (
        <AppLayout
            title="Desk Groups"
            breadcrumbs={breadcrumbs}
            actions={<Button onClick={handleOpenAdd}>Add Desk Group</Button>}
        >
            <DataTable
                ref={tableRef}
                columns={columns}
                dataUrl={route('configuration.desk-groups.index')}
                onSelectionChange={setSelectedIds}
                extraActions={<BulkDeleteButton selectedCount={selectedIds.length} onDelete={handleBulkDelete} />}
            />
            <BaseDialog
                open={open}
                onOpenChange={setOpen}
                title={isEdit ? 'Edit Desk Group' : 'Add Desk Group'}
                description={
                    isEdit
                        ? 'Update the details of the existing desk group.'
                        : 'Fill in the details to create a new desk group.'
                }
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
                    <Label htmlFor="code">Code</Label>
                    <Input name="code" value={form.code} onChange={handleChange} />
                    {formErrors.code && <p className="text-sm text-red-500">{formErrors.code}</p>}
                </div>
                <div>
                    <Label htmlFor="type">Type</Label>
                    <Select
                        value={form.type}
                        onValueChange={(value) => setForm((prev) => ({ ...prev, type: value }))}
                    >
                        <SelectTrigger>
                            <SelectValue placeholder="Select a type" />
                        </SelectTrigger>
                        <SelectContent>
                            {deskGroupTypes.map((t) => (
                                <SelectItem key={t.value} value={String(t.value)}>
                                    {t.label}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    {formErrors.type && <p className="text-sm text-red-500">{formErrors.type}</p>}
                </div>
                <div>
                    <Label htmlFor="description">Description</Label>
                    <Input name="description" value={form.description} onChange={handleChange} />
                    {formErrors.description && <p className="text-sm text-red-500">{formErrors.description}</p>}
                </div>
            </BaseDialog>
        </AppLayout>
    );
}
