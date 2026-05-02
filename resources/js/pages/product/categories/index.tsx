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

const breadcrumbs: BreadcrumbItem[] = [breadcrumbItems.dashboard, breadcrumbItems.productCategories];

interface Category {
    id: number;
    name: string;
}

interface Props {
    categories: Category[];
}

const emptyForm = {
    id: undefined as number | undefined,
    name: '',
    parent_id: '',
    image: null as File | null,
};

export default function Index({ categories }: Props) {
    const tableRef = useRef<{ refetch: () => void }>(null);
    const [open, setOpen] = useState(false);
    const [isEdit, setIsEdit] = useState(false);
    const [form, setForm] = useState({ ...emptyForm });
    const [formErrors, setFormErrors] = useState<Record<string, string>>({});
    const [selectedIds, setSelectedIds] = useState<(string | number)[]>([]);

    // Extra filters
    const [filterName, setFilterName] = useState('');
    const [filterParentName, setFilterParentName] = useState('');
    const [filterDateFrom, setFilterDateFrom] = useState('');
    const [filterDateTo, setFilterDateTo] = useState('');

    const extraParams = {
        ...(filterName ? { name: filterName } : {}),
        ...(filterParentName ? { parent_name: filterParentName } : {}),
        ...(filterDateFrom ? { created_at_from: filterDateFrom } : {}),
        ...(filterDateTo ? { created_at_to: filterDateTo } : {}),
    };

    const columns = [
        { accessorKey: 'id', header: 'ID', sortable: true },
        { accessorKey: 'name', header: 'Name', sortable: true },
        { accessorKey: 'parent_name', header: 'Parent Category', sortable: true },
        {
            accessorKey: 'image_url',
            header: 'Image',
            sortable: false,
            cell: ({ row }: any) =>
                row.image_url ? <img src={row.image_url} alt={row.name} className="h-8 w-8 rounded object-cover" /> : null,
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
        setForm({ id: row.id, name: row.name, parent_id: row.parent_id ? String(row.parent_id) : '', image: null });
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
        if (form.parent_id) data.append('parent_id', form.parent_id);
        if (form.image) data.append('image', form.image);

        if (isEdit && form.id) {
            data.append('_method', 'PUT');
            router.post(route('product.categories.update', form.id), data, {
                forceFormData: true,
                onSuccess: () => {
                    toast.success('Category updated successfully.');
                    handleClose();
                    tableRef.current?.refetch();
                },
                onError: (errors) => setFormErrors(errors),
            });
        } else {
            router.post(route('product.categories.store'), data, {
                forceFormData: true,
                onSuccess: () => {
                    toast.success('Category created successfully.');
                    handleClose();
                    tableRef.current?.refetch();
                },
                onError: (errors) => setFormErrors(errors),
            });
        }
    };

    const handleDelete = (id: number) => {
        router.delete(route('product.categories.destroy', id), {
            onSuccess: () => {
                toast.success('Category deleted successfully.');
                tableRef.current?.refetch();
            },
        });
    };

    const handleBulkDelete = () => {
        if (selectedIds.length === 0) return;
        axios
            .delete(route('product.categories.bulk-delete'), { data: { ids: selectedIds } })
            .then(() => {
                toast.success(`${selectedIds.length} category/categories deleted successfully.`);
                tableRef.current?.refetch();
            })
            .catch(() => toast.error('Error deleting selected categories.'));
    };

    const parentOptions = isEdit ? categories.filter((c) => c.id !== form.id) : categories;

    return (
        <AppLayout title="Product Categories" breadcrumbs={breadcrumbs} actions={<Button onClick={handleOpenAdd}>Add Category</Button>}>
            <DataTable
                ref={tableRef}
                columns={columns}
                dataUrl={route('product.categories.index')}
                onSelectionChange={setSelectedIds}
                extraActions={<BulkDeleteButton selectedCount={selectedIds.length} onDelete={handleBulkDelete} />}
                extraParams={extraParams}
                extraFilterCount={[filterName, filterParentName, filterDateFrom, filterDateTo].filter(Boolean).length}
                onClearExtraFilters={() => {
                    setFilterName('');
                    setFilterParentName('');
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
                        <div className="flex min-w-[160px] flex-1 flex-col gap-1">
                            <label className="text-xs font-medium text-muted-foreground">Parent Category</label>
                            <Input
                                type="text"
                                value={filterParentName}
                                onChange={(e) => setFilterParentName(e.target.value)}
                                placeholder="Filter by parent…"
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
                title={isEdit ? 'Edit Category' : 'Add Category'}
                description={isEdit ? 'Update the details of the existing category.' : 'Fill in the details to create a new category.'}
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
                    <Label htmlFor="parent_id">Parent Category</Label>
                    <Select value={form.parent_id} onValueChange={(v) => setForm((prev) => ({ ...prev, parent_id: v === '__none__' ? '' : v }))}>
                        <SelectTrigger>
                            <SelectValue placeholder="None (top-level)" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="__none__">None (top-level)</SelectItem>
                            {parentOptions.map((c) => (
                                <SelectItem key={c.id} value={String(c.id)}>
                                    {c.name}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    {formErrors.parent_id && <p className="text-sm text-red-500">{formErrors.parent_id}</p>}
                </div>
                <div>
                    <Label htmlFor="image">Image</Label>
                    <Input name="image" type="file" accept="image/*" onChange={handleChange} />
                    {formErrors.image && <p className="text-sm text-red-500">{formErrors.image}</p>}
                </div>
            </BaseDialog>
        </AppLayout>
    );
}
