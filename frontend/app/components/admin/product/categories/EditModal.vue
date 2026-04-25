<script setup lang="ts">
import * as z from 'zod'
import type { FormSubmitEvent } from '@nuxt/ui'

interface Category {
  id: number
  name: string
  parent_category_id: number | null
  children: Category[]
}

const props = defineProps<{ category: Category; open?: boolean }>()
const emit = defineEmits<{ updated: []; 'update:open': [boolean] }>()

const schema = z.object({
  name: z.string().min(2, 'Category name must be at least 2 characters')
})

type Schema = z.output<typeof schema>

const internalOpen = ref(false)
const isOpen = computed({
  get: () => (props.open !== undefined ? props.open : internalOpen.value),
  set: (val) => {
    internalOpen.value = val
    emit('update:open', val)
  }
})

const toast = useToast()
const { apiCall, apiFetch } = useApiClient()
const state = reactive<Partial<Schema>>({ name: '' })
const selectedParentId = ref<number | null>(null)

const { data: treeData } = await apiFetch<{ data: Category[] }>('/product/categories/tree', { lazy: true })

const parentOptions = computed(() => {
  const result: Array<{ label: string; value: number }> = []
  function flatten(cats: Category[]) {
    for (const cat of cats) {
      if (cat.id !== props.category.id) {
        result.push({ label: cat.name, value: cat.id })
        if (cat.children?.length) flatten(cat.children)
      }
    }
  }
  flatten(treeData.value?.data ?? [])
  return result
})

watch(
  isOpen,
  (val) => {
    if (val) {
      Object.assign(state, { name: props.category.name })
      selectedParentId.value = props.category.parent_category_id
    }
  },
  { immediate: true }
)

async function onSubmit(event: FormSubmitEvent<Schema>) {
  try {
    await apiCall(`/product/categories/${props.category.id}`, {
      method: 'PUT',
      body: { name: event.data.name, parent_category_id: selectedParentId.value }
    })
    toast.add({ title: 'Category updated', description: `"${event.data.name}" has been updated.`, color: 'success' })
    isOpen.value = false
    emit('updated')
  } catch (error: any) {
    toast.add({
      title: 'Failed to update category',
      description: error?.data?.message || 'An error occurred.',
      color: 'error'
    })
  }
}
</script>

<template>
  <UModal v-model:open="isOpen" title="Edit Category" description="Update category details">
    <slot :open-modal="() => (isOpen = true)" />

    <template #body>
      <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
        <UFormField label="Category Name" name="name">
          <UInput v-model="state.name" class="w-full" />
        </UFormField>
        <UFormField label="Parent Category">
          <USelectMenu
            v-model="selectedParentId"
            :items="parentOptions"
            value-key="value"
            placeholder="No parent (root category)"
            class="w-full"
          />
        </UFormField>
        <div class="flex justify-end gap-2 pt-2">
          <UButton label="Cancel" color="neutral" variant="subtle" @click="isOpen = false" />
          <UButton label="Save" color="primary" variant="solid" type="submit" loading-auto />
        </div>
      </UForm>
    </template>
  </UModal>
</template>
