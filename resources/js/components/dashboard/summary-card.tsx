import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { type LucideIcon } from 'lucide-react';

interface SummaryCardProps {
    title: string;
    value: number | string;
    icon: LucideIcon;
    description?: string;
}

function formatValue(value: number | string): string {
    if (typeof value === 'number') {
        return value.toLocaleString();
    }

    return value;
}

export function SummaryCard({ title, value, icon: Icon, description }: SummaryCardProps) {
    return (
        <Card className="group relative overflow-hidden border-border/50 bg-gradient-to-br from-card to-card/80 transition-all hover:border-border/80 hover:shadow-md">
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <span className="text-xs font-medium tracking-wider text-muted-foreground uppercase">{title}</span>
                <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-primary/20 to-primary/10 text-primary shadow-sm">
                    <Icon className="h-4 w-4" />
                </div>
            </CardHeader>
            <CardContent>
                <div className="text-3xl font-bold tracking-tight">{formatValue(value)}</div>
                {description ? <p className="mt-1 text-xs text-muted-foreground">{description}</p> : <div className="mt-1 h-4" />}
            </CardContent>
        </Card>
    );
}
