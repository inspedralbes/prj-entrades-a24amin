const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const cors = require('cors');
const Redis = require('ioredis');

const app = express();
app.use(cors());

const server = http.createServer(app);

const io = new Server(server, {
  cors: {
    origin: '*',
    methods: ['GET', 'POST']
  }
});

// Connexió a Redis
const redis = new Redis({
  host: process.env.REDIS_HOST || 'redis',
  port: 6379,
});

redis.subscribe('seat-updates', (err, count) => {
  if (err) {
    console.error('Error al subscriure a Redis:', err);
  } else {
    console.log(`Subscrit a ${count} canals de Redis.`);
  }
});

redis.on('message', (channel, message) => {
  if (channel === 'seat-updates') {
    try {
      const data = JSON.parse(message);
      const room = `event_${data.event_id}`;
      io.to(room).emit('seat_updated', data);
      console.log(`Emesa actualització a la sala ${room}: Seient ${data.seat_id} -> ${data.status}`);
    } catch (e) {
      console.error('Error al processar missatge de Redis:', e);
    }
  }
});

io.on('connection', (socket) => {
  console.log("Un usuari s'ha connectat:", socket.id);

  socket.on('join_event', (eventId) => {
    if (!eventId) return;
    const room = `event_${eventId}`;
    socket.join(room);
    console.log(`L'usuari ${socket.id} s'ha unit a la sala ${room}`);
  });

  socket.on('disconnect', () => {
    console.log('Usuari desconnectat:', socket.id);
  });
});

const PORT = 3000;
server.listen(PORT, () => {
  console.log(`Servidor Socket.IO escoltant al port ${PORT}`);
});
