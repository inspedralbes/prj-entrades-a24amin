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
  color: #fff;
  text-decoration: none;
  font-size: 1.2rem;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 2px;
}

.nav-link {
  color: #fff;
  text-decoration: none;
  font-weight: 700;
  text-transform: uppercase;
  font-size: 0.8rem;
  letter-spacing: 1px;
  transition: opacity 0.3s;
}

.nav-link:hover {
  opacity: 0.7;
}

.btn-auth {
  background-color: #fff;
  color: #000;
.user-name {
  font-weight: bold;
}

.logout-btn {
  background: none;
  border: 1px solid white;
  cursor: pointer;
}
</style>
