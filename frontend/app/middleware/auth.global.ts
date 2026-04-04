export default defineNuxtRouteMiddleware((to) => {
  const { loggedIn } = useUserSession()

  if (!loggedIn.value && to.path !== '/auth/login') {
    return navigateTo('/auth/login')
  }

  if (loggedIn.value && to.path === '/auth/login') {
    return navigateTo('/')
  }
})
