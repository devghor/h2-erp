import DataTable from '@/components/data-table/data-table';
import { RowActions } from '@/components/data-table/row-actions';
import { Button } from '@/components/ui/button';
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import BulkDeleteButton from '@/components/bulk-delete-button';
import { BaseDialog } from '@/components/dialog/base-dialog';
import { breadcrumbItems } from '@/config/breadcrumbs';
import AppLayout from '@/layouts/app-layout';
import { cn } from '@/lib/utils';
import { BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/react';
import axios from 'axios';
import { Check, ChevronsUpDown } from 'lucide-react';
import { useMemo, useRef, useState } from 'react';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [breadcrumbItems.dashboard, breadcrumbItems.configurationPositions];

type Branch = { id: number; name: string };
type Division = { id: number; name: string };
type Department = { id: number; name: string };
type PositionGroup = { id: number; name: string };
type PositionOption = { id: number; name: string; parent_id: number | null };

function getDescendantIds(positionId: number, positions: PositionOption[]): Set<number> {
    const ids = new Set<number>();
    const children = positions.filter((p) => p.parent_id === positionId);
    for (const child of children) {
        ids.add(child.id);
        for (const id of getDescendantIds(child.id, positions)) {
            ids.add(id);
        }
    }
    return ids;
}

export default function Index({
    branches,
    divisions,
    departments,
    positionGroups,
    positions,
}: {
    branches: Branch[];
    divisions: Division[];
    departments: Department[];
    positionGroups: PositionGroup[];
    positions: PositionOption[];
}) {
    const tableRef = useRef<{ refetch: () => void }>(null);
    const [open, setOpen] = useState(false);
    const [isEdit, setIsEdit] = useState(false);
    const [form, setForm] = useState({
        id: undefined as number | undefined,
        name: '',
        parent_id: '',
        description: '',
        branch_id: '',
        division_id: '',
        department_id: '',
        position_group_id: '',
    });
    const [formErrors, setFormErrors] = useState<Record<string, string>>({});
    const [selectedIds, setSelectedIds] = useState<(string | number)[]>([]);

    const [branchOpen, setBranchOpen] = useState(false);
    const [divisionOpen, setDivisionOpen] = useState(false);
    const [departmentOpen, setDepartmentOpen] = useState(false);
    const [positionGroupOpen, setPositionGroupOpen] = useState(false);
    const [parentPositionOpen, setParentPositionOpen] = useState(false);
    const [filterParentPositionOpen, setFilterParentPositionOpen] = useState(false);
    const [filterBranchId, setFilterBranchId] = useState('');
    const [filterDivisionId, setFilterDivisionId] = useState('');
    const [filterDepartmentId, setFilterDepartmentId] = useState('');
    const [filterPositionGroupId, setFilterPositionGroupId] = useState('');
    const [filterParentPositionId, setFilterParentPositionId] = useState('');

    const availableParentPositions = useMemo(() => {
        if (!form.id) return positions;
        const excludedIds = getDescendantIds(form.id, positions);
        excludedIds.add(form.id);
        return positions.filter((p) => !excludedIds.has(p.id));
    }, [positions, form.id]);

    const extraParams = {
        ...(filterBranchId ? { branch_id: filterBranchId } : {}),
        ...(filterDivisionId ? { division_id: filterDivisionId } : {}),
        ...(filterDepartmentId ? { department_id: filterDepartmentId } : {}),
        ...(filterPositionGroupId ? { position_group_filter: filterPositionGroupId } : {}),
        ...(filterParentPositionId ? { parent_position_filter: filterParentPositionId } : {}),
    };

    const columns = [
        { accessorKey: 'id', header: 'ID', sortable: true, searchable: true },
        { accessorKey: 'name', header: 'Name', sortable: true, searchable: true },
        { accessorKey: 'branch_name', header: 'Branch', sortable: true },
        { accessorKey: 'division_name', header: 'Division', sortable: true },
        { accessorKey: 'department_name', header: 'Department', sortable: true },
        { accessorKey: 'position_group_name', header: 'Position Group', sortable: true },
        { accessorKey: 'parent_name', header: 'Parent Position', sortable: true },
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
        parent_id: '',
        description: '',
        branch_id: '',
        division_id: '',
        department_id: '',
        position_group_id: '',
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
            name: row.name,
            parent_id: row.parent_id ?? '',
            description: row.description ?? '',
            branch_id: row.branch_id ? row.branch_id.toString() : '',
            division_id: row.division_id ? row.division_id.toString() : '',
            department_id: row.department_id ? row.department_id.toString() : '',
            position_group_id: row.position_group_id ? row.position_group_id.toString() : '',
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
        router.delete(route('configuration.positions.destroy', id), {
            onSuccess: () => tableRef.current?.refetch(),
        });
    };

    const handleBulkDelete = () => {
        if (selectedIds.length === 0) return;
        axios.delete(route('configuration.positions.bulk-delete'), { data: { ids: selectedIds } }).then(() => {
            toast.success(`${selectedIds.length} position(s) deleted successfully`);
            tableRef.current?.refetch();
        }).catch(() => {
            toast.error('Error deleting selected positions');
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
            parent_id: form.parent_id || null,
            description: form.description || null,
            branch_id: form.branch_id || null,
            division_id: form.division_id || null,
            department_id: form.department_id || null,
            position_group_id: form.position_group_id ? Number(form.position_group_id) : null,
        };
        if (isEdit && form.id) {
            router.put(route('configuration.positions.update', form.id), data, {
                onSuccess: () => {
                    handleClose();
                    tableRef.current?.refetch();
                },
                onError: (errors) => setFormErrors(errors),
            });
        } else {
            router.post(route('configuration.positions.store'), data, {
                onSuccess: () => {
                    handleClose();
                    tableRef.current?.refetch();
                },
                onError: (errors) => setFormErrors(errors),
            });
        }
    };

    return (
        <AppLayout title="Positions" breadcrumbs={breadcrumbs} actions={<Button onClick={handleOpenAdd}>Add Position</Button>}>
            <DataTable
                ref={tableRef}
                columns={columns}
                dataUrl={route('configuration.positions.index')}
                onSelectionChange={setSelectedIds}
                extraActions={<BulkDeleteButton selectedCount={selectedIds.length} onDelete={handleBulkDelete} />}
                extraParams={extraParams}
                extraFilterCount={[filterBranchId, filterDivisionId, filterDepartmentId, filterPositionGroupId, filterParentPositionId].filter(Boolean).length}
                onClearExtraFilters={() => {
                    setFilterBranchId('');
                    setFilterDivisionId('');
                    setFilterDepartmentId('');
                    setFilterPositionGroupId('');
                    setFilterParentPositionId('');
                }}
                extraFilters={
                    <>
                        <div className="flex min-w-[160px] flex-1 flex-col gap-1">
                            <label className="text-xs font-medium text-muted-foreground">Branch</label>
                            <Popover open={branchOpen} onOpenChange={setBranchOpen}>
                                <PopoverTrigger asChild>
                                    <Button variant="outline" role="combobox" aria-expanded={branchOpen} className="h-8 justify-between text-sm font-normal">
                                        <span className="truncate">
                                            {filterBranchId ? branches.find((b) => String(b.id) === filterBranchId)?.name : 'All branches…'}
                                        </span>
                                        <ChevronsUpDown className="ml-2 h-3.5 w-3.5 shrink-0 opacity-50" />
                                    </Button>
                                </PopoverTrigger>
                                <PopoverContent className="w-[220px] p-0">
                                    <Command>
                                        <CommandInput placeholder="Search branch…" className="h-8" />
                                        <CommandList>
                                            <CommandEmpty>No branch found.</CommandEmpty>
                                            <CommandGroup>
                                                <CommandItem value="__all__" onSelect={() => { setFilterBranchId(''); setBranchOpen(false); }}>
                                                    <Check className={cn('mr-2 h-3.5 w-3.5', !filterBranchId ? 'opacity-100' : 'opacity-0')} />
                                                    All
                                                </CommandItem>
                                                {branches.map((b) => (
                                                    <CommandItem key={b.id} value={b.name} onSelect={() => { setFilterBranchId(String(b.id)); setBranchOpen(false); }}>
                                                        <Check className={cn('mr-2 h-3.5 w-3.5', filterBranchId === String(b.id) ? 'opacity-100' : 'opacity-0')} />
                                                        {b.name}
                                                    </CommandItem>
                                                ))}
                                            </CommandGroup>
                                        </CommandList>
                                    </Command>
                                </PopoverContent>
                            </Popover>
                        </div>

                        <div className="flex min-w-[160px] flex-1 flex-col gap-1">
                            <label className="text-xs font-medium text-muted-foreground">Division</label>
                            <Popover open={divisionOpen} onOpenChange={setDivisionOpen}>
                                <PopoverTrigger asChild>
                                    <Button variant="outline" role="combobox" aria-expanded={divisionOpen} className="h-8 justify-between text-sm font-normal">
                                        <span className="truncate">
                                            {filterDivisionId ? divisions.find((d) => String(d.id) === filterDivisionId)?.name : 'All divisions…'}
                                        </span>
                                        <ChevronsUpDown className="ml-2 h-3.5 w-3.5 shrink-0 opacity-50" />
                                    </Button>
                                </PopoverTrigger>
                                <PopoverContent className="w-[220px] p-0">
                                    <Command>
                                        <CommandInput placeholder="Search division…" className="h-8" />
                                        <CommandList>
                                            <CommandEmpty>No division found.</CommandEmpty>
                                            <CommandGroup>
                                                <CommandItem value="__all__" onSelect={() => { setFilterDivisionId(''); setDivisionOpen(false); }}>
                                                    <Check className={cn('mr-2 h-3.5 w-3.5', !filterDivisionId ? 'opacity-100' : 'opacity-0')} />
                                                    All
                                                </CommandItem>
                                                {divisions.map((d) => (
                                                    <CommandItem key={d.id} value={d.name} onSelect={() => { setFilterDivisionId(String(d.id)); setDivisionOpen(false); }}>
                                                        <Check className={cn('mr-2 h-3.5 w-3.5', filterDivisionId === String(d.id) ? 'opacity-100' : 'opacity-0')} />
                                                        {d.name}
                                                    </CommandItem>
                                                ))}
                                            </CommandGroup>
                                        </CommandList>
                                    </Command>
                                </PopoverContent>
                            </Popover>
                        </div>

                        <div className="flex min-w-[160px] flex-1 flex-col gap-1">
                            <label className="text-xs font-medium text-muted-foreground">Department</label>
                            <Popover open={departmentOpen} onOpenChange={setDepartmentOpen}>
                                <PopoverTrigger asChild>
                                    <Button variant="outline" role="combobox" aria-expanded={departmentOpen} className="h-8 justify-between text-sm font-normal">
                                        <span className="truncate">
                                            {filterDepartmentId ? departments.find((d) => String(d.id) === filterDepartmentId)?.name : 'All departments…'}
                                        </span>
                                        <ChevronsUpDown className="ml-2 h-3.5 w-3.5 shrink-0 opacity-50" />
                                    </Button>
                                </PopoverTrigger>
                                <PopoverContent className="w-[220px] p-0">
                                    <Command>
                                        <CommandInput placeholder="Search department…" className="h-8" />
                                        <CommandList>
                                            <CommandEmpty>No department found.</CommandEmpty>
                                            <CommandGroup>
                                                <CommandItem value="__all__" onSelect={() => { setFilterDepartmentId(''); setDepartmentOpen(false); }}>
                                                    <Check className={cn('mr-2 h-3.5 w-3.5', !filterDepartmentId ? 'opacity-100' : 'opacity-0')} />
                                                    All
                                                </CommandItem>
                                                {departments.map((d) => (
                                                    <CommandItem key={d.id} value={d.name} onSelect={() => { setFilterDepartmentId(String(d.id)); setDepartmentOpen(false); }}>
                                                        <Check className={cn('mr-2 h-3.5 w-3.5', filterDepartmentId === String(d.id) ? 'opacity-100' : 'opacity-0')} />
                                                        {d.name}
                                                    </CommandItem>
                                                ))}
                                            </CommandGroup>
                                        </CommandList>
                                    </Command>
                                </PopoverContent>
                            </Popover>
                        </div>

                        <div className="flex min-w-[160px] flex-1 flex-col gap-1">
                            <label className="text-xs font-medium text-muted-foreground">Position Group</label>
                            <Popover open={positionGroupOpen} onOpenChange={setPositionGroupOpen}>
                                <PopoverTrigger asChild>
                                    <Button variant="outline" role="combobox" aria-expanded={positionGroupOpen} className="h-8 justify-between text-sm font-normal">
                                        <span className="truncate">
                                            {filterPositionGroupId ? positionGroups.find((g) => String(g.id) === filterPositionGroupId)?.name : 'All position groups…'}
                                        </span>
                                        <ChevronsUpDown className="ml-2 h-3.5 w-3.5 shrink-0 opacity-50" />
                                    </Button>
                                </PopoverTrigger>
                                <PopoverContent className="w-[220px] p-0">
                                    <Command>
                                        <CommandInput placeholder="Search position group…" className="h-8" />
                                        <CommandList>
                                            <CommandEmpty>No position group found.</CommandEmpty>
                                            <CommandGroup>
                                                <CommandItem value="__all__" onSelect={() => { setFilterPositionGroupId(''); setPositionGroupOpen(false); }}>
                                                    <Check className={cn('mr-2 h-3.5 w-3.5', !filterPositionGroupId ? 'opacity-100' : 'opacity-0')} />
                                                    All
                                                </CommandItem>
                                                {positionGroups.map((g) => (
                                                    <CommandItem key={g.id} value={g.name} onSelect={() => { setFilterPositionGroupId(String(g.id)); setPositionGroupOpen(false); }}>
                                                        <Check className={cn('mr-2 h-3.5 w-3.5', filterPositionGroupId === String(g.id) ? 'opacity-100' : 'opacity-0')} />
                                                        {g.name}
                                                    </CommandItem>
                                                ))}
                                            </CommandGroup>
                                        </CommandList>
                                    </Command>
                                </PopoverContent>
                            </Popover>
                        </div>

                        <div className="flex min-w-[160px] flex-1 flex-col gap-1">
                            <label className="text-xs font-medium text-muted-foreground">Parent Position</label>
                            <Popover open={filterParentPositionOpen} onOpenChange={setFilterParentPositionOpen}>
                                <PopoverTrigger asChild>
                                    <Button variant="outline" role="combobox" aria-expanded={filterParentPositionOpen} className="h-8 justify-between text-sm font-normal">
                                        <span className="truncate">
                                            {filterParentPositionId ? positions.find((p) => String(p.id) === filterParentPositionId)?.name : 'All parent positions…'}
                                        </span>
                                        <ChevronsUpDown className="ml-2 h-3.5 w-3.5 shrink-0 opacity-50" />
                                    </Button>
                                </PopoverTrigger>
                                <PopoverContent className="w-[220px] p-0">
                                    <Command>
                                        <CommandInput placeholder="Search position…" className="h-8" />
                                        <CommandList>
                                            <CommandEmpty>No position found.</CommandEmpty>
                                            <CommandGroup>
                                                <CommandItem value="__all__" onSelect={() => { setFilterParentPositionId(''); setFilterParentPositionOpen(false); }}>
                                                    <Check className={cn('mr-2 h-3.5 w-3.5', !filterParentPositionId ? 'opacity-100' : 'opacity-0')} />
                                                    All
                                                </CommandItem>
                                                {positions.map((p) => (
                                                    <CommandItem key={p.id} value={p.name} onSelect={() => { setFilterParentPositionId(String(p.id)); setFilterParentPositionOpen(false); }}>
                                                        <Check className={cn('mr-2 h-3.5 w-3.5', filterParentPositionId === String(p.id) ? 'opacity-100' : 'opacity-0')} />
                                                        {p.name}
                                                    </CommandItem>
                                                ))}
                                            </CommandGroup>
                                        </CommandList>
                                    </Command>
                                </PopoverContent>
                            </Popover>
                        </div>
                    </>
                }
            />
            <BaseDialog
                open={open}
                onOpenChange={setOpen}
                title={isEdit ? 'Edit Position' : 'Add Position'}
                description={isEdit ? 'Update the details of the existing position.' : 'Fill in the details to create a new position.'}
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
                    <Label htmlFor="branch_id">Branch</Label>
                    <Select
                        value={form.branch_id}
                        onValueChange={(value) => setForm((prev) => ({ ...prev, branch_id: value }))}
                    >
                        <SelectTrigger>
                            <SelectValue placeholder="Select a branch" />
                        </SelectTrigger>
                        <SelectContent>
                            {branches.map((branch) => (
                                <SelectItem key={branch.id} value={branch.id.toString()}>
                                    {branch.name}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    {formErrors.branch_id && <p className="text-sm text-red-500">{formErrors.branch_id}</p>}
                </div>
                <div>
                    <Label htmlFor="division_id">Division</Label>
                    <Select
                        value={form.division_id}
                        onValueChange={(value) => setForm((prev) => ({ ...prev, division_id: value }))}
                    >
                        <SelectTrigger>
                            <SelectValue placeholder="Select a division" />
                        </SelectTrigger>
                        <SelectContent>
                            {divisions.map((division) => (
                                <SelectItem key={division.id} value={division.id.toString()}>
                                    {division.name}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    {formErrors.division_id && <p className="text-sm text-red-500">{formErrors.division_id}</p>}
                </div>
                <div>
                    <Label htmlFor="department_id">Department</Label>
                    <Select
                        value={form.department_id}
                        onValueChange={(value) => setForm((prev) => ({ ...prev, department_id: value }))}
                    >
                        <SelectTrigger>
                            <SelectValue placeholder="Select a department" />
                        </SelectTrigger>
                        <SelectContent>
                            {departments.map((department) => (
                                <SelectItem key={department.id} value={department.id.toString()}>
                                    {department.name}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    {formErrors.department_id && <p className="text-sm text-red-500">{formErrors.department_id}</p>}
                </div>
                <div>
                    <Label htmlFor="position_group_id">Position Group</Label>
                    <Select
                        value={form.position_group_id}
                        onValueChange={(value) => setForm((prev) => ({ ...prev, position_group_id: value }))}
                    >
                        <SelectTrigger>
                            <SelectValue placeholder="Select a position group" />
                        </SelectTrigger>
                        <SelectContent>
                            {positionGroups.map((group) => (
                                <SelectItem key={group.id} value={group.id.toString()}>
                                    {group.name}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    {formErrors.position_group_id && <p className="text-sm text-red-500">{formErrors.position_group_id}</p>}
                </div>
                <div>
                    <Label htmlFor="parent_id">Parent Position</Label>
                    <Popover open={parentPositionOpen} onOpenChange={setParentPositionOpen}>
                        <PopoverTrigger asChild>
                            <Button
                                type="button"
                                variant="outline"
                                role="combobox"
                                aria-expanded={parentPositionOpen}
                                className="w-full justify-between font-normal"
                            >
                                <span className="truncate">
                                    {form.parent_id
                                        ? availableParentPositions.find((p) => String(p.id) === form.parent_id)?.name
                                        : 'None'}
                                </span>
                                <ChevronsUpDown className="ml-2 h-3.5 w-3.5 shrink-0 opacity-50" />
                            </Button>
                        </PopoverTrigger>
                        <PopoverContent className="w-[320px] p-0">
                            <Command>
                                <CommandInput placeholder="Search position…" className="h-8" />
                                <CommandList>
                                    <CommandEmpty>No position found.</CommandEmpty>
                                    <CommandGroup>
                                        <CommandItem
                                            value="__none__"
                                            onSelect={() => {
                                                setForm((prev) => ({ ...prev, parent_id: '' }));
                                                setParentPositionOpen(false);
                                            }}
                                        >
                                            <Check className={cn('mr-2 h-3.5 w-3.5', !form.parent_id ? 'opacity-100' : 'opacity-0')} />
                                            None
                                        </CommandItem>
                                        {availableParentPositions.map((p) => (
                                            <CommandItem
                                                key={p.id}
                                                value={p.name}
                                                onSelect={() => {
                                                    setForm((prev) => ({ ...prev, parent_id: String(p.id) }));
                                                    setParentPositionOpen(false);
                                                }}
                                            >
                                                <Check className={cn('mr-2 h-3.5 w-3.5', form.parent_id === String(p.id) ? 'opacity-100' : 'opacity-0')} />
                                                {p.name}
                                            </CommandItem>
                                        ))}
                                    </CommandGroup>
                                </CommandList>
                            </Command>
                        </PopoverContent>
                    </Popover>
                    {formErrors.parent_id && <p className="text-sm text-red-500">{formErrors.parent_id}</p>}
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
