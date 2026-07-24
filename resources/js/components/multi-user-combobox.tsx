import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { cn } from '@/lib/utils';
import { Check, ChevronsUpDown, X } from 'lucide-react';
import { useState } from 'react';

type User = { id: number; name: string; username?: string; email: string };

interface MultiUserComboboxProps {
    users: User[];
    value: number[];
    onChange: (userIds: number[]) => void;
    placeholder?: string;
}

export function MultiUserCombobox({ users, value, onChange, placeholder = 'Select users...' }: MultiUserComboboxProps) {
    const [open, setOpen] = useState(false);

    const toggleUser = (userId: number) => {
        onChange(value.includes(userId) ? value.filter((id) => id !== userId) : [...value, userId]);
    };

    const removeUser = (userId: number) => {
        onChange(value.filter((id) => id !== userId));
    };

    return (
        <>
            <Popover open={open} onOpenChange={setOpen}>
                <PopoverTrigger asChild>
                    <Button
                        type="button"
                        variant="outline"
                        role="combobox"
                        aria-expanded={open}
                        className={cn('w-full justify-between font-normal', !value.length && 'text-muted-foreground')}
                    >
                        <span className="truncate">{value.length > 0 ? `${value.length} user(s) selected` : placeholder}</span>
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
                                        value={`${user.name} ${user.username ?? ''} ${user.email}`}
                                        onSelect={() => toggleUser(user.id)}
                                    >
                                        <Check className={cn('mr-2 h-4 w-4', value.includes(user.id) ? 'opacity-100' : 'opacity-0')} />
                                        <span>
                                            {user.name}
                                            {user.username ? ` (${user.username})` : ''}
                                        </span>
                                    </CommandItem>
                                ))}
                            </CommandGroup>
                        </CommandList>
                    </Command>
                </PopoverContent>
            </Popover>
            {value.length > 0 && (
                <div className="mt-2 flex flex-wrap gap-1">
                    {value.map((userId) => {
                        const user = users.find((u) => u.id === userId);
                        return user ? (
                            <Badge key={userId} variant="secondary" className="flex items-center gap-1">
                                {user.name}
                                {user.username ? ` (${user.username})` : ''}
                                <button type="button" onClick={() => removeUser(userId)} className="ml-1 rounded-full hover:bg-muted">
                                    <X className="h-3 w-3" />
                                </button>
                            </Badge>
                        ) : null;
                    })}
                </div>
            )}
        </>
    );
}
