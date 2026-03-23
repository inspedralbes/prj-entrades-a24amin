## Flux Estratègic d'Implementació

Aquest pla segueix un flux tipus *Spec-Driven Development* i la IA hi intervindrà validant l'aplicació tècnica. Cal generar el codi segons aquest ordre:

### Pas 1. Servidor Temps Real (Socket.IO + Node.js)
  1. Base express i Socket.IO com a `websocket-server/index.js`.
  2. Subscriure a una cua Redis de missatgeria on s'esperen comandes emeses pel panell Laravel.
  3. Lògica del Client:
    - Funció on `client.on('join_event')`.
    - Gestió `client.on('request_reservation')` -> Crida HTTP cap a l'API del Backend per que s'encarregui a Laravel com l'Orquestrador Principal, i d'ell s'esperarà l'aprovació i la distribució per tothom.
  
### Pas 2. Base de Dades i Laravel ORM Backend 
  1. Mètodes CRUD i validacions per a Events, Zones, Seats, Reservations i Comandes de client final en la carpeta de `backend`.
  2. Logar lògica de `Redis::lock('seat_'.$id)->get(...)` per les cursors d'entrades per ser de forma pura atòmica la veritat de les restriccions sobre concurrències.
  3. Transmetre esdeveniments per via Redis Publisher quan succeeixi que hi canviïn l'estat real d'un seient a `reserved` / `available` / `sold` temporal. Els escoltarà el Node.

### Pas 3. Frontend Base Pinia
  1. Integració Pinia (a través dels Stores composition en Nuxt). Definició de `eventStateStore`.
  2. Configurar la connexió del Middleware cap a Websockets com plugin (`plugins/socket.client.ts`). Escoltar els esdeveniments per canviar dades als `stores`.
  3. UI: Maquetació amb components de Seat.vue que canviïn el seu estat visual reactivament mitjançant un 'getter' al `eventStateStore.seatById(x)`.

### Pas 4. Testing i Confirmació de Regles
  1. Testing local (simulant peticions massives de reserva Cypress Test / script Bash simulador massiu sobre WebSocket).
  2. Verificació d'errors visuals per a conflictes de cursa.
