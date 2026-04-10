<script setup>
import { useEventStore } from '~/stores/event'

const props = defineProps({
  event: Object
})

const eventStore = useEventStore()

// Initialitzem l'estat si encara no s'ha fet
onMounted(() => {
  if (!eventStore.currentEvent || eventStore.currentEvent.id !== props.event.id) {
    eventStore.fetchEvent(props.event.id)
  }
})

const getSeatColor = (seat, zone) => {
  // Prioritat: Seleccionat per mi
  if (eventStore.selectedSeats.find(s => s.id === seat.id)) {
    return '#ffffff'
  }

  switch (seat.status) {
    case 'available': 
      if (zone.name.toLowerCase().includes('vip')) return '#ffd700' // Gold
      if (zone.name.toLowerCase().includes('unique')) return '#00d4ff' // Cyan/Neon Blue
      return '#ff5500' // Neon Orange (Standard)
    case 'reserved': return '#555' // Grey (Pending/Locked)
    case 'occupied': return '#222' // Dark Grey (Sold)
    default: return '#111'
  }
}

const formatTime = (seconds) => {
  const m = Math.floor(seconds / 60)
  const s = seconds % 60
  return `${m}:${s < 10 ? '0' : ''}${s}`
}

const processingSeat = ref(null)

const handleSeatClick = async (seat) => {
  if (seat.status === 'available' || eventStore.selectedSeats.find(s => s.id === seat.id)) {
    processingSeat.value = seat.id
    try {
      await eventStore.toggleSeat(seat)
    } finally {
      processingSeat.value = null
    }
  }
}

const isSeatSelected = (seatId) => {
  return eventStore.selectedSeats.some(s => s.id === seatId)
}
</script>

<template>
  <div class="seat-map-wrapper">
    <!-- Timer Banner -->
    <div v-if="eventStore.selectedSeats.length > 0" class="timer-banner">
      <div class="timer-content">
        <span class="icon">⌛</span> 
        LA TEVA SELECCIÓ EXPIRA EN: <strong>{{ formatTime(eventStore.timeLeft) }}</strong>
      </div>
      <NuxtLink to="/checkout" class="checkout-btn">ANAR AL CHECKOUT →</NuxtLink>
    </div>

    <div v-if="eventStore.loading" class="loader">Carregant mapa...</div>
    
    <div v-else-if="eventStore.currentEvent" class="seat-map-container">
      <div v-for="zone in eventStore.currentEvent.zones" :key="zone.id" class="zone-section">
        <div class="zone-header">
          <h3>{{ zone.name }}</h3>
          <span class="price-tag">{{ zone.price }}€ /entrada</span>
        </div>
        
        <div class="svg-map">
          <svg :viewBox="`0 0 ${zone.seats.reduce((max, s) => Math.max(max, s.col), 0) * 45 + 50} ${zone.seats.reduce((max, s) => Math.max(max, s.row), 0) * 45 + 50}`">
            <g v-for="seat in zone.seats" :key="seat.id" 
               class="seat-group"
               :class="{ 
                 'selectable': seat.status === 'available', 
                 'selected': isSeatSelected(seat.id),
                 'reserved': seat.status === 'reserved' && !isSeatSelected(seat.id),
                 'sold': seat.status === 'occupied'
               }"
               @click="(seat.status === 'available' || isSeatSelected(seat.id)) && handleSeatClick(seat)">
              <rect 
                :x="(seat.col - 1) * 45 + 10" 
                :y="(seat.row - 1) * 45 + 10" 
                width="35" height="35" rx="8"
                :fill="getSeatColor(seat, zone)"
                class="seat-rect"
              />
              <text 
                :x="(seat.col - 1) * 45 + 27" 
                :y="(seat.row - 1) * 45 + 28" 
                text-anchor="middle" font-size="10" 
                class="seat-label"
                :fill="isSeatSelected(seat.id) || seat.status === 'available' ? '#000' : '#888'">
                {{ seat.row }}{{ String.fromCharCode(64 + seat.col) }}
              </text>
              <text 
                v-if="seat.status !== 'available' && !isSeatSelected(seat.id)"
                :x="(seat.col - 1) * 45 + 27" 
                :y="(seat.row - 1) * 45 + 40" 
                text-anchor="middle"
                font-size="6" 
                font-weight="950"
                fill="#ff5500"
                style="pointer-events: none; user-select: none; letter-spacing: 0;">
                {{ seat.status === 'occupied' ? 'COMPRAT' : 'RESERVAT' }}
              </text>

              <!-- Local Loading Spinner -->
              <circle 
                v-if="processingSeat === seat.id"
                :cx="(seat.col - 1) * 45 + 27" 
                :cy="(seat.row - 1) * 45 + 27" 
                r="10" 
                fill="none" 
                stroke="#fff" 
                stroke-width="2" 
                stroke-dasharray="20 10" 
                class="spinner" 
              />
            </g>
          </svg>
        </div>
      </div>

      <!-- Llegenda -->
      <div class="legend">
        <div class="legend-item"><span class="box available"></span> Disponible</div>
        <div class="legend-item"><span class="box vip"></span> VIP</div>
        <div class="legend-item"><span class="box unique"></span> Unique</div>
        <div class="legend-item"><span class="box selected"></span> Seleccionat</div>
        <div class="legend-item"><span class="box reserved"></span> Ocupat / Comprat</div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.seat-map-wrapper {
  background: #0a0a0a;
  border: 1px solid #1a1a1a;
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 20px 40px rgba(0,0,0,0.4);
}

