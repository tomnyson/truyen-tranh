export const useCustomAuth = () => {
    const config = useRuntimeConfig()
    const { signIn, signOut, status, getSession } = useAuth()
    const user = useAuthUser()
    const authStore = useAuthStore()
  
    const login = async (email: string, password: string) => {
      try {
        const result = await signIn('credentials', {
          email,
          password,
          redirect: false
        })
  
        if (result?.error) {
          throw new Error(result.error)
        }
  
        const session = await getSession()
        if (session?.user && session?.accessToken) {
          authStore.setAuth(session.user, session.accessToken)
        }
  
        return result
      } catch (error) {
        console.error('Login error:', error)
        throw error
      }
    }
  
    const register = async (userData: { 
      email: string, 
      password: string, 
      name: string 
    }) => {
      try {
        const response = await $fetch(`${config.public.apiBase}/api/register`, {
          method: 'POST',
          body: userData
        })
  
        if (response) {
          return login(userData.email, userData.password)
        }
      } catch (error) {
        console.error('Registration error:', error)
        throw error
      }
    }
  
    const logout = async () => {
      try {
        const session = await getSession()
        if (session?.accessToken) {
          // Call Laravel logout endpoint
          await $fetch(`${config.public.apiBase}/api/logout`, {
            method: 'POST',
            headers: {
              'Authorization': `Bearer ${session.accessToken}`
            }
          })
        }
  
        await signOut({ redirect: false })
        authStore.clearAuth()
        navigateTo('/login')
      } catch (error) {
        console.error('Logout error:', error)
        throw error
      }
    }
  
    // Custom fetch wrapper with auth header
    const authFetch = async (url: string, options: any = {}) => {
      const session = await getSession()
      if (session?.accessToken) {
        options.headers = {
          ...options.headers,
          'Authorization': `Bearer ${session.accessToken}`
        }
      }
      return $fetch(url, options)
    }
  
    return {
      login,
      register,
      logout,
      authFetch,
      user,
      status,
      getSession
    }
  }