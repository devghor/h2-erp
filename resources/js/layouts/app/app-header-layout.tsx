import { AppContent } from '@/layouts/partials/app-content';
import { AppHeader } from '@/layouts/partials/app-header';
import { AppShell } from '@/layouts/partials/app-shell';
import { type BreadcrumbItem } from '@/types';
import type { PropsWithChildren } from 'react';

export default function AppHeaderLayout({ children, breadcrumbs }: PropsWithChildren<{ breadcrumbs?: BreadcrumbItem[] }>) {
    return (
        <AppShell>
            <AppHeader breadcrumbs={breadcrumbs} />
            <AppContent title="">{children}</AppContent>
        </AppShell>
    );
}
