<script setup>
const route = useRoute()
const eventId = route.params.id
const config = useRuntimeConfig()
const { data: event, error } = await useFetch(`${config.public.apiBase}/events/${eventId}`)

const selectedSeat = ref(null)

const onSeatSelect = (seat) => {
  selectedSeat.value = seat
}
</script>

<template>
  <div class="event-detail">
    <div v-if="error" class="error-msg">Error al carregar l'esdeveniment</div>
    <div v-else-if="!event" class="loading">Carregant...</div>
    <div v-else class="container">
      <div class="event-header">
        <h1>{{ event.name }}</h1>
        <p class="meta">📅 {{ new Date(event.event_date).toLocaleDateString() }} | 📍 {{ event.location }}</p>
      </div>

      <div class="main-content">
        <div class="map-section">
          <h2>Tria els teus seients</h2>
          <EventSeatMap :event="event" @select-seat="onSeatSelect" />
        </div>

        <aside class="sidebar" v-if="selectedSeat">
          <div class="selection-card">
            <h3>Selecció</h3>
            <p><strong>Seient:</strong> {{ selectedSeat.row }}{{ String.fromCharCode(65 + selectedSeat.col) }}</p>
            <p><strong>Preu:</strong> {{ event.zones.find(z => z.id === selectedSeat.event_zone_id)?.price }}€</p>
            <button class="btn-primary">CONTINUAR</button>
          </div>
        </aside>
      </div>

      <NuxtLink to="/" class="back-link">← Torna a la llista</NuxtLink>
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
  max-width: 1200px;
  margin: 0 auto;
  padding: 4rem 1.5rem;
}

.event-header {
  margin-bottom: 3rem;
  border-bottom: 1px solid #1a1a1a;
  padding-bottom: 1.5rem;
}

.event-header h1 {
  font-size: 2.5rem;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: -1px;
  margin: 0 0 1rem 0;
}

.meta {
  color: #888;
  font-size: 1rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.main-content {
  display: grid;
  grid-template-columns: 1fr 340px;
  gap: 3rem;
}

@media (max-width: 992px) {
  .main-content { grid-template-columns: 1fr; }
}

.selection-card {
  background: #050505;
  padding: 2.5rem;
  border-radius: 24px;
  border: 1px solid #222;
  position: sticky;
  top: 2rem;
}

.selection-card h3 {
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 2rem;
  font-weight: 900;
}

.selection-card p {
  margin-bottom: 1rem;
  font-size: 1.1rem;
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
  margin-top: 2rem;
  transition: transform 0.2s;
}

.btn-primary:hover {
  transform: scale(1.02);
}

.back-link {
  display: inline-block;
  margin-top: 3rem;
  color: #888;
  text-decoration: none;
  font-weight: 700;
  text-transform: uppercase;
  font-size: 0.8rem;
  letter-spacing: 1px;
}

.back-link:hover {
  color: #fff;
}
</style>
