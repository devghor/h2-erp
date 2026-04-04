<script setup lang="ts">
import type { UamPermission, UamPermissionsGrouped } from '~/types'

const search = ref('')
const { apiFetch } = useApiClient()

const { data, status } = await apiFetch<{ data: { permissions: UamPermissionsGrouped } }>(
  '/uam/permissions/grouped',
  { lazy: true }
)

const grouped = computed(() => data.value?.data?.permissions || {})

const filteredGrouped = computed(() => {
  if (!search.value) return grouped.value
  const q = search.value.toLowerCase()
  return Object.fromEntries(
    Object.entries(grouped.value)
      .map(([module, perms]) => [
        module,
        (perms as UamPermission[]).filter(p => p.name.toLowerCase().includes(q) || module.toLowerCase().includes(q))
      ])
      .filter(([, perms]) => (perms as UamPermission[]).length > 0)
  ) as UamPermissionsGrouped
})

const totalCount = computed(() =>
  Object.values(grouped.value).reduce((acc, perms) => acc + (perms as UamPermission[]).length, 0)
)
</script>

<template>
  <UDashboardPanel id="uam-permissions">
    <template #header>
      <UDashboardNavbar title="Permissions">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <UBadge variant="subtle" color="neutral" class="text-sm">
            {{ totalCount }} total
          </UBadge>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6">
        <UInput
          v-model="search"
          class="max-w-sm"
          icon="i-lucide-search"
          placeholder="Search permissions..."
        />

        <div v-if="status === 'pending'" class="flex justify-center py-12">
          <UIcon name="i-lucide-loader-circle" class="animate-spin text-muted size-8" />
        </div>

        <div v-else-if="!Object.keys(filteredGrouped).length" class="text-center py-12 text-muted">
          No permissions found.
        </div>

        <div v-else class="grid gap-6">
          <div
            v-for="(perms, module) in filteredGrouped"
            :key="module"
            class="border border-default rounded-lg overflow-hidden"
          >
            <div class="px-4 py-3 bg-elevated/50 border-b border-default flex items-center justify-between">
              <div class="flex items-center gap-2">
                <UIcon name="i-lucide-shield" class="text-primary size-4" />
                <span class="font-semibold text-highlighted uppercase tracking-wide text-sm">{{ module }}</span>
              </div>
              <UBadge variant="subtle" color="neutral">
                {{ (perms as UamPermission[]).length }}
              </UBadge>
            </div>
            <div class="p-4">
              <div class="flex flex-wrap gap-2">
                <UBadge
                  v-for="perm in (perms as UamPermission[])"
                  :key="perm.id"
                  variant="outline"
                  color="neutral"
                  class="font-mono text-xs"
                >
                  {{ perm.name }}
                </UBadge>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </UDashboardPanel>
</template>
