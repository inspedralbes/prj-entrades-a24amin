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
        SHÔKO<span>CINEMA</span>
      </NuxtLink>

      <nav class="nav-links">
        <NuxtLink to="/" class="nav-link">Cartellera</NuxtLink>
        
        <template v-if="!auth.isLoggedIn">
          <NuxtLink to="/login" class="nav-link btn-auth">Accedir</NuxtLink>
        </template>
        
        <template v-else>
          <NuxtLink v-if="auth.user?.is_admin" to="/admin" class="nav-link admin-link">Admin</NuxtLink>
          <NuxtLink to="/tickets" class="nav-link">Les meves entrades</NuxtLink>
          <div class="header-right">
            <SocketStatus class="status-badge" />
            <div class="user-menu">
              <span class="user-name">{{ auth.user?.name }}</span>
              <button @click="logout" class="logout-btn">Sortir</button>
            </div>
          </div>
        </template>
      </nav>
    </div>
  </header>
</template>

<style scoped>
.app-header {
  background-color: #000;
  color: white;
  padding: 1.2rem 2rem;
  border-bottom: 1px solid #111;
  position: sticky;
  top: 0;
  z-index: 100;
  backdrop-filter: blur(10px);
  background: rgba(0, 0, 0, 0.8);
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
  font-weight: 900;
  text-decoration: none;
  color: white;
  letter-spacing: 2px;
  text-transform: uppercase;
}

.logo span {
  color: #ff5500;
  margin-left: 2px;
}

.nav-links {
  display: flex;
  align-items: center;
  gap: 2rem;
}

.nav-link {
  color: #888;
  text-decoration: none;
  font-weight: 800;
  text-transform: uppercase;
  font-size: 0.75rem;
  letter-spacing: 1.5px;
  transition: all 0.2s ease;
}

.nav-link:hover, .nav-link.router-link-active:not(.btn-auth) {
  color: #fff;
}

.nav-link.btn-auth {
  background: #fff;
  color: #000 !important;
  padding: 0.6rem 1.2rem;
  border-radius: 8px;
  opacity: 1 !important;
}

.btn-auth:hover {
  background: #ff5500;
  color: #fff;
}

.header-right {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.status-badge {
  opacity: 0.8;
}

.user-menu {
  display: flex;
  align-items: center;
  gap: 1rem;
  border-left: 1px solid #222;
  padding-left: 1.5rem;
}

.user-name {
  font-weight: 900;
  font-size: 0.75rem;
  text-transform: uppercase;
  color: #ff5500;
}

.admin-link {
  color: #00d4ff !important;
  font-weight: 900;
}

.admin-link:hover {
  text-shadow: 0 0 10px #00d4ffaa;
}

.logout-btn {
  background: none;
  border: 1px solid #444;
  color: #444;
  padding: 0.4rem 0.8rem;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 800;
  font-size: 0.65rem;
  text-transform: uppercase;
  transition: all 0.2s;
}

.logout-btn:hover {
  border-color: #fff;
  color: #fff;
}
</style>
