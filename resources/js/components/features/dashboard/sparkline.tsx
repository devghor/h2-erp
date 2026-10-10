import { cn } from '@/lib/utils';

interface SparklineProps {
    data: number[];
    className?: string;
    strokeClassName?: string;
    fillClassName?: string;
    strokeWidth?: number;
    height?: number;
    width?: number;
}

export function Sparkline({
    data,
    className,
    strokeClassName = 'stroke-primary',
    fillClassName = 'fill-primary/10',
    strokeWidth = 2,
    height = 48,
    width = 160,
}: SparklineProps) {
    if (data.length === 0) {
        return null;
    }

    const min = Math.min(...data);
    const max = Math.max(...data);
    const range = max - min || 1;
    const padding = strokeWidth;
    const innerWidth = width - padding * 2;
    const innerHeight = height - padding * 2;

    const points = data.map((value, index) => {
        const x = padding + (index / (data.length - 1 || 1)) * innerWidth;
        const y = padding + innerHeight - ((value - min) / range) * innerHeight;
        return `${x},${y}`;
    });

    const pathD = `M ${points.join(' L ')}`;
    const areaD = `${pathD} L ${width - padding},${height - padding} L ${padding},${height - padding} Z`;

    return (
        <svg
            viewBox={`0 0 ${width} ${height}`}
            className={cn('h-full w-full overflow-visible', className)}
            preserveAspectRatio="none"
            aria-hidden="true"
        >
            <path d={areaD} className={cn('transition-all', fillClassName)} />
            <path
                d={pathD}
                className={cn('fill-none transition-all', strokeClassName)}
                strokeWidth={strokeWidth}
                strokeLinecap="round"
                strokeLinejoin="round"
            />
        </svg>
    );
}
