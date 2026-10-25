-- Gampola Trails database (MySQL / MariaDB via XAMPP)
-- Import with phpMyAdmin: Import -> choose this file -> Go

CREATE DATABASE IF NOT EXISTS gampola_trails CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gampola_trails;

DROP TABLE IF EXISTS places;
DROP TABLE IF EXISTS admins;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

CREATE TABLE places (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    category VARCHAR(40) NOT NULL,          -- historical, religious, natural, recreational
    tags VARCHAR(120) DEFAULT '',           -- extra labels shown on the card
    description TEXT NOT NULL,
    distance_km DECIMAL(5,1) NOT NULL,      -- approx. distance from Gampola
    latitude DECIMAL(9,6) NOT NULL,
    longitude DECIMAL(9,6) NOT NULL,
    opening_hours VARCHAR(100) DEFAULT '',
    entrance_fee VARCHAR(100) DEFAULT '',
    facilities VARCHAR(255) DEFAULT '',
    visit_minutes INT NOT NULL DEFAULT 60,  -- suggested visit duration for the planner
    photo VARCHAR(255) DEFAULT '',          -- file name inside /uploads (empty = placeholder)
    source_url VARCHAR(255) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin: username "admin", password "admin123" 
INSERT INTO admins (username, password_hash) VALUES
('admin', '$2y$10$TJQQgV1uzAI9wMoq.14MB.TziZ6t9ehcGXwfpQTMaAuXjPuA1k09y');

INSERT INTO places (name, category, tags, description, distance_km, latitude, longitude, opening_hours, entrance_fee, facilities, visit_minutes, source_url) VALUES
('Ambuluwawa Tower', 'natural', 'Recreational', 'About 3,567 feet above sea level, the cone-shaped Ambuluwawa tower rises 48 metres and is reached by a winding climb. The adventure rewards you with some of the most stunning panoramic views in the hill country.', 5.0, 7.1633, 80.5290, '8:00 AM - 5:00 PM (daily)', 'Small entrance fee (check on arrival)', 'Parking, small shops, toilets', 120, 'https://ambuluwawa.com/'),
('Fool''s Bridge, Ulapane', 'historical', 'Architectural', 'A rare upside-down bridge design that is unique in the world. Some people believe the uncommon structure is a mistake, which led to its nickname "Fool''s Bridge".', 10.0, 7.1990, 80.6050, 'Open all day', 'Free', 'Roadside parking', 30, 'https://hikkasl.blogspot.com/2024/09/a-new-way-to-up-country-hills-sri-lanka.html'),
('Lankathilaka Vihara', 'historical', 'Religious', 'A Buddhist temple with integrated Hindu shrines in Udunuwara, Kandy. It is considered the most magnificent architectural edifice created during the Gampola era.', 15.0, 7.2190, 80.5870, '8:00 AM - 5:00 PM', 'Small ticket for foreign visitors', 'Parking, toilets', 60, 'https://en.wikipedia.org/wiki/Lankatilaka_Vihara'),
('Embekke Devalaya', 'historical', 'Religious', 'Built by King Vikramabahu III of the Gampola era (AD 1357-1374) and dedicated to God Kataragama. A local deity called "Devatha Bandara" is also worshipped here. Famous for its intricate wood carvings.', 13.0, 7.2388, 80.5668, '8:30 AM - 4:30 PM', 'Small ticket for foreign visitors', 'Parking, toilets', 60, 'https://en.wikipedia.org/wiki/Embekka_Devalaya'),
('Bowala View Point', 'natural', 'Scenic', 'A scenic location in the Bowala area where visitors can enjoy views of the surrounding hills and landscapes. A good recreational and photography stop.', 12.0, 7.2050, 80.6180, 'Open all day', 'Free', 'Roadside parking', 45, ''),
('National Railway Museum, Kadugannawa', 'historical', 'Educational', 'Opened on 27 December 2014 to commemorate the 150th anniversary of the first train run in Ceylon (Colombo to Ambepussa, 27 December 1864). Displays old locomotives, carriages and railway equipment.', 18.0, 7.2540, 80.5240, '9:00 AM - 5:00 PM (closed Mondays)', 'Small entrance fee', 'Parking, toilets, tea stall', 90, 'https://www.railway.gov.lk/'),
('Asupini Ella', 'natural', 'Waterfall', 'A famous and beautiful waterfall on the border of the Kegalle District, in the upper reaches of the river in the Aranayaka and Rakshawa area.', 20.0, 7.1990, 80.4660, 'Daylight hours', 'Free', 'Limited parking', 90, 'https://travelwithuma.com/waterfall/asupini-ella/'),
('Royal Botanic Gardens, Peradeniya', 'natural', 'Recreational', 'Established in 1821, six years after the British occupation of the Kandyan Kingdom. Home to orchids, spice gardens, giant trees and wide lawns - ideal for a relaxed walk.', 14.0, 7.2690, 80.5960, '7:30 AM - 5:00 PM (daily)', 'Entrance ticket required', 'Parking, cafe, toilets, paved paths', 150, 'https://botanicgardens.gov.lk/service/royal-botanic-gardens-peradeniya/'),
('Kotmale Dam', 'recreational', 'Natural', 'A large hydroelectric and irrigation dam in Kotmale. Three 67 MW turbines give a total installed capacity of 201 MW, making it the second largest hydroelectric power station in Sri Lanka.', 14.0, 7.0390, 80.6050, 'Daylight hours', 'Free (viewing from outside)', 'Roadside parking', 45, 'https://en.wikipedia.org/wiki/Kotmale_Dam'),
('Gadaladeniya Vihara', 'historical', 'Religious', 'A temple whose history goes back to the 14th century. According to historical reports it was built in 1344 under the patronage of King Bhuvanekabahu IV.', 14.0, 7.2320, 80.5590, '8:00 AM - 5:00 PM', 'Small ticket for foreign visitors', 'Parking, toilets', 60, 'https://en.wikipedia.org/wiki/Gadaladeniya_Vihara');
