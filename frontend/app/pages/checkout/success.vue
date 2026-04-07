<script setup>
const router = useRouter()

const handleGoToTickets = () => {
    router.push('/tickets')
}

const handleGoHome = () => {
    router.push('/')
}
</script>

<template>
  <div class="success-page container">
    <div class="success-card">
      <div class="icon-wrapper">
        <div class="check-icon">✓</div>
      </div>
      
      <h1 class="title">Compra Finalitzada!</h1>
      <p class="subtitle">Enhorabona! Ja tens les teves entrades per gaudir de la millor experiència cinematogràfica.</p>
      
      <div class="info-box">
        <p>T'hem enviat un correu de confirmació amb els detalls de la teva compra.</p>
      </div>

      <div class="actions">
        <button @click="handleGoToTickets" class="btn-primary">VEURE LES MEVES ENTRADES</button>
        <button @click="handleGoHome" class="btn-secondary">TORNAR A LA CARTELLERA</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.success-page {
  min-height: 80vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.success-card {
  background: #0a0a0a;
  border: 1px solid #1a1a1a;
  padding: 4rem;
  border-radius: 40px;
  text-align: center;
  max-width: 600px;
  width: 100%;
  box-shadow: 0 40px 80px rgba(0,0,0,0.8);
  animation: slideUp 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

.icon-wrapper {
  width: 100px;
  height: 100px;
  background: #ff5500;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 2.5rem auto;
  box-shadow: 0 15px 30px rgba(255, 85, 0, 0.3);
}

.check-icon {
  color: #fff;
  font-size: 3rem;
  font-weight: 900;
}

.title {
  font-size: 2.5rem;
  font-weight: 950;
  text-transform: uppercase;
  letter-spacing: -1px;
  margin-bottom: 1rem;
}

.subtitle {
  color: #888;
  font-size: 1.1rem;
  line-height: 1.6;
  margin-bottom: 3rem;
}

.info-box {
  background: #111;
  padding: 1.5rem;
  border-radius: 20px;
  border: 1px solid #1a1a1a;
  margin-bottom: 3rem;
  font-size: 0.9rem;
  color: #666;
}

.actions {
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}

.btn-primary {
  background: #fff;
  color: #000;
  border: none;
  padding: 1.4rem;
  border-radius: 16px;
  font-weight: 950;
  text-transform: uppercase;
  letter-spacing: 2px;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-primary:hover {
  background: #ff5500;
  color: #fff;
  transform: translateY(-3px);
  box-shadow: 0 10px 20px rgba(255, 85, 0, 0.2);
}

.btn-secondary {
  background: transparent;
  color: #444;
  border: 1px solid #222;
  padding: 1.2rem;
  border-radius: 16px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 1px;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-secondary:hover {
  border-color: #666;
  color: #888;
}
</style>
