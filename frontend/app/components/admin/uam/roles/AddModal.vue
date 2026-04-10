<script setup lang="ts">
import * as z from 'zod'
import type { FormSubmitEvent } from '@nuxt/ui'

const emit = defineEmits<{ created: [] }>()

const schema = z.object({
  name: z.string().min(2, 'Role name must be at least 2 characters'),
  description: z.string().optional()
})

type Schema = z.output<typeof schema>

const open = ref(false)
const toast = useToast()
const { apiCall } = useApiClient()

const state = reactive<Partial<Schema>>({ name: '', description: '' })

async function onSubmit(event: FormSubmitEvent<Schema>) {
  try {
    await apiCall('/uam/roles', { method: 'POST', body: event.data })
    toast.add({ title: 'Role created', description: `Role "${event.data.name}" has been created.`, color: 'success' })
    open.value = false
    Object.assign(state, { name: '', description: '' })
    emit('created')
  } catch (error: any) {
    toast.add({ title: 'Failed to create role', description: error?.data?.message || 'An error occurred.', color: 'error' })
  }
}
</script>

<template>
  <UModal v-model:open="open" title="New Role" description="Create a new role">
    <UButton label="New Role" icon="i-lucide-plus" />

    <template #body>
      <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
        <UFormField label="Role Name" name="name">
          <UInput v-model="state.name" placeholder="e.g. Manager" class="w-full" />
        </UFormField>
        <UFormField label="Description" name="description">
          <UTextarea v-model="state.description" placeholder="Optional description" class="w-full" />
        </UFormField>

        <div class="flex justify-end gap-2 pt-2">
          <UButton label="Cancel" color="neutral" variant="subtle" @click="open = false" />
          <UButton label="Create" color="primary" variant="solid" type="submit" loading-auto />
        </div>
      </UForm>
    </template>
  </UModal>
</template>
