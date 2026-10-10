import { Separator } from '@/components/ui/separator';
import { cn } from '@/lib/utils';
import { type LucideIcon } from 'lucide-react';

interface SectionHeaderProps {
    title: string;
    description?: string;
    icon?: LucideIcon;
    className?: string;
}

export function SectionHeader({ title, description, icon: Icon, className }: SectionHeaderProps) {
    return (
        <div className={cn('flex flex-col gap-1', className)}>
            <div className="flex items-center gap-2">
                {Icon && <Icon className="size-4 text-muted-foreground" />}
                <h2 className="text-sm font-semibold tracking-tight text-foreground">{title}</h2>
            </div>
            {description ? <p className="text-xs text-muted-foreground">{description}</p> : <Separator className="mt-1" />}
        </div>
    );
}
