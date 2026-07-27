import { Breadcrumbs } from '@/components/breadcrumbs';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { useSearch } from '@/context/search-provider';
import { cn } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import { Search as SearchIcon } from 'lucide-react';
import { useEffect, useState } from 'react';
import { Search } from './search';

type HeaderProps = React.HTMLAttributes<HTMLElement> & {
    breadcrumbs?: BreadcrumbItem[];
    fixed?: boolean;
    ref?: React.Ref<HTMLElement>;
};

export function AppSidebarHeader({ className, fixed, breadcrumbs = [], children, ...props }: HeaderProps) {
    const { setOpen } = useSearch();
    const [offset, setOffset] = useState(0);

    useEffect(() => {
        const onScroll = () => {
            setOffset(document.body.scrollTop || document.documentElement.scrollTop);
        };

        document.addEventListener('scroll', onScroll, { passive: true });
        return () => document.removeEventListener('scroll', onScroll);
    }, []);

    return (
        <header
            className={cn(
                'flex h-14 shrink-0 items-center justify-between gap-2 border-b bg-background/60 px-4 backdrop-blur-md',
                fixed && 'header-fixed peer/header sticky top-0 z-20',
                offset > 10 && fixed ? 'shadow' : 'shadow-none',
                className,
            )}
            {...props}
        >
            <div className="flex items-center gap-2">
                <SidebarTrigger variant="outline" className="max-md:scale-125" />
                <Separator orientation="vertical" className="h-6" />
                <Breadcrumbs breadcrumbs={breadcrumbs} />
            </div>

            <div className="flex items-center gap-2">
                <div className="hidden w-40 md:block lg:w-64">
                    <Search />
                </div>
                <Button variant="ghost" size="icon" className="md:hidden" onClick={() => setOpen(true)}>
                    <SearchIcon />
                </Button>
                {children}
            </div>
        </header>
    );
}
