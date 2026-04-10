import { ROUTES } from "~/constants/routes"

/**
 * Provides a thin wrapper around $fetch and useFetch that automatically
 * injects the Bearer token from the user session. Intended for use in
 * client-side (SSR=false) pages under /admin.
 *
 * When the API returns 401 (token expired / unauthorized), the session is
 * cleared and the user is redirected to the login page automatically.
 */
export function useApiClient() {
  const { user, clear } = useUserSession()
  const config = useRuntimeConfig()
  const baseURL = config.public.apiBase as string
  const router = useRouter()

  function authHeaders(): Record<string, string> {
    const u = user.value as Record<string, unknown> | null
    const headers: Record<string, string> = {}
    if (u?.access_token) headers['Authorization'] = `Bearer ${u.access_token as string}`
    const tenantId = (u?.user as Record<string, string> | null)?.tenant_id
    if (tenantId) headers['X-Tenant'] = tenantId
    return headers
  }

  async function handleUnauthorized() {
    await clear()
    await router.push(ROUTES.AUTH.LOGIN)
  }

  /**
   * Reactive useFetch wrapper — headers refresh automatically when session changes.
   */
  function apiFetch<T>(
    path: string | (() => string),
    options: Record<string, unknown> = {}
  ) {
    const url = typeof path === 'function'
      ? computed(() => `${baseURL}${path()}`)
      : `${baseURL}${path}`

    return useFetch<T>(url as any, {
      ...options,
      headers: computed(() => ({ ...authHeaders(), ...(options.headers as object | undefined) })),
      onResponseError: async ({ response }: { response: { status: number } }) => {
        if (response.status === 401) {
          await handleUnauthorized()
        }
        if (typeof options.onResponseError === 'function') {
          ;(options.onResponseError as (...args: unknown[]) => void)({ response } as any)
        }
      }
    } as any)
  }

  /**
   * One-shot $fetch wrapper for mutations (POST, PUT, DELETE).
   */
  async function apiCall<T = unknown>(
    path: string,
    options: Parameters<typeof $fetch>[1] = {}
  ): Promise<T> {
    try {
      return await $fetch<T>(`${baseURL}${path}`, {
        ...options,
        headers: { ...authHeaders(), ...(options.headers as object | undefined) }
      }) as Promise<T>
    } catch (err: unknown) {
      const status = (err as { response?: { status?: number } })?.response?.status
      if (status === 401) {
        await handleUnauthorized()
      }
      throw err
    }
  }

  /**
   * Download a file from the API (handles binary responses with auth headers).
   */
  async function apiDownload(path: string, fileName: string, params: Record<string, string> = {}) {
    const query = new URLSearchParams(params).toString()
    const url = `${baseURL}${path}${query ? `?${query}` : ''}`

    const response = await fetch(url, { headers: authHeaders() })

    if (!response.ok) {
      if (response.status === 401) await handleUnauthorized()
      throw new Error(`Download failed: ${response.statusText}`)
    }

    const blob = await response.blob()
    const anchor = document.createElement('a')
    anchor.href = URL.createObjectURL(blob)
    anchor.download = fileName
    anchor.click()
    URL.revokeObjectURL(anchor.href)
  }

  return { apiFetch, apiCall, apiDownload, baseURL, authHeaders }
}
