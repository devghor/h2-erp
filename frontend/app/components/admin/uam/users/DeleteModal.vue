<script setup lang="ts">
import type { UamUser } from '~/types'

const props = defineProps<{ user?: UamUser; count?: number; ulids?: string[]; open?: boolean }>()
const emit = defineEmits<{ deleted: []; 'update:open': [boolean] }>()

const internalOpen = ref(false)
const isOpen = computed({
  get: () => props.open !== undefined ? props.open : internalOpen.value,
  set: (val) => { internalOpen.value = val; emit('update:open', val) }
})

const toast = useToast()
const { apiCall } = useApiClient()
const isBulk = computed(() => !!props.ulids?.length)
const title = computed(() =>
  isBulk.value
    ? `Delete ${props.count} user${(props.count || 0) > 1 ? 's' : ''}`
    : `Delete ${props.user?.name}`
)

async function onConfirm() {
  try {
    if (isBulk.value) {
      await apiCall('/uam/users/bulk-delete', { method: 'POST', body: { ulids: props.ulids } })
      toast.add({ title: 'Users deleted', description: `${props.count} user(s) have been deleted.`, color: 'success' })
    } else if (props.user) {
      await apiCall(`/uam/users/${props.user.ulid}`, { method: 'DELETE' })
      toast.add({ title: 'User deleted', description: `${props.user.name} has been deleted.`, color: 'success' })
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
