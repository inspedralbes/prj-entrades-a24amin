# Protocol Socket.IO (Temps Real)

Aquesta descripció dicta inicialment com parlaran el Nuxt client amb el Node.js Socket.IO server per garantir zero conflictes de simultaneïtat.

> [!WARNING]
> La Regla d'Or del sistema és: "El client mai decideix l’estat d’un seient. El servidor és sempre l’única font de veritat."

## 1. Conexió i Salas (Connexió inicial)
- Quan un usuari navega per un esdeveniment específic (`/events/1`), el frontend Nuxt emet l'event `join_event`.
  - **Payload enviat (Client -> Servidor):** `{ "eventId": 1 }`
  - Això fa que expressament Socket.IO posin aquest usuari dintre d'una 'room' exclusivament d'aquell esdeveniment, per rebre només els moviments d'aquell estadi i no el d'altres estadis a la vegada.

## 2. Acció del Client: Demanar Reserva de Seient
- Quan l'usuari fa GO sobre el botó d'un seient de "Fila 1 Asiento 6" de la zona (en estadi `available`).
  - **Payload enviat (`request_reservation` -> Servidor):** `{ "eventId": 1, "seatId": 40 }` 
  - El Servidor comprova via Base de Dades o Redis si *realment* continua de veritat `available`. Pot ser que just abans arribés el mateix paquet d'una altra connexió a Austràlia.

## 3. Respostes del Servidor
Després d'una decisió ferma a BD:

- **Cas d'Èxit (Concedit):**
  - El seient s'actualitza a model amb estatus `reserved` i guardem marca de temps. 
  - S'emet UNICAMENT al client triomfador l'event de resposta: `reservation_success: { seatId: 40, reservedUntil: "2026-04-12T10:05:00" }` perquè arrenqui el seu crono visual al Front.
  - S'emet TOTHOM (a tota la `room` de l'event) inclòs l'administrador: `seat_status_changed: { seatId: 40, newStatus: "reserved", reservedBy: userId }`.  Pinia dels clients ha de canviar el botonet de color i bloquejar-lo immediatament.

- **Cas de Fracàs (Agotat / Conflicte per cursa d'usuaris / Limitació / etc):**
  - El servidor ignora la petició.
  - S'emet UNICAMENT al client 'perdedor': `reservation_failed: { seatId: 40, reason: "El seient ha volat / Ja ha estat reclamat milisegons enrere" }`.
  - El client mostrarà una torrada o modal de fallada suau.

## 4. Finalització de Temporitzadors (Expiració)
- Si l'usuari triga més de N minuts al caixer i no paga, o si trenca la connexió directament i l'administrador/sistema l'expulsa:
  - El servidor detecta la fi de reserva i allibera immediatament de la DB.
  - S'emet A TOTHOM l'event: `seat_status_changed: { seatId: 40, newStatus: "available", reservedBy: null }`. Així torna a pintar-se verd als mapes dels espectadors a temps real.

## 5. Cas Compra Finalitzada
- S'emeten automàticament els seients aprovats de la cartellera: `seat_status_changed: { seatId: 40, newStatus: "sold" }`.
