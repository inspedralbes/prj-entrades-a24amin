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
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem 1.5rem;
}

.event-header {
  margin-bottom: 2rem;
  border-bottom: 1px solid #ddd;
  padding-bottom: 1.5rem;
}

.meta {
  color: #666;
  font-size: 1.1rem;
}

.main-content {
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 2rem;
}

@media (max-width: 768px) {
  .main-content { grid-template-columns: 1fr; }
}

.selection-card {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  position: sticky;
  top: 2rem;
}

.btn-primary {
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

.back-link {
  display: inline-block;
  margin-top: 2rem;
  color: var(--color-3);
  text-decoration: none;
  font-weight: bold;
}
</style>
