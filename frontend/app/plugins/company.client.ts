import type { SessionUser } from '~/types/auth'

export default defineNuxtPlugin(() => {
  const { user } = useUserSession()
  const companyStore = useCompanyStore()

  companyStore.setCompanyId((user.value as SessionUser | null)?.company_id ?? null)

  watch(
    () => (user.value as SessionUser | null)?.company_id,
    (id) => companyStore.setCompanyId(id ?? null)
  )
})
