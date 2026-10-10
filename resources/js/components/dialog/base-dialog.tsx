import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { router } from '@inertiajs/react';
import { Loader2 } from 'lucide-react';
import React, { useEffect, useState } from 'react';

type BaseDialogProps = {
    open: boolean;
    onOpenChange: (open: boolean) => void;
    title: string;
    description?: string;
    onSubmit: (e: React.FormEvent) => void;
    onCancel?: () => void;
    submitLabel?: string;
    cancelLabel?: string;
    children: React.ReactNode;
    className?: string;
};

export function BaseDialog({
    open,
    onOpenChange,
    title,
    description,
    onSubmit,
    onCancel,
    submitLabel = 'Submit',
    cancelLabel = 'Cancel',
    children,
    className,
}: BaseDialogProps) {
    const [processing, setProcessing] = useState(false);

    useEffect(() => {
        if (!open) return;
        const offStart = router.on('start', () => setProcessing(true));
        const offFinish = router.on('finish', () => setProcessing(false));
        return () => {
            offStart();
            offFinish();
            setProcessing(false);
        };
    }, [open]);

    const handleCancel = () => {
        onCancel?.();
        onOpenChange(false);
    };

    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent className={className}>
                <DialogHeader>
                    <DialogTitle className="text-xl font-bold">{title}</DialogTitle>
                    {description && <DialogDescription>{description}</DialogDescription>}
                </DialogHeader>
                <form onSubmit={onSubmit} className="space-y-4">
                    {children}
                    <DialogFooter>
                        <Button type="button" variant="outline" onClick={handleCancel} disabled={processing}>
                            {cancelLabel}
                        </Button>
                        <Button type="submit" disabled={processing}>
                            {processing && <Loader2 className="animate-spin" />}
                            {submitLabel}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
