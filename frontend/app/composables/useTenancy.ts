import type { Tenant, TenantListResponse } from '~/types/index.d.ts'

export function useTenancy() {
  const { user, fetch: fetchSession } = useUserSession()
  const { apiCall } = useApiClient()
  const toast = useToast()

  const tenants = ref<Tenant[]>([])
  const loading = ref(false)
  const switching = ref(false)

  const currentTenantId = computed(() => {
    const u = user.value as Record<string, unknown> | null
    return (u?.user as Record<string, string> | null)?.tenant_id ?? null
  })

  const currentTenant = computed<Tenant | null>(() => {
    if (!currentTenantId.value) return null
    return tenants.value.find((t) => t.id === currentTenantId.value) ?? null
  })

  async function fetchTenants() {
    loading.value = true
    try {
      const response = await apiCall<TenantListResponse>('/tenancy')
      tenants.value = response.data
    } catch {
      // silently fail — menu stays empty
    } finally {
      loading.value = false
    }
  }

  async function switchTenant(tenantId: string) {
    if (switching.value || tenantId === currentTenantId.value) return

    switching.value = true
    try {
      await apiCall('/tenancy/switch', {
        method: 'POST',
        body: { tenant_id: tenantId }
      })
      await fetchSession()
    } catch (err: unknown) {
      const message = (err as { data?: { message?: string } })?.data?.message ?? 'Failed to switch tenant'
      toast.add({ title: 'Switch failed', description: message, color: 'error' })
    } finally {
      switching.value = false
    }
  }

  return { tenants, loading, switching, currentTenant, currentTenantId, fetchTenants, switchTenant }
}
