<script setup>
import { io } from 'socket.io-client'

const props = defineProps({
  event: Object
})

const emit = defineEmits(['select-seat'])

const socket = ref(null)
const localEvent = ref({ ...props.event })

// Configuració del mapa
const seatSize = 30
const seatGap = 10

onMounted(() => {
  // Connectem al servidor de WebSockets
  // En producció el proxy de Nginx ens permet connectar a la mateixa URL
  const socketUrl = process.env.NODE_ENV === 'production' 
    ? window.location.origin 
    : 'http://localhost:3000'
  
  socket.value = io(socketUrl)

  // Unim-nos a la sala de l'esdeveniment
  socket.value.emit('join_event', props.event.id)

  // Escoltem actualitzacions
  socket.value.on('seat_updated', (data) => {
    console.log('Actualització de seient:', data)
    updateSeatStatus(data.seat_id, data.status)
  })
})

onUnmounted(() => {
  if (socket.value) socket.value.disconnect()
})

const updateSeatStatus = (seatId, newStatus) => {
  localEvent.value.zones.forEach(zone => {
    const seat = zone.seats.find(s => s.id === seatId)
    if (seat) {
      seat.status = newStatus
    }
  })
}

const getSeatColor = (status) => {
  switch (status) {
    case 'available': return '#ff5500' // Neon Orange
    case 'reserved': return '#444'      // Mid Grey
    case 'occupied': return '#222'      // Dark Grey
    default: return '#111'
  }
}

const selectedSeat = ref(null)
const timeLeft = ref(600) // 10 minuts
let timerInterval = null

const startTimer = () => {
  if (timerInterval) clearInterval(timerInterval)
  timeLeft.value = 600
  timerInterval = setInterval(() => {
    if (timeLeft.value > 0) {
      timeLeft.value--
    } else {
      clearInterval(timerInterval)
      alert('La teva reserva ha expirat.')
      selectedSeat.value = null
    }
  }, 1000)
}

const formatTime = (seconds) => {
  const m = Math.floor(seconds / 60)
  const s = seconds % 60
  return `${m}:${s < 10 ? '0' : ''}${s}`
}

const handleSeatClick = (seat) => {
  if (seat.status === 'available') {
    selectedSeat.value = seat
    emit('select-seat', seat)
    startTimer()
  }
}
</script>

<template>
  <div class="seat-map-wrapper">
    <div v-if="selectedSeat" class="timer-banner">
      <span class="icon">⌛</span> Your selection will expire in: <strong>{{ formatTime(timeLeft) }}</strong>
    </div>

    <div class="seat-map-container">
      <div v-for="zone in localEvent.zones" :key="zone.id" class="zone-section">
        <h3>{{ zone.name }} — {{ zone.price }}€</h3>
        
        <div class="svg-map">
          <svg :viewBox="`0 0 ${zone.seats.reduce((max, s) => Math.max(max, s.col), 0) * 45 + 50} ${zone.seats.reduce((max, s) => Math.max(max, s.row), 0) * 45 + 50}`">
            <g v-for="seat in zone.seats" :key="seat.id" 
               class="seat-group"
               :class="{ 'selectable': seat.status === 'available', 'selected': selectedSeat?.id === seat.id }"
               @click="handleSeatClick(seat)">
              <rect 
                :x="(seat.col - 1) * 45 + 10" 
                :y="(seat.row - 1) * 45 + 10" 
                width="35" height="35" rx="8"
                :fill="selectedSeat?.id === seat.id ? '#ffffff' : getSeatColor(seat.status)"
                :stroke="seat.status === 'available' ? '#444' : 'none'"
                stroke-width="1"
              />
              <text 
                :x="(seat.col - 1) * 45 + 27" 
                :y="(seat.row - 1) * 45 + 32" 
                text-anchor="middle" font-size="10" 
                :fill="selectedSeat?.id === seat.id || seat.status === 'available' ? '#000' : '#444'">
                {{ seat.row }}{{ String.fromCharCode(65 + seat.col - 1) }}
              </text>
            </g>
          </svg>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.seat-map-wrapper {
  background: #050505;
  border: 1px solid #1a1a1a;
  border-radius: 24px;
  overflow: hidden;
}

.timer-banner {
  background: #fff;
  color: #000;
  padding: 1rem;
  text-align: center;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 2px;
  font-size: 0.8rem;
}

.seat-map-container {
  padding: 3rem;
  color: white;
}

.zone-section {
  margin-bottom: 4rem;
}

.zone-section h3 {
  color: #fff;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-bottom: 2rem;
  font-size: 1.1rem;
  border-bottom: 1px solid #222;
  padding-bottom: 1rem;
}

.svg-map {
  display: flex;
  justify-content: center;
  background: #000;
  padding: 2rem;
  border-radius: 12px;
}

.seat-group {
  cursor: default;
}

.seat-group.selectable {
  cursor: pointer;
}

  stroke-width: 2;
}

.legend {
  display: flex;
  justify-content: center;
  gap: 2rem;
  margin-top: 2rem;
  font-size: 0.9rem;
}

.legend .item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.box {
  width: 15px;
  height: 15px;
  border-radius: 3px;
}

.box.available { background-color: var(--color-3); }
.box.reserved { background-color: var(--color-5); }
.box.occupied { background-color: #cccccc; }
</style>
