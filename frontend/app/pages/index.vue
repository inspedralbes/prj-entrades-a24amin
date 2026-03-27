<script setup>
const runtimeConfig = useRuntimeConfig()
const { data: events, error } = await useFetch(`${runtimeConfig.public.apiBase}/events`)

const formatDateShoko = (dateStr) => {
  const date = new Date(dateStr)
  const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
  const dayName = days[date.getDay()]
  const day = date.getDate()
  const month = date.getMonth() + 1
  const year = date.getFullYear()
  return `${dayName}, ${day}.${month}.${year}`
}
</script>

<template>
  <div class="shoko-home">
    <section class="hero">
      <div class="container">
        <h1>Troba el teu proper esdeveniment</h1>
        <p>Les millors festes i esdeveniments de Barcelona en un sol lloc.</p>
        <div class="search-bar-mock">
          <input type="text" placeholder="Busca per artista, esdeveniment o recinte">
          <button class="btn-search">🔍</button>
        </div>
      </div>
    </section>

    <div class="container">
      <h2 class="section-title">Esdeveniments Destacats</h2>
      
      <div v-if="error" class="error-msg">
        No s'han pogut carregar els esdeveniments. Si us plau, torna-ho a intentar més tard.
      </div>

      <div v-else class="event-grid">
        <div v-for="event in events" :key="event.id" class="shoko-card">
          <div class="image-wrapper">
            <img :src="event.image_url" :alt="event.name" class="event-image">
            <div class="badge-icon">🎟️</div>
          </div>
          <div class="card-content">
            <p class="event-date">{{ formatDateShoko(event.event_date) }}</p>
            <h3 class="event-title">{{ event.name }}</h3>
            <NuxtLink :to="`/events/${event.id}`" class="btn-shoko">Buy tickets</NuxtLink>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.shoko-home {
  background-color: #000;
  min-height: 100vh;
  color: white;
  padding-bottom: 5rem;
}

.hero {
  padding: 6rem 1.5rem;
  text-align: center;
}

.hero h1 {
  margin-bottom: 1rem;
  text-transform: uppercase;
  letter-spacing: 2px;
}

.section-title {
  font-size: 1.8rem;
  margin-bottom: 2rem;
  border-bottom: 2px solid #ff5500;
  padding-bottom: 0.5rem;
}

.events-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 2rem;
}

.event-card {
  background: #050505;
  border: 1px solid #111;
  border-radius: 12px;
  overflow: hidden;
  transition: transform 0.3s, border-color 0.3s;
  color: white;
}

.event-card:hover {
  transform: translateY(-8px);
  border-color: #ff5500;
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
