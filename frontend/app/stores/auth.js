import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: typeof window !== 'undefined' ? localStorage.getItem('token') : null,
    }),

    getters: {
        isLoggedIn: (state) => !!state.token,
    },

    actions: {
        async login(email, password) {
            const config = useRuntimeConfig()
            try {
                const response = await $fetch(`${config.public.apiBase}/login`, {
                    method: 'POST',
                    body: { email, password },
                })

                this.token = response.access_token
                this.user = response.user

                if (typeof window !== 'undefined') {
                    localStorage.setItem('token', this.token)
                }

                return true
            } catch (error) {
                console.error('Login error:', error)
                throw error
            }
        },

        async register(name, email, password, password_confirmation) {
            const config = useRuntimeConfig()
            try {
                const response = await $fetch(`${config.public.apiBase}/register`, {
                    method: 'POST',
                    body: { name, email, password, password_confirmation },
                })

                this.token = response.access_token
                this.user = response.user

                if (typeof window !== 'undefined') {
                    localStorage.setItem('token', this.token)
                }

                return true
            } catch (error) {
                console.error('Registration error:', error)
                throw error
            }
        },

        async logout() {
            const config = useRuntimeConfig()
            try {
                await $fetch(`${config.public.apiBase}/logout`, {
                    method: 'POST',
                    headers: {
                        Authorization: `Bearer ${this.token}`,
                    },
                })
            } catch (error) {
                console.error('Logout error:', error)
            } finally {
                this.token = null
                this.user = null
                if (typeof window !== 'undefined') {
                    localStorage.removeItem('token')
                }
            }
        },

        async fetchUser() {
            if (!this.token) return
            const config = useRuntimeConfig()

            try {
                const user = await $fetch(`${config.public.apiBase}/me`, {
                    headers: {
                        Authorization: `Bearer ${this.token}`,
                    },
                })
                this.user = user
            } catch (error) {
                console.error('Fetch user error:', error)
                this.logout()
            }
        },
    },
})
