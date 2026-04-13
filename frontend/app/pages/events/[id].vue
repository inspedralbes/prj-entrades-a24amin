<script setup>
import { useEventStore } from '~/stores/event'

const route = useRoute()
const eventId = route.params.id
const eventStore = useEventStore()

// Carreguem la sessió a l'store
onMounted(async () => {
  await eventStore.fetchEvent(eventId)
})

const totalPrice = computed(() => {
  return eventStore.selectedSeats.reduce((acc, seat) => {
    const zone = eventStore.currentEvent?.zones.find(z => 
      z.seats.some(s => s.id === seat.id)
    )
    return acc + Number(zone?.price || 0)
  }, 0)
})

const formatTime = (seconds) => {
  const m = Math.floor(seconds / 60)
  const s = seconds % 60
  return `${m}:${s < 10 ? '0' : ''}${s}`
}
</script>

<template>
  <div class="event-detail">
    <div v-if="eventStore.error" class="error-msg">{{ eventStore.error }}</div>
    <div v-else-if="eventStore.loading && !eventStore.currentEvent" class="loading">Preparant la sessió...</div>
    
    <div v-else-if="eventStore.currentEvent" class="container">
      <div class="event-header">
        <div class="header-content">
          <span class="category-badge">Cinema</span>
          <h1>{{ eventStore.currentEvent.name }}</h1>
          <p class="meta">📅 {{ new Date(eventStore.currentEvent.event_date).toLocaleDateString() }} | 📍 {{ eventStore.currentEvent.location }}</p>
        </div>
        <div v-if="eventStore.selectedSeats.length > 0" class="mini-timer">
          <span class="icon">⌛</span> {{ formatTime(eventStore.timeLeft) }}
        </div>
      </div>

      <div class="main-content">
        <div class="map-section">
          <h2>Tria les teves butaques</h2>
          <div class="screen-indicator">PANTALLA</div>
          <EventSeatMap :event="eventStore.currentEvent" />
        </div>

        <aside class="sidebar">
          <div class="selection-card" :class="{ 'empty': eventStore.selectedSeats.length === 0 }">
            <h3>Les teves butaques</h3>
            
            <div v-if="eventStore.selectedSeats.length > 0" class="selected-list">
              <div v-for="seat in eventStore.selectedSeats" :key="seat.id" class="mini-seat">
                <span>Fila {{ seat.row }}, Butaca {{ String.fromCharCode(64 + seat.col) }}</span>
                <strong>{{ eventStore.currentEvent.zones.find(z => z.seats.some(s => s.id === seat.id))?.price }}€</strong>
              </div>
              
              <div class="total">
                <span>TOTAL</span>
                <strong>{{ totalPrice }}€</strong>
              </div>
              
              <NuxtLink to="/checkout" class="btn-primary">RESERVAR I PAGAR</NuxtLink>
            </div>
            
            <div v-else class="empty-state">
              <p>Encara no has triat cap butaca.</p>
              <div class="info-bubble">Pots seleccionar fins a 6 butaques per sessió.</div>
            </div>
          </div>
        </aside>
      </div>

      <NuxtLink to="/" class="back-link">← Torna a la cartellera</NuxtLink>
    </div>
  </div>
</template>

<style scoped>
.event-detail {
  background-color: #000;
  min-height: 100vh;
  color: white;
  padding-bottom: 5rem;
}

.container {
  max-width: 1240px;
  margin: 0 auto;
  padding: 4rem 1.5rem;
}

.event-header {
  margin-bottom: 3rem;
  border-bottom: 1px solid #1a1a1a;
  padding-bottom: 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
}

.category-badge {
  color: #ff5500;
  font-weight: 900;
  text-transform: uppercase;
  font-size: 0.7rem;
  letter-spacing: 2px;
  margin-bottom: 0.5rem;
  display: block;
}

.event-header h1 {
  font-size: 3.5rem;
  font-weight: 950;
  text-transform: uppercase;
  letter-spacing: -2px;
  margin: 0 0 0.5rem 0;
  background: linear-gradient(to bottom, #fff, #888);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.meta {
  color: #666;
  font-size: 0.9rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 2px;
  margin: 0;
}

.mini-timer {
  background: #ff5500;
  color: #fff;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-weight: 900;
  font-size: 1.1rem;
  font-family: monospace;
}

.main-content {
  display: grid;
  grid-template-columns: 1fr 340px;
  gap: 4rem;
}

@media (max-width: 992px) {
  .main-content { grid-template-columns: 1fr; }
}

.screen-indicator {
  width: 80%;
  margin: 0 auto 4rem auto;
  height: 8px;
  background: linear-gradient(to bottom, #333, #111);
  box-shadow: 0 20px 40px rgba(255,255,255,0.05);
  border-radius: 50%;
  text-align: center;
  color: #222;
  font-size: 0.6rem;
  font-weight: 900;
  letter-spacing: 10px;
  padding-top: 20px;
}

.selection-card {
  background: #0a0a0a;
  padding: 2.5rem;
  border-radius: 32px;
  border: 1px solid #1a1a1a;
  position: sticky;
  top: 6rem;
  transition: all 0.3s ease;
}

.selection-card.empty {
  opacity: 0.6;
}

.selection-card h3 {
  text-transform: uppercase;
  letter-spacing: 3px;
  margin-bottom: 2rem;
  font-weight: 950;
  font-size: 0.9rem;
  color: #444;
}

.selected-list {
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}

.mini-seat {
  display: flex;
  justify-content: space-between;
  border-bottom: 1px solid #111;
  padding-bottom: 1rem;
  font-size: 0.9rem;
  font-weight: 700;
}

.total {
  margin-top: 1.5rem;
  display: flex;
  justify-content: space-between;
  font-weight: 950;
  font-size: 1.4rem;
  color: #ff5500;
}

.empty-state {
  text-align: center;
  color: #444;
  padding: 2rem 0;
}

.info-bubble {
  margin-top: 2.5rem;
  font-size: 0.75rem;
  background: #111;
  padding: 1.2rem;
  border-radius: 16px;
  line-height: 1.6;
  color: #666;
}

.btn-primary {
  display: block;
  width: 100%;
  padding: 1.4rem;
  background-color: #fff;
  color: #000;
  text-decoration: none;
  text-align: center;
  border-radius: 16px;
  font-weight: 950;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  margin-top: 2rem;
  transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.btn-primary:hover {
  background-color: #ff5500;
  color: #fff;
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(255, 85, 0, 0.2);
}

.back-link {
  display: inline-block;
  margin-top: 5rem;
  color: #333;
  text-decoration: none;
  font-weight: 900;
  text-transform: uppercase;
  font-size: 0.75rem;
  letter-spacing: 2px;
  transition: all 0.2s;
}

.back-link:hover {
  color: #ff5500;
}

.loading {
  height: 60vh;
  display: flex;
  align-items: center;
  justify-content: center;
  text-transform: uppercase;
  letter-spacing: 8px;
  font-weight: 950;
  color: #111;
}

.error-msg {
  padding: 4rem;
  text-align: center;
  color: #ff5500;
  font-weight: 900;
  text-transform: uppercase;
}
</style>
