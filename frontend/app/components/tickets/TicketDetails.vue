<script setup>
const props = defineProps({
  ticket: Object,
  event: Object
})

const qrCodeUrl = computed(() => {
  // Simulem un QR code usant un servei extern o simplement una representació visual
  return `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${props.ticket.ticket_code}&bgcolor=000&color=fff`
})
</script>

<template>
  <div class="ticket-detail-view">
    <div class="ticket-card">
      <div class="ticket-main">
        <div class="header-logo">SHÔKO BARCELONA</div>
        <h2 class="event-name">{{ event?.name }}</h2>
        
        <div class="ticket-body">
          <div class="seat-details">
            <div class="detail-group">
              <span class="label">UBICACIÓ</span>
              <span class="value">{{ event?.location }}</span>
            </div>
            <div class="detail-group">
              <span class="label">SESSIDÓ</span>
              <span class="value">{{ new Date(event?.event_date).toLocaleDateString() }}</span>
            </div>
            <div class="detail-group highlight">
              <span class="label">SEIENT</span>
              <span class="value">FILA {{ ticket.seat.row }} | {{ String.fromCharCode(64 + ticket.seat.col) }}</span>
            </div>
          </div>

          <div class="qr-section">
            <img :src="qrCodeUrl" alt="QR Code" class="qr-image" />
            <span class="ticket-id">{{ ticket.ticket_code }}</span>
          </div>
        </div>
      </div>
      
      <div class="ticket-stub">
        <div class="stub-content">
          <span class="vertical-text">ADMIT ONE</span>
          <div class="stub-info">
            <span class="order-id">#{{ ticket.id }}</span>
            <span class="zone">{{ ticket.seat.zone?.name }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.ticket-detail-view {
  padding: 2rem;
  background: #000;
  display: flex;
  justify-content: center;
}

.ticket-card {
  display: flex;
  background: #0a0a0a;
  border-radius: 20px;
  overflow: hidden;
  border: 1px solid #1a1a1a;
  max-width: 800px;
  width: 100%;
  box-shadow: 0 40px 80px rgba(0,0,0,0.8);
}

.ticket-main {
  flex: 1;
  padding: 3rem;
  border-right: 2px dashed #1a1a1a;
  position: relative;
}

.ticket-main::before, .ticket-main::after {
  content: '';
  position: absolute;
  right: -11px;
  width: 20px;
  height: 20px;
  background: #000;
  border-radius: 50%;
  border: 1px solid #1a1a1a;
}
.ticket-main::before { top: -11px; }
.ticket-main::after { bottom: -11px; }

.header-logo {
  font-weight: 900;
  letter-spacing: 4px;
  font-size: 0.7rem;
  color: #444;
  margin-bottom: 2rem;
}

.event-name {
  font-size: 2rem;
  font-weight: 950;
  text-transform: uppercase;
  letter-spacing: -1px;
  color: #ff5500;
  margin-bottom: 3rem;
}

.ticket-body {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
}

.seat-details {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.detail-group .label {
  display: block;
  font-size: 0.65rem;
  font-weight: 900;
  color: #444;
  letter-spacing: 2px;
  margin-bottom: 0.3rem;
}

.detail-group .value {
  display: block;
  font-weight: 800;
  font-size: 0.9rem;
  color: #fff;
}

.detail-group.highlight .value {
  font-size: 1.5rem;
  color: #fff;
}

.qr-section {
  text-align: center;
}

.qr-image {
  width: 150px;
  height: 150px;
  border-radius: 12px;
  border: 10px solid #fff;
  margin-bottom: 1rem;
}

.ticket-id {
  display: block;
  font-family: monospace;
  font-size: 0.7rem;
  color: #333;
}

.ticket-stub {
  width: 120px;
  background: #111;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}

.stub-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 3rem;
}

.vertical-text {
  writing-mode: vertical-rl;
  text-orientation: mixed;
  font-weight: 900;
  letter-spacing: 8px;
  color: #222;
  font-size: 1.2rem;
}

.stub-info {
  display: flex;
  flex-direction: column;
  align-items: center;
  font-weight: 950;
  font-size: 0.7rem;
}

.order-id { color: #444; }
.zone { color: #ff5500; text-transform: uppercase; margin-top: 0.5rem; }
</style>
