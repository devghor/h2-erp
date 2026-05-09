import BulkDeleteButton from '@/components/bulk-delete-button';
import DataTable from '@/components/data-table/data-table';
import { RowActions } from '@/components/data-table/row-actions';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { breadcrumbItems } from '@/config/breadcrumbs';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/react';
import axios from 'axios';
import { useRef, useState } from 'react';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [breadcrumbItems.dashboard, breadcrumbItems.productProducts];

type Option = { id: number; name: string };
type EnumOption = { value: string; label: string };

interface Props {
    categories: Option[];
    brands: Option[];
    types: EnumOption[];
}

export default function Index({ categories, brands, types }: Props) {
    const tableRef = useRef<{ refetch: () => void }>(null);
    const [selectedIds, setSelectedIds] = useState<(string | number)[]>([]);

    const [filterName, setFilterName] = useState('');
    const [filterType, setFilterType] = useState('');
    const [filterBrandId, setFilterBrandId] = useState('');
    const [filterCategoryId, setFilterCategoryId] = useState('');
    const [filterDateFrom, setFilterDateFrom] = useState('');
    const [filterDateTo, setFilterDateTo] = useState('');

    const extraParams = {
        ...(filterName ? { name: filterName } : {}),
        ...(filterType ? { type: filterType } : {}),
        ...(filterBrandId ? { brand_id: filterBrandId } : {}),
        ...(filterCategoryId ? { category_id: filterCategoryId } : {}),
        ...(filterDateFrom ? { created_at_from: filterDateFrom } : {}),
        ...(filterDateTo ? { created_at_to: filterDateTo } : {}),
    };

    const columns = [
        { accessorKey: 'id', header: 'ID', sortable: true },
        { accessorKey: 'name', header: 'Name', sortable: true },
        {
            accessorKey: 'image_url',
            header: 'Image',
            sortable: false,
            cell: ({ row }: any) =>
                row.image_url ? (
                    <img src={row.image_url} alt={row.name} className="h-8 w-8 rounded object-cover" />
                ) : null,
        },
        { accessorKey: 'type', header: 'Type', sortable: true },
        { accessorKey: 'code', header: 'Code', sortable: true },
        { accessorKey: 'brand_name', header: 'Brand', sortable: true },
        { accessorKey: 'category_name', header: 'Category', sortable: true },
        { accessorKey: 'product_cost', header: 'Cost', sortable: true },
        { accessorKey: 'product_price', header: 'Price', sortable: true },
        { accessorKey: 'is_featured', header: 'Featured', sortable: true },
        { accessorKey: 'created_at', header: 'Created At', sortable: true },
        {
            accessorKey: 'actions',
            header: 'Actions',
            sortable: false,
            className: 'w-[60px] text-center',
            cell: ({ row }: any) => (
                <RowActions
                    onEdit={() => router.visit(route('product.products.show', row.id))}
                    onDelete={() => handleDelete(row.id)}
                />
            ),
        },
    ];

    const handleDelete = (id: number) => {
        router.delete(route('product.products.destroy', id), {
            onSuccess: () => {
                toast.success('Product deleted successfully.');
                tableRef.current?.refetch();
            },
        });
    };

    const handleBulkDelete = () => {
        if (selectedIds.length === 0) return;
        axios
            .delete(route('product.products.bulk-delete'), { data: { ids: selectedIds } })
            .then(() => {
                toast.success(`${selectedIds.length} product(s) deleted successfully.`);
                tableRef.current?.refetch();
            })
            .catch(() => toast.error('Error deleting selected products.'));
    };

    const clearFilters = () => {
        setFilterName('');
        setFilterType('');
        setFilterBrandId('');
        setFilterCategoryId('');
        setFilterDateFrom('');
        setFilterDateTo('');
    };

    return (
        <AppLayout
            title="Products"
            breadcrumbs={breadcrumbs}
            actions={<Button onClick={() => router.visit(route('product.products.create'))}>Add Product</Button>}
        >
            <DataTable
                ref={tableRef}
                columns={columns}
                dataUrl={route('product.products.index')}
                onSelectionChange={setSelectedIds}
                extraActions={<BulkDeleteButton selectedCount={selectedIds.length} onDelete={handleBulkDelete} />}
                extraParams={extraParams}
                extraFilterCount={[filterName, filterType, filterBrandId, filterCategoryId, filterDateFrom, filterDateTo].filter(Boolean).length}
                onClearExtraFilters={clearFilters}
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
                            <label className="text-xs font-medium text-muted-foreground">Type</label>
                            <Select value={filterType} onValueChange={setFilterType}>
                                <SelectTrigger className="h-8 text-sm">
                                    <SelectValue placeholder="All types" />
                                </SelectTrigger>
                                <SelectContent>
                                    {types.map((t) => (
                                        <SelectItem key={t.value} value={t.value}>
                                            {t.label}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                        <div className="flex min-w-[140px] flex-1 flex-col gap-1">
                            <label className="text-xs font-medium text-muted-foreground">Brand</label>
                            <Select value={filterBrandId} onValueChange={setFilterBrandId}>
                                <SelectTrigger className="h-8 text-sm">
                                    <SelectValue placeholder="All brands" />
                                </SelectTrigger>
                                <SelectContent>
                                    {brands.map((b) => (
                                        <SelectItem key={b.id} value={String(b.id)}>
                                            {b.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                        <div className="flex min-w-[140px] flex-1 flex-col gap-1">
                            <label className="text-xs font-medium text-muted-foreground">Category</label>
                            <Select value={filterCategoryId} onValueChange={setFilterCategoryId}>
                                <SelectTrigger className="h-8 text-sm">
                                    <SelectValue placeholder="All categories" />
                                </SelectTrigger>
                                <SelectContent>
                                    {categories.map((c) => (
                                        <SelectItem key={c.id} value={String(c.id)}>
                                            {c.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
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
        </AppLayout>
    );
}
