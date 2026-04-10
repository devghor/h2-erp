<script setup lang="ts">
import type { UamPermission, UamPermissionsGrouped } from '~/types'

definePageMeta({ title: 'Permissions' })

const search = ref('')
const { apiFetch, apiCall } = useApiClient()
const toast = useToast()

// All roles for the dropdown
const { data: rolesData } = await apiFetch<{ data: { roles: { id: number; name: string }[] } }>(
  '/uam/roles/all',
  { lazy: true }
)
const roles = computed(() => rolesData.value?.data?.roles || [])

// All permissions grouped by module → group
const { data: permData, status: permStatus } = await apiFetch<{ data: { permissions: UamPermissionsGrouped } }>(
  '/uam/permissions/grouped',
  { lazy: true }
)
const allGrouped = computed(() => permData.value?.data?.permissions || {})

const totalCount = computed(() =>
  Object.values(allGrouped.value).reduce(
    (acc, groups) =>
      acc +
      Object.values(groups).reduce((a, perms) => a + (perms as UamPermission[]).length, 0),
    0
  )
)

// Role selection state
const selectedRoleId = ref<number | null>(null)
const isFetchingRole = ref(false)
const checkedPermissions = ref(new Set<string>())
const isDirty = ref(false)

watch(selectedRoleId, async (id) => {
  checkedPermissions.value = new Set()
  isDirty.value = false
  if (!id) return

  isFetchingRole.value = true
  try {
    const res = await apiCall<{ data: { permissions: UamPermission[] } }>(`/uam/roles/${id}`)
    const perms = res?.data?.permissions || []
    checkedPermissions.value = new Set(perms.map((p) => p.name))
  } finally {
    isFetchingRole.value = false
  }
})

// Filtered structure based on search
const filteredGrouped = computed(() => {
  if (!search.value) return allGrouped.value
  const q = search.value.toLowerCase()
  return Object.fromEntries(
    Object.entries(allGrouped.value)
      .map(([module, groups]) => [
        module,
        Object.fromEntries(
          Object.entries(groups as Record<string, UamPermission[]>)
            .map(([group, perms]) => [
              group,
              (perms as UamPermission[]).filter(
                (p) =>
                  p.name.toLowerCase().includes(q) ||
                  (p.display_name || '').toLowerCase().includes(q) ||
                  module.toLowerCase().includes(q) ||
                  group.toLowerCase().includes(q)
              )
            ])
            .filter(([, perms]) => (perms as UamPermission[]).length > 0)
        )
      ])
      .filter(([, groups]) => Object.keys(groups as object).length > 0)
  ) as UamPermissionsGrouped
})

// ── Helpers ──────────────────────────────────────────────────────────────────

/** All perms in a module across all groups */
function allModulePerms(module: string): UamPermission[] {
  return Object.values((filteredGrouped.value[module] || {}) as Record<string, UamPermission[]>).flat()
}

/** All perms in a module+group */
function groupPerms(module: string, group: string): UamPermission[] {
  return ((filteredGrouped.value[module] || {})[group] || []) as UamPermission[]
}

function isModuleAllChecked(module: string) {
  const perms = allModulePerms(module)
  return perms.length > 0 && perms.every((p) => checkedPermissions.value.has(p.name))
}
function isModuleSomeChecked(module: string) {
  return allModulePerms(module).some((p) => checkedPermissions.value.has(p.name))
}
function isGroupAllChecked(module: string, group: string) {
  const perms = groupPerms(module, group)
  return perms.length > 0 && perms.every((p) => checkedPermissions.value.has(p.name))
}
function isGroupSomeChecked(module: string, group: string) {
  return groupPerms(module, group).some((p) => checkedPermissions.value.has(p.name))
}

function toggleModule(module: string, val: boolean | 'indeterminate') {
  allModulePerms(module).forEach((p) => {
    if (val === true) checkedPermissions.value.add(p.name)
    else checkedPermissions.value.delete(p.name)
  })
  isDirty.value = true
}

function toggleGroup(module: string, group: string, val: boolean | 'indeterminate') {
  groupPerms(module, group).forEach((p) => {
    if (val === true) checkedPermissions.value.add(p.name)
    else checkedPermissions.value.delete(p.name)
  })
  isDirty.value = true
}

function togglePermission(name: string, val: boolean | 'indeterminate') {
  if (val === true) checkedPermissions.value.add(name)
  else checkedPermissions.value.delete(name)
  isDirty.value = true
}

function formatPermName(perm: UamPermission): string {
  if (perm.display_name) return perm.display_name
  return perm.name
    .split('_')
    .map((w) => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase())
    .join(' ')
}

function moduleTotalCount(module: string): number {
  return Object.values((filteredGrouped.value[module] || {}) as Record<string, UamPermission[]>).reduce(
    (acc, perms) => acc + (perms as UamPermission[]).length,
    0
  )
}

// Save
const isSaving = ref(false)

