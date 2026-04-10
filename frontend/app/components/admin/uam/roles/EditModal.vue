<script setup lang="ts">
import * as z from 'zod'
import type { FormSubmitEvent } from '@nuxt/ui'
import type { UamRole } from '~/types'

const props = defineProps<{ role: UamRole; open?: boolean }>()
const emit = defineEmits<{ updated: []; 'update:open': [boolean] }>()

const schema = z.object({
  name: z.string().min(2, 'Role name must be at least 2 characters'),
  description: z.string().optional()
})

type Schema = z.output<typeof schema>

const internalOpen = ref(false)
const isOpen = computed({
  get: () => props.open !== undefined ? props.open : internalOpen.value,
  set: (val) => { internalOpen.value = val; emit('update:open', val) }
})

const toast = useToast()
const { apiCall } = useApiClient()
const state = reactive<Partial<Schema>>({ name: '', description: '' })

watch(isOpen, (val) => {
  if (val) {
    Object.assign(state, { name: props.role.name, description: props.role.description || '' })
  }
}, { immediate: true })

async function onSubmit(event: FormSubmitEvent<Schema>) {
  try {
    await apiCall(`/uam/roles/${props.role.id}`, {
      method: 'PUT',
      body: event.data
    })
    toast.add({ title: 'Role updated', description: `Role "${event.data.name}" has been updated.`, color: 'success' })
    isOpen.value = false
    emit('updated')
  } catch (error: any) {
    toast.add({ title: 'Failed to update role', description: error?.data?.message || 'An error occurred.', color: 'error' })
  }
}
</script>

<template>
  <UModal v-model:open="isOpen" title="Edit Role" description="Update role name and description">
    <slot :open-modal="() => (isOpen = true)" />

    <template #body>
      <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
        <UFormField label="Role Name" name="name">
          <UInput v-model="state.name" class="w-full" />
        </UFormField>
        <UFormField label="Description" name="description">
          <UTextarea v-model="state.description" class="w-full" />
        </UFormField>

        <div class="flex justify-end gap-2 pt-2">
          <UButton label="Cancel" color="neutral" variant="subtle" @click="isOpen = false" />
          <UButton label="Save" color="primary" variant="solid" type="submit" loading-auto />
        </div>
      </UForm>
    </template>
  </UModal>
</template>
