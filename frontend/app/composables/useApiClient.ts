/**
 * Provides a thin wrapper around $fetch and useFetch that automatically
 * injects the Bearer token from the user session. Intended for use in
 * client-side (SSR=false) pages under /admin.
 */
export function useApiClient() {
  const { user } = useUserSession()
  const config = useRuntimeConfig()
  const baseURL = config.public.apiBase as string

  function authHeaders(): Record<string, string> {
    const u = user.value as Record<string, unknown> | null
    const headers: Record<string, string> = {}
    if (u?.access_token) headers['Authorization'] = `Bearer ${u.access_token as string}`
    const tenantId = (u?.user as Record<string, string> | null)?.tenant_id
    if (tenantId) headers['X-Tenant'] = tenantId
    return headers
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
      headers: computed(() => ({ ...authHeaders(), ...(options.headers as object | undefined) }))
    } as any)
  }

  /**
   * One-shot $fetch wrapper for mutations (POST, PUT, DELETE).
   */
  async function apiCall<T = unknown>(
    path: string,
    options: Parameters<typeof $fetch>[1] = {}
  ): Promise<T> {
    return $fetch<T>(`${baseURL}${path}`, {
      ...options,
      headers: { ...authHeaders(), ...(options.headers as object | undefined) }
    })
  }

  return { apiFetch, apiCall, baseURL, authHeaders }
}
