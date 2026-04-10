# Project Diagrams - Shôko Cinema

## Entity-Relationship Diagram (ERD)
Aquest diagrama mostra l'estructura de la base de dades i les relacions entre les entitats.

```mermaid
erDiagram
    USERS ||--o{ ORDERS : "fa"
    EVENTS ||--o{ EVENT_ZONES : "té"
    EVENT_ZONES ||--o{ SEATS : "conté"
    ORDERS ||--o{ TICKETS : "inclou"
    EVENTS ||--o{ ORDERS : "registra"
    SEATS ||--o| TICKETS : "està assignat a"

    USERS {
        bigint id PK
        string name
        string email
        string password
        boolean is_admin
    }

    EVENTS {
        bigint id PK
        string name
        text description
        string location
        datetime event_date
        string image_url
        string director
        string genre
    }

    EVENT_ZONES {
        bigint id PK
        bigint event_id FK
        string name
        decimal price
    }

    SEATS {
        bigint id PK
        bigint zone_id FK
        integer row
        integer col
        enum status "available, occupied"
    }

    ORDERS {
        bigint id PK
        bigint user_id FK
        bigint event_id FK
        decimal total_price
        string status "paid, pending"
        string order_number
    }

    TICKETS {
        bigint id PK
        bigint order_id FK
        bigint seat_id FK
        string ticket_code
        decimal price_paid
    }
```

## Sequence Diagram: Real-Time Reservation
Flux de treball de Socket.IO per a la sincronització de concurrència.

```mermaid
sequenceDiagram
    participant U as Usuari
    participant F as Frontend (Nuxt/Pinia)
    participant S as Socket Server (Node)
    participant B as Backend API (Laravel)
    participant D as Base de Dades

    U->>F: Clic en seient (available)
    F->>S: emit 'reserve_seat' {seatId, eventId, userId}
    S->>B: POST /api/reserve {seatId, userId}
    B->>D: Update seat set status='reserved'
    D-->>B: Success
    B-->>S: 200 OK
    S->>F: broadcast 'seat_reserved' {seatId}
    S-->>F: emit 'reservation_success'
    F->>U: Seient seleccionat + Timer iniciat
```
