# Spec: Real-Time Seat Reservation Behavior

## Flux de l'Usuari
1. L'usuari entra a la pàgina de l'esdeveniment i rep l'estat actual.
2. En fer clic a un seient disponible:
   - S'emet l'event `reserve_seat` al servidor.
   - El servidor valida la disponibilitat.
   - Si és vàlid, s'emet `seat_reserved` a tots els usuaris i s'inicia un temporitzador per a l'usuari actual.
3. Si la reserva expira:
   - El servidor allibera el seient i emet `seat_released`.
4. En completar el checkout:
   - El seient passa a estat `occupied` i s'emet `seat_sold`.

## Casos de Conflicte
- Si dos usuaris demanen el mateix seient al mateix mil·lisegon, el servidor ha d'acceptar el primer i retornar un error (o ignorar) el segon.
- El client que rep l'error ha de mostrar una notificació visual de "Seient no disponible".
