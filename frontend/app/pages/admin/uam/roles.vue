<script setup lang="ts">
import type { TableColumn } from '@nuxt/ui'
import { getPaginationRowModel } from '@tanstack/table-core'
import type { Row } from '@tanstack/table-core'
import type { UamRole, UamRoleListResponse } from '~/types'

const UBadge = resolveComponent('UBadge')
const UButton = resolveComponent('UButton')
const UDropdownMenu = resolveComponent('UDropdownMenu')
const UCheckbox = resolveComponent('UCheckbox')

const table = useTemplateRef('table')
const { apiFetch } = useApiClient()

const filters = reactive({ search: '' })
const appliedFilters = reactive({ search: '' })

const activeFilterCount = computed(() => [appliedFilters.search].filter(Boolean).length)

function applyFilters() {
  Object.assign(appliedFilters, filters)
}

function clearFilters() {
  filters.search = ''
  appliedFilters.search = ''
}
const columnVisibility = ref()
const rowSelection = ref({})
const pagination = ref({ pageIndex: 0, pageSize: 15 })

const editingRole = ref<UamRole | null>(null)
const deletingRole = ref<UamRole | null>(null)
const showEditModal = ref(false)
const showDeleteModal = ref(false)

const { data, status, refresh } = await apiFetch<UamRoleListResponse>('/uam/roles', { lazy: true })

const roles = computed(() => data.value?.data || [])

const selectedRows:any = computed(() =>
  table.value?.tableApi?.getFilteredSelectedRowModel().rows.map(r => r.original as UamRole) || []
)
const selectedIds = computed(() => selectedRows.value.map(r => r.id))

const filteredRoles = computed(() => {
  if (!appliedFilters.search) return roles.value
  const q = appliedFilters.search.toLowerCase()
  return roles.value.filter(r =>
    r.name.toLowerCase().includes(q) || (r.description || '').toLowerCase().includes(q)
  )
})

function openEdit(role: UamRole) {
  editingRole.value = role
  showEditModal.value = true
}

function openDelete(role: UamRole) {
  deletingRole.value = role
  showDeleteModal.value = true
}

function getRowItems(row: Row<UamRole>) {
  return [
    { type: 'label' as const, label: 'Actions' },
    {
      label: 'Edit role',
      icon: 'i-lucide-pencil',
      onSelect() { openEdit(row.original) }
    },
    { type: 'separator' as const },
    {
      label: 'Delete role',
      icon: 'i-lucide-trash',
      color: 'error' as const,
      onSelect() { openDelete(row.original) }
    }
  ]
}

const columns: TableColumn<UamRole>[] = [
  {
    id: 'select',
    header: ({ table: t }) =>
      h(UCheckbox, {
        'modelValue': t.getIsSomePageRowsSelected() ? 'indeterminate' : t.getIsAllPageRowsSelected(),
        'onUpdate:modelValue': (val: boolean | 'indeterminate') => t.toggleAllPageRowsSelected(!!val),
        'ariaLabel': 'Select all'
      }),
    cell: ({ row }) =>
      h(UCheckbox, {
        'modelValue': row.getIsSelected(),
        'onUpdate:modelValue': (val: boolean | 'indeterminate') => row.toggleSelected(!!val),
        'ariaLabel': 'Select row'
      })
  },
  {
    accessorKey: 'name',
    header: 'Name',
    cell: ({ row }) =>
      h('p', { class: 'font-medium text-highlighted capitalize' }, row.original.name)
  },
  {
    accessorKey: 'description',
    header: 'Description',
    cell: ({ row }) =>
      h('p', { class: 'text-sm text-muted' }, row.original.description || '—')
  },
  {
    accessorKey: 'permissions',
    header: 'Permissions',
    cell: ({ row }) => {
      const count = row.original.permissions?.length || 0
      return h(UBadge, { variant: 'subtle', color: count > 0 ? 'success' : 'neutral' }, () => `${count} permission${count !== 1 ? 's' : ''}`)
    }
  },
  {
    accessorKey: 'created_at',
    header: 'Created',
    cell: ({ row }) =>
      h('span', { class: 'text-sm text-muted' }, new Date(row.original.created_at).toLocaleDateString())
  },
  {
    id: 'actions',
    cell: ({ row }) =>
      h('div', { class: 'text-right' },
        h(UDropdownMenu, {
          content: { align: 'end' },
          items: getRowItems(row)
        }, () => h(UButton, { icon: 'i-lucide-ellipsis-vertical', color: 'neutral', variant: 'ghost', class: 'ml-auto' }))
      )
  }
]
</script>

<template>
  <UDashboardPanel id="uam-roles">
    <template #header>
      <UDashboardNavbar title="Roles">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <AdminUamRolesAddModal @created="refresh()" />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <SharedTableToolbar :table-api="table?.tableApi" :active-filters="activeFilterCount" @apply="applyFilters" @clear="clearFilters">
        <template #filters>
          <UFormField label="Search">
            <UInput v-model="filters.search" icon="i-lucide-search" placeholder="Name or description..." class="w-full" />
          </UFormField>
        </template>
        <template #actions>
          <AdminUamRolesDeleteModal
            v-if="selectedIds.length"
            :count="selectedIds.length"
            :ids="selectedIds"
            @deleted="() => { rowSelection = {}; refresh() }"
          >
            <template #default="{ openModal }">
              <UButton label="Delete" color="error" variant="subtle" icon="i-lucide-trash" @click="openModal">
                <template #trailing>
                  <UKbd>{{ selectedIds.length }}</UKbd>
                </template>
              </UButton>
            </template>
          </AdminUamRolesDeleteModal>
        </template>
      </SharedTableToolbar>

      <UTable
        ref="table"
        v-model:column-visibility="columnVisibility"
        v-model:row-selection="rowSelection"
        v-model:pagination="pagination"
        :pagination-options="{ getPaginationRowModel: getPaginationRowModel() }"
        class="shrink-0"
        :data="filteredRoles"
        :columns="columns"
        :loading="status === 'pending'"
        :ui="tableStyles"
      />

      <SharedTablePagination
        :selected="selectedIds.length"
        :total="filteredRoles.length"
        v-model:pagination="pagination"
      />

    </template>
  </UDashboardPanel>

  <AdminUamRolesEditModal
    v-if="editingRole"
    :role="editingRole"
    :open="showEditModal"
    @update:open="val => { showEditModal = val; if (!val) editingRole = null }"
    @updated="() => { showEditModal = false; editingRole = null; refresh() }"
  />

  <AdminUamRolesDeleteModal
    v-if="deletingRole"
    :role="deletingRole"
    :open="showDeleteModal"
    @update:open="val => { showDeleteModal = val; if (!val) deletingRole = null }"
    @deleted="() => { showDeleteModal = false; deletingRole = null; refresh() }"
  />
</template>
