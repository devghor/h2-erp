declare module '#auth-utils' {
  interface UserSession {
    user: SessionUser
  }
}

export interface SessionUser {
  id: number
  name: string
  email: string
  company_id: string
  access_token: string
  roles: string[]
  permissions: string[]
}
