<script setup>
const { $socket } = useNuxtApp()
const isConnected = ref(false)

onMounted(() => {
  isConnected.value = $socket.connected
  
  $socket.on('connect', () => isConnected.value = true)
  $socket.on('disconnect', () => isConnected.value = false)
})
</script>

<template>
  <div class="socket-status" :title="isConnected ? 'Sincronitzat' : 'Desconnectat'">
    <span :class="['status-dot', { connected: isConnected }]"></span>
    <span class="status-text">{{ isConnected ? 'REAL-TIME' : 'OFLINE' }}</span>
  </div>
</template>

<style scoped>
.socket-status {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.65rem;
  font-weight: 950;
  letter-spacing: 1px;
  background: rgba(0,0,0,0.5);
  padding: 4px 10px;
  border-radius: 50px;
  border: 1px solid #222;
}

.status-dot {
  width: 6px;
  height: 6px;
  background: #ff5500;
  border-radius: 50%;
  box-shadow: 0 0 5px #ff5500;
}

.status-dot.connected {
  background: #00ffcc;
  box-shadow: 0 0 10px #00ffcc;
  animation: pulse-green 2s infinite;
}

.status-text {
  color: #666;
}

@keyframes pulse-green {
  0% { transform: scale(0.9); opacity: 0.8; }
  50% { transform: scale(1.1); opacity: 1; }
  100% { transform: scale(0.9); opacity: 0.8; }
}
</style>
