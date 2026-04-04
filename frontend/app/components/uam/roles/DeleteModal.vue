<script setup lang="ts">
import type { UamRole } from '~/types'

const props = defineProps<{ role?: UamRole; count?: number; ids?: number[]; open?: boolean }>()
const emit = defineEmits<{ deleted: []; 'update:open': [boolean] }>()

const internalOpen = ref(false)
const isOpen = computed({
  get: () => props.open !== undefined ? props.open : internalOpen.value,
  set: (val) => { internalOpen.value = val; emit('update:open', val) }
})

const toast = useToast()
const { apiCall } = useApiClient()
const isBulk = computed(() => !!props.ids?.length)
const title = computed(() =>
  isBulk.value
    ? `Delete ${props.count} role${(props.count || 0) > 1 ? 's' : ''}`
    : `Delete "${props.role?.name}"`
)

async function onConfirm() {
  try {
    if (isBulk.value) {
      await apiCall('/uam/roles/bulk-delete', { method: 'POST', body: { ids: props.ids } })
      toast.add({ title: 'Roles deleted', description: `${props.count} role(s) have been deleted.`, color: 'success' })
    } else if (props.role) {
      await apiCall(`/uam/roles/${props.role.id}`, { method: 'DELETE' })
      toast.add({ title: 'Role deleted', description: `"${props.role.name}" has been deleted.`, color: 'success' })
    }
    isOpen.value = false
    emit('deleted')
  } catch (error: any) {
    toast.add({ title: 'Failed to delete', description: error?.data?.message || 'An error occurred.', color: 'error' })
  }
}
</script>

<template>
  <UModal v-model:open="isOpen" :title="title" description="This action cannot be undone.">
    <slot :open-modal="() => (isOpen = true)" />

    <template #body>
      <div class="flex justify-end gap-2">
        <UButton label="Cancel" color="neutral" variant="subtle" @click="isOpen = false" />
        <UButton label="Delete" color="error" variant="solid" loading-auto @click="onConfirm" />
      </div>
    </template>
  </UModal>
</template>
