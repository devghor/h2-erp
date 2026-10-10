import { AppContent } from '@/layouts/partials/app-content';
import { AppShell } from '@/layouts/partials/app-shell';
import { AppSidebar } from '@/layouts/partials/app-sidebar';
import { AppSidebarHeader } from '@/layouts/partials/app-sidebar-header';
import { ImpersonationBanner } from '@/layouts/partials/impersonation-banner';
import { NavUser } from '@/layouts/partials/nav-user';
import { Notification } from '@/components/features/notification/notification';
import { SearchProvider } from '@/context/search-provider';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { type PropsWithChildren } from 'react';

export default function AppSidebarLayout({
    children,
    breadcrumbs = [],
    title,
    description,
    actions,
}: PropsWithChildren<{ breadcrumbs?: BreadcrumbItem[]; title: string; description?: string; actions?: React.ReactNode }>) {
    const { auth } = usePage<SharedData>().props;

    return (
        <AppShell variant="sidebar">
            <Head title={title} />
            <AppSidebar />
            <AppContent title={title} variant="sidebar">
                <SearchProvider>
                    <AppSidebarHeader fixed breadcrumbs={breadcrumbs}>
                        {auth.impersonating && <ImpersonationBanner />}
                        <Notification />
                        <NavUser />
                    </AppSidebarHeader>
                </SearchProvider>
                <div className="container mx-auto py-4">
                    <div className="mb-2 flex flex-wrap items-center justify-between gap-x-4 gap-y-2">
                        <div>
                            <h2 className="text-2xl font-bold tracking-tight">{title}</h2>
                            <p className="text-muted-foreground">{description}</p>
                        </div>
                        <div>{actions}</div>
                    </div>
                    <div className="mt-4">{children}</div>
                </div>
            </AppContent>
        </AppShell>
    );
}
