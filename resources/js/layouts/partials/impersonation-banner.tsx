import { type SharedData } from '@/types';
import { router, usePage } from '@inertiajs/react';
import { UserRoundX } from 'lucide-react';

export function ImpersonationBanner() {
    const { auth } = usePage<SharedData>().props;

    const handleLeave = () => {
        router.post(route('uam.impersonate.leave'));
    };

    return (
        <button
            onClick={handleLeave}
            className="flex items-center gap-1.5 rounded-md bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700 transition-colors hover:bg-amber-200 dark:bg-amber-900/40 dark:text-amber-300 dark:hover:bg-amber-900/60"
        >
            <UserRoundX className="h-3.5 w-3.5" />
            <span>Impersonating {auth.user.name}</span>
            <span className="ml-0.5 opacity-70">— Leave</span>
        </button>
    );
}