async function save() {
  if (!selectedRoleId.value) return
  isSaving.value = true
  try {
    await apiCall(`/uam/roles/${selectedRoleId.value}`, {
      method: 'PUT',
      body: { permissions: Array.from(checkedPermissions.value) }
    })
    toast.add({ title: 'Permissions updated', description: 'Role permissions have been saved.', color: 'success' })
    isDirty.value = false
  } catch (e: any) {
    toast.add({ title: 'Failed to save', description: e?.data?.message || 'An error occurred.', color: 'error' })
  } finally {
    isSaving.value = false
  }
}
</script>

<template>
  <UDashboardPanel id="uam-permissions">
    <template #header>
      <UDashboardNavbar title="Permissions">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <div class="flex items-center gap-3">
            <UBadge variant="subtle" color="neutral" class="text-sm">
              {{ totalCount }} total
            </UBadge>
            <UButton
              v-if="selectedRoleId"
              label="Save Changes"
              color="primary"
              icon="i-lucide-save"
              :loading="isSaving"
              :disabled="!isDirty"
              @click="save"
            />
          </div>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6">
        <!-- Controls -->
        <div class="flex flex-col sm:flex-row gap-3">
          <USelectMenu
            v-model="selectedRoleId"
            :items="roles.map((r) => ({ label: r.name, value: r.id }))"
            value-key="value"
            placeholder="Select a role..."
            icon="i-lucide-shield"
            class="w-full sm:w-72"
          />
          <UInput
            v-model="search"
            icon="i-lucide-search"
            placeholder="Search permissions..."
            class="w-full sm:max-w-sm"
            :disabled="!selectedRoleId"
          />
        </div>

        <!-- No role selected -->
        <div v-if="!selectedRoleId" class="flex flex-col items-center justify-center py-16 gap-3 text-center">
          <UIcon name="i-lucide-shield-question" class="size-12 text-muted" />
          <p class="text-muted text-sm">Select a role above to view and edit its permissions.</p>
        </div>

        <!-- Loading -->
        <div v-else-if="permStatus === 'pending' || isFetchingRole" class="flex justify-center py-12">
          <UIcon name="i-lucide-loader-circle" class="animate-spin text-muted size-8" />
        </div>

        <!-- Empty search result -->
        <div v-else-if="!Object.keys(filteredGrouped).length" class="text-center py-12 text-muted text-sm">
          No permissions match your search.
        </div>

        <!-- Module cards -->
        <div v-else class="grid gap-4">
          <div
            v-for="(groups, module) in filteredGrouped"
            :key="module"
            class="border border-default rounded-lg overflow-hidden"
          >
            <!-- Module header -->
            <div class="px-4 py-3 bg-elevated/50 border-b border-default flex items-center justify-between">
              <div class="flex items-center gap-3">
                <UCheckbox
                  :model-value="
                    isModuleAllChecked(module as string)
                      ? true
                      : isModuleSomeChecked(module as string)
                        ? 'indeterminate'
                        : false
                  "
                  @update:model-value="toggleModule(module as string, $event)"
                />
                <UIcon name="i-lucide-shield" class="text-primary size-4" />
                <span class="font-semibold text-highlighted uppercase tracking-wide text-sm">{{ module }}</span>
              </div>
              <UBadge variant="subtle" color="neutral">{{ moduleTotalCount(module as string) }}</UBadge>
            </div>

            <!-- Groups inside module -->
            <div class="divide-y divide-default">
              <div
                v-for="(perms, group) in (groups as Record<string, UamPermission[]>)"
                :key="group"
              >
                <!-- Group sub-header (only when group name is non-empty) -->
                <div
                  v-if="group"
                  class="px-4 py-2 bg-default/30 flex items-center justify-between"
                >
                  <div class="flex items-center gap-2">
                    <UCheckbox
                      :model-value="
                        isGroupAllChecked(module as string, group as string)
                          ? true
                          : isGroupSomeChecked(module as string, group as string)
                            ? 'indeterminate'
                            : false
                      "
                      @update:model-value="toggleGroup(module as string, group as string, $event)"
                    />
                    <span class="text-xs font-medium text-muted uppercase tracking-wide">{{ group }}</span>
                  </div>
                  <UBadge variant="subtle" color="neutral" size="sm">{{ (perms as UamPermission[]).length }}</UBadge>
                </div>

                <!-- Permissions grid -->
                <div class="p-4">
                  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    <label
                      v-for="perm in (perms as UamPermission[])"
                      :key="perm.id"
                      class="flex items-center gap-2 cursor-pointer group"
                    >
                      <UCheckbox
                        :model-value="checkedPermissions.has(perm.name)"
                        @update:model-value="togglePermission(perm.name, $event)"
                      />
                      <span class="text-sm text-default group-hover:text-highlighted transition-colors select-none">
                        {{ formatPermName(perm) }}
                      </span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </UDashboardPanel>
</template>