.timer-banner {
  background: #fff;
  color: #000;
  padding: 0.8rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 1px;
  font-size: 0.85rem;
  border-bottom: 2px solid #ff5500;
}

.checkout-btn {
  background: #000;
  color: #fff;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  text-decoration: none;
  font-size: 0.75rem;
  transition: all 0.2s ease;
}

.checkout-btn:hover {
  background: #ff5500;
  transform: translateX(5px);
}

.seat-map-container {
  padding: 3rem;
  display: flex;
  flex-direction: column;
  gap: 6rem;
}

.zone-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #222;
  margin-bottom: 2rem;
  padding-bottom: 0.5rem;
}

.zone-header h3 {
  color: #fff;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 2px;
  font-size: 1.2rem;
  margin: 0;
}

.price-tag {
  color: #ff5500;
  font-weight: 700;
  font-size: 0.9rem;
}

.svg-map {
  display: flex;
  justify-content: center;
  background: #000;
  padding: 2.5rem;
  border-radius: 16px;
  border: 1px solid #111;
}

.seat-group {
  transition: all 0.2s ease;
}

.seat-group.selectable {
  cursor: pointer;
}

.seat-group.selectable:hover .seat-rect {
  fill: #fff;
}

.seat-rect {
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.spinner {
  animation: rotate-spinner 0.8s linear infinite;
  transform-origin: center;
}

@keyframes rotate-spinner {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.seat-group.selected .seat-rect {
  filter: drop-shadow(0 0 5px #fff);
  animation: pulse-selected 2s infinite;
}

.seat-group.reserved, .seat-group.sold {
  pointer-events: none !important;
  cursor: default !important;
}

@keyframes pulse-selected {
  0% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.05); opacity: 0.8; }
  100% { transform: scale(1); opacity: 1; }
}

.seat-label {
  pointer-events: none;
  font-weight: 800;
  user-select: none;
}

.legend {
  display: flex;
  justify-content: center;
  gap: 2.5rem;
  margin-top: 3rem;
  padding-top: 2rem;
  border-top: 1px solid #111;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 0.8rem;
  font-size: 0.8rem;
  color: #888;
  text-transform: uppercase;
  font-weight: 700;
  letter-spacing: 1px;
}

.box {
  width: 14px;
  height: 14px;
  border-radius: 4px;
}

.box.available { background: #ff5500; }
.box.vip { background: #ffd700; }
.box.unique { background: #00d4ff; }
.box.selected { background: #ffffff; }
.box.reserved { background: #555555; }
.box.sold { background: #222222; }

.loader {
  padding: 4rem;
  text-align: center;
  color: #444;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 4px;
}
</style>
