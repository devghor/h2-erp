export default defineEventHandler(async (event) => {
  const { email, password } = await readBody(event)
  const config = useRuntimeConfig(event)

  const response = await $fetch<{ data: Record<string, unknown> }>(
    `${config.public.apiBase}/auth/login`,
    {
      method: 'POST',
      body: { email, password }
    }
  )

  await setUserSession(event, { user: response.data })

  return { success: true }
})
