import { RowActions } from '@/components/data-table/row-actions';
import { BaseDialog } from '@/components/dialog/base-dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { breadcrumbItems } from '@/config/breadcrumbs';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/react';
import { ChevronDown, ChevronRight, Pencil, Plus, X } from 'lucide-react';
import { Fragment, useState } from 'react';

type PositionGroup = { id: number; name: string };
type ApprovalLevelOption = { id: number; name: string; type: string };
type TypeOption = { value: string; label: string };

interface ApprovalFlowGroupItem {
    id: number;
    approval_flow_group_id: number;
    approval_level_id: number;
    sequence: number;
    approval_level: { id: number; name: string; type: string; hrbp: { id: number; name: string } | null };
}

interface ApprovalFlowGroup {
    id: number;
    approval_flow_id: number;
    position_group_id: number;
    position_group: PositionGroup;
    items: ApprovalFlowGroupItem[];
}

interface ApprovalFlow {
    id: number;
    name: string;
    type: string;
    groups: ApprovalFlowGroup[];
}

interface Props {
    flow: ApprovalFlow;
    positionGroups: PositionGroup[];
    approvalLevels: ApprovalLevelOption[];
    typeOptions: TypeOption[];
}

type ItemRow = { approval_level_id: number | undefined; sequence: number };

