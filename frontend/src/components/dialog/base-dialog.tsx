'use client'

import { cn } from '@/lib/utils'
import { Button } from '@/components/ui/button'
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'

type BaseDialogProps = {
  open: boolean
  onOpenChange: (open: boolean) => void
  title: React.ReactNode
  description?: React.ReactNode
  /** Provide for form dialogs — wires the submit button to the form via id */
  formId?: string
  /** Provide for non-form dialogs — called when the confirm button is clicked */
  onConfirm?: () => void
  isSubmitting?: boolean
  submitText?: string
  submittingText?: string
  cancelText?: string
  destructive?: boolean
  disabled?: boolean
  className?: string
  children?: React.ReactNode
}

export function BaseDialog({
  open,
  onOpenChange,
  title,
  description,
  formId,
  onConfirm,
  isSubmitting = false,
  submitText = 'Save changes',
  submittingText = 'Saving...',
  cancelText,
  destructive = false,
  disabled = false,
  className,
  children,
}: BaseDialogProps) {
  return (
    <Dialog
      open={open}
      onOpenChange={(state) => {
        if (!isSubmitting) {
          onOpenChange(state)
        }
      }}
    >
      <DialogContent className={cn('sm:max-w-lg', className)}>
        <DialogHeader className='text-start'>
          <DialogTitle>{title}</DialogTitle>
          {description && (
            <DialogDescription asChild>
              <div>{description}</div>
            </DialogDescription>
          )}
        </DialogHeader>
        {children && (
          <div className='w-[calc(100%+0.75rem)] overflow-y-auto py-1 pe-3'>
            {children}
          </div>
        )}
        <DialogFooter>
          {cancelText && (
            <DialogClose asChild>
              <Button variant='outline' disabled={isSubmitting}>
                {cancelText}
              </Button>
            </DialogClose>
          )}
          <Button
            type={formId ? 'submit' : 'button'}
            form={formId}
            variant={destructive ? 'destructive' : 'default'}
            disabled={disabled || isSubmitting}
            onClick={onConfirm}
          >
            {isSubmitting ? submittingText : submitText}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  )
}
