# Foundations: Real-Time Seat Reservation

## Context
Aquest projecte simula una plataforma de venda d'entrades d'alta demanda on la concurrència és el repte principal.

## Objectius
1. Garantir que dos usuaris no puguin reservar el mateix seient simultàniament.
2. Sincronitzar l'estat dels seients a tots els clients en temps real.
3. Gestionar l'expiració de reserves temporals des del servidor.

## Restriccions
- El servidor (Node.js/Laravel) és l'única font de veritat.
- El client no pot forçar canvis d'estat manualment.
- Ús obligatori de Socket.IO per a la comunicació bidireccional.
