import DataTable from '@/components/data-table/data-table';
import { RowActions } from '@/components/data-table/row-actions';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import BulkDeleteButton from '@/components/bulk-delete-button';
import { BaseDialog } from '@/components/dialog/base-dialog';
import { breadcrumbItems } from '@/config/breadcrumbs';
import AppLayout from '@/layouts/app-layout';
import { cn } from '@/lib/utils';
import { BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/react';
import axios from 'axios';
import { Check, ChevronsUpDown, X } from 'lucide-react';
import { useRef, useState } from 'react';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [breadcrumbItems.dashboard, breadcrumbItems.configurationFunctionAssignments];

type User = { id: number; name: string; email: string };
type TypeOption = { value: number; label: string };

export default function Index({ users, typeOptions }: { users: User[]; typeOptions: TypeOption[] }) {
    const tableRef = useRef<{ refetch: () => void }>(null);
    const [open, setOpen] = useState(false);
    const [isEdit, setIsEdit] = useState(false);
    const [form, setForm] = useState({
        id: undefined as number | undefined,
        name: '',
        code: '',
        user_ids: [] as number[],
        description: '',
        type: '' as unknown as number,
    });
    const [formErrors, setFormErrors] = useState<Record<string, string>>({});
    const [selectedIds, setSelectedIds] = useState<(string | number)[]>([]);
    const [userPopoverOpen, setUserPopoverOpen] = useState(false);

    const emptyForm = () => ({
        id: undefined as number | undefined,
        name: '',
        code: '',
        user_ids: [] as number[],
        description: '',
        type: '' as unknown as number,
    });

    const columns = [
        { accessorKey: 'id', header: 'ID', sortable: true, searchable: true },
        { accessorKey: 'name', header: 'Name', sortable: true, searchable: true },
        { accessorKey: 'code', header: 'Code', sortable: true, searchable: true },
        { accessorKey: 'type_label', header: 'Type', sortable: true },
        { accessorKey: 'description', header: 'Description', sortable: false },
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
            user_ids: row.user_ids ?? [],
            description: row.description ?? '',
            type: row.type,
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
        router.delete(route('configuration.function-assignments.destroy', id), {
            onSuccess: () => tableRef.current?.refetch(),
        });
    };

    const handleBulkDelete = () => {
        if (selectedIds.length === 0) return;
        axios
            .delete(route('configuration.function-assignments.bulk-delete'), { data: { ids: selectedIds } })
            .then(() => {
                toast.success(`${selectedIds.length} function assignment(s) deleted successfully`);
                tableRef.current?.refetch();
            })
            .catch(() => toast.error('Error deleting selected function assignments'));
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        const { name, value } = e.target;
        setForm((prev) => ({ ...prev, [name]: value }));
    };

    const toggleUser = (userId: number) => {
        setForm((prev) => ({
            ...prev,
            user_ids: prev.user_ids.includes(userId)
                ? prev.user_ids.filter((id) => id !== userId)
                : [...prev.user_ids, userId],
        }));
    };

    const removeUser = (userId: number) => {
        setForm((prev) => ({
            ...prev,
            user_ids: prev.user_ids.filter((id) => id !== userId),
        }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (isEdit && form.id) {
            router.put(
                route('configuration.function-assignments.update', form.id),
                {
                    name: form.name,
                    code: form.code || null,
                    user_ids: form.user_ids,
                    description: form.description || null,
                    type: form.type,
                },
                {
                    onSuccess: () => {
                        handleClose();
                        tableRef.current?.refetch();
                    },
                    onError: (errors) => setFormErrors(errors),
                },
            );
        } else {
            router.post(
                route('configuration.function-assignments.store'),
                {
                    name: form.name,
                    code: form.code || null,
                    user_ids: form.user_ids,
                    description: form.description || null,
                    type: form.type,
                },
                {
                    onSuccess: () => {
                        handleClose();
                        tableRef.current?.refetch();
                    },
                    onError: (errors) => setFormErrors(errors),
                },
            );
        }
    };

    return (
        <AppLayout title="Function Assignments" breadcrumbs={breadcrumbs} actions={<Button onClick={handleOpenAdd}>Add Function Assignment</Button>}>
            <DataTable
                ref={tableRef}
                columns={columns}
                dataUrl={route('configuration.function-assignments.index')}
                onSelectionChange={setSelectedIds}
                extraActions={<BulkDeleteButton selectedCount={selectedIds.length} onDelete={handleBulkDelete} />}
            />
            <BaseDialog
                open={open}
                onOpenChange={setOpen}
                title={isEdit ? 'Edit Function Assignment' : 'Add Function Assignment'}
                description={
                    isEdit
                        ? 'Update the details of the existing function assignment.'
                        : 'Fill in the details to create a new function assignment.'
                }
                onSubmit={handleSubmit}
                onCancel={handleClose}
                submitLabel={isEdit ? 'Update' : 'Create'}
            >
                <div>
                    <Label htmlFor="name">Name</Label>
                    <Input id="name" name="name" value={form.name} onChange={handleChange} required />
                    {formErrors.name && <p className="text-sm text-red-500">{formErrors.name}</p>}
                </div>
                <div>
                    <Label htmlFor="code">Code</Label>
                    <Input id="code" name="code" value={form.code} onChange={handleChange} />
                    {formErrors.code && <p className="text-sm text-red-500">{formErrors.code}</p>}
                </div>
                <div>
                    <Label htmlFor="type">Type</Label>
                    <Select
                        value={form.type !== undefined && form.type !== ('' as unknown as number) ? String(form.type) : ''}
                        onValueChange={(value) => setForm((prev) => ({ ...prev, type: Number(value) }))}
                    >
                        <SelectTrigger>
                            <SelectValue placeholder="Select a type" />
                        </SelectTrigger>
                        <SelectContent>
                            {typeOptions.map((option) => (
                                <SelectItem key={option.value} value={String(option.value)}>
                                    {option.label}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    {formErrors.type && <p className="text-sm text-red-500">{formErrors.type}</p>}
                </div>
                <div>
                    <Label>Users</Label>
                    <Popover open={userPopoverOpen} onOpenChange={setUserPopoverOpen}>
                        <PopoverTrigger asChild>
                            <Button
                                variant="outline"
                                role="combobox"
                                aria-expanded={userPopoverOpen}
                                className={cn('w-full justify-between font-normal', !form.user_ids.length && 'text-muted-foreground')}
                            >
                                <span className="truncate">
                                    {form.user_ids.length > 0 ? `${form.user_ids.length} user(s) selected` : 'Select users...'}
                                </span>
                                <ChevronsUpDown className="ml-2 h-4 w-4 shrink-0 opacity-50" />
                            </Button>
                        </PopoverTrigger>
                        <PopoverContent className="w-[320px] p-0">
                            <Command>
                                <CommandInput placeholder="Search users..." />
                                <CommandList>
                                    <CommandEmpty>No users found.</CommandEmpty>
                                    <CommandGroup>
                                        {users.map((user) => (
                                            <CommandItem
                                                key={user.id}
                                                value={`${user.name} ${user.email}`}
                                                onSelect={() => toggleUser(user.id)}
                                            >
                                                <Check
                                                    className={cn(
                                                        'mr-2 h-4 w-4',
                                                        form.user_ids.includes(user.id) ? 'opacity-100' : 'opacity-0',
                                                    )}
                                                />
                                                <div className="flex flex-col">
                                                    <span>{user.name}</span>
                                                    <span className="text-xs text-muted-foreground">{user.email}</span>
                                                </div>
                                            </CommandItem>
                                        ))}
                                    </CommandGroup>
                                </CommandList>
                            </Command>
                        </PopoverContent>
                    </Popover>
                    {form.user_ids.length > 0 && (
                        <div className="mt-2 flex flex-wrap gap-1">
                            {form.user_ids.map((userId) => {
                                const user = users.find((u) => u.id === userId);
                                return user ? (
                                    <Badge key={userId} variant="secondary" className="flex items-center gap-1">
                                        {user.name}
                                        <button
                                            type="button"
                                            onClick={() => removeUser(userId)}
                                            className="ml-1 rounded-full hover:bg-muted"
                                        >
                                            <X className="h-3 w-3" />
                                        </button>
                                    </Badge>
                                ) : null;
                            })}
                        </div>
                    )}
                    {formErrors.user_ids && <p className="text-sm text-red-500">{formErrors.user_ids}</p>}
                </div>
                <div>
                    <Label htmlFor="description">Description</Label>
                    <Textarea id="description" name="description" value={form.description} onChange={handleChange} />
                    {formErrors.description && <p className="text-sm text-red-500">{formErrors.description}</p>}
                </div>
            </BaseDialog>
        </AppLayout>
    );
}
