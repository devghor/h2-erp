export const ROUTES = {
  HOME: '/admin',
  INBOX: '/admin/inbox',
  CUSTOMERS: '/admin/customers',

  UAM: {
    USERS: '/admin/uam/users',
    ROLES: '/admin/uam/roles',
    PERMISSIONS: '/admin/uam/permissions',
  },

  SETTINGS: {
    INDEX: '/settings',
    MEMBERS: '/settings/members',
    NOTIFICATIONS: '/settings/notifications',
    SECURITY: '/settings/security',
  },

  AUTH:{
    LOGIN: '/auth/login',
  }
} as const
