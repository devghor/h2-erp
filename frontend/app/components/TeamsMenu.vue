<script setup lang="ts">
import type { DropdownMenuItem } from '@nuxt/ui'

defineProps<{
  collapsed?: boolean
}>()

const { tenants, switching, currentTenant, fetchTenants, switchTenant } = useTenancy()

await fetchTenants()

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
