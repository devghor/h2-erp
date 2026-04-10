<script setup lang="ts">
import type { TableColumn } from '@nuxt/ui'
import { getPaginationRowModel } from '@tanstack/table-core'
import type { Row } from '@tanstack/table-core'
import type { UamRole, UamRoleListResponse } from '~/types'

const UBadge = resolveComponent('UBadge')
const UButton = resolveComponent('UButton')
const UDropdownMenu = resolveComponent('UDropdownMenu')
const UCheckbox = resolveComponent('UCheckbox')

const toast = useToast()
const table = useTemplateRef('table')
const { apiFetch } = useApiClient()

const filters = reactive({ name: '', description: '', from_date: '', to_date: '' })
const appliedFilters = reactive({ name: '', description: '', from_date: '', to_date: '' })

const columnVisibility = ref()
const rowSelection = ref({})
const pagination = ref({ pageIndex: 0, pageSize: 15 })

const editingRole = ref<UamRole | null>(null)
const deletingRole = ref<UamRole | null>(null)
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const showBulkDeleteModal = ref(false)

const { data, status, refresh } = await apiFetch<UamRoleListResponse>(
  () =>
    `/uam/roles?page=${pagination.value.pageIndex + 1}&per_page=${pagination.value.pageSize}${appliedFilters.name ? `&name=${encodeURIComponent(appliedFilters.name)}` : ''}${appliedFilters.description ? `&description=${encodeURIComponent(appliedFilters.description)}` : ''}${appliedFilters.from_date ? `&from_date=${appliedFilters.from_date}` : ''}${appliedFilters.to_date ? `&to_date=${appliedFilters.to_date}` : ''}`,
  { lazy: true }
)

const roles = computed(() => data.value?.data || [])
const total = computed(() => data.value?.meta?.total || 0)

const selectedRows: any = computed(
  () => table.value?.tableApi?.getFilteredSelectedRowModel().rows.map((r: any) => r.original as UamRole) || []
)
const selectedIds = computed(() => selectedRows.value.map((r: any) => r.id))

async function openEdit(role: UamRole) {
  editingRole.value = role
  await nextTick()
  showEditModal.value = true
}

async function openDelete(role: UamRole) {
  deletingRole.value = role
  await nextTick()
  showDeleteModal.value = true
}

function openBulkDelete() {
  showBulkDeleteModal.value = true
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

const activeFilterCount = computed(
  () => [appliedFilters.name, appliedFilters.description, appliedFilters.from_date, appliedFilters.to_date].filter(Boolean).length
)

function applyFilters() {
  Object.assign(appliedFilters, filters)
  pagination.value.pageIndex = 0
  refresh()
}

function clearFilters() {
  filters.name = ''
  filters.description = ''
  filters.from_date = ''
  filters.to_date = ''
  appliedFilters.name = ''
  appliedFilters.description = ''
  appliedFilters.from_date = ''
  appliedFilters.to_date = ''
  pagination.value.pageIndex = 0
  refresh()
}

const isExporting = ref(false)

async function downloadExport(path: string, filename: string) {
  isExporting.value = true
  try {
    const { apiDownload } = useApiClient()
    const params = Object.fromEntries(
      Object.entries(appliedFilters).filter(([, v]) => v) as [string, string][]
    )
    await apiDownload(path, filename, params)
  } catch {
    toast.add({ title: 'Export failed', description: 'Could not download the file.', color: 'error' })
  } finally {
    isExporting.value = false
  }
}

function exportToExcel() {
  downloadExport('/uam/roles/export', `roles_${new Date().toISOString().slice(0, 10)}.xlsx`)
}

const exportItems = [[{ label: 'Export Excel', icon: 'i-lucide-file-spreadsheet', onSelect: exportToExcel }]]
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
      <CoreTableToolbar :table-api="table?.tableApi" :active-filters="activeFilterCount" @apply="applyFilters" @clear="clearFilters">
        <template #filters>
          <div class="grid grid-cols-4 gap-4 w-full">
            <UFormField label="Name" class="w-full">
              <UInput v-model="filters.name" icon="i-lucide-shield" placeholder="Filter by name..." class="w-full" />
            </UFormField>
            <UFormField label="Description" class="w-full">
              <UInput v-model="filters.description" icon="i-lucide-search" placeholder="Filter by description..." class="w-full" />
            </UFormField>
            <UFormField label="From Date" class="w-full">
              <UInput v-model="filters.from_date" type="date" class="w-full" />
            </UFormField>
            <UFormField label="To Date" class="w-full">
              <UInput v-model="filters.to_date" type="date" class="w-full" />
            </UFormField>
          </div>
        </template>
        <template #actions>
          <UButton
            v-if="selectedIds.length"
            label="Delete"
            color="neutral"
            variant="outline"
            icon="i-lucide-trash"
            size="xs"
            @click="openBulkDelete"
          >
            <template #trailing>
              <span
                class="inline-flex items-center justify-center w-3.5 h-3.5 rounded-full bg-error text-white text-[9px] font-bold leading-none"
              >
                {{ selectedIds.length }}
              </span>
            </template>
          </UButton>
          <UDropdownMenu :items="exportItems" :content="{ align: 'end' }">
            <UButton
              label="Export"
              color="neutral"
              variant="outline"
              icon="i-lucide-download"
              trailing-icon="i-lucide-chevron-down"
              size="xs"
              :loading="isExporting"
            />
          </UDropdownMenu>
        </template>
      </CoreTableToolbar>

      <UTable
        ref="table"
        v-model:column-visibility="columnVisibility"
        v-model:row-selection="rowSelection"
        v-model:pagination="pagination"
        :pagination-options="{ getPaginationRowModel: getPaginationRowModel() }"
        class="shrink-0"
        :data="roles"
        :columns="columns"
        :loading="status === 'pending'"
        :ui="tableStyles"
      />

      <CoreTablePagination
        v-model:pagination="pagination"
        :selected="selectedIds.length"
        :total="total"
        @update:pagination="refresh()"
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

  <AdminUamRolesDeleteModal
    :count="selectedIds.length"
    :ids="selectedIds"
    :open="showBulkDeleteModal"
    @update:open="val => { showBulkDeleteModal = val }"
    @deleted="() => { showBulkDeleteModal = false; rowSelection = {}; refresh() }"
  />
</template>
