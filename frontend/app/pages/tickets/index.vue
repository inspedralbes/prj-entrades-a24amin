<script setup>
import { useAuthStore } from '~/stores/auth'

const auth = useAuthStore()
const config = useRuntimeConfig()
const orders = ref([])
const loading = ref(true)

onMounted(async () => {
  if (!auth.isLoggedIn) {
    return navigateTo('/login')
  }

  try {
    orders.value = await $fetch(`${config.public.apiBase}/orders`, {
      headers: {
        Authorization: `Bearer ${auth.token}`
      }
    })
  } catch (err) {
    console.error('Error fetching orders:', err)
  } finally {
    loading.value = false
  }
})

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('ca-ES', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}
</script>

<template>
  <div class="tickets-page container">
    <h1 class="page-title">Les meves entrades</h1>

    <div v-if="loading" class="loader">Carregant el teu historial...</div>

    <div v-else-if="orders.length === 0" class="empty-state">
      <div class="empty-icon">🎫</div>
      <p>Encara no has comprat cap entrada.</p>
      <NuxtLink to="/" class="btn-primary">EXPLORAR ESDEVENIMENTS</NuxtLink>
    </div>

    <div v-else class="orders-grid">
      <div v-for="order in orders" :key="order.id" class="order-card">
        <div class="order-header">
          <div class="header-left">
            <span class="order-number">{{ order.order_number }}</span>
            <span class="order-date">{{ formatDate(order.created_at) }}</span>
          </div>
          <span class="order-status" :class="order.status">{{ order.status }}</span>
        </div>

        <div class="order-body">
          <div class="event-details">
            <h3>{{ order.event?.name }}</h3>
            <p class="location">📍 {{ order.event?.location }}</p>
            <p class="event-date">📅 {{ formatDate(order.event?.event_date) }}</p>
          </div>

          <div class="tickets-stack">
            <div v-for="ticket in order.tickets" :key="ticket.id" class="compact-ticket">
              <div class="ticket-info">
                <span class="seat-info">Fila {{ ticket.seat.row }}, Seient {{ String.fromCharCode(64 + ticket.seat.col) }}</span>
                <span class="zone-name">{{ ticket.seat.zone?.name }}</span>
              </div>
              <div class="ticket-code">{{ ticket.ticket_code }}</div>
            </div>
          </div>
        </div>

        <div class="order-footer">
          <span class="total-label">Total Pagat</span>
          <span class="total-amount">{{ order.total_price }}€</span>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.tickets-page {
  padding: 4rem 1.5rem;
  min-height: 80vh;
}

.page-title {
  font-size: 2.5rem;
  font-weight: 950;
  text-transform: uppercase;
  letter-spacing: -1px;
  margin-bottom: 3rem;
  border-bottom: 1px solid #111;
  padding-bottom: 1rem;
}

.loader {
  text-align: center;
  padding: 5rem;
  color: #444;
  text-transform: uppercase;
  font-weight: 900;
  letter-spacing: 3px;
}

.empty-state {
  text-align: center;
  padding: 5rem;
  background: #050505;
  border-radius: 32px;
  border: 1px solid #111;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 1.5rem;
  opacity: 0.2;
}

.empty-state p {
  color: #666;
  margin-bottom: 2rem;
  font-size: 1.1rem;
}

.btn-primary {
  display: inline-block;
  background: #fff;
  color: #000;
  text-decoration: none;
  padding: 1rem 2rem;
  border-radius: 12px;
  font-weight: 900;
  text-transform: uppercase;
  transition: all 0.2s;
}

.btn-primary:hover {
  background: #ff5500;
  color: #fff;
}

.orders-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
  gap: 2rem;
}

@media (max-width: 600px) {
  .orders-grid { grid-template-columns: 1fr; }
}

.order-card {
  background: #0a0a0a;
  border: 1px solid #1a1a1a;
  border-radius: 24px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transition: transform 0.3s ease;
}

.order-card:hover {
  transform: translateY(-5px);
  border-color: #333;
}

.order-header {
  padding: 1.5rem;
  background: #111;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-left {
  display: flex;
  flex-direction: column;
}

.order-number {
  font-weight: 900;
  font-size: 0.8rem;
  color: #fff;
}

.order-date {
  font-size: 0.7rem;
  color: #666;
  text-transform: uppercase;
  margin-top: 0.2rem;
}

.order-status {
  font-size: 0.65rem;
  font-weight: 900;
  text-transform: uppercase;
  padding: 0.3rem 0.6rem;
  border-radius: 4px;
}

.order-status.paid { background: #00ff8822; color: #00ff88; }

.order-body {
  padding: 2rem;
  flex: 1;
}

.event-details h3 {
  margin: 0;
  font-weight: 900;
  text-transform: uppercase;
  color: #ff5500;
  font-size: 1.2rem;
}

.location, .event-date {
  font-size: 0.85rem;
  color: #888;
  margin: 0.5rem 0 0 0;
}

.tickets-stack {
  margin-top: 2rem;
  border-top: 1px solid #111;
  padding-top: 1.5rem;
}

.compact-ticket {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #050505;
  padding: 1rem;
  border-radius: 12px;
  margin-bottom: 0.8rem;
  border: 1px solid #111;
}

.seat-info {
  display: block;
  font-weight: 800;
  font-size: 0.9rem;
}

.zone-name {
  display: block;
  font-size: 0.7rem;
  color: #555;
  text-transform: uppercase;
}

.ticket-code {
  font-family: monospace;
  font-size: 0.8rem;
  color: #333;
  letter-spacing: 1px;
}

.order-footer {
  padding: 1.5rem 2rem;
  border-top: 1px solid #111;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.total-label {
  font-weight: 900;
  text-transform: uppercase;
  font-size: 0.75rem;
  color: #444;
}

.total-amount {
  font-size: 1.5rem;
  font-weight: 900;
  color: #fff;
}
</style>
