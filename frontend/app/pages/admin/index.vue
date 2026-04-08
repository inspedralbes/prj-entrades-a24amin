<script setup>
import { useAuthStore } from '~/stores/auth'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
  ArcElement
} from 'chart.js'
import { Bar, Pie } from 'vue-chartjs'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement)

const auth = useAuthStore()
const config = useRuntimeConfig()
const { $socket } = useNuxtApp()

const stats = ref([])
const activeUsers = ref(0)
const loading = ref(true)
const activeTab = ref('dashboard') // 'dashboard' | 'events'

// Formulari per a nous esdeveniments
const showEventModal = ref(false)
const eventForm = ref({
  id: null,
  name: '',
  description: '',
  location: '',
  event_date: '',
  image_url: ''
})
const savingEvent = ref(false)

// Estructura per als gràfics
const chartData = computed(() => ({
  labels: stats.value.map(s => s.event_name),
  datasets: [
    {
      label: 'Ocupació (%)',
      backgroundColor: '#ff5500',
      data: stats.value.map(s => s.occupancy_percentage)
    }
  ]
}))

const revenueData = computed(() => ({
  labels: stats.value.map(s => s.event_name),
  datasets: [
    {
      label: 'Ingressos (€)',
      backgroundColor: ['#ffd700', '#ff8800', '#ffcc00'],
      data: stats.value.map(s => s.total_revenue)
    }
  ]
}))

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { labels: { color: '#fff', font: { weight: '900' } } }
  },
  scales: {
    y: { ticks: { color: '#666' }, grid: { color: '#111' } },
    x: { ticks: { color: '#666' }, grid: { color: '#111' } }
  }
}

onMounted(async () => {
  if (!auth.isLoggedIn || !auth.user?.is_admin) {
    return navigateTo('/')
  }

  await fetchAllStats()
  
  // Escoltant usuaris connectats real-time
  $socket.on('user_count_updated', (count) => {
    activeUsers.value = count
  })
})

const fetchAllStats = async () => {
  try {
    const events = await $fetch(`${config.public.apiBase}/admin/events`, {
      headers: { Authorization: `Bearer ${auth.token}` }
    })
    
    const statsPromises = events.map(event => 
      $fetch(`${config.public.apiBase}/admin/events/${event.id}/stats`, {
        headers: { Authorization: `Bearer ${auth.token}` }
      }).then(res => ({ ...res, event_name: event.name, event_data: event }))
    )
    
    stats.value = await Promise.all(statsPromises)
  } catch (err) {
    console.error('Error fetching admin stats:', err)
  } finally {
    loading.value = false
  }
}

const openNewEvent = () => {
  eventForm.value = { id: null, name: '', description: '', location: '', event_date: '', image_url: '' }
  showEventModal.value = true
}

const editEvent = (event) => {
  eventForm.value = { ...event, event_date: event.event_date.replace(' ', 'T') }
  showEventModal.value = true
}

const saveEvent = async () => {
  savingEvent.ref = true
  try {
    const url = eventForm.value.id 
      ? `${config.public.apiBase}/admin/events/${eventForm.value.id}`
      : `${config.public.apiBase}/admin/events`
    
    await $fetch(url, {
      method: eventForm.value.id ? 'PUT' : 'POST',
      body: eventForm.value,
      headers: { Authorization: `Bearer ${auth.token}` }
    })
    
    showEventModal.value = false
    await fetchAllStats()
  } catch (err) {
    alert('Error al desar l\'esdeveniment.')
  } finally {
    savingEvent.value = false
  }
}

const deleteEvent = async (id) => {
  if (!confirm('Segur que vols eliminar aquest esdeveniment?')) return
  
  try {
    await $fetch(`${config.public.apiBase}/admin/events/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${auth.token}` }
    })
    await fetchAllStats()
  } catch (err) {
    alert('Error al eliminar l\'esdeveniment.')
  }
}
</script>

