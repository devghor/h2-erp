export const useCompanyStore = defineStore('company', () => {
  const companyId = ref<string | null>(null)

  function setCompanyId(id: string | null) {
    companyId.value = id
  }

  return { companyId, setCompanyId }
})
