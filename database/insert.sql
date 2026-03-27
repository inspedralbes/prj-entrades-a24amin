-- Insert Database for Ticket Platform (Invented Events)
-- Clean existing data to avoid conflicts (Optional, but recommended for fresh setup)
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE seats;
TRUNCATE TABLE event_zones;
TRUNCATE TABLE events;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Sónar Festival 2026
INSERT INTO events (id, name, event_date, location, description, image_url, created_at, updated_at)
VALUES (1, 'Sónar Festival 2026', '2026-06-12 10:00:00', 'Fira Montjuïc, Barcelona', 'El festival de música, creativitat i tecnologia més important.', 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?q=80&w=2070&auto=format&fit=crop', NOW(), NOW());

INSERT INTO event_zones (id, event_id, name, price, created_at, updated_at)
VALUES (1, 1, 'SonarDay Pass', 60.00, NOW(), NOW());
INSERT INTO event_zones (id, event_id, name, price, created_at, updated_at)
VALUES (2, 1, 'SonarNight VIP', 120.00, NOW(), NOW());

-- 2. F1 Spanish Grand Prix 2026
INSERT INTO events (id, name, event_date, location, description, image_url, created_at, updated_at)
VALUES (2, 'F1 Spanish Grand Prix 2026', '2026-05-24 14:00:00', 'Circuit de Catalunya, Montmeló', 'Sent l''adrenalina de la Fórmula 1.', 'https://images.unsplash.com/photo-1530661634027-4f691886178a?q=80&w=2070&auto=format&fit=crop', NOW(), NOW());

INSERT INTO event_zones (id, event_id, name, price, created_at, updated_at)
VALUES (3, 2, 'Grandstand A', 350.00, NOW(), NOW());

-- 3. The Weeknd - After Hours
INSERT INTO events (id, name, event_date, location, description, image_url, created_at, updated_at)
VALUES (3, 'The Weeknd - After Hours 2026', '2026-07-20 21:00:00', 'Estadi Olímpic, Barcelona', 'L''artista global The Weeknd presenta el seu nou show.', 'https://images.unsplash.com/photo-1493225255756-d9584f8606e9?q=80&w=2070&auto=format&fit=crop', NOW(), NOW());

INSERT INTO event_zones (id, event_id, name, price, created_at, updated_at)
VALUES (4, 3, 'Pista Premium', 180.00, NOW(), NOW());

-- Seats for Event 1 (Sample)
INSERT INTO seats (event_zone_id, row, col, status, created_at, updated_at)
SELECT 1, r, c, 'available', NOW(), NOW()
FROM (SELECT 1 r UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) rows,
     (SELECT 1 c UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10) cols;

-- Seats for Event 2 (Sample)
INSERT INTO seats (event_zone_id, row, col, status, created_at, updated_at)
SELECT 3, r, c, 'available', NOW(), NOW()
FROM (SELECT 1 r UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) rows,
     (SELECT 1 c UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8) cols;
