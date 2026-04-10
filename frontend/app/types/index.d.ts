import type { AvatarProps } from '@nuxt/ui'

export type UserStatus = 'subscribed' | 'unsubscribed' | 'bounced'
export type SaleStatus = 'paid' | 'failed' | 'refunded'

export interface User {
  id: number
  name: string
  email: string
  avatar?: AvatarProps
  status: UserStatus
  location: string
}

export interface Mail {
  id: number
  unread?: boolean
  from: User
  subject: string
  body: string
  date: string
}

export interface Member {
  name: string
  username: string
  role: 'member' | 'owner'
  avatar: AvatarProps
}

export interface Stat {
  title: string
  icon: string
  value: number | string
  variation: number
  formatter?: (value: number) => string
}

export interface Sale {
  id: string
  date: string
  status: SaleStatus
  email: string
  amount: number
}

export interface Notification {
  id: number
  unread?: boolean
  sender: User
  body: string
  date: string
}

export type Period = 'daily' | 'weekly' | 'monthly'

export interface Range {
  start: Date
  end: Date
}

// UAM types
export interface UamUser {
  ulid: string
  name: string
  email: string
  email_verified_at: string | null
  tenant_id: string
  roles: string[]
  permissions: string[]
  created_at: string
  updated_at: string
}

export interface UamRole {
  id: number
  name: string
  guard_name: string
  description: string | null
  permissions: UamPermission[]
  created_at: string
  updated_at: string
}

export interface UamPermission {
  id: number
  name: string
}

export interface UamPaginationMeta {
  current_page: number
  last_page: number
  per_page: number
  total: number
  from: number
  to: number
}

export interface UamUserListResponse {
  data: UamUser[]
  meta: UamPaginationMeta
  links: Record<string, string | null>
}

export interface UamRoleListResponse {
  data: UamRole[]
  meta: UamPaginationMeta
  links: Record<string, string | null>
}

export interface UamPermissionsGrouped {
  [module: string]: UamPermission[]
}
