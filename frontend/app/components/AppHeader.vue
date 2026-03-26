<script setup>
import { useAuthStore } from '~/stores/auth'

const auth = useAuthStore()

const logout = async () => {
  await auth.logout()
  navigateTo('/')
}
</script>

<template>
  <header class="app-header">
    <div class="header-content">
      <NuxtLink to="/" class="logo">
        TICKET<span>MASTER</span> CLONE
      </NuxtLink>

      <div class="search-bar">
        <input type="text" placeholder="Busca per artista, esdeveniment o recinte" />
        <button><i class="search-icon">🔍</i></button>
      </div>

      <nav class="nav-links">
        <template v-if="!auth.isLoggedIn">
          <NuxtLink to="/login" class="nav-link">Accede/Regístrate</NuxtLink>
        </template>
        <template v-else>
          <div class="user-menu">
            <span class="user-name">{{ auth.user?.name }}</span>
            <button @click="logout" class="nav-link logout-btn">Sortir</button>
          </div>
        </template>
      </nav>
    </div>
  </header>
</template>

<style scoped>
.app-header {
  background-color: var(--color-3); /* Teal */
  color: white;
  padding: 0.8rem 1.5rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.header-content {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.logo {
  font-size: 1.5rem;
  font-weight: 800;
  text-decoration: none;
  color: white;
  letter-spacing: -1px;
}

.logo span {
  font-weight: 300;
}

.search-bar {
  display: flex;
  flex: 1;
  max-width: 600px;
  margin: 0 2rem;
  background-color: white;
  border-radius: 4px;
  overflow: hidden;
}

.search-bar input {
  flex: 1;
  border: none;
  padding: 0.6rem 1rem;
  font-size: 0.9rem;
  outline: none;
}

.search-bar button {
  background-color: #004fb0; /* Accent blue for search like ticketmaster */
  border: none;
  padding: 0 1.2rem;
  cursor: pointer;
}

.nav-links {
  display: flex;
  align-items: center;
}

.nav-link {
  color: white;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.9rem;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  transition: background 0.2s;
}

.nav-link:hover {
  background-color: rgba(255,255,255,0.1);
}

.user-menu {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.user-name {
  font-weight: bold;
}

.logout-btn {
  background: none;
  border: 1px solid white;
  cursor: pointer;
}
</style>