export default function Show({ flow, positionGroups, approvalLevels, typeOptions }: Props) {
    const breadcrumbs: BreadcrumbItem[] = [breadcrumbItems.dashboard, breadcrumbItems.configurationApprovalFlows, { title: flow.name, href: '#' }];

    const [expandedGroupId, setExpandedGroupId] = useState<number | null>(null);

    // ── Flow name/type edit dialog ──────────────────────────────────────────
    const [flowOpen, setFlowOpen] = useState(false);
    const [flowForm, setFlowForm] = useState({ name: flow.name, type: flow.type });
    const [flowErrors, setFlowErrors] = useState<Record<string, string>>({});

    // ── Group add/edit dialog (position group + dynamic list of levels) ────
    const [groupOpen, setGroupOpen] = useState(false);
    const [groupEdit, setGroupEdit] = useState(false);
    const [groupForm, setGroupForm] = useState<{ id?: number; position_group_id: number | undefined; items: ItemRow[] }>({
        id: undefined,
        position_group_id: undefined,
        items: [{ approval_level_id: undefined, sequence: 1 }],
    });
    const [groupErrors, setGroupErrors] = useState<Record<string, string>>({});

    const toggleExpand = (groupId: number) => {
        setExpandedGroupId((prev) => (prev === groupId ? null : groupId));
    };

    // ── Flow handlers ────────────────────────────────────────────────────────
    const handleFlowEditOpen = () => {
        setFlowForm({ name: flow.name, type: flow.type });
        setFlowErrors({});
        setFlowOpen(true);
    };

    const handleFlowSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        router.put(route('configuration.approval-flows.update', flow.id), flowForm, {
            onSuccess: () => {
                setFlowOpen(false);
                router.reload({ only: ['flow'] });
            },
            onError: (errors) => setFlowErrors(errors),
        });
    };

    // ── Group handlers ──────────────────────────────────────────────────────
    const handleGroupAdd = () => {
        setGroupForm({ id: undefined, position_group_id: undefined, items: [{ approval_level_id: undefined, sequence: 1 }] });
        setGroupEdit(false);
        setGroupOpen(true);
        setGroupErrors({});
    };

    const handleGroupEdit = (group: ApprovalFlowGroup) => {
        setGroupForm({
            id: group.id,
            position_group_id: group.position_group_id,
            items: [...group.items]
                .sort((a, b) => a.sequence - b.sequence)
                .map((item) => ({ approval_level_id: item.approval_level_id, sequence: item.sequence })),
        });
        setGroupEdit(true);
        setGroupOpen(true);
        setGroupErrors({});
    };

    const addItemRow = () => {
        setGroupForm((prev) => ({
            ...prev,
            items: [...prev.items, { approval_level_id: undefined, sequence: prev.items.length + 1 }],
        }));
    };

    const removeItemRow = (index: number) => {
        setGroupForm((prev) => ({ ...prev, items: prev.items.filter((_, i) => i !== index) }));
    };

    const updateItemRow = (index: number, changes: Partial<ItemRow>) => {
        setGroupForm((prev) => ({
            ...prev,
            items: prev.items.map((item, i) => (i === index ? { ...item, ...changes } : item)),
        }));
    };

    const handleGroupSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const data = { position_group_id: groupForm.position_group_id, items: groupForm.items };
        const opts = {
            onSuccess: () => {
                setGroupOpen(false);
                router.reload({ only: ['flow'] });
            },
            onError: (errors: any) => setGroupErrors(errors),
        };
        if (groupEdit && groupForm.id) {
            router.put(route('configuration.approval-flow-groups.update', groupForm.id), data, opts);
        } else {
            router.post(route('configuration.approval-flow-groups.store'), { approval_flow_id: flow.id, ...data }, opts);
        }
    };

    const handleGroupDelete = (id: number) => {
        router.delete(route('configuration.approval-flow-groups.destroy', id), {
            onSuccess: () => router.reload({ only: ['flow'] }),
        });
    };

    return (
        <AppLayout title={flow.name} breadcrumbs={breadcrumbs}>
            <div className="mb-6 rounded-lg border bg-card p-6">
                <div className="mb-4 flex items-start justify-between">
                    <div className="grid grid-cols-2 gap-x-8 gap-y-3 md:grid-cols-3">
                        <div>
                            <p className="text-muted-foreground text-xs font-medium uppercase tracking-wide">Name</p>
                            <p className="mt-1 font-semibold">{flow.name}</p>
                        </div>
                        <div>
                            <p className="text-muted-foreground text-xs font-medium uppercase tracking-wide">Type</p>
                            <p className="mt-1">{flow.type}</p>
                        </div>
                    </div>
                    <Button variant="outline" size="sm" onClick={handleFlowEditOpen}>
                        <Pencil className="mr-2 h-3.5 w-3.5" />
                        Edit
                    </Button>
                </div>
            </div>

            <div className="mb-3 flex items-center justify-between">
                <h2 className="text-lg font-semibold">Approval Flow Groups</h2>
                <Button size="sm" onClick={handleGroupAdd}>
                    Add Group
                </Button>
            </div>

            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead className="w-[40px]" />
                        <TableHead>Position Group</TableHead>
                        <TableHead>Steps</TableHead>
                        <TableHead className="w-[60px] text-center">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {flow.groups.length === 0 && (
                        <TableRow>
                            <TableCell colSpan={4} className="text-center text-muted-foreground">
                                No groups added yet.
                            </TableCell>
                        </TableRow>
                    )}
                    {flow.groups.map((group) => (
                        <Fragment key={group.id}>
                            <TableRow>
                                <TableCell>
                                    <Button variant="ghost" size="icon" className="h-7 w-7" onClick={() => toggleExpand(group.id)}>
                                        {expandedGroupId === group.id ? <ChevronDown className="h-4 w-4" /> : <ChevronRight className="h-4 w-4" />}
                                    </Button>
                                </TableCell>
                                <TableCell>{group.position_group.name}</TableCell>
                                <TableCell>{group.items.length} step(s)</TableCell>
                                <TableCell className="text-center">
                                    <RowActions onEdit={() => handleGroupEdit(group)} onDelete={() => handleGroupDelete(group.id)} />
                                </TableCell>
                            </TableRow>
                            {expandedGroupId === group.id && (
                                <TableRow>
                                    <TableCell colSpan={4} className="bg-muted/30 p-4">
                                        <span className="mb-2 block text-sm font-medium">Approval Steps</span>
                                        <Table>
                                            <TableHeader>
                                                <TableRow>
                                                    <TableHead>Sequence</TableHead>
                                                    <TableHead>Approval Level</TableHead>
                                                    <TableHead>Type</TableHead>
                                                </TableRow>
                                            </TableHeader>
                                            <TableBody>
                                                {[...group.items]
                                                    .sort((a, b) => a.sequence - b.sequence)
                                                    .map((item) => (
                                                        <TableRow key={item.id}>
                                                            <TableCell>{item.sequence}</TableCell>
                                                            <TableCell>{item.approval_level.name}</TableCell>
                                                            <TableCell>{item.approval_level.type}</TableCell>
                                                        </TableRow>
                                                    ))}
                                            </TableBody>
                                        </Table>
                                    </TableCell>
                                </TableRow>
                            )}
                        </Fragment>
                    ))}
                </TableBody>
            </Table>

            {/* Flow name/type edit dialog */}
            <BaseDialog
                open={flowOpen}
                onOpenChange={setFlowOpen}
                title="Edit Approval Flow"
                onSubmit={handleFlowSubmit}
                onCancel={() => setFlowOpen(false)}
                submitLabel="Update"
            >
                <div>
                    <Label htmlFor="name">Name</Label>
                    <Input
                        name="name"
                        value={flowForm.name}
                        onChange={(e) => setFlowForm((prev) => ({ ...prev, name: e.target.value }))}
                        required
                    />
                    {flowErrors.name && <p className="text-sm text-red-500">{flowErrors.name}</p>}
                </div>
                <div>
                    <Label htmlFor="type">Type</Label>
                    <Select value={flowForm.type} onValueChange={(value) => setFlowForm((prev) => ({ ...prev, type: value }))}>
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
                    {flowErrors.type && <p className="text-sm text-red-500">{flowErrors.type}</p>}
                </div>
            </BaseDialog>

            {/* Group add/edit dialog: position group + dynamic list of approval levels */}
            <BaseDialog
                open={groupOpen}
                onOpenChange={setGroupOpen}
                title={groupEdit ? 'Edit Approval Flow Group' : 'Add Approval Flow Group'}
                onSubmit={handleGroupSubmit}
                onCancel={() => setGroupOpen(false)}
                submitLabel={groupEdit ? 'Update' : 'Create'}
                className="sm:max-w-lg"
            >
                <div>
                    <Label htmlFor="position_group_id">Position Group</Label>
                    <Select
                        value={groupForm.position_group_id ? String(groupForm.position_group_id) : ''}
                        onValueChange={(value) => setGroupForm((prev) => ({ ...prev, position_group_id: Number(value) }))}
                    >
                        <SelectTrigger>
                            <SelectValue placeholder="Select a position group" />
                        </SelectTrigger>
                        <SelectContent>
                            {positionGroups.map((pg) => (
                                <SelectItem key={pg.id} value={String(pg.id)}>
                                    {pg.name}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    {groupErrors.position_group_id && <p className="text-sm text-red-500">{groupErrors.position_group_id}</p>}
                </div>

                <div>
                    <div className="mb-2 flex items-center justify-between">
                        <Label>Approval Levels</Label>
                        <Button type="button" size="sm" variant="outline" onClick={addItemRow}>
                            <Plus className="mr-1 h-3.5 w-3.5" />
                            Add Level
                        </Button>
                    </div>
                    <div className="space-y-2">
                        {groupForm.items.map((item, index) => (
                            <div key={index} className="flex items-start gap-2">
                                <div className="w-20">
                                    <Input
                                        type="number"
                                        min={1}
                                        value={item.sequence}
                                        onChange={(e) => updateItemRow(index, { sequence: Number(e.target.value) })}
                                        placeholder="Seq"
                                    />
                                </div>
                                <div className="flex-1">
                                    <Select
                                        value={item.approval_level_id ? String(item.approval_level_id) : ''}
                                        onValueChange={(value) => updateItemRow(index, { approval_level_id: Number(value) })}
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder="Select an approval level" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {approvalLevels.map((level) => (
                                                <SelectItem key={level.id} value={String(level.id)}>
                                                    {level.name} ({level.type})
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                </div>
                                <Button
                                    type="button"
                                    variant="ghost"
                                    size="icon"
                                    className="h-9 w-9 shrink-0"
                                    onClick={() => removeItemRow(index)}
                                >
                                    <X className="h-4 w-4" />
                                </Button>
                            </div>
                        ))}
                    </div>
                    {groupErrors.items && <p className="text-sm text-red-500">{groupErrors.items}</p>}
                </div>
            </BaseDialog>
        </AppLayout>
    );
}
