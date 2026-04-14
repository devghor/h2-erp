<script setup lang="ts">
import type { DropdownMenuItem } from '@nuxt/ui'
import type { Tenant, TenantListResponse } from '~/types/index.d.ts'

defineProps<{
  collapsed?: boolean
}>()

const { user, fetch: fetchSession } = useUserSession()
const { apiCall } = useApiClient()
const toast = useToast()

const tenants = ref<Tenant[]>([])
const switching = ref(false)

const currentTenantId = computed(() => {
  const u = user.value as Record<string, unknown> | null
  return (u?.user as Record<string, string> | null)?.tenant_id ?? null
})

const currentTenant = computed<Tenant | null>(() =>
  tenants.value.find(t => t.id === currentTenantId.value) ?? null
)

try {
  const response = await apiCall<TenantListResponse>('/tenancy')
  tenants.value = response.data
} catch {
  // silently fail — menu stays empty
}

async function switchTenant(tenantId: string) {
  if (switching.value || tenantId === currentTenantId.value) return
  switching.value = true
  try {
    await apiCall('/tenancy/switch', { method: 'POST', body: { tenant_id: tenantId } })
    await fetchSession()
  } catch (err: unknown) {
    const message = (err as { data?: { message?: string } })?.data?.message ?? 'Failed to switch tenant'
    toast.add({ title: 'Switch failed', description: message, color: 'error' })
  } finally {
    switching.value = false
  }
}

const items = computed<DropdownMenuItem[][]>(() => [
  tenants.value.map(tenant => ({
    label: tenant.name,
    icon: currentTenant.value?.id === tenant.id ? 'i-lucide-check' : undefined,
    onSelect() {
      switchTenant(tenant.id)
    }
  }))
])

const displayLabel = computed(() => currentTenant.value?.name ?? 'Select Tenant')
</script>

<template>
  <UDropdownMenu
    :items="items"
    :content="{ align: 'center', collisionPadding: 12 }"
    :ui="{ content: collapsed ? 'w-40' : 'w-(--reka-dropdown-menu-trigger-width)' }"
  >
    <UButton
      :label="collapsed ? undefined : displayLabel"
      :trailing-icon="collapsed ? undefined : 'i-lucide-chevrons-up-down'"
      :loading="switching"
      color="neutral"
      variant="ghost"
      block
      :square="collapsed"
      class="data-[state=open]:bg-elevated"
      :class="[!collapsed && 'py-2']"
      :ui="{ trailingIcon: 'text-dimmed' }"
    />
  </UDropdownMenu>
</template>
