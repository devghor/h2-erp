import BulkDeleteButton from '@/components/bulk-delete-button';
import DataTable from '@/components/data-table/data-table';
import { RowActions } from '@/components/data-table/row-actions';
import { BaseDialog } from '@/components/dialog/base-dialog';
import { MultiCombobox } from '@/components/multi-combobox';
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

const breadcrumbs: BreadcrumbItem[] = [breadcrumbItems.dashboard, breadcrumbItems.configurationApprovalLevels];

type TypeOption = { value: string; label: string };
type Position = { id: number; name: string };
type Hrbp = { id: number; name: string };

export default function Index({ typeOptions, positions, hrbps }: { typeOptions: TypeOption[]; positions: Position[]; hrbps: Hrbp[] }) {
    const tableRef = useRef<{ refetch: () => void }>(null);
    const [open, setOpen] = useState(false);
    const [isEdit, setIsEdit] = useState(false);
    const [form, setForm] = useState({
        id: undefined as number | undefined,
        name: '',
        type: '',
        position_ids: [] as number[],
        hrbp_id: undefined as number | undefined,
    });
    const [formErrors, setFormErrors] = useState<Record<string, string>>({});
    const [selectedIds, setSelectedIds] = useState<(string | number)[]>([]);

    const columns = [
        { accessorKey: 'id', header: 'ID', sortable: true, searchable: true },
        { accessorKey: 'name', header: 'Name', sortable: true, searchable: true },
        { accessorKey: 'type_label', header: 'Type', sortable: false, searchable: false },
        { accessorKey: 'hrbp_name', header: 'HRBP', sortable: false, searchable: false },
        { accessorKey: 'position_names', header: 'Positions', sortable: false, searchable: false },
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
        type: '',
        position_ids: [] as number[],
        hrbp_id: undefined as number | undefined,
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
            type: row.type ?? '',
            position_ids: row.position_ids ?? [],
            hrbp_id: row.hrbp_id ?? undefined,
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
        router.delete(route('configuration.approval-levels.destroy', id), {
            onSuccess: () => tableRef.current?.refetch(),
        });
    };

    const handleBulkDelete = () => {
        if (selectedIds.length === 0) return;
        axios
            .delete(route('configuration.approval-levels.bulk-delete'), { data: { ids: selectedIds } })
            .then(() => {
                toast.success(`${selectedIds.length} approval level(s) deleted successfully`);
                tableRef.current?.refetch();
            })
            .catch(() => {
                toast.error('Error deleting selected approval levels');
            });
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value } = e.target;
        setForm((prev) => ({ ...prev, [name]: value }));
    };

    const handleTypeChange = (type: string) => {
        setForm((prev) => ({
            ...prev,
            type,
            position_ids: type === 'Position' ? prev.position_ids : [],
            hrbp_id: type === 'HRBP' ? prev.hrbp_id : undefined,
        }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const data: Record<string, any> = {
            name: form.name,
            type: form.type,
        };
        if (form.type === 'Position') {
            data.position_ids = form.position_ids;
        }
        if (form.type === 'HRBP') {
            data.hrbp_id = form.hrbp_id;
        }
        if (isEdit && form.id) {
            router.put(route('configuration.approval-levels.update', form.id), data, {
                onSuccess: () => {
                    handleClose();
                    tableRef.current?.refetch();
                },
                onError: (errors) => setFormErrors(errors),
            });
        } else {
            router.post(route('configuration.approval-levels.store'), data, {
                onSuccess: () => {
                    handleClose();
                    tableRef.current?.refetch();
                },
                onError: (errors) => setFormErrors(errors),
            });
        }
    };

    const positionOptions = positions.map((p) => ({ id: p.id, label: p.name }));

    return (
        <AppLayout title="Approval Levels" breadcrumbs={breadcrumbs} actions={<Button onClick={handleOpenAdd}>Add Approval Level</Button>}>
            <DataTable
                ref={tableRef}
                columns={columns}
                dataUrl={route('configuration.approval-levels.index')}
                onSelectionChange={setSelectedIds}
                extraActions={<BulkDeleteButton selectedCount={selectedIds.length} onDelete={handleBulkDelete} />}
            />
            <BaseDialog
                open={open}
                onOpenChange={setOpen}
                title={isEdit ? 'Edit Approval Level' : 'Add Approval Level'}
                description={
                    isEdit ? 'Update the details of the existing approval level.' : 'Fill in the details to create a new approval level.'
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
                    <Label htmlFor="type">Type</Label>
                    <Select value={form.type} onValueChange={handleTypeChange}>
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
                {form.type === 'Position' && (
                    <div>
                        <Label>Positions</Label>
                        <MultiCombobox
                            options={positionOptions}
                            value={form.position_ids}
                            onChange={(position_ids) => setForm((prev) => ({ ...prev, position_ids }))}
                            placeholder="Select positions..."
                            searchPlaceholder="Search positions..."
                        />
                        {formErrors.position_ids && <p className="text-sm text-red-500">{formErrors.position_ids}</p>}
                    </div>
                )}
                {form.type === 'HRBP' && (
                    <div>
                        <Label htmlFor="hrbp_id">HRBP</Label>
                        <Select
                            value={form.hrbp_id ? String(form.hrbp_id) : ''}
                            onValueChange={(value) => setForm((prev) => ({ ...prev, hrbp_id: Number(value) }))}
                        >
                            <SelectTrigger>
                                <SelectValue placeholder="Select an HRBP" />
                            </SelectTrigger>
                            <SelectContent>
                                {hrbps.map((hrbp) => (
                                    <SelectItem key={hrbp.id} value={String(hrbp.id)}>
                                        {hrbp.name}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        {formErrors.hrbp_id && <p className="text-sm text-red-500">{formErrors.hrbp_id}</p>}
                    </div>
                )}
            </BaseDialog>
        </AppLayout>
    );
}
