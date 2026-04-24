<script setup lang="ts">
import type { NavigationMenuItem } from '@nuxt/ui'
import { ROUTES } from '~/constants/routes'

const open = ref(false)
const route = useRoute()

const links = computed(
  () =>
    [
      [
        {
          label: 'Home',
          icon: 'i-lucide-house',
          to: ROUTES.HOME,
          onSelect: () => {
            open.value = false
          }
        },
        {
          label: 'User Access',
          icon: 'i-lucide-shield-check',
          type: 'trigger',
          defaultOpen: route.path.startsWith('/admin/uam'),
          children: [
            {
              label: 'Users',
              to: ROUTES.UAM.USERS,
              icon: 'i-lucide-users',
              onSelect: () => {
                open.value = false
              }
            },
            {
              label: 'Roles',
              to: ROUTES.UAM.ROLES,
              icon: 'i-lucide-key-round',
              onSelect: () => {
                open.value = false
              }
            },
            {
              label: 'Permissions',
              to: ROUTES.UAM.PERMISSIONS,
              icon: 'i-lucide-lock',
              onSelect: () => {
                open.value = false
              }
            }
          ]
        }
      ]
    ] as NavigationMenuItem[][]
)

const groups = computed(() => [
  {
    id: 'links',
    label: 'Go to',
    items: links.value.flat() as any[]
  }
])
</script>

<template>
  <UDashboardGroup unit="rem">
    <UDashboardSidebar
      id="default"
      v-model:open="open"
      collapsible
      resizable
      class="bg-elevated/25"
      :ui="{ footer: 'lg:border-t lg:border-default' }"
    >
      <template #header="{ collapsed }">
        <TeamsMenu :collapsed="collapsed" />
      </template>

      <template #default="{ collapsed }">
        <UDashboardSearchButton :collapsed="collapsed" class="bg-transparent ring-default" />

        <UNavigationMenu :collapsed="collapsed" :items="links[0]!" orientation="vertical" tooltip popover />
      </template>

      <template #footer="{ collapsed }">
        <UserMenu :collapsed="collapsed" />
      </template>
    </UDashboardSidebar>

    <UDashboardSearch :groups="groups" />

    <slot />

    <NotificationsSlideover />
  </UDashboardGroup>
</template>
