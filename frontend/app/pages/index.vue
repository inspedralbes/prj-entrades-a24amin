<script setup>
const runtimeConfig = useRuntimeConfig()
const apiBase = import.meta.server ? runtimeConfig.apiBase : runtimeConfig.public.apiBase
const { data: events, error } = await useFetch(`${apiBase}/events`)

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
        <h1>Troba la teva propera pel·lícula</h1>
        <p>Les millors estrenes i clàssics a Shôko Cinema Barcelona.</p>
        <div class="search-bar-mock">
          <input type="text" placeholder="Busca per títol, director o gènere">
          <button class="btn-search">🔍</button>
        </div>
      </div>
    </section>

    <div class="container">
      <h2 class="section-title">En Cartellera</h2>
      
      <div v-if="error" class="error-msg">
        No s'han pogut carregar les pel·lícules. Si us plau, torna-ho a intentar més tard.
      </div>

      <div v-else class="event-grid">
        <div v-for="event in events" :key="event.id" class="shoko-card">
          <div class="image-wrapper">
            <img :src="event.image_url" :alt="event.name" class="event-image">
            <div class="badge-icon">🎬</div>
          </div>
          <div class="card-content">
            <p class="event-date">{{ formatDateShoko(event.event_date) }}</p>
            <h3 class="event-title">{{ event.name }}</h3>
            <NuxtLink :to="`/events/${event.id}`" class="btn-shoko">Comprar entrades</NuxtLink>
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
  font-weight: 950;
}

.section-title {
  font-size: 1.8rem;
  margin-bottom: 2rem;
  border-bottom: 2px solid #ff5500;
  padding-bottom: 0.5rem;
  text-transform: uppercase;
  font-weight: 900;
}

.event-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 2.5rem;
}

.shoko-card {
  background: #0a0a0a;
  border: 1px solid #1a1a1a;
  border-radius: 20px;
  overflow: hidden;
  transition: all 0.3s ease;
}

.shoko-card:hover {
  transform: translateY(-10px);
  border-color: #ff5500;
  box-shadow: 0 20px 40px rgba(255, 85, 0, 0.1);
}

.image-wrapper {
  position: relative;
  height: 400px;
}

.event-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.badge-icon {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: rgba(0,0,0,0.8);
  padding: 0.5rem;
  border-radius: 50%;
  font-size: 1.2rem;
}

.card-content {
  padding: 1.5rem;
  text-align: center;
}

.event-date {
  font-size: 0.75rem;
  color: #ff5500;
  text-transform: uppercase;
  font-weight: 900;
  margin-bottom: 0.5rem;
}

.event-title {
  font-size: 1.2rem;
  font-weight: 900;
  text-transform: uppercase;
  margin-bottom: 1.5rem;
  letter-spacing: 1px;
}

.btn-shoko {
  display: inline-block;
  padding: 0.8rem 1.5rem;
  background: #fff;
  color: #000;
  text-decoration: none;
  font-weight: 900;
  text-transform: uppercase;
  font-size: 0.8rem;
  border-radius: 12px;
  transition: all 0.2s;
}

.btn-shoko:hover {
  background: #ff5500;
  color: #fff;
}

.search-bar-mock {
  max-width: 600px;
  margin: 2rem auto;
  display: flex;
  background: #0a0a0a;
  border: 1px solid #1a1a1a;
  border-radius: 16px;
  overflow: hidden;
}

.search-bar-mock input {
  flex: 1;
  background: transparent;
  border: none;
  padding: 1rem 1.5rem;
  color: #fff;
  font-weight: 700;
}

.btn-search {
  background: transparent;
  border: none;
  padding: 1rem;
  cursor: pointer;
}
</style>
