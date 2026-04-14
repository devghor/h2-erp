import type { TenantSwitchResponse } from '~/app/types/index.d.ts'

export default defineEventHandler(async (event) => {
  const { tenant_id } = await readBody(event)
  const config = useRuntimeConfig(event)
  const session = await getUserSession(event)

  const sessionUser = session.user as Record<string, unknown> | undefined
  const accessToken = sessionUser?.access_token as string | undefined

  if (!accessToken) {
    throw createError({ statusCode: 401, message: 'Unauthorized' })
  }

  const response = await $fetch<{ data: TenantSwitchResponse }>(
    `${config.public.apiBase}/tenancy/switch`,
    {
      method: 'POST',
      body: { tenant_id },
      headers: { Authorization: `Bearer ${accessToken}` }
    }
  )

  const { access_token, token_type, expires_at, tenant } = response.data

  // Preserve existing user info, update token and tenant_id
  const existingUser = sessionUser?.user as Record<string, unknown> | undefined
  await setUserSession(event, {
    user: {
      ...sessionUser,
      access_token,
      token_type,
      expires_at,
      user: {
        ...existingUser,
        tenant_id: tenant.id
      },
      tenant
    }
  })

  return { success: true, tenant }
})
