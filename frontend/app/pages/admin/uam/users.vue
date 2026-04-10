<script setup lang="ts">
import type { TableColumn } from '@nuxt/ui'
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

const filters = reactive({ name: '', email: '', from_date: '', to_date: '' })
const appliedFilters = reactive({
  name: '',
  email: '',
  from_date: '',
  to_date: ''
})
const columnVisibility = ref()
const rowSelection = ref({})
const pagination = ref({ pageIndex: 0, pageSize: 15 })

const editingUser = ref<UamUser | null>(null)
const deletingUser = ref<UamUser | null>(null)
const showEditModal = ref(false)
const showDeleteModal = ref(false)

const { data, status, refresh } = await apiFetch<UamUserListResponse>(
  () =>
    `/uam/users?page=${pagination.value.pageIndex + 1}&per_page=${pagination.value.pageSize}${appliedFilters.name ? `&name=${encodeURIComponent(appliedFilters.name)}` : ''}${appliedFilters.email ? `&email=${encodeURIComponent(appliedFilters.email)}` : ''}${appliedFilters.from_date ? `&from_date=${appliedFilters.from_date}` : ''}${appliedFilters.to_date ? `&to_date=${appliedFilters.to_date}` : ''}`,
  { lazy: true }
)

const users = computed(() => data.value?.data || [])
const total = computed(() => data.value?.meta?.total || 0)

const selectedRows: any = computed(
  () =>
    table.value?.tableApi
      ?.getFilteredSelectedRowModel()
      .rows.map((r: any) => r.original as UamUser) || []
)
const selectedUlids = computed(() => selectedRows.value.map((u: any) => u.ulid))

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
        toast.add({
          title: 'Copied to clipboard',
          description: 'User ULID copied.'
        })
      }
    },
    { type: 'separator' as const },
    {
      label: 'Edit user',
      icon: 'i-lucide-pencil',
      onSelect() {
        openEdit(row.original)
      }
    },
    { type: 'separator' as const },
    {
      label: 'Delete user',
      icon: 'i-lucide-trash',
      color: 'error' as const,
      onSelect() {
        openDelete(row.original)
      }
    }
  ]
}

const columns: TableColumn<UamUser>[] = [
  {
    id: 'select',
    header: ({ table: t }) =>
      h(UCheckbox, {
        modelValue: t.getIsSomePageRowsSelected()
          ? 'indeterminate'
          : t.getIsAllPageRowsSelected(),
        'onUpdate:modelValue': (val: boolean | 'indeterminate') =>
          t.toggleAllPageRowsSelected(!!val),
        ariaLabel: 'Select all'
      }),
    cell: ({ row }) =>
      h(UCheckbox, {
        modelValue: row.getIsSelected(),
        'onUpdate:modelValue': (val: boolean | 'indeterminate') =>
          row.toggleSelected(!!val),
        ariaLabel: 'Select row'
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
          ? roles.map((r: any) =>
              h(
                UBadge,
                { variant: 'subtle', color: 'primary', class: 'capitalize' },
                () => r
              )
            )
          : [h('span', { class: 'text-muted text-xs' }, 'No roles')]
      )
    }
  },
  {
    accessorKey: 'created_at',
    header: 'Created',
    cell: ({ row }) =>
      h(
        'span',
        { class: 'text-sm text-muted' },
        new Date(row.original.created_at).toLocaleDateString()
      )
  },
  {
    id: 'actions',
    cell: ({ row }) =>
      h(
        'div',
        { class: 'text-right' },
        h(
          UDropdownMenu,
          {
            content: { align: 'end' },
            items: getRowItems(row)
          },
          () =>
            h(UButton, {
              icon: 'i-lucide-ellipsis-vertical',
              color: 'neutral',
              variant: 'ghost',
              class: 'ml-auto'
            })
        )
      )
  }
]

const activeFilterCount = computed(
  () =>
    [
      appliedFilters.name,
      appliedFilters.email,
      appliedFilters.from_date,
      appliedFilters.to_date
    ].filter(Boolean).length
)

function applyFilters() {
  Object.assign(appliedFilters, filters)
  pagination.value.pageIndex = 0
  refresh()
}

function clearFilters() {
  filters.name = ''
  filters.email = ''
  filters.from_date = ''
  filters.to_date = ''
  appliedFilters.name = ''
  appliedFilters.email = ''
  appliedFilters.from_date = ''
  appliedFilters.to_date = ''
  pagination.value.pageIndex = 0
  refresh()
}
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
      <CoreTableToolbar
        :table-api="table?.tableApi"
        :active-filters="activeFilterCount"
        @apply="applyFilters"
        @clear="clearFilters"
      >
        <template #filters>
          <div class="grid grid-cols-4 gap-4 w-full">
            <UFormField label="Name" class="w-full">
              <UInput
                v-model="filters.name"
                icon="i-lucide-user"
                placeholder="Filter by name..."
                class="w-full"
              />
            </UFormField>
            <UFormField label="Email" class="w-full">
              <UInput
                v-model="filters.email"
                icon="i-lucide-mail"
                placeholder="Filter by email..."
                class="w-full"
              />
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
          <AdminUamUsersDeleteModal
            v-if="selectedUlids.length"
            :count="selectedUlids.length"
            :ulids="selectedUlids"
            @deleted="
              () => {
                rowSelection = {}
                refresh()
              }
            "
          >
            <template #default="{ openModal }">
              <UButton
                label="Delete"
                color="error"
                variant="outline"
                icon="i-lucide-trash"
                size="xs"
                @click="openModal"
              >
                <template #trailing>
                  <UKbd>{{ selectedUlids.length }}</UKbd>
                </template>
              </UButton>
            </template>
          </AdminUamUsersDeleteModal>
        </template>
      </CoreTableToolbar>

      <UTable
        ref="table"
        v-model:column-visibility="columnVisibility"
        v-model:row-selection="rowSelection"
        v-model:pagination="pagination"
        :pagination-options="{ getPaginationRowModel: getPaginationRowModel() }"
        :data="users"
        :columns="columns"
        :loading="status === 'pending'"
        :ui="tableStyles"
      />

      <CoreTablePagination
        v-model:pagination="pagination"
        :selected="selectedUlids.length"
        :total="total"
        @update:pagination="refresh()"
      />
    </template>
  </UDashboardPanel>

  <AdminUamUsersEditModal
    v-if="editingUser"
    :user="editingUser"
    :open="showEditModal"
    @update:open="
      (val) => {
        showEditModal = val
        if (!val) editingUser = null
      }
    "
    @updated="
      () => {
        showEditModal = false
        editingUser = null
        refresh()
      }
    "
  />

  <AdminUamUsersDeleteModal
    v-if="deletingUser"
    :user="deletingUser"
    :open="showDeleteModal"
    @update:open="
      (val) => {
        showDeleteModal = val
        if (!val) deletingUser = null
      }
    "
    @deleted="
      () => {
        showDeleteModal = false
        deletingUser = null
        refresh()
      }
    "
  />
</template>
