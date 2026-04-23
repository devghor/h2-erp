import type { SessionUser } from '~/types/auth'

export default defineEventHandler(async (event) => {
  const { company_id } = await readBody(event)
  const config = useRuntimeConfig(event)
  const session = await getUserSession(event)
  const user = session.user as SessionUser

  const response = await $fetch<{ data: SessionUser }>(
    `${config.public.apiBase}/configuration/companies/switch`,
    {
      method: 'POST',
      body: { company_id },
      headers: {
        Authorization: `Bearer ${user.access_token}`,
        'X-Tenant': user.company_id
      }
    }
  )

  await setUserSession(event, { user: response.data })

  return { success: true }
})
