export default defineNuxtPlugin(async (nuxtApp) => {
    const authStore = useAuthStore()

    // Si estem al client i tenim token, verifiquem l'usuari
    if (import.meta.client && authStore.token) {
        await authStore.fetchUser()
    }
})
