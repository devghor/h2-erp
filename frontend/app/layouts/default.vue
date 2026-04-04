<script setup lang="ts">
import type { NavigationMenuItem } from '@nuxt/ui'
import { ROUTES } from '~/constants/routes'

const open = ref(false)

const links = [[{
  label: 'Home',
  icon: 'i-lucide-house',
  to: ROUTES.HOME,
  onSelect: () => { open.value = false }
}, {
  label: 'Inbox',
  icon: 'i-lucide-inbox',
  to: ROUTES.INBOX,
  badge: '4',
  onSelect: () => { open.value = false }
}, {
  label: 'Customers',
  icon: 'i-lucide-users',
  to: ROUTES.CUSTOMERS,
  onSelect: () => { open.value = false }
}, {
  label: 'User Access',
  icon: 'i-lucide-shield-check',
  type: 'trigger',
  children: [{
    label: 'Users',
    to: ROUTES.UAM.USERS,
    icon: 'i-lucide-users',
    onSelect: () => { open.value = false }
  }, {
    label: 'Roles',
    to: ROUTES.UAM.ROLES,
    icon: 'i-lucide-key-round',
    onSelect: () => { open.value = false }
  }, {
    label: 'Permissions',
    to: ROUTES.UAM.PERMISSIONS,
    icon: 'i-lucide-lock',
    onSelect: () => { open.value = false }
  }]
}, {
  label: 'Settings',
  to: ROUTES.SETTINGS.INDEX,
  icon: 'i-lucide-settings',
  type: 'trigger',
  children: [{
    label: 'General',
    to: ROUTES.SETTINGS.INDEX,
    exact: true,
    onSelect: () => { open.value = false }
  }, {
    label: 'Members',
    to: ROUTES.SETTINGS.MEMBERS,
    onSelect: () => { open.value = false }
  }, {
    label: 'Notifications',
    to: ROUTES.SETTINGS.NOTIFICATIONS,
    onSelect: () => { open.value = false }
  }, {
    label: 'Security',
    to: ROUTES.SETTINGS.SECURITY,
    onSelect: () => { open.value = false }
  }]
}]] satisfies NavigationMenuItem[][]

const groups = computed(() => [{
  id: 'links',
  label: 'Go to',
  items: links.flat()
}])
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

        <UNavigationMenu
          :collapsed="collapsed"
          :items="links[0]"
          orientation="vertical"
          tooltip
          popover
        />
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
