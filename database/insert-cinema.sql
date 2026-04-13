-- Insert Database for Cinema Platform
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE seats;
TRUNCATE TABLE event_zones;
TRUNCATE TABLE events;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Oppenheimer
INSERT INTO events (id, name, event_date, location, description, image_url, created_at, updated_at)
VALUES (1, 'Oppenheimer', '2026-04-10 18:00:00', 'Sala IMAX 1 - Cines Verdi', 'L''obra mestra de Christopher Nolan sobre el pare de la bomba atòmica.', 'https://images.unsplash.com/photo-1485846234645-a62644f84728?q=80&w=2059&auto=format&fit=crop', NOW(), NOW());

INSERT INTO event_zones (id, event_id, name, price, created_at, updated_at)
VALUES (1, 1, 'Plateia Standard', 9.50, NOW(), NOW());
INSERT INTO event_zones (id, event_id, name, price, created_at, updated_at)
VALUES (2, 1, 'VIP Experience', 15.00, NOW(), NOW());

-- 2. Dune: Part Two
INSERT INTO events (id, name, event_date, location, description, image_url, created_at, updated_at)
VALUES (2, 'Dune: Part Two', '2026-04-12 21:00:00', 'Sala 4 - Cines Verdi', 'La continuació de la saga èpica de Denis Villeneuve a Arrakis.', 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?q=80&w=1925&auto=format&fit=crop', NOW(), NOW());

INSERT INTO event_zones (id, event_id, name, price, created_at, updated_at)
VALUES (3, 2, 'General Admission', 10.50, NOW(), NOW());

-- 3. Inception (Special Screening)
INSERT INTO events (id, name, event_date, location, description, image_url, created_at, updated_at)
VALUES (3, 'Inception', '2026-04-15 22:30:00', 'Sala Clàssics - Cines Verdi', 'Viu de nou el somni dins del somni en pantalla gran.', 'https://images.unsplash.com/photo-1505686994434-e3cc5abf1330?q=80&w=2073&auto=format&fit=crop', NOW(), NOW());

INSERT INTO event_zones (id, event_id, name, price, created_at, updated_at)
VALUES (4, 3, 'Unique Zone', 8.00, NOW(), NOW());

-- Seats for Oppenheimer (Sala IMAX layout)
INSERT INTO seats (event_zone_id, `row`, `col`, status, created_at, updated_at)
SELECT 1, r, c, 'available', NOW(), NOW()
FROM (SELECT 1 AS r UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6) AS r_rows,
     (SELECT 1 AS c UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) AS c_cols;

-- Seats for Dune (Standard layout)
INSERT INTO seats (event_zone_id, `row`, `col`, status, created_at, updated_at)
SELECT 3, r, c, 'available', NOW(), NOW()
FROM (SELECT 1 AS r UNION SELECT 2 UNION SELECT 3 UNION SELECT 4) AS r_rows,
     (SELECT 1 AS c UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8) AS c_cols;
