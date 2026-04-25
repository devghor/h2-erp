<script setup lang="ts">
import * as z from 'zod'
import type { FormSubmitEvent } from '@nuxt/ui'

interface Category {
  id: number
  name: string
  parent_category_id: number | null
  children: Category[]
}

const emit = defineEmits<{ created: [] }>()

const schema = z.object({
  name: z.string().min(2, 'Category name must be at least 2 characters')
})

type Schema = z.output<typeof schema>

const open = ref(false)
const toast = useToast()
const { apiCall, apiFetch } = useApiClient()

const state = reactive<Partial<Schema>>({ name: '' })
const selectedParentId = ref<number | null>(null)

const { data: treeData } = await apiFetch<{ data: { data: Category[] } }>('/product/categories/tree', { lazy: true })

const parentOptions = computed(() => {
  const result: Array<{ label: string; value: number }> = []
  function flatten(cats: Category[]) {
    for (const cat of cats) {
      result.push({ label: cat.name, value: cat.id })
      if (cat.children?.length) flatten(cat.children)
    }
  }
  flatten(treeData.value?.data || [])
  return result
})

async function onSubmit(event: FormSubmitEvent<Schema>) {
  try {
    await apiCall('/product/categories', {
      method: 'POST',
      body: { name: event.data.name, parent_category_id: selectedParentId.value }
    })
    toast.add({ title: 'Category created', description: `"${event.data.name}" has been created.`, color: 'success' })
    open.value = false
    Object.assign(state, { name: '' })
    selectedParentId.value = null
    emit('created')
  } catch (error: any) {
    toast.add({
      title: 'Failed to create category',
      description: error?.data?.message || 'An error occurred.',
      color: 'error'
    })
  }
}
</script>

<template>
  <UModal v-model:open="open" title="New Category" description="Create a new product category">
    <UButton label="New Category" icon="i-lucide-plus" />

    <template #body>
      <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
        <UFormField label="Category Name" name="name">
          <UInput v-model="state.name" placeholder="e.g. Electronics" class="w-full" />
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
          <UButton label="Cancel" color="neutral" variant="subtle" @click="open = false" />
          <UButton label="Create" color="primary" variant="solid" type="submit" loading-auto />
        </div>
      </UForm>
    </template>
  </UModal>
</template>
