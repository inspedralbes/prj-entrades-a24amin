# Plan: Real-Time Seat Reservation Implementation

## Estratègia d'Implementació
1. **Model de Dades**: Afegir camps `status`, `expires_at` i `user_id` als seients (o taula de reserves).
2. **Websockets (Node.js)**: Implementar handlers per `reserve_seat` amb bloqueig (atomic operation).
3. **Frontend (Nuxt)**: Sincronització de l'estat via Pinia Store. Ús de `socket.io-client`.

## Verificació
- Tests de concurrència amb Cypress simulant múltiples intents de reserva.
- Monitorització de l'estat visual en el Dashboard d'Admin en temps real.
