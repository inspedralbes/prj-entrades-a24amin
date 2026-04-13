<script setup>
import { useAuthStore } from '~/stores/auth'

const auth = useAuthStore()
const email = ref('')
const password = ref('')
const errorMsg = ref('')
const loading = ref(false)

const handleLogin = async () => {
  loading.value = true
  errorMsg.value = ''
  try {
    await auth.login(email.value, password.value)
    navigateTo('/')
  } catch (err) {
    errorMsg.value = 'Email o contrasenya incorrectes'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="auth-page">
    <div class="auth-card">
      <div class="auth-header">
        <span class="cinema-label">Shôko Cinema</span>
        <h1>Inicia Sessió</h1>
        <p class="subtitle">Bentornat al millor cinema de Barcelona</p>
      </div>

      <form @submit.prevent="handleLogin" class="auth-form">
        <div class="form-group">
          <label>Correu Electrònic</label>
          <div class="input-wrapper">
            <span class="icon">✉️</span>
            <input v-model="email" type="email" required placeholder="nom@exemple.com" />
          </div>
        </div>

        <div class="form-group">
          <label>Contrasenya</label>
          <div class="input-wrapper">
            <span class="icon">🔒</span>
            <input v-model="password" type="password" required placeholder="L'entrada de la teva identitat" />
          </div>
        </div>

        <div v-if="errorMsg" class="error-banner">
          <span class="error-icon">⚠️</span>
          {{ errorMsg }}
        </div>

        <button type="submit" class="btn-auth" :disabled="loading">
          {{ loading ? 'VERIFICANT...' : 'ENTRA A LA SALA' }}
        </button>
      </form>

      <div class="auth-footer">
        <p>No tens compte encara?</p>
        <NuxtLink to="/register" class="link-secondary">Registra't per acumular punts</NuxtLink>
      </div>
    </div>
    
    <div class="bg-decoration">
      <div class="glow-1"></div>
      <div class="glow-2"></div>
    </div>
  </div>
</template>

<style scoped>
.auth-page {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #000;
  background-image: 
    radial-gradient(circle at 20% 30%, rgba(255, 85, 0, 0.05) 0%, transparent 50%),
    radial-gradient(circle at 80% 70%, rgba(255, 85, 0, 0.05) 0%, transparent 50%),
    url('https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?q=80&w=2070&auto=format&fit=crop');
  background-size: cover;
  background-position: center;
  position: relative;
  overflow: hidden;
  padding: 2rem;
}

.auth-card {
  width: 100%;
  max-width: 440px;
  background: rgba(10, 10, 10, 0.7);
  backdrop-filter: blur(25px);
  padding: 4rem 3rem;
  border-radius: 40px;
  border: 1px solid rgba(255, 255, 255, 0.05);
  box-shadow: 0 50px 100px rgba(0,0,0,0.8);
  position: relative;
  z-index: 10;
}

.auth-header {
  text-align: center;
  margin-bottom: 3rem;
}

.cinema-label {
  color: #ff5500;
  text-transform: uppercase;
  font-weight: 900;
  font-size: 0.7rem;
  letter-spacing: 5px;
  display: block;
  margin-bottom: 1rem;
}

.auth-header h1 {
  font-size: 2.2rem;
  font-weight: 950;
  text-transform: uppercase;
  letter-spacing: -1px;
  margin: 0;
  color: #fff;
}

.subtitle {
  color: #666;
  font-size: 0.9rem;
  margin-top: 0.5rem;
}

.auth-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group label {
  display: block;
  font-size: 0.75rem;
  font-weight: 800;
  text-transform: uppercase;
  color: #444;
  margin-bottom: 0.8rem;
  letter-spacing: 1px;
}

.input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.input-wrapper .icon {
  position: absolute;
  left: 1.2rem;
  font-size: 1rem;
  opacity: 0.3;
}

.input-wrapper input {
  width: 100%;
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.08);
  padding: 1.2rem 1.2rem 1.2rem 3rem;
  border-radius: 16px;
  color: #fff;
  font-size: 0.95rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.input-wrapper input:focus {
  background: rgba(255, 255, 255, 0.06);
  border-color: #ff5500;
  outline: none;
  box-shadow: 0 0 20px rgba(255, 85, 0, 0.1);
}

.error-banner {
  background: rgba(231, 76, 60, 0.1);
  border: 1px solid rgba(231, 76, 60, 0.2);
  color: #e74c3c;
  padding: 1rem;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 0.8rem;
}

.btn-auth {
  width: 100%;
  padding: 1.3rem;
  background: #fff;
  color: #000;
  border: none;
  border-radius: 16px;
  font-weight: 950;
  text-transform: uppercase;
  letter-spacing: 2px;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  margin-top: 1rem;
}

.btn-auth:hover:not(:disabled) {
  background: #ff5500;
  color: #fff;
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(255, 85, 0, 0.3);
}

.btn-auth:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.auth-footer {
  text-align: center;
  margin-top: 3rem;
  padding-top: 2rem;
  border-top: 1px solid rgba(255, 255, 255, 0.05);
}

.auth-footer p {
  font-size: 0.85rem;
  color: #444;
  margin-bottom: 0.5rem;
}

.link-secondary {
  color: #ff5500;
  text-decoration: none;
  font-weight: 900;
  text-transform: uppercase;
  font-size: 0.75rem;
  letter-spacing: 1px;
}

.link-secondary:hover {
  text-decoration: underline;
}

.bg-decoration div {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  z-index: 1;
}

.glow-1 {
  width: 400px;
  height: 400px;
  background: rgba(255, 85, 0, 0.1);
  top: -100px;
  left: -100px;
}

.glow-2 {
  width: 500px;
  height: 500px;
  background: rgba(255, 85, 0, 0.05);
  bottom: -200px;
  right: -200px;
}
</style>