<template>
  <div class="admin-page container">
    <div class="admin-header">
      <h1 class="page-title">Admin Dashboard</h1>
      <div class="admin-nav">
        <button 
          @click="activeTab = 'dashboard'" 
          :class="['nav-btn', { active: activeTab === 'dashboard' }]"
        >Dashboard</button>
        <button 
          @click="activeTab = 'events'" 
          :class="['nav-btn', { active: activeTab === 'events' }]"
        >Gestionar Events</button>
      </div>
      <div class="realtime-badge">
        <span class="dot"></span>
        {{ activeUsers }} Usuaris a la web
      </div>
    </div>

    <div v-if="loading" class="loader">Processant dades del cinema...</div>

    <div v-else class="admin-content">
      <!-- TAB DASHBOARD -->
      <div v-if="activeTab === 'dashboard'" class="admin-grid animate-fade">
        <!-- Cards de Resum -->
        <div class="stats-row">
          <div class="stat-card">
            <span class="label">Ingressos Totals</span>
            <span class="value">{{ stats.reduce((acc, s) => acc + s.total_revenue, 0) }}€</span>
          </div>
          <div class="stat-card gold">
            <span class="label">Ocupació Mitjana</span>
            <span class="value">{{ stats.length ? Math.round(stats.reduce((acc, s) => acc + s.occupancy_percentage, 0) / stats.length) : 0 }}%</span>
          </div>
        </div>

        <!-- Gràfics -->
        <div class="charts-row">
          <div class="chart-container">
            <h3>Ocupació per Pel·lícula</h3>
            <Bar :data="chartData" :options="chartOptions" />
          </div>
          <div class="chart-container">
            <h3>Distribució d'Ingressos</h3>
            <div class="pie-wrapper">
               <Pie :data="revenueData" :options="{ ...chartOptions, scales: {} }" />
            </div>
          </div>
        </div>

        <!-- Llista de Detalls -->
        <div class="details-table">
          <h3>Detall de Sessions</h3>
          <table>
            <thead>
              <tr>
                <th>Pel·lícula</th>
                <th>Venudes</th>
                <th>Reservades</th>
                <th>Ingressos</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="s in stats" :key="s.event_name">
                <td class="name">{{ s.event_name }}</td>
                <td>{{ s.occupied_seats }}</td>
                <td>{{ s.reserved_seats }}</td>
                <td class="price">{{ s.total_revenue }}€</td>
                <td>
                  <span class="progress-bar">
                    <span class="fill" :style="{ width: s.occupancy_percentage + '%' }"></span>
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- TAB GESTIÓ EVENTS -->
      <div v-else class="events-management animate-fade">
        <div class="table-header">
          <h2>Llistat d'Esdeveniments</h2>
          <button class="btn-primary" @click="openNewEvent">NOU ESDEVENIMENT</button>
        </div>

        <div class="details-table">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Data</th>
                <th>Ubicació</th>
                <th>Accions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="s in stats" :key="s.event_data.id">
                <td>#{{ s.event_data.id }}</td>
                <td class="name">{{ s.event_name }}</td>
                <td>{{ new Date(s.event_data.event_date).toLocaleDateString() }}</td>
                <td>{{ s.event_data.location }}</td>
                <td class="actions">
                  <button class="btn-icon" @click="editEvent(s.event_data)">✏️</button>
                  <button class="btn-icon danger" @click="deleteEvent(s.event_data.id)">🗑️</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- MODAL ESDEVENIMENT -->
    <div v-if="showEventModal" class="modal-overlay">
      <div class="modal-card">
        <h3>{{ eventForm.id ? 'Editar Esdeveniment' : 'Nou Esdeveniment' }}</h3>
        <form @submit.prevent="saveEvent" class="admin-form">
          <div class="form-group">
            <label>Nom de la Pel·lícula</label>
            <input v-model="eventForm.name" required placeholder="Ex: Oppenheimer" />
          </div>
          <div class="form-group">
            <label>Descripció</label>
            <textarea v-model="eventForm.description" rows="3"></textarea>
          </div>
          <div class="form-group-row">
            <div class="form-group">
              <label>Data i Hora</label>
              <input type="datetime-local" v-model="eventForm.event_date" required />
            </div>
            <div class="form-group">
              <label>Ubicació (Sala)</label>
              <input v-model="eventForm.location" required placeholder="Sala Premium 1" />
            </div>
          </div>
          <div class="form-group">
            <label>URL de la Imatge (Pòster)</label>
            <input v-model="eventForm.image_url" placeholder="https://..." />
          </div>
          
          <div class="modal-actions">
            <button type="button" class="btn-secondary" @click="showEventModal = false">CANCEL·LAR</button>
            <button type="submit" class="btn-primary" :disabled="savingEvent">
              {{ savingEvent ? 'GUARDANT...' : 'DESAR CANVIS' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
.admin-page {
  padding: 4rem 1.5rem;
  background: #000;
  min-height: 90vh;
}

.admin-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 3rem;
  border-bottom: 1px solid #111;
  padding-bottom: 1.5rem;
  gap: 2rem;
}

.admin-nav {
  display: flex;
  gap: 1rem;
  flex: 1;
}

.nav-btn {
  background: #111;
  border: 1px solid #222;
  color: #666;
  padding: 0.8rem 1.5rem;
  border-radius: 12px;
  font-weight: 900;
  text-transform: uppercase;
  font-size: 0.75rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.nav-btn.active {
  background: #ff5500;
  color: #fff;
  border-color: #ff5500;
  box-shadow: 0 5px 15px rgba(255, 85, 0, 0.3);
}

.table-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.table-header h2 {
  font-size: 1.5rem;
  font-weight: 950;
  text-transform: uppercase;
}

.btn-primary {
  background: #ff5500;
  color: #fff;
  border: none;
  padding: 1rem 2rem;
  border-radius: 12px;
  font-weight: 950;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 20px rgba(255, 85, 0, 0.4);
}

.btn-secondary {
  background: #111;
  color: #fff;
  border: 1px solid #222;
  padding: 1rem 2rem;
  border-radius: 12px;
  font-weight: 950;
  cursor: pointer;
}

.actions {
  display: flex;
  gap: 0.5rem;
}

.btn-icon {
  background: #111;
  border: 1px solid #222;
  padding: 0.5rem;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-icon:hover { background: #222; }
.btn-icon.danger:hover { background: #411; border-color: #f00; }

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.85);
  backdrop-filter: blur(10px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-card {
  background: #0a0a0a;
  border: 1px solid #222;
  padding: 3rem;
  border-radius: 32px;
  width: 100%;
  max-width: 600px;
  box-shadow: 0 50px 100px rgba(0,0,0,0.5);
}

.admin-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  margin-top: 2rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-group label {
  font-size: 0.7rem;
  font-weight: 900;
  text-transform: uppercase;
  color: #444;
}

.form-group-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}

input, textarea {
  background: #111;
  border: 1px solid #222;
  padding: 1rem;
  border-radius: 12px;
  color: #fff;
  font-family: inherit;
}

input:focus, textarea:focus {
  border-color: #ff5500;
  outline: none;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 2rem;
}

.animate-fade {
  animation: fadeIn 0.4s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.page-title {
  font-size: 2.5rem;
  font-weight: 950;
  text-transform: uppercase;
  letter-spacing: -1px;
}

.realtime-badge {
  background: #111;
  border: 1px solid #222;
  padding: 0.6rem 1.2rem;
  border-radius: 50px;
  font-size: 0.75rem;
  font-weight: 900;
  text-transform: uppercase;
  display: flex;
  align-items: center;
  gap: 0.8rem;
  color: #888;
}

.dot {
  width: 10px;
  height: 10px;
  background: #0ff;
  border-radius: 50%;
  box-shadow: 0 0 10px #0ff;
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0% { transform: scale(0.9); opacity: 0.5; }
  50% { transform: scale(1.1); opacity: 1; }
  100% { transform: scale(0.9); opacity: 0.5; }
}

.stats-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
  margin-bottom: 3rem;
}

.stat-card {
  background: #0a0a0a;
  border: 1px solid #1a1a1a;
  padding: 2.5rem;
  border-radius: 32px;
  display: flex;
  flex-direction: column;
}

.stat-card.gold .value { color: #ffd700; }

.stat-card .label {
  font-size: 0.75rem;
  font-weight: 900;
  text-transform: uppercase;
  color: #444;
  margin-bottom: 0.5rem;
}

.stat-card .value {
  font-size: 2.5rem;
  font-weight: 950;
  color: #ff5500;
}

.charts-row {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 2rem;
  margin-bottom: 3rem;
}

@media (max-width: 900px) {
  .charts-row { grid-template-columns: 1fr; }
}

.chart-container {
  background: #0a0a0a;
  border: 1px solid #1a1a1a;
  padding: 2.5rem;
  border-radius: 32px;
  height: 450px;
  display: flex;
  flex-direction: column;
}

.chart-container h3 {
  margin: 0 0 2rem 0;
  font-size: 0.9rem;
  text-transform: uppercase;
  color: #888;
  font-weight: 900;
}

.pie-wrapper {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
}

.details-table {
  background: #0a0a0a;
  border: 1px solid #1a1a1a;
  padding: 2.5rem;
  border-radius: 32px;
}

.details-table h3 {
  margin: 0 0 2rem 0;
  font-size: 0.9rem;
  text-transform: uppercase;
  color: #888;
  font-weight: 900;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th {
  text-align: left;
  font-size: 0.7rem;
  text-transform: uppercase;
  color: #444;
  padding: 1rem;
  border-bottom: 1px solid #111;
}

td {
  padding: 1.5rem 1rem;
  border-bottom: 1px solid #050505;
  font-size: 0.9rem;
  font-weight: 800;
}

.name { color: #ff5500; text-transform: uppercase; }
.price { color: #fff; }

.progress-bar {
  width: 100%;
  height: 6px;
  background: #111;
  border-radius: 10px;
  display: block;
  overflow: hidden;
}

.fill {
  height: 100%;
  background: linear-gradient(90deg, #ff5500, #ffd700);
  display: block;
  border-radius: 10px;
}

.loader {
  text-align: center;
  padding: 10rem;
  font-weight: 950;
  text-transform: uppercase;
  letter-spacing: 5px;
  color: #222;
}
</style>
