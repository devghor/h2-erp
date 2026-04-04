<script setup lang="ts">
import type { TableColumn } from '@nuxt/ui'
import { upperFirst } from 'scule'
import { getPaginationRowModel } from '@tanstack/table-core'
import type { Row } from '@tanstack/table-core'
import type { UamUser, UamUserListResponse } from '~/types'

const UBadge = resolveComponent('UBadge')
const UButton = resolveComponent('UButton')
const UDropdownMenu = resolveComponent('UDropdownMenu')
const UCheckbox = resolveComponent('UCheckbox')

const toast = useToast()
const table = useTemplateRef('table')
const { apiFetch } = useApiClient()

const search = ref('')
const columnVisibility = ref()
const rowSelection = ref({})
const pagination = ref({ pageIndex: 0, pageSize: 15 })

const editingUser = ref<UamUser | null>(null)
const deletingUser = ref<UamUser | null>(null)
const showEditModal = ref(false)
const showDeleteModal = ref(false)

const { data, status, refresh } = await apiFetch<UamUserListResponse>(
  () => `/uam/users?page=${pagination.value.pageIndex + 1}&per_page=${pagination.value.pageSize}${search.value ? `&search=${encodeURIComponent(search.value)}` : ''}`,
  { lazy: true }
)

const users = computed(() => data.value?.data || [])
const total = computed(() => data.value?.meta?.total || 0)

const selectedRows = computed(() =>
  table.value?.tableApi?.getFilteredSelectedRowModel().rows.map(r => r.original as UamUser) || []
)
const selectedUlids = computed(() => selectedRows.value.map(u => u.ulid))

function openEdit(user: UamUser) {
  editingUser.value = user
  showEditModal.value = true
}

function openDelete(user: UamUser) {
  deletingUser.value = user
  showDeleteModal.value = true
}

function getRowItems(row: Row<UamUser>) {
  return [
    { type: 'label' as const, label: 'Actions' },
    {
      label: 'Copy user ID',
      icon: 'i-lucide-copy',
      onSelect() {
        navigator.clipboard.writeText(row.original.ulid)
        toast.add({ title: 'Copied to clipboard', description: 'User ULID copied.' })
      }
    },
    { type: 'separator' as const },
    {
      label: 'Edit user',
      icon: 'i-lucide-pencil',
      onSelect() { openEdit(row.original) }
    },
    { type: 'separator' as const },
    {
      label: 'Delete user',
      icon: 'i-lucide-trash',
      color: 'error' as const,
      onSelect() { openDelete(row.original) }
    }
  ]
}

const columns: TableColumn<UamUser>[] = [
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
      h('div', [
        h('p', { class: 'font-medium text-highlighted' }, row.original.name),
        h('p', { class: 'text-xs text-muted' }, row.original.email)
      ])
  },
  {
    accessorKey: 'roles',
    header: 'Roles',
    cell: ({ row }) => {
      const roles = row.original.roles || []
      return h(
        'div',
        { class: 'flex flex-wrap gap-1' },
        roles.length
          ? roles.map(r => h(UBadge, { variant: 'subtle', color: 'primary', class: 'capitalize' }, () => r))
          : [h('span', { class: 'text-muted text-xs' }, 'No roles')]
      )
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

let searchTimeout: ReturnType<typeof setTimeout>
watch(search, () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    pagination.value.pageIndex = 0
    refresh()
  }, 400)
})
</script>

<template>
  <UDashboardPanel id="uam-users">
    <template #header>
      <UDashboardNavbar title="Users">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <AdminUamUsersAddModal @created="refresh()" />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-wrap items-center justify-between gap-1.5 mb-4">
        <UInput v-model="search" class="max-w-sm" icon="i-lucide-search" placeholder="Search users..." />

        <div class="flex flex-wrap items-center gap-1.5">
          <AdminUamUsersDeleteModal
            v-if="selectedUlids.length"
            :count="selectedUlids.length"
            :ulids="selectedUlids"
            @deleted="() => { rowSelection = {}; refresh() }"
          >
            <template #default="{ openModal }">
              <UButton label="Delete" color="error" variant="subtle" icon="i-lucide-trash" @click="openModal">
                <template #trailing>
                  <UKbd>{{ selectedUlids.length }}</UKbd>
                </template>
              </UButton>
            </template>
          </AdminUamUsersDeleteModal>

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
        :data="users"
        :columns="columns"
        :loading="status === 'pending'"
        :ui="{
          base: 'table-fixed border-separate border-spacing-0',
          thead: '[&>tr]:bg-elevated/50 [&>tr]:after:content-none',
          tbody: '[&>tr]:last:[&>td]:border-b-0',
          th: 'py-2 first:rounded-l-lg last:rounded-r-lg border-y border-default first:border-l last:border-r',
          td: 'border-b border-default',
          separator: 'h-0'
        }"
      />

      <div class="flex items-center justify-between gap-3 border-t border-default pt-4 mt-auto">
        <div class="text-sm text-muted">
          {{ selectedUlids.length }} of {{ total }} row(s) selected.
        </div>
        <UPagination
          :default-page="pagination.pageIndex + 1"
          :items-per-page="pagination.pageSize"
          :total="total"
          @update:page="(p: number) => { pagination.pageIndex = p - 1; refresh() }"
        />
      </div>
    </template>
  </UDashboardPanel>

  <AdminUamUsersEditModal
    v-if="editingUser"
    :user="editingUser"
    :open="showEditModal"
    @update:open="val => { showEditModal = val; if (!val) editingUser = null }"
    @updated="() => { showEditModal = false; editingUser = null; refresh() }"
  />

  <AdminUamUsersDeleteModal
    v-if="deletingUser"
    :user="deletingUser"
    :open="showDeleteModal"
    @update:open="val => { showDeleteModal = val; if (!val) deletingUser = null }"
    @deleted="() => { showDeleteModal = false; deletingUser = null; refresh() }"
  />
</template>
