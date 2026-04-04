<script setup lang="ts">
import type { TableColumn } from '@nuxt/ui'
import { upperFirst } from 'scule'
import { getPaginationRowModel } from '@tanstack/table-core'
import type { Row } from '@tanstack/table-core'
import type { UamRole, UamRoleListResponse } from '~/types'

const UBadge = resolveComponent('UBadge')
const UButton = resolveComponent('UButton')
const UDropdownMenu = resolveComponent('UDropdownMenu')
const UCheckbox = resolveComponent('UCheckbox')

const table = useTemplateRef('table')
const { apiFetch } = useApiClient()

const search = ref('')
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
  if (!search.value) return roles.value
  const q = search.value.toLowerCase()
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
      <div class="flex flex-wrap items-center justify-between gap-1.5 mb-4">
        <UInput v-model="search" class="max-w-sm" icon="i-lucide-search" placeholder="Search roles..." />

        <div class="flex flex-wrap items-center gap-1.5">
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

          <UDropdownMenu
            :items="
              table?.tableApi
                ?.getAllColumns()
                .filter((col: any) => col.getCanHide())
                .map((col: any) => ({
                  label: upperFirst(col.id),
                  type: 'checkbox' as const,
                  checked: col.getIsVisible(),
                  onUpdateChecked(checked: boolean) {
                    table?.tableApi?.getColumn(col.id)?.toggleVisibility(!!checked)
                  },
                  onSelect(e?: Event) { e?.preventDefault() }
                }))
            "
            :content="{ align: 'end' }"
          >
            <UButton label="Display" color="neutral" variant="outline" trailing-icon="i-lucide-settings-2" />
          </UDropdownMenu>
        </div>
      </div>

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

      <div class="flex items-center justify-between gap-3 border-t border-default pt-4 mt-auto">
        <div class="text-sm text-muted">
          {{ selectedIds.length }} of {{ filteredRoles.length }} row(s) selected.
        </div>
        <UPagination
          :default-page="(table?.tableApi?.getState().pagination.pageIndex || 0) + 1"
          :items-per-page="table?.tableApi?.getState().pagination.pageSize"
          :total="table?.tableApi?.getFilteredRowModel().rows.length"
          @update:page="(p: number) => table?.tableApi?.setPageIndex(p - 1)"
        />
      </div>
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
