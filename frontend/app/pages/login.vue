<script setup>
import { useAuthStore } from '~/stores/auth'

const auth = useAuthStore()
const email = ref('')
const password = ref('')
const errorMsg = ref('')

const handleLogin = async () => {
  try {
    await auth.login(email.value, password.value)
    navigateTo('/')
  } catch (err) {
    errorMsg.value = 'Email o contrasenya incorrectes'
  }
}
</script>

<template>
  <div class="auth-page">
    <div class="auth-card">
      <h2>Inicia Sessió</h2>
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label>Email</label>
          <input v-model="email" type="email" required placeholder="tu@email.com" />
        </div>
        <div class="form-group">
          <label>Contrasenya</label>
          <input v-model="password" type="password" required placeholder="********" />
        </div>
        <div v-if="errorMsg" class="error">{{ errorMsg }}</div>
        <button type="submit" class="btn-auth">Entra</button>
      </form>
      <p class="switch-auth">
        No tens compte? <NuxtLink to="/register">Registra't aquí</NuxtLink>
      </p>
    </div>
  </div>
</template>

<style scoped>
.auth-page {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2rem;
  background-color: var(--color-1);
}

.auth-card {
  background: white;
  padding: 2.5rem;
  border-radius: 8px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.1);
  width: 100%;
  max-width: 400px;
}

h2 {
  text-align: center;
  margin-bottom: 2rem;
  color: var(--color-3);
}

.form-group {
  margin-bottom: 1.5rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: var(--color-4);
}

input {
  width: 100%;
  padding: 0.8rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.btn-auth {
  width: 100%;
  padding: 1rem;
  background-color: var(--color-3);
  color: white;
  border: none;
  border-radius: 4px;
  font-weight: bold;
  cursor: pointer;
  font-size: 1.1rem;
  margin-top: 1rem;
}

.error {
  color: #e74c3c;
  font-size: 0.9rem;
  margin-bottom: 1rem;
  text-align: center;
}

.switch-auth {
  text-align: center;
  margin-top: 1.5rem;
  font-size: 0.9rem;
}

.switch-auth a {
  color: var(--color-3);
  font-weight: bold;
  text-decoration: none;
}
</style>
