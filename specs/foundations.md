---
title: Reserva Temporal de Seients (OpenSpec)
type: Foundation
---

## 1. Context
En entorns d'alta concurrència temporal per a esdeveniments massius, els clients fan click a un seient disponible a la vegada. Això provoca "condicions de carrera" on diversos usuaris volen adjudicar-se un només seient de manera temporal.
L'aplicació requereix desenvolupar la lògica que accepti que múltiples intents passen a la vegada, es defineixen com bloquejats, i només un guanyi aquesta plaça reservada (durant un temps màxim estipulat de transacció, com 5 minuts).

## 2. Objectius del Domini (Backend/Sockets)
  - Processar instantàniament les peticions de reserva via Socket.IO amb integració Node.js (temps real) connectat al Backend.
  - Implementar sistemes robustos anti-conflicte usant Lock atòmics a nivell de la base de dades (MySQL Transaccions O exclusivament un emmagatzematge en memòria com Redis per controlar l'estat en qüestió de milisegons).
  - Gestionar temporitzadors actius per alliberar forçosament els `reserved_until` que es caduquin (Expiring locks).

## 3. Objectius de Frontend (Vue 3 / Nuxt)
  - Interfície Pinia centralitzada exclusivament que serà la que manipuli l'arribada d'estats dels seients directament dels Events del Servei Socket.
  - Mostrar els temporitzadors visualment calculats des de la data donada en l'acceptació de la reserva pel servidor.
  - Generar alertes coherents un usuari ha "perdut" la cursa d'un seient contra un altre usuari que ha clicat més ràpid el plànol.

## 4. Restriccions
- "Font Única de la Veritat": El Frontend de Nuxt no pot autoconvence's de cap canvi local "emit" de Vue fins que NO arribi l'autorització o broadcast final del backend Socket.
- La confirmació màxima de nombre de seients seleccionats per client està subjecte a canvis (Definit globalment, p.ex. a `N=4` màxim).
