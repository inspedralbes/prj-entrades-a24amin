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
.login-container {
  min-height: calc(100vh - 80px);
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #000;
  background-image: linear-gradient(rgba(0,0,0,0.85), rgba(0,0,0,0.85)), url('https://shoko.biz/cdn/shop/files/shoko-barcelona-club-1.jpg?v=1631527015');
  background-size: cover;
  background-position: center;
  padding: 2rem;
}

.login-card {
  width: 100%;
  max-width: 400px;
  background-color: rgba(10, 10, 10, 0.9);
  backdrop-filter: blur(10px);
  padding: 4rem 3rem;
  border-radius: 24px;
  border: 1px solid #222;
  color: #fff;
}

h1 {
  color: #fff;
  text-align: center;
  margin-bottom: 2.5rem;
  text-transform: uppercase;
  letter-spacing: 4px;
  font-weight: 900;
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

.input-group input {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid #333;
  color: white;
  padding: 1rem;
  width: 100%;
  border-radius: 12px;
  margin-bottom: 0.5rem;
}

.input-group input:focus {
  border-color: #fff;
  outline: none;
}

.btn-primary {
  width: 100%;
  padding: 1.2rem;
  background-color: #fff;
  color: #000;
  border: none;
  border-radius: 50px;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 1px;
  cursor: pointer;
  transition: transform 0.2s;
}

.btn-primary:hover:not(:disabled) {
  transform: scale(1.02);
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
