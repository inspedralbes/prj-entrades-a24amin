const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const cors = require('cors');

const app = express();
app.use(cors());

const server = http.createServer(app);

const io = new Server(server, {
  cors: {
    origin: '*',
    methods: ['GET', 'POST']
  }
});

io.on('connection', (socket) => {
  console.log("Un usuari s'ha connectat:", socket.id);

  socket.on('join_event', (data) => {
    const room = `event_${data.eventId}`;
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
