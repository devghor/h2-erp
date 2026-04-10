<script setup lang="ts">
import * as z from 'zod'
import type { FormSubmitEvent } from '@nuxt/ui'
import type { UamUser } from '~/types'

const props = defineProps<{ user: UamUser; open?: boolean }>()
const emit = defineEmits<{ updated: []; 'update:open': [boolean] }>()

const schema = z.object({
  name: z.string().min(2, 'Name must be at least 2 characters'),
  email: z.string().email('Invalid email address'),
  password: z.string().min(8).optional().or(z.literal('')),
  password_confirmation: z.string().optional().or(z.literal(''))
}).refine(data => !data.password || data.password === data.password_confirmation, {
  message: 'Passwords do not match',
  path: ['password_confirmation']
})

type Schema = z.output<typeof schema>

const internalOpen = ref(false)
const isOpen = computed({
  get: () => props.open !== undefined ? props.open : internalOpen.value,
  set: (val) => { internalOpen.value = val; emit('update:open', val) }
})

const toast = useToast()
const { apiCall } = useApiClient()
const state = reactive<Partial<Schema>>({ name: '', email: '', password: '', password_confirmation: '' })

watch(isOpen, (val) => {
  if (val) {
    Object.assign(state, { name: props.user.name, email: props.user.email, password: '', password_confirmation: '' })
  }
}, { immediate: true })

async function onSubmit(event: FormSubmitEvent<Schema>) {
  const body: Record<string, string> = { name: event.data.name, email: event.data.email }
  if (event.data.password) {
    body.password = event.data.password
    body.password_confirmation = event.data.password_confirmation || ''
  }
  try {
    await apiCall(`/uam/users/${props.user.ulid}`, { method: 'PUT', body })
    toast.add({ title: 'User updated', description: `${event.data.name} has been updated.`, color: 'success' })
    isOpen.value = false
    emit('updated')
  } catch (error: any) {
    toast.add({ title: 'Failed to update user', description: error?.data?.message || 'An error occurred.', color: 'error' })
  }
}
</script>

<template>
  <UModal v-model:open="isOpen" title="Edit User" description="Update user details">
    <slot :open-modal="() => (isOpen = true)" />

    <template #body>
      <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
        <UFormField label="Name" name="name">
          <UInput v-model="state.name" class="w-full" />
        </UFormField>
        <UFormField label="Email" name="email">
          <UInput v-model="state.email" class="w-full" />
        </UFormField>
        <UFormField label="New Password" name="password" hint="Leave blank to keep current">
          <UInput v-model="state.password" type="password" placeholder="Min 8 characters" class="w-full" />
        </UFormField>
        <UFormField label="Confirm New Password" name="password_confirmation">
          <UInput v-model="state.password_confirmation" type="password" class="w-full" />
        </UFormField>
        <div class="flex justify-end gap-2 pt-2">
          <UButton label="Cancel" color="neutral" variant="subtle" @click="isOpen = false" />
          <UButton label="Save" color="primary" variant="solid" type="submit" loading-auto />
        </div>
      </UForm>
    </template>
  </UModal>
</template>
