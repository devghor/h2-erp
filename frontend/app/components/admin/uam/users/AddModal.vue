<script setup lang="ts">
import * as z from 'zod'
import type { FormSubmitEvent } from '@nuxt/ui'
import type { UamRoleListResponse } from '~/types'

const emit = defineEmits<{ created: [] }>()

const schema = z.object({
  name: z.string().min(2, 'Name must be at least 2 characters'),
  email: z.string().email('Invalid email address'),
  password: z.string().min(8, 'Password must be at least 8 characters'),
  password_confirmation: z.string()
}).refine(data => data.password === data.password_confirmation, {
  message: 'Passwords do not match',
  path: ['password_confirmation']
})

type Schema = z.output<typeof schema>

const open = ref(false)
const toast = useToast()
const { apiCall, apiFetch } = useApiClient()

const selectedRoles = ref<string[]>([])

const { data: rolesData } = await apiFetch<UamRoleListResponse>('/uam/roles?per_page=100', { lazy: true })
const roleOptions = computed(() => (rolesData.value?.data || []).map(r => ({ label: r.name, value: r.name })))

const state = reactive<Partial<Schema>>({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

async function onSubmit(event: FormSubmitEvent<Schema>) {
  try {
    await apiCall('/uam/users', { method: 'POST', body: { ...event.data, roles: selectedRoles.value } })
    toast.add({ title: 'User created', description: `${event.data.name} has been added.`, color: 'success' })
    open.value = false
    Object.assign(state, { name: '', email: '', password: '', password_confirmation: '' })
    selectedRoles.value = []
    emit('created')
  } catch (error: any) {
    toast.add({ title: 'Failed to create user', description: error?.data?.message || 'An error occurred.', color: 'error' })
  }
}
</script>

<template>
  <UModal v-model:open="open" title="New User" description="Add a new user to the system">
    <UButton label="New User" icon="i-lucide-plus" />

    <template #body>
      <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
        <UFormField label="Name" name="name">
          <UInput v-model="state.name" placeholder="John Doe" class="w-full" />
        </UFormField>
        <UFormField label="Email" name="email">
          <UInput v-model="state.email" placeholder="john.doe@example.com" class="w-full" />
        </UFormField>
        <UFormField label="Password" name="password">
          <UInput v-model="state.password" type="password" placeholder="Min 8 characters" class="w-full" />
        </UFormField>
        <UFormField label="Confirm Password" name="password_confirmation">
          <UInput v-model="state.password_confirmation" type="password" placeholder="Repeat password" class="w-full" />
        </UFormField>
        <UFormField label="Roles">
          <USelectMenu
            v-model="selectedRoles"
            :items="roleOptions"
            value-key="value"
            multiple
            placeholder="Select roles..."
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
