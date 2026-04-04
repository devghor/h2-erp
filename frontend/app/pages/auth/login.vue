<script setup lang="ts">
import { z } from 'zod'
import type { FormSubmitEvent } from '@nuxt/ui'
import { ROUTES } from '~/constants/routes'

definePageMeta({
  layout: 'auth'
})

const { fetch: fetchSession } = useUserSession()
const toast = useToast()
const loading = ref(false)

const schema = z.object({
  email: z.string().email({ error: 'Invalid email address' }),
  password: z.string().min(1, 'Password is required')
})

type Schema = z.output<typeof schema>

const state = reactive<Schema>({
  email: '',
  password: ''
})

async function onSubmit(event: FormSubmitEvent<Schema>) {
  loading.value = true
  try {
    await $fetch('/api/auth/login', {
      method: 'POST',
      body: event.data
    })
    await fetchSession()
    await navigateTo(ROUTES.HOME)
  } catch (error: any) {
    const message = error?.data?.message || error?.message || 'Invalid email or password'
    toast.add({
      title: 'Login failed',
      description: message,
      color: 'error'
    })
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="w-full max-w-sm px-4">
    <div class="mb-8 text-center">
      <h1 class="text-2xl font-bold text-highlighted">
        H2 ERP
      </h1>
      <p class="mt-1 text-sm text-muted">
        Sign in to your account
      </p>
    </div>

    <UCard class="shadow-lg">
      <UForm
        :schema="schema"
        :state="state"
        class="space-y-4"
        @submit="onSubmit"
      >
        <UFormField
          label="Email"
          name="email"
          required
        >
          <UInput
            v-model="state.email"
            type="email"
            placeholder="you@example.com"
            autocomplete="email"
            class="w-full"
          />
        </UFormField>

        <UFormField
          label="Password"
          name="password"
          required
        >
          <UInput
            v-model="state.password"
            type="password"
            placeholder="••••••••"
            autocomplete="current-password"
            class="w-full"
          />
        </UFormField>

        <UButton
          type="submit"
          block
          :loading="loading"
          class="mt-2"
        >
          Sign in
        </UButton>
      </UForm>
    </UCard>
  </div>
</template>
