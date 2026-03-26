<script setup>
const config = useRuntimeConfig()
const { data: events, error } = await useFetch(`${config.public.apiBase}/admin/events`)
</script>

<template>
  <div class="home-page">
    <section class="hero">
      <div class="hero-content">
        <h1>Troba el teu proper esdeveniment</h1>
        <p>Les millors entrades per a concerts, esports i teatre.</p>
      </div>
    </section>

    <div class="container">
      <h2 class="section-title">Esdeveniments Destacats</h2>
      
      <div v-if="error" class="error-msg">Error al carregar esdeveniments</div>
      <div v-else-if="!events" class="loading">Carregant...</div>
      
      <div class="events-grid">
        <div v-for="event in events" :key="event.id" class="event-card">
          <div class="event-image">
            <div class="date-badge">
              <span class="day">{{ new Date(event.event_date).getDate() }}</span>
              <span class="month">{{ new Date(event.event_date).toLocaleString('default', { month: 'short' }) }}</span>
            </div>
          </div>
          <div class="event-info">
            <h3>{{ event.name }}</h3>
            <p class="location">📍 {{ event.location }}</p>
            <p class="description">{{ event.description?.substring(0, 100) }}...</p>
            <NuxtLink :to="'/events/' + event.id" class="btn-ticket">ENTRADES</NuxtLink>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem 1.5rem;
}

.hero {
  background-color: var(--color-4);
  color: white;
  padding: 4rem 2rem;
  text-align: center;
  background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1459749411177-042180ce673c?q=80&w=2070&auto=format&fit=crop');
  background-size: cover;
  background-position: center;
}

.hero h1 {
  font-size: 3rem;
  margin-bottom: 1rem;
  color: white;
}

.section-title {
  font-size: 1.8rem;
  margin-bottom: 2rem;
  border-bottom: 2px solid var(--color-3);
  padding-bottom: 0.5rem;
}

.events-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 2rem;
}

.event-card {
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  overflow: hidden;
  transition: transform 0.3s;
}

.event-card:hover {
  transform: translateY(-5px);
}

.event-image {
  height: 200px;
  background-color: var(--color-2);
  position: relative;
  background-image: url('https://images.unsplash.com/photo-1506157786151-b8491531f063?q=80&w=2070&auto=format&fit=crop');
  background-size: cover;
}

.date-badge {
  position: absolute;
  top: 1rem;
  left: 1rem;
  background-color: white;
  padding: 0.5rem;
  border-radius: 4px;
  display: flex;
  flex-direction: column;
  align-items: center;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.date-badge .day {
  font-size: 1.2rem;
  font-weight: bold;
  color: var(--color-3);
}

.date-badge .month {
  font-size: 0.7rem;
  text-transform: uppercase;
  color: var(--color-4);
}

.event-info {
  padding: 1.5rem;
}

.event-info h3 {
  margin: 0 0 0.5rem 0;
  color: var(--color-4);
}

.location {
  font-size: 0.9rem;
  color: #666;
  margin-bottom: 1rem;
}

.btn-ticket {
  display: block;
  width: 100%;
  padding: 0.8rem;
  background-color: #004fb0;
  color: white;
  text-align: center;
  text-decoration: none;
  font-weight: bold;
  border-radius: 4px;
  transition: background 0.2s;
}

.btn-ticket:hover {
  background-color: #003a85;
}
</style>
