<script setup>
import { useAuthStore } from '~/stores/auth'

const auth = useAuthStore()

// Carreguem l'usuari si ja tenim un token guardat
onMounted(() => {
  if (auth.token) {
    auth.fetchUser()
  }
})
</script>

<template>
  <div class="app-wrapper">
    <AppHeader />
    <main class="app-main">
      <NuxtPage />
    </main>
    <AppFooter />
  </div>
</template>

<style>
/* Estils globals ja que aquest és el component arrel */
:root {
  scroll-behavior: smooth;
}

body {
  margin: 0;
  padding: 0;
  background-color: #000;
  color: #fff;
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.app-wrapper {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: #000;
}

.app-main {
  flex: 1;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1.5rem;
}

/* Utilitats globals */
.error-msg {
  color: #ff5500;
  padding: 1.5rem;
  background: #111;
  border: 1px solid #ff5500;
  border-radius: 8px;
  margin: 2rem 0;
  text-align: center;
  font-weight: bold;
}
</style>
