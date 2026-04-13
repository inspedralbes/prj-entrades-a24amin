<script setup>
import { useEventStore } from '~/stores/event'
import { useAuthStore } from '~/stores/auth'

const eventStore = useEventStore()
const auth = useAuthStore()
const router = useRouter()

// Protecció de ruta
onMounted(() => {
  if (!auth.isLoggedIn) {
    router.push('/login')
  }
  if (eventStore.selectedSeats.length === 0) {
    router.push('/')
  }
})

const totalPrice = computed(() => {
  return eventStore.selectedSeats.reduce((acc, seat) => {
    // Busquem el preu de la zona que conté el seient
    const zone = eventStore.currentEvent?.zones.find(z => 
      z.seats.some(s => s.id === seat.id)
    )
    return acc + Number(zone?.price || 0)
  }, 0)
})

const isProcessing = ref(false)
const paymentData = ref({
  cardNumber: '',
  expiry: '',
  cvv: '',
  holderName: ''
})

// Watchers per al formatat real-time
watch(() => paymentData.value.cardNumber, (newVal) => {
  // Eliminar qualsevol caràcter que no sigui dígit
  const digits = newVal.replace(/\D/g, '')
  // Agrupar de 4 en 4 amb espais
  const formatted = digits.match(/.{1,4}/g)?.join(' ') || ''
  if (formatted !== newVal) {
    paymentData.value.cardNumber = formatted
  }
})

watch(() => paymentData.value.expiry, (newVal) => {
  const digits = newVal.replace(/\D/g, '')
  let formatted = digits
  if (digits.length > 2) {
    formatted = `${digits.slice(0, 2)}/${digits.slice(2, 4)}`
  }
  if (formatted !== newVal) {
    paymentData.value.expiry = formatted
  }
})

const handleCheckout = async () => {
  // Simple validation
  if (!paymentData.value.cardNumber || !paymentData.value.expiry || !paymentData.value.cvv || !paymentData.value.holderName) {
    alert('Si us plau, omple totes les dades de pagament.')
    return
  }

  isProcessing.value = true
  const config = useRuntimeConfig()
  
  try {
    await $fetch(`${config.public.apiBase}/orders`, {
      method: 'POST',
      body: {
        event_id: eventStore.currentEvent.id,
        seats: eventStore.selectedSeats.map(s => s.id),
        payment_info: {
          last4: paymentData.value.cardNumber.slice(-4),
          holder: paymentData.value.holderName
        }
      },
      headers: {
        Authorization: `Bearer ${auth.token}`
      }
    })
    
    eventStore.clearSelection()
    router.push('/checkout/success')
  } catch (err) {
    alert(err.data?.message || 'Error al processar la compra.')
  } finally {
    isProcessing.value = false
  }
}

const formatTime = (seconds) => {
  const m = Math.floor(seconds / 60)
  const s = seconds % 60
  return `${m}:${s < 10 ? '0' : ''}${s}`
}
</script>

<template>
  <div class="checkout-page container">
    <div class="checkout-card">
      <h1 class="title">Confirmació de Compra</h1>
      
      <div v-if="eventStore.currentEvent" class="event-summary">
        <h2>{{ eventStore.currentEvent.name }}</h2>
        <p class="location">{{ eventStore.currentEvent.location }}</p>
      </div>

      <div class="timer-warning">
        <span class="icon">⚠️</span> Tens <strong>{{ formatTime(eventStore.timeLeft) }}</strong> per finalitzar la compra.
      </div>

      <div class="seats-list">
        <div v-for="seat in eventStore.selectedSeats" :key="seat.id" class="seat-item">
          <div class="seat-info">
            <span class="seat-label">Fila {{ seat.row }}, Seient {{ String.fromCharCode(64 + seat.col) }}</span>
            <span class="seat-zone">Entrada Individual</span>
          </div>
          <span class="seat-price">{{ eventStore.currentEvent?.zones.find(z => z.seats.some(s => s.id === seat.id))?.price }}€</span>
        </div>
      </div>

      <div class="total-section">
        <span class="total-label">Subtotal</span>
        <span class="total-amount">{{ totalPrice }}€</span>
      </div>

      <!-- Payment Section -->
      <div class="payment-section">
        <h3 class="payment-title">Mètode de Pagament</h3>
        <div class="payment-form">
          <div class="form-group full">
            <label>NÚMERO DE TARGETA</label>
            <input v-model="paymentData.cardNumber" type="text" placeholder="0000 0000 0000 0000" maxlength="19" />
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>EXPIRACIÓ</label>
              <input v-model="paymentData.expiry" type="text" placeholder="MM/YY" maxlength="5" />
            </div>
            <div class="form-group">
              <label>CVV</label>
              <input v-model="paymentData.cvv" type="password" placeholder="***" maxlength="3" />
            </div>
          </div>
          <div class="form-group full">
            <label>TITULAR DE LA TARGETA</label>
            <input v-model="paymentData.holderName" type="text" placeholder="Nom complet" />
          </div>
        </div>
      </div>

      <div class="actions-wrapper">
        <button 
          class="confirm-btn" 
          :disabled="isProcessing"
          @click="handleCheckout">
          {{ isProcessing ? 'PROCESSANT...' : 'FINALITZAR COMPRA' }}
        </button>
        <NuxtLink to="/" class="back-link">← TORNAR AL MAPA</NuxtLink>
      </div>
    </div>
  </div>
