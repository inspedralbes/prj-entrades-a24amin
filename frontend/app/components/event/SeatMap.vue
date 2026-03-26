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
    case 'available': return 'var(--color-3)' // Teal
    case 'reserved': return 'var(--color-5)'  // Brown/Yellow
    case 'occupied': return '#cccccc'         // Grey
    default: return '#eee'
  }
}

const handleSeatClick = (seat) => {
  if (seat.status === 'available') {
    emit('select-seat', seat)
  }
}
</script>

<template>
  <div class="seat-map-container">
    <div v-for="zone in localEvent.zones" :key="zone.id" class="zone-section">
      <h3>{{ zone.name }} - {{ zone.price }}€</h3>
      
      <div class="svg-map">
        <svg 
          :width="8 * (seatSize + seatGap)" 
          :height="5 * (seatSize + seatGap)"
          viewBox="0 0 400 200"
        >
          <g v-for="seat in zone.seats" :key="seat.id">
            <rect
              :x="seat.col * (seatSize + seatGap)"
              :y="seat.row * (seatSize + seatGap)"
              :width="seatSize"
              :height="seatSize"
              :rx="4"
              :fill="getSeatColor(seat.status)"
              class="seat"
              :class="{ 'clickable': seat.status === 'available' }"
              @click="handleSeatClick(seat)"
            >
              <title>Fila {{ seat.row }}, Col {{ seat.col }} - {{ seat.status }}</title>
            </rect>
            <text
              :x="seat.col * (seatSize + seatGap) + seatSize/2"
              :y="seat.row * (seatSize + seatGap) + seatSize/2 + 5"
              font-size="10"
              fill="white"
              text-anchor="middle"
              pointer-events="none"
            >
              {{ seat.row }}{{ String.fromCharCode(65 + seat.col) }}
            </text>
          </g>
        </svg>
      </div>
    </div>

    <div class="legend">
      <div class="item"><span class="box available"></span> Lliure</div>
      <div class="item"><span class="box reserved"></span> Reservat</div>
      <div class="item"><span class="box occupied"></span> Ocupat</div>
    </div>
  </div>
</template>

<style scoped>
.seat-map-container {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.zone-section {
  margin-bottom: 3rem;
}

.svg-map {
  display: flex;
  justify-content: center;
  overflow-x: auto;
  padding: 1rem;
}

.seat {
  transition: all 0.2s;
  stroke: rgba(0,0,0,0.1);
  stroke-width: 1;
}

.seat.clickable {
  cursor: pointer;
}

.seat.clickable:hover {
  filter: brightness(1.2);
  stroke: var(--color-4);
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
