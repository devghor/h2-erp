import BulkDeleteButton from '@/components/bulk-delete-button';
import DataTable from '@/components/data-table/data-table';
import { RowActions } from '@/components/data-table/row-actions';
import { BaseDialog } from '@/components/dialog/base-dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { breadcrumbItems } from '@/config/breadcrumbs';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/react';
import axios from 'axios';
import { useRef, useState } from 'react';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [breadcrumbItems.dashboard, breadcrumbItems.productUnits];

const emptyForm = {
    id: undefined as number | undefined,
    name: '',
    code: '',
};

export default function Index() {
    const tableRef = useRef<{ refetch: () => void }>(null);
    const [open, setOpen] = useState(false);
    const [isEdit, setIsEdit] = useState(false);
    const [form, setForm] = useState({ ...emptyForm });
    const [formErrors, setFormErrors] = useState<Record<string, string>>({});
    const [selectedIds, setSelectedIds] = useState<(string | number)[]>([]);

    const [filterName, setFilterName] = useState('');
    const [filterCode, setFilterCode] = useState('');
    const [filterDateFrom, setFilterDateFrom] = useState('');
    const [filterDateTo, setFilterDateTo] = useState('');

    const extraParams = {
        ...(filterName ? { name: filterName } : {}),
        ...(filterCode ? { code: filterCode } : {}),
        ...(filterDateFrom ? { created_at_from: filterDateFrom } : {}),
        ...(filterDateTo ? { created_at_to: filterDateTo } : {}),
    };

    const columns = [
        { accessorKey: 'id', header: 'ID', sortable: true },
        { accessorKey: 'name', header: 'Name', sortable: true },
        { accessorKey: 'code', header: 'Code', sortable: true },
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
        setForm({ id: row.id, name: row.name, code: row.code });
        setIsEdit(true);
        setOpen(true);
        setFormErrors({});
    };

    const handleClose = () => {
        setOpen(false);
        setIsEdit(false);
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value } = e.target;
        setForm((prev) => ({ ...prev, [name]: value }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (isEdit && form.id) {
            router.put(route('product.units.update', form.id), { name: form.name, code: form.code }, {
                onSuccess: () => {
                    toast.success('Unit updated successfully.');
                    handleClose();
                    tableRef.current?.refetch();
                },
                onError: (errors) => setFormErrors(errors),
            });
        } else {
            router.post(route('product.units.store'), { name: form.name, code: form.code }, {
                onSuccess: () => {
                    toast.success('Unit created successfully.');
                    handleClose();
                    tableRef.current?.refetch();
                },
                onError: (errors) => setFormErrors(errors),
            });
        }
    };

    const handleDelete = (id: number) => {
        router.delete(route('product.units.destroy', id), {
            onSuccess: () => {
                toast.success('Unit deleted successfully.');
                tableRef.current?.refetch();
            },
        });
    };

    const handleBulkDelete = () => {
        if (selectedIds.length === 0) return;
        axios
            .delete(route('product.units.bulk-delete'), { data: { ids: selectedIds } })
            .then(() => {
                toast.success(`${selectedIds.length} unit(s) deleted successfully.`);
                tableRef.current?.refetch();
            })
            .catch(() => toast.error('Error deleting selected units.'));
    };

    return (
        <AppLayout title="Product Units" breadcrumbs={breadcrumbs} actions={<Button onClick={handleOpenAdd}>Add Unit</Button>}>
            <DataTable
                ref={tableRef}
                columns={columns}
                dataUrl={route('product.units.index')}
                onSelectionChange={setSelectedIds}
                extraActions={<BulkDeleteButton selectedCount={selectedIds.length} onDelete={handleBulkDelete} />}
                extraParams={extraParams}
                extraFilterCount={[filterName, filterCode, filterDateFrom, filterDateTo].filter(Boolean).length}
                onClearExtraFilters={() => {
                    setFilterName('');
                    setFilterCode('');
                    setFilterDateFrom('');
                    setFilterDateTo('');
                }}
                extraFilters={
                    <>
                        <div className="flex min-w-[160px] flex-1 flex-col gap-1">
                            <label className="text-xs font-medium text-muted-foreground">Name</label>
                            <Input
                                type="text"
                                value={filterName}
                                onChange={(e) => setFilterName(e.target.value)}
                                placeholder="Filter by name…"
                                className="h-8 text-sm"
                            />
                        </div>
                        <div className="flex min-w-[140px] flex-1 flex-col gap-1">
                            <label className="text-xs font-medium text-muted-foreground">Code</label>
                            <Input
                                type="text"
                                value={filterCode}
                                onChange={(e) => setFilterCode(e.target.value)}
                                placeholder="Filter by code…"
                                className="h-8 text-sm"
                            />
                        </div>
                        <div className="flex min-w-[140px] flex-1 flex-col gap-1">
                            <label className="text-xs font-medium text-muted-foreground">Created From</label>
                            <Input
                                type="date"
                                value={filterDateFrom}
                                onChange={(e) => setFilterDateFrom(e.target.value)}
                                className="h-8 text-sm"
                            />
                        </div>
                        <div className="flex min-w-[140px] flex-1 flex-col gap-1">
                            <label className="text-xs font-medium text-muted-foreground">Created To</label>
                            <Input
                                type="date"
                                value={filterDateTo}
                                onChange={(e) => setFilterDateTo(e.target.value)}
                                className="h-8 text-sm"
                            />
                        </div>
                    </>
                }
            />

            <BaseDialog
                open={open}
                onOpenChange={setOpen}
                title={isEdit ? 'Edit Unit' : 'Add Unit'}
                description={isEdit ? 'Update the details of the existing unit.' : 'Fill in the details to create a new unit.'}
                onSubmit={handleSubmit}
                onCancel={handleClose}
                submitLabel={isEdit ? 'Update' : 'Create'}
            >
                <div>
                    <Label htmlFor="name">Name *</Label>
                    <Input name="name" value={form.name} onChange={handleChange} required />
                    {formErrors.name && <p className="text-sm text-red-500">{formErrors.name}</p>}
                </div>
                <div>
                    <Label htmlFor="code">Code *</Label>
                    <Input name="code" value={form.code} onChange={handleChange} required />
                    {formErrors.code && <p className="text-sm text-red-500">{formErrors.code}</p>}
                </div>
            </BaseDialog>
        </AppLayout>
    );
}
