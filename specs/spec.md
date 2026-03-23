## Comportament Esperat i Condicionants Específics

### 1. Escenari Bàsic de Compra Directa:
1. L'usuari 'Amin' obre el plànol de l'esdeveniment (via Vue en el client). Ell automàticament s'uneix a la `room` Socket assignada, extraient l'estat inicial via Nuxt Pinia a l'arrencada de pàgina.
2. Ell clica a un seient Fila 2, Asiento 1 lliure (`available`).
3. El seu navegador emet la sol·licitud per aquest ID de seient a través del canal bidireccional Socket.IO.
4. Tota la petició es posa com 'Loading...'. 
5. El sistema del backend aprova el Lock.
6. Amin rep notificació d'èxit ("Has assegurat el Seient 1, pots passar a pagar en els següents N minuts!").
7. **TOTS** els usuaris que estaven veient aquest mateix plànol a qualsevol part del món reben l'estat actualitzat per veure el F2A1 com `reserved` en viu (pintat groc, gris clar, o bloquejat) gairebé de manera instantània (Socket publish de NodeJS).

### 2. Condició de Carrera (Race Condition entre varis):
1. L'usuari A i Usuari B ambdós veuen Fila 1 com `available`.
2. Ells el trien a la vegueria de forma sub-mil·lisegonda.
3. El node.js i Redis accepten primer la consulta A procedint a validar i crear una reserva.
4. L'usuari A rep notificació d'èxit de Node. 
5. L'Usuari B, instants abans de ser atès, rep un denegat per validació atòmica o perquè descobreix el Lock present. B rep un missatge de caient i una alerta que diu "Algú més acaba de tenir el seient escollit de moment!". A més, el seient al seu escriptori ja torna a blocar-se groc.

### 3. Expiració Temporal:
1. El seient d'A està marcat al servidor fins a cert moment `reserved_until: timestamps.now() + 5m`.
2. Si un agent cron del Laravel, o un Job al Node.js arriba i descobreix que A mai va procedir a formalitzar pagament, executa la neteja.
3. S'emet la caiguda lliure per a aquest mateix SeatId a TOTA LA COMUNITAT d'espectadors connectada: es torna disponible per ser clicat. L'usuari A ha d'escollir enrere.
