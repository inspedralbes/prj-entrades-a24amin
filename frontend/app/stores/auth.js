import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: useCookie('user').value || null,
        token: useCookie('token').value || null,
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

                const tokenCookie = useCookie('token')
                const userCookie = useCookie('user')
                tokenCookie.value = this.token
                userCookie.value = this.user

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

                const tokenCookie = useCookie('token')
                const userCookie = useCookie('user')
                tokenCookie.value = this.token
                userCookie.value = this.user

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
                const tokenCookie = useCookie('token')
                const userCookie = useCookie('user')
                tokenCookie.value = null
                userCookie.value = null
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
                const userCookie = useCookie('user')
                userCookie.value = user
            } catch (error) {
                console.error('Fetch user error:', error)
                this.logout()
            }
        },
    },
})
