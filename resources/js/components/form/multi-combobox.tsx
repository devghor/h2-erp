import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { cn } from '@/lib/utils';
import { Check, ChevronsUpDown, X } from 'lucide-react';
import { useState } from 'react';

type Option = { id: number; label: string };

interface MultiComboboxProps {
    options: Option[];
    value: number[];
    onChange: (ids: number[]) => void;
    placeholder?: string;
    searchPlaceholder?: string;
}

export function MultiCombobox({ options, value, onChange, placeholder = 'Select...', searchPlaceholder = 'Search...' }: MultiComboboxProps) {
    const [open, setOpen] = useState(false);

    const toggleOption = (id: number) => {
        onChange(value.includes(id) ? value.filter((v) => v !== id) : [...value, id]);
    };

    const removeOption = (id: number) => {
        onChange(value.filter((v) => v !== id));
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
                        <span className="truncate">{value.length > 0 ? `${value.length} selected` : placeholder}</span>
                        <ChevronsUpDown className="ml-2 h-4 w-4 shrink-0 opacity-50" />
                    </Button>
                </PopoverTrigger>
                <PopoverContent className="w-[320px] p-0">
                    <Command>
                        <CommandInput placeholder={searchPlaceholder} />
                        <CommandList>
                            <CommandEmpty>No options found.</CommandEmpty>
                            <CommandGroup>
                                {options.map((option) => (
                                    <CommandItem key={option.id} value={option.label} onSelect={() => toggleOption(option.id)}>
                                        <Check className={cn('mr-2 h-4 w-4', value.includes(option.id) ? 'opacity-100' : 'opacity-0')} />
                                        <span>{option.label}</span>
                                    </CommandItem>
                                ))}
                            </CommandGroup>
                        </CommandList>
                    </Command>
                </PopoverContent>
            </Popover>
            {value.length > 0 && (
                <div className="mt-2 flex flex-wrap gap-1">
                    {value.map((id) => {
                        const option = options.find((o) => o.id === id);
                        return option ? (
                            <Badge key={id} variant="secondary" className="flex items-center gap-1">
                                {option.label}
                                <button type="button" onClick={() => removeOption(id)} className="ml-1 rounded-full hover:bg-muted">
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
