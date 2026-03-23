-- Creació de l'estructura inicial de la base de dades per al sistema de compra d'entrades.
-- Aquest fitxer serà executat automàticament per Docker la primera vegada que s'aixequi la BD.

CREATE DATABASE IF NOT EXISTS entrades_db;
USE entrades_db;

-- Usuaris registrats
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Esdeveniments i informació de preus
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    event_date DATETIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT,
    capacity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Zones del plànol (per categoritzar, e.g. "Pista", "VIP", "Graderia A")
CREATE TABLE event_zones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- Cada seient és un recurs atòmic individual
CREATE TABLE seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_zone_id INT NOT NULL,
    identifier VARCHAR(50) NOT NULL, -- ex: "F13-S2"
    status ENUM('available', 'reserved', 'sold') DEFAULT 'available',
    reserved_by INT NULL, -- L'usuari que el bloqueja
    reserved_until DATETIME NULL, -- Quan acaba el timer de reserva (es netejarà si caduca)
    FOREIGN KEY (event_zone_id) REFERENCES event_zones(id) ON DELETE CASCADE,
    FOREIGN KEY (reserved_by) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE(event_zone_id, identifier)
);

-- Comandes finalitzades de manera satisfactòria
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'completed', 'canceled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
);

-- Les entrades ja venudes (la passarel·la entre la comanda total i el seient emès)
CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    seat_id INT NOT NULL,
    price_paid DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (seat_id) REFERENCES seats(id) ON DELETE RESTRICT,
    UNIQUE(seat_id) -- Un seient només pot ser venut a TICKET de forma única
);
