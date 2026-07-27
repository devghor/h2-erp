import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { cn } from '@/lib/utils';
import { type LucideIcon } from 'lucide-react';
import { Sparkline } from './sparkline';

interface StatCardProps {
    title: string;
    value: number | string;
    icon: LucideIcon;
    description?: string;
    trend?: 'up' | 'down' | 'neutral';
    trendLabel?: string;
    accent?: 'default' | 'blue' | 'amber' | 'emerald' | 'violet' | 'rose';
    sparklineData?: number[];
    className?: string;
}

const accentStyles = {
    default: 'bg-primary/10 text-primary dark:bg-primary/15',
    blue: 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400',
    amber: 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400',
    emerald: 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400',
    violet: 'bg-violet-50 text-violet-600 dark:bg-violet-950/40 dark:text-violet-400',
    rose: 'bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400',
};

const strokeStyles = {
    default: 'stroke-primary',
    blue: 'stroke-blue-500 dark:stroke-blue-400',
    amber: 'stroke-amber-500 dark:stroke-amber-400',
    emerald: 'stroke-emerald-500 dark:stroke-emerald-400',
    violet: 'stroke-violet-500 dark:stroke-violet-400',
    rose: 'stroke-rose-500 dark:stroke-rose-400',
};

const fillStyles = {
    default: 'fill-primary/10',
    blue: 'fill-blue-500/10 dark:fill-blue-400/10',
    amber: 'fill-amber-500/10 dark:fill-amber-400/10',
    emerald: 'fill-emerald-500/10 dark:fill-emerald-400/10',
    violet: 'fill-violet-500/10 dark:fill-violet-400/10',
    rose: 'fill-rose-500/10 dark:fill-rose-400/10',
};

function formatValue(value: number | string): string {
    if (typeof value === 'number') {
        return value.toLocaleString();
    }

    return value;
}

export function StatCard({ title, value, icon: Icon, description, trend, trendLabel, accent = 'default', sparklineData, className }: StatCardProps) {
    const hasSparkline = sparklineData && sparklineData.length > 0;

    return (
        <Card
            className={cn(
                'group relative overflow-hidden border-border/50 bg-gradient-to-br from-card to-card/80 transition-all hover:border-border/80 hover:shadow-md',
                className,
            )}
        >
            <CardHeader className="flex flex-row items-start justify-between gap-3 pb-3">
                <div className="flex flex-col gap-1">
                    <span className="text-xs font-medium tracking-wider text-muted-foreground uppercase">{title}</span>
                    <div className="text-2xl font-bold tracking-tight sm:text-3xl">{formatValue(value)}</div>
                </div>
                <div
                    className={cn(
                        'flex size-9 shrink-0 items-center justify-center rounded-lg shadow-sm transition-transform group-hover:scale-105',
                        accentStyles[accent],
                    )}
                >
                    <Icon className="size-4" />
                </div>
            </CardHeader>
            <CardContent className="pt-0">
                <div className="flex items-center justify-between gap-4">
                    <div className="flex min-w-0 flex-col">
                        {description ? <p className="text-xs text-muted-foreground">{description}</p> : <div className="h-4" />}
                        {trend && (
                            <div className="mt-2 flex items-center gap-2">
                                <Badge
                                    variant={trend === 'up' ? 'default' : trend === 'down' ? 'destructive' : 'secondary'}
                                    className="h-5 px-1.5 text-[10px] font-medium"
                                >
                                    {trend === 'up' ? '↑' : trend === 'down' ? '↓' : '—'} {trendLabel}
                                </Badge>
                            </div>
                        )}
                    </div>
                    {hasSparkline && (
                        <div className="h-10 w-24 shrink-0 sm:w-28">
                            <Sparkline data={sparklineData} strokeClassName={strokeStyles[accent]} fillClassName={fillStyles[accent]} />
                        </div>
                    )}
                </div>
            </CardContent>
        </Card>
    );
}
