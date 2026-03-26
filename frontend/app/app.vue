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
    <footer class="app-footer">
      <div class="container">
        <p>&copy; 2026 Ticket Platform Clone. DAW 2 Project.</p>
      </div>
    </footer>
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
  background-color: var(--color-1);
  color: var(--color-4);
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.app-wrapper {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.app-main {
  flex: 1;
}

.app-footer {
  background-color: var(--color-4);
  color: #aaa;
  padding: 2rem 0;
  text-align: center;
  font-size: 0.9rem;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1.5rem;
}

/* Utilitats globals */
.error-msg {
  color: #e74c3c;
  padding: 1rem;
  background: #fdeaea;
  border-radius: 4px;
  margin: 1rem 0;
}
</style>