</template>

<style scoped>
.checkout-page {
  padding: 4rem 1.5rem;
  display: flex;
  justify-content: center;
}

.checkout-card {
  background: #0a0a0a;
  border: 1px solid #1a1a1a;
  padding: 3rem;
  border-radius: 32px;
  width: 100%;
  max-width: 600px;
  box-shadow: 0 30px 60px rgba(0,0,0,0.6);
  position: relative;
}

.title {
  margin: 0 0 2rem 0;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 2px;
  font-size: 1.8rem;
  text-align: center;
}

.event-summary {
  text-align: center;
  margin-bottom: 2rem;
}

.event-summary h2 {
  color: #ff5500;
  margin: 0;
  text-transform: uppercase;
  font-weight: 800;
}

.location {
  color: #666;
  font-size: 0.9rem;
  margin: 0.5rem 0 0 0;
}

.timer-warning {
  background: #111;
  border: 1px solid #222;
  padding: 1rem;
  border-radius: 12px;
  text-align: center;
  margin-bottom: 2rem;
  font-size: 0.85rem;
  color: #888;
}

.timer-warning strong {
  color: #fff;
}

.seats-list {
  border-bottom: 1px solid #222;
  margin-bottom: 1.5rem;
}

.seat-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.2rem 0;
  border-top: 1px solid #111;
}

.seat-label {
  display: block;
  font-weight: 700;
  font-size: 1rem;
}

.seat-zone {
  display: block;
  font-size: 0.75rem;
  color: #666;
  text-transform: uppercase;
  margin-top: 0.2rem;
}

.seat-price {
  font-weight: 800;
  color: #fff;
}

.total-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2.5rem;
  padding-bottom: 1rem;
  border-bottom: 1px dashed #222;
}

.total-label {
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: #666;
}

.total-amount {
  font-size: 2rem;
  font-weight: 900;
  color: #ff5500;
}

/* Payment Form Styles */
.payment-section {
  margin-bottom: 3rem;
}

.payment-title {
  font-size: 0.8rem;
  text-transform: uppercase;
  color: #444;
  margin-bottom: 1.5rem;
  letter-spacing: 2px;
  font-weight: 900;
}

.payment-form {
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.2rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-group label {
  font-size: 0.65rem;
  font-weight: 900;
  color: #666;
  letter-spacing: 1px;
}

.form-group input {
  background: #111;
  border: 1px solid #222;
  padding: 1rem;
  border-radius: 12px;
  color: #fff;
  font-family: inherit;
  font-weight: 700;
  transition: all 0.2s;
}

.form-group input:focus {
  border-color: #ff5500;
  outline: none;
  background: #161616;
}

/* Actions Wrapper to center the button */
.actions-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1.5rem;
}

.confirm-btn {
  width: 100%;
  max-width: 350px; /* Centered and smaller as requested */
  background: #fff;
  color: #000;
  border: none;
  padding: 1.2rem;
  border-radius: 12px;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 2px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.confirm-btn:hover:not(:disabled) {
  background: #ff5500;
  color: #fff;
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(255, 85, 0, 0.3);
}

.confirm-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.back-link {
  color: #444;
  text-decoration: none;
  font-size: 0.8rem;
  font-weight: 700;
  letter-spacing: 1px;
  transition: color 0.2s;
}

.back-link:hover {
  color: #888;
}
</style>
