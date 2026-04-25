<script setup lang="ts">
interface Category {
  id: number
  name: string
}

const props = defineProps<{ category?: Category; count?: number; ids?: number[]; open?: boolean }>()
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
    ? `Delete ${props.count} categor${(props.count || 0) > 1 ? 'ies' : 'y'}`
    : `Delete "${props.category?.name}"`
)

async function onConfirm() {
  try {
    if (isBulk.value) {
      await apiCall('/product/categories/bulk-delete', { method: 'POST', body: { ids: props.ids } })
      toast.add({ title: 'Categories deleted', description: `${props.count} categor${(props.count || 0) > 1 ? 'ies' : 'y'} deleted.`, color: 'success' })
    } else if (props.category) {
      await apiCall(`/product/categories/${props.category.id}`, { method: 'DELETE' })
      toast.add({ title: 'Category deleted', description: `"${props.category.name}" deleted.`, color: 'success' })
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
