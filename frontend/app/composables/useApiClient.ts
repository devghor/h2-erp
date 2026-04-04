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
    const token = (user.value as Record<string, string> | null)?.access_token
    return token ? { Authorization: `Bearer ${token}` } : {}
  }

  /**
   * Reactive useFetch wrapper — headers refresh automatically when session changes.
   */
  function apiFetch<T>(
    path: string | (() => string),
    options: Parameters<typeof useFetch>[1] = {}
  ) {
    const url = typeof path === 'function'
      ? computed(() => `${baseURL}${path()}`)
      : `${baseURL}${path}`

    return useFetch<T>(url as string, {
      ...options,
      headers: computed(() => ({ ...authHeaders(), ...(options.headers as object | undefined) }))
    } as Parameters<typeof useFetch>[1])
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
