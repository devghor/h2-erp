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

const breadcrumbs: BreadcrumbItem[] = [breadcrumbItems.dashboard, breadcrumbItems.productBrands];

const emptyForm = {
    id: undefined as number | undefined,
    name: '',
    logo: null as File | null,
};

export default function Index() {
    const tableRef = useRef<{ refetch: () => void }>(null);
    const [open, setOpen] = useState(false);
    const [isEdit, setIsEdit] = useState(false);
    const [form, setForm] = useState({ ...emptyForm });
    const [formErrors, setFormErrors] = useState<Record<string, string>>({});
    const [selectedIds, setSelectedIds] = useState<(string | number)[]>([]);

    const [filterName, setFilterName] = useState('');
    const [filterDateFrom, setFilterDateFrom] = useState('');
    const [filterDateTo, setFilterDateTo] = useState('');

    const extraParams = {
        ...(filterName ? { name: filterName } : {}),
        ...(filterDateFrom ? { created_at_from: filterDateFrom } : {}),
        ...(filterDateTo ? { created_at_to: filterDateTo } : {}),
    };

    const columns = [
        { accessorKey: 'id', header: 'ID', sortable: true },
        { accessorKey: 'name', header: 'Name', sortable: true },
        {
            accessorKey: 'logo_url',
            header: 'Logo',
            sortable: false,
            cell: ({ row }: any) =>
                row.logo_url ? <img src={row.logo_url} alt={row.name} className="h-8 w-8 rounded object-cover" /> : null,
        },
        { accessorKey: 'created_at', header: 'Created At', sortable: true, filterType: 'date' as const },
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
        setForm({ id: row.id, name: row.name, logo: null });
        setIsEdit(true);
        setOpen(true);
        setFormErrors({});
    };

    const handleClose = () => {
        setOpen(false);
        setIsEdit(false);
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value, files } = e.target;
        setForm((prev) => ({ ...prev, [name]: files ? files[0] : value }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const data = new FormData();
        data.append('name', form.name);
        if (form.logo) data.append('logo', form.logo);

        if (isEdit && form.id) {
            data.append('_method', 'PUT');
            router.post(route('product.brands.update', form.id), data, {
                forceFormData: true,
                onSuccess: () => {
                    toast.success('Brand updated successfully.');
                    handleClose();
                    tableRef.current?.refetch();
                },
                onError: (errors) => setFormErrors(errors),
            });
        } else {
            router.post(route('product.brands.store'), data, {
                forceFormData: true,
                onSuccess: () => {
                    toast.success('Brand created successfully.');
                    handleClose();
                    tableRef.current?.refetch();
                },
                onError: (errors) => setFormErrors(errors),
            });
        }
    };

    const handleDelete = (id: number) => {
        router.delete(route('product.brands.destroy', id), {
            onSuccess: () => {
                toast.success('Brand deleted successfully.');
                tableRef.current?.refetch();
            },
        });
    };

    const handleBulkDelete = () => {
        if (selectedIds.length === 0) return;
        axios
            .delete(route('product.brands.bulk-delete'), { data: { ids: selectedIds } })
            .then(() => {
                toast.success(`${selectedIds.length} brand(s) deleted successfully.`);
                tableRef.current?.refetch();
            })
            .catch(() => toast.error('Error deleting selected brands.'));
    };

    return (
        <AppLayout title="Product Brands" breadcrumbs={breadcrumbs} actions={<Button onClick={handleOpenAdd}>Add Brand</Button>}>
            <DataTable
                ref={tableRef}
                columns={columns}
                dataUrl={route('product.brands.index')}
                onSelectionChange={setSelectedIds}
                extraActions={<BulkDeleteButton selectedCount={selectedIds.length} onDelete={handleBulkDelete} />}
                extraParams={extraParams}
                extraFilterCount={[filterName, filterDateFrom, filterDateTo].filter(Boolean).length}
                onClearExtraFilters={() => {
                    setFilterName('');
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
                title={isEdit ? 'Edit Brand' : 'Add Brand'}
                description={isEdit ? 'Update the details of the existing brand.' : 'Fill in the details to create a new brand.'}
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
                    <Label htmlFor="logo">Logo</Label>
                    <Input name="logo" type="file" accept="image/*" onChange={handleChange} />
                    {formErrors.logo && <p className="text-sm text-red-500">{formErrors.logo}</p>}
                </div>
            </BaseDialog>
        </AppLayout>
    );
}
