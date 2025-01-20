export default defineNuxtConfig({
  app: {
    head: {
      title: 'My Global Title 11',
      meta: [{ name: 'description', content: 'Default description for all pages' }],
      link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }],
    },
  },

  runtimeConfig: {
    apiBaseUrl: process.env.API_BASE_URL || 'http://localhost:8000',
    public: {
      apiBaseUrl: process.env.PUBLIC_API_BASE_URL,
    },
  },
  plugins: ['~/plugins/toast.ts'],

  modules: ['@sidebase/nuxt-auth', '@nuxt/icon'],
  auth: {
    globalAppMiddleware: true,
    enableGlobalAppMiddleware: true,
    baseURL: process.env.NUXT_PUBLIC_API_URL,
    provider: {
      type: 'local',
      endpoints: {
        signIn: { path: '/login', method: 'post' },
        signOut: { path: '/identity/accounts/logout', method: 'get' },
        signUp: { path: '/identity/accounts/register', method: 'post' },
        getSession: { path: '/current-user', method: 'get' },
      },
      pages: {
        login: '/login',
      },
      token: {
        signInResponseTokenPointer: '/accessToken',
        maxAge: 24 * 60 * 60,
      },
      sessionDataType: {},
    },
    enableSessionRefreshPeriodically: 5000,
    enableSessionRefreshOnWindowFocus: true,
    globalMiddlewareOptions: {
      allow404WithoutAuth: true,
      addDefaultCallbackUrl: '/',
    },
    session: {
      maxAge: 24 * 60 * 60, // 1 day in seconds
      updateAge: 3600, // Optional: Prevent session refreshing unless explicitly done
    },
  },
  compatibilityDate: '2024-12-19',
  env: {
    API_BASE_URL: 'https://api.cuuduatin.net',
  },
})
