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
      acc + Object.values(groups).reduce((a, perms) => a + (perms as UamPermission[]).length, 0),
    0
  )
)

// Role selection state
const selectedRoleId = ref<number | null>(null)
const isFetchingRole = ref(false)
const checkedPermissions = ref(new Set<string>())
const originalPermissions = ref(new Set<string>())
const isDirty = ref(false)

watch(selectedRoleId, async (id) => {
  checkedPermissions.value = new Set()
  originalPermissions.value = new Set()
  isDirty.value = false
  if (!id) return

  isFetchingRole.value = true
  try {
    const res = await apiCall<{ data: { permissions: UamPermission[] } }>(`/uam/roles/${id}`)
    const perms = res?.data?.permissions || []
    checkedPermissions.value = new Set(perms.map((p) => p.name))
    originalPermissions.value = new Set(perms.map((p) => p.name))
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

function allModulePerms(module: string): UamPermission[] {
  return Object.values((filteredGrouped.value[module] || {}) as Record<string, UamPermission[]>).flat()
}

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

function moduleAssignedCount(module: string): number {
  return allModulePerms(module).filter((p) => checkedPermissions.value.has(p.name)).length
}

const assignedCount = computed(() => checkedPermissions.value.size)

const coveragePercent = computed(() =>
  totalCount.value ? Math.round((assignedCount.value / totalCount.value) * 100) : 0
)

function moduleIcon(module: string): string {
  const m = module.toLowerCase()
  if (m.includes('user')) return 'i-lucide-users'
  if (m.includes('role')) return 'i-lucide-shield-check'
  if (m.includes('permission')) return 'i-lucide-key-round'
  if (m.includes('report') || m.includes('analytic')) return 'i-lucide-bar-chart-2'
  if (m.includes('setting') || m.includes('config')) return 'i-lucide-settings-2'
  if (m.includes('product') || m.includes('inventory') || m.includes('stock')) return 'i-lucide-package'
  if (m.includes('order') || m.includes('sale')) return 'i-lucide-shopping-cart'
  if (m.includes('finance') || m.includes('account') || m.includes('billing')) return 'i-lucide-landmark'
  if (m.includes('hr') || m.includes('employee') || m.includes('payroll')) return 'i-lucide-user-circle'
  if (m.includes('dashboard')) return 'i-lucide-layout-dashboard'
  if (m.includes('customer') || m.includes('client')) return 'i-lucide-contact'
  if (m.includes('supplier') || m.includes('vendor')) return 'i-lucide-truck'
  if (m.includes('audit') || m.includes('log')) return 'i-lucide-clipboard-list'
  return 'i-lucide-layers'
}

function getActionTag(name: string): { label: string; color: 'neutral' | 'success' | 'primary' | 'warning' | 'error' | 'info' } {
  const last = name.split('_').at(-1)?.toLowerCase() || ''
  const map: Record<string, { label: string; color: 'neutral' | 'success' | 'primary' | 'warning' | 'error' | 'info' }> = {
    view:    { label: 'view',    color: 'neutral'  },
    read:    { label: 'view',    color: 'neutral'  },
    list:    { label: 'list',    color: 'neutral'  },
    index:   { label: 'list',    color: 'neutral'  },
    show:    { label: 'view',    color: 'neutral'  },
    create:  { label: 'create',  color: 'success'  },
    store:   { label: 'create',  color: 'success'  },
    add:     { label: 'create',  color: 'success'  },
    update:  { label: 'edit',    color: 'primary'  },
    edit:    { label: 'edit',    color: 'primary'  },
    delete:  { label: 'delete',  color: 'error'    },
    destroy: { label: 'delete',  color: 'error'    },
    remove:  { label: 'delete',  color: 'error'    },
    export:  { label: 'export',  color: 'warning'  },
    import:  { label: 'import',  color: 'warning'  },
    download:{ label: 'export',  color: 'warning'  },
  }
  return map[last] || { label: last, color: 'neutral' }
}

const selectedRole = computed(() =>
  roles.value.find((r) => r.id === selectedRoleId.value) || null
)

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
    originalPermissions.value = new Set(checkedPermissions.value)
    isDirty.value = false
  } catch (e: any) {
    toast.add({ title: 'Failed to save', description: e?.data?.message || 'An error occurred.', color: 'error' })
  } finally {
    isSaving.value = false
  }
}

function discard() {
  checkedPermissions.value = new Set(originalPermissions.value)
  isDirty.value = false
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
          <div class="flex items-center gap-2">
            <UBadge variant="subtle" color="neutral" class="hidden sm:flex gap-1.5">
              <UIcon name="i-lucide-key-round" class="size-3" />
              {{ totalCount }} permissions
            </UBadge>
          </div>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex gap-5 min-h-0 pb-20">

        <!-- ── Left sidebar ────────────────────────────────────────────── -->
        <div class="w-64 shrink-0 flex flex-col gap-4">

          <!-- Role selector card -->
          <div class="border border-default rounded-xl overflow-hidden">
            <div class="px-4 py-3 bg-elevated/60 border-b border-default">
              <p class="text-xs font-semibold text-muted uppercase tracking-widest">Active Role</p>
            </div>
            <div class="p-3">
              <USelectMenu
                v-model="selectedRoleId"
                :items="roles.map((r) => ({ label: r.name, value: r.id }))"
                value-key="value"
                placeholder="Select a role..."
                icon="i-lucide-shield"
                class="w-full"
              />

              <!-- Role stats -->
              <template v-if="selectedRoleId && !isFetchingRole">
                <div class="mt-3 grid grid-cols-2 gap-2">
                  <div class="bg-elevated/60 rounded-lg p-3 text-center">
                    <p class="text-lg font-bold text-highlighted tabular-nums">{{ assignedCount }}</p>
                    <p class="text-[11px] text-muted mt-0.5 uppercase tracking-wide">Assigned</p>
                  </div>
                  <div class="bg-elevated/60 rounded-lg p-3 text-center">
                    <p class="text-lg font-bold text-muted tabular-nums">{{ totalCount - assignedCount }}</p>
                    <p class="text-[11px] text-muted mt-0.5 uppercase tracking-wide">Available</p>
                  </div>
                </div>
                <div class="mt-3 px-0.5">
                  <div class="flex justify-between text-[11px] text-muted mb-1.5">
                    <span>Permission coverage</span>
                    <span class="font-semibold text-default tabular-nums">{{ coveragePercent }}%</span>
                  </div>
                  <div class="h-1.5 bg-elevated rounded-full overflow-hidden">
                    <div
                      class="h-full bg-primary rounded-full transition-all duration-500"
                      :style="{ width: `${coveragePercent}%` }"
                    />
                  </div>
                </div>
              </template>
            </div>
          </div>

          <!-- Module quick-nav -->
          <div
            v-if="selectedRoleId && !isFetchingRole && Object.keys(filteredGrouped).length"
            class="border border-default rounded-xl overflow-hidden"
          >
            <div class="px-4 py-3 bg-elevated/60 border-b border-default">
              <p class="text-xs font-semibold text-muted uppercase tracking-widest">Modules</p>
            </div>
            <div class="divide-y divide-default max-h-[400px] overflow-y-auto">
              <div
                v-for="(_, module) in filteredGrouped"
                :key="module"
                class="flex items-center justify-between px-4 py-2.5 hover:bg-elevated/40 transition-colors cursor-default"
              >
                <div class="flex items-center gap-2.5 min-w-0">
                  <UIcon :name="moduleIcon(module as string)" class="size-3.5 text-muted shrink-0" />
                  <span class="text-sm text-default capitalize truncate">{{ module }}</span>
                </div>
                <div class="flex items-center gap-1.5 shrink-0 ml-2">
                  <span class="text-[11px] text-muted tabular-nums">
                    {{ moduleAssignedCount(module as string) }}/{{ moduleTotalCount(module as string) }}
                  </span>
                  <div
                    class="size-1.5 rounded-full shrink-0"
                    :class="
                      isModuleAllChecked(module as string)
                        ? 'bg-success'
                        : isModuleSomeChecked(module as string)
                          ? 'bg-warning'
                          : 'bg-elevated'
                    "
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ── Main content ────────────────────────────────────────────── -->
        <div class="flex-1 min-w-0 flex flex-col gap-4">

          <!-- Search -->
          <UInput
            v-model="search"
            icon="i-lucide-search"
            placeholder="Search permissions, modules, or groups..."
            :disabled="!selectedRoleId || isFetchingRole"
          >
            <template v-if="search" #trailing>
              <UButton
                icon="i-lucide-x"
                variant="ghost"
                color="neutral"
                size="xs"
                class="-mr-1"
                @click="search = ''"
              />
            </template>
          </UInput>

          <!-- No role selected -->
          <div
            v-if="!selectedRoleId"
            class="flex flex-col items-center justify-center py-20 gap-4 text-center"
          >
            <div class="size-16 rounded-2xl bg-elevated/60 flex items-center justify-center">
              <UIcon name="i-lucide-shield-question" class="size-8 text-muted" />
            </div>
            <div>
              <p class="font-semibold text-highlighted">No role selected</p>
              <p class="text-sm text-muted mt-1">Select a role from the sidebar to view and manage its permissions.</p>
            </div>
          </div>

          <!-- Loading -->
          <div
            v-else-if="permStatus === 'pending' || isFetchingRole"
            class="flex flex-col items-center justify-center py-20 gap-3"
          >
            <UIcon name="i-lucide-loader-circle" class="animate-spin text-muted size-8" />
            <p class="text-sm text-muted">Loading permissions...</p>
          </div>

          <!-- Empty search -->
          <div
            v-else-if="!Object.keys(filteredGrouped).length"
            class="flex flex-col items-center justify-center py-20 gap-4 text-center"
          >
            <div class="size-16 rounded-2xl bg-elevated/60 flex items-center justify-center">
              <UIcon name="i-lucide-search-x" class="size-8 text-muted" />
            </div>
            <div>
              <p class="font-semibold text-highlighted">No results found</p>
              <p class="text-sm text-muted mt-1">No permissions match <span class="font-medium text-default">"{{ search }}"</span></p>
            </div>
            <UButton variant="soft" color="neutral" size="sm" label="Clear search" @click="search = ''" />
          </div>

          <!-- Module cards -->
          <div v-else class="flex flex-col gap-3">
            <div
              v-for="(groups, module) in filteredGrouped"
              :key="module"
              class="border border-default rounded-xl overflow-hidden"
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
                  <div class="size-7 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                    <UIcon :name="moduleIcon(module as string)" class="size-3.5 text-primary" />
                  </div>
                  <div>
                    <p class="font-semibold text-highlighted text-sm capitalize leading-none">{{ module }}</p>
                    <p class="text-[11px] text-muted mt-0.5">
                      {{ moduleAssignedCount(module as string) }} of {{ moduleTotalCount(module as string) }} assigned
                    </p>
                  </div>
                </div>
                <div class="flex items-center gap-2">
                  <div
                    class="h-1 w-16 bg-elevated rounded-full overflow-hidden hidden sm:block"
                  >
                    <div
                      class="h-full bg-primary/60 rounded-full transition-all duration-300"
                      :style="{
                        width: `${moduleTotalCount(module as string) ? (moduleAssignedCount(module as string) / moduleTotalCount(module as string)) * 100 : 0}%`
                      }"
                    />
                  </div>
                  <UBadge variant="subtle" color="neutral" size="sm">
                    {{ moduleTotalCount(module as string) }}
                  </UBadge>
                </div>
              </div>

              <!-- Groups inside module -->
              <div class="divide-y divide-default">
                <div
                  v-for="(perms, group) in (groups as Record<string, UamPermission[]>)"
                  :key="group"
                >
                  <!-- Group sub-header -->
                  <div
                    v-if="group"
                    class="px-4 py-2 bg-default/20 flex items-center justify-between"
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
                      <span class="text-[11px] font-semibold text-muted uppercase tracking-widest">{{ group }}</span>
                    </div>
                    <UBadge variant="subtle" color="neutral" size="sm">
                      {{ (perms as UamPermission[]).length }}
                    </UBadge>
                  </div>

                  <!-- Permissions list -->
                  <div class="p-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-1.5">
                      <label
                        v-for="perm in (perms as UamPermission[])"
                        :key="perm.id"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg cursor-pointer transition-colors hover:bg-elevated/60"
                        :class="checkedPermissions.has(perm.name) ? 'bg-primary/5' : ''"
                      >
                        <UCheckbox
                          :model-value="checkedPermissions.has(perm.name)"
                          @update:model-value="togglePermission(perm.name, $event)"
                        />
                        <UBadge
                          :color="getActionTag(perm.name).color"
                          variant="subtle"
                          size="sm"
                          class="shrink-0 capitalize text-[10px] min-w-[42px] justify-center"
                        >
                          {{ getActionTag(perm.name).label }}
                        </UBadge>
                        <span class="text-sm text-default select-none truncate">
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
      </div>

      <!-- ── Floating save bar ────────────────────────────────────────── -->
      <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-4"
      >
        <div
          v-if="isDirty && selectedRoleId"
          class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 pointer-events-none"
        >
          <div class="pointer-events-auto flex items-center gap-4 bg-default border border-default rounded-2xl shadow-2xl px-5 py-3">
            <div class="flex items-center gap-2">
              <div class="size-2 rounded-full bg-warning animate-pulse" />
              <span class="text-sm font-medium text-default">Unsaved changes</span>
              <span class="text-sm text-muted hidden sm:inline">
                — <span class="font-semibold text-highlighted capitalize">{{ selectedRole?.name }}</span>
              </span>
            </div>
            <div class="flex items-center gap-2">
              <UButton
                label="Discard"
                color="neutral"
                variant="ghost"
                size="sm"
                :disabled="isSaving"
                @click="discard"
              />
              <UButton
                label="Save Changes"
                icon="i-lucide-save"
                size="sm"
                :loading="isSaving"
                @click="save"
              />
            </div>
          </div>
        </div>
      </Transition>
    </template>
  </UDashboardPanel>
</template>
