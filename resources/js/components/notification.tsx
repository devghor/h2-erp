import { NotificationCard } from '@/components/notification-card';
import { Button } from '@/components/ui/button';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Separator } from '@/components/ui/separator';
import type { SharedData } from '@/types';
import { Link, router, usePage } from '@inertiajs/react';
import axios from 'axios';
import { Bell, ChevronRight } from 'lucide-react';
import { useCallback, useEffect, useRef, useState } from 'react';

interface NotificationItem {
    id: string;
    title: string;
    message: string | null;
    type: string;
    is_read: boolean;
    time: string;
    created_at: string;
}

interface PaginatedResponse {
    data: NotificationItem[];
    current_page: number;
    last_page: number;
    total: number;
}

export function Notification() {
    const { auth } = usePage<SharedData>().props;
    const [unreadCount, setUnreadCount] = useState(auth.unread_notifications_count);
    const [open, setOpen] = useState(false);
    const [items, setItems] = useState<NotificationItem[]>([]);
    const [loading, setLoading] = useState(false);
    const initializedRef = useRef(false);

    useEffect(() => {
        setUnreadCount(auth.unread_notifications_count);
    }, [auth.unread_notifications_count]);

    const fetchPage = useCallback(async (pageNum: number) => {
        setLoading(true);
        try {
            const { data } = await axios.get<PaginatedResponse>(route('notification.dropdown'), {
                params: { page: pageNum },
            });
            setItems((prev) => (pageNum === 1 ? data.data : [...prev, ...data.data]));
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => {
        if (open && !initializedRef.current) {
            initializedRef.current = true;
            fetchPage(1);
        }
        if (!open) {
            initializedRef.current = false;
            setItems([]);
        }
    }, [open, fetchPage]);

    const handleMarkAsRead = (id: string) => {
        setItems((prev) => prev.map((n) => (n.id === id ? { ...n, is_read: true } : n)));
        setUnreadCount((c) => Math.max(0, c - 1));
        axios.patch(route('notification.read', id)).catch(() => {
            setItems((prev) => prev.map((n) => (n.id === id ? { ...n, is_read: false } : n)));
            setUnreadCount((c) => c + 1);
        });
    };

    const handleMarkAllAsRead = () => {
        const snap = { items, count: unreadCount };
        setItems((prev) => prev.map((n) => ({ ...n, is_read: true })));
        setUnreadCount(0);
        axios.post(route('notification.mark-all-read')).catch(() => {
            setItems(snap.items);
            setUnreadCount(snap.count);
        });
    };

    const handleAction = (id: string) => {
        setOpen(false);
        router.visit(route('notification.show', id));
    };

    const count = unreadCount;
    const MAX_VISIBLE = 5;
    const visibleItems = items.slice(0, MAX_VISIBLE);

    return (
        <Popover open={open} onOpenChange={setOpen}>
            <PopoverTrigger asChild>
                <Button variant="ghost" size="icon" className="relative size-8">
                    <Bell />
                    {count > 0 && (
                        <span className="text-destructive-foreground absolute -top-0.5 -right-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-destructive px-1 text-[10px] font-medium">
                            {count > 9 ? '9+' : count}
                        </span>
                    )}
                    <span className="sr-only">Notifications</span>
                </Button>
            </PopoverTrigger>
            <PopoverContent align="end" sideOffset={8} className="w-[calc(100vw-2rem)] p-0 sm:w-[380px]">
                <div className="flex items-center justify-between px-4 py-3">
                    <Link href={route('notification.index')} className="group flex items-center gap-1" onClick={() => setOpen(false)}>
                        <h4 className="text-sm font-semibold group-hover:underline">Notifications</h4>
                        <ChevronRight className="size-3.5 text-muted-foreground transition-transform group-hover:translate-x-0.5" />
                    </Link>
                    <div className="flex items-center gap-2">
                        {count > 0 && <span className="rounded-full bg-muted px-2 py-0.5 text-xs text-muted-foreground">{count} new</span>}
                        {count > 0 && (
                            <Button
                                variant="ghost"
                                size="sm"
                                className="h-auto px-2 py-1 text-xs text-muted-foreground"
                                onClick={handleMarkAllAsRead}
                            >
                                Mark all as read
                            </Button>
                        )}
                    </div>
                </div>
                <Separator />
                <ScrollArea className="h-[400px]">
                    <div className="flex flex-col gap-1 p-2">
                        {loading && items.length === 0 ? (
                            <div className="flex flex-col items-center justify-center py-12">
                                <span className="text-sm text-muted-foreground">Loading…</span>
                            </div>
                        ) : items.length === 0 ? (
                            <div className="flex flex-col items-center justify-center py-12">
                                <Bell className="mb-2 size-8 text-muted-foreground/40" />
                                <p className="text-sm text-muted-foreground">No notifications yet</p>
                            </div>
                        ) : (
                            visibleItems.map((n) => (
                                <NotificationCard
                                    key={n.id}
                                    id={n.id}
                                    title={n.title}
                                    body={n.message ?? ''}
                                    status={n.is_read ? 'read' : 'unread'}
                                    createdAt={n.created_at}
                                    actions={[{ id: 'view', label: 'View', type: 'redirect' }]}
                                    onMarkAsRead={handleMarkAsRead}
                                    onAction={handleAction}
                                />
                            ))
                        )}
                    </div>
                </ScrollArea>
            </PopoverContent>
        </Popover>
    );
}
