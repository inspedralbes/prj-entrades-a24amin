<script setup>
import { useAuthStore } from '~/stores/auth'

const auth = useAuthStore()
const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const errorMsg = ref('')

const handleRegister = async () => {
  if (password.value !== password_confirmation.value) {
    errorMsg.value = 'Les contrasenyes no coincideixen'
    return
  }

  try {
    await auth.register(name.value, email.value, password.value, password_confirmation.value)
    navigateTo('/')
  } catch (err) {
    errorMsg.value = 'Error en el registre. Revisa les dades.'
  }
}
</script>

<template>
  <div class="auth-page">
    <div class="auth-card">
      <h2>Registra't</h2>
      <form @submit.prevent="handleRegister">
        <div class="form-group">
          <label>Nom complet</label>
          <input v-model="name" type="text" required placeholder="La teva identitat" />
        </div>
        <div class="form-group">
          <label>Email</label>
          <input v-model="email" type="email" required placeholder="tu@email.com" />
        </div>
        <div class="form-group">
          <label>Contrasenya</label>
          <input v-model="password" type="password" required placeholder="********" />
        </div>
        <div class="form-group">
          <label>Confirma Contrasenya</label>
          <input v-model="password_confirmation" type="password" required placeholder="********" />
        </div>
        <div v-if="errorMsg" class="error">{{ errorMsg }}</div>
        <button type="submit" class="btn-auth">Crea el compte</button>
      </form>
      <p class="switch-auth">
        Ja tens compte? <NuxtLink to="/login">Inicia sessió</NuxtLink>
      </p>
    </div>
  </div>
</template>

<style scoped>
/* Mateixos estils que login */
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
  margin-bottom: 1.2rem;
}

label {
  display: block;
  margin-bottom: 0.4rem;
  font-weight: 600;
}

input {
  width: 100%;
  padding: 0.7rem;
  border: 1px solid #ddd;
  border-radius: 4px;
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
