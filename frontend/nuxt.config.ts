// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  modules: [
    '@nuxt/eslint',
    '@nuxt/ui',
    '@vueuse/nuxt',
    '@sidebase/nuxt-auth'
  ],

  devtools: {
    enabled: true
  },

  css: ['~/assets/css/main.css'],

  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000'
    }
  },

  routeRules: {
    '/api/**': {
      cors: true
    }
  },

  auth: {
    baseURL: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000',
    provider: {
      type: 'local',
      endpoints: {
        signIn: { path: '/auth/login', method: 'post' },
        signOut: { path: '/auth/logout', method: 'post' },
        getSession: { path: '/uam/me', method: 'get' }
      },
      token: {
        signInResponseTokenPointer: '/data/access_token',
        type: 'Bearer',
        cookieName: 'auth.token',
        headerName: 'Authorization',
        maxAgeInSeconds: 1800,
        sameSiteAttribute: 'lax',
        secureCookieAttribute: false,
        httpOnlyCookieAttribute: false
      },
      session: {
        dataResponsePointer: '/data'
      }
    },
    globalAppMiddleware: {
      isEnabled: true,
      addDefaultCallbackUrl: '/'
    },
    pages: {
      login: '/auth/login'
    }
  },

  compatibilityDate: '2024-07-11',

  eslint: {
    config: {
      stylistic: {
        commaDangle: 'never',
        braceStyle: '1tbs'
      }
    }
  }
})