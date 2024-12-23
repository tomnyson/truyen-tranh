import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null
  }),
  actions: {
    setAuth(user, token) {
      this.user = user
      this.token = token
    },
    clearAuth() {
      this.user = null
      this.token = null
    }
  }
})
