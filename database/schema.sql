-- StayEase database schema
-- Import this file first: mysql -u USER -p DBNAME < schema.sql

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS hotels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    location VARCHAR(120) NOT NULL,
    category ENUM('luxury','budget','resort') NOT NULL,
    description TEXT,
    image_url VARCHAR(500),
    badge VARCHAR(60),
    price DECIMAL(10,2) NOT NULL,
    rating DECIMAL(2,1) DEFAULT 0,
    reviews_count INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    hotel_id INT NOT NULL,
    guest_name VARCHAR(120) NOT NULL,
    guest_email VARCHAR(190) NOT NULL,
    checkin DATE NOT NULL,
    checkout DATE NOT NULL,
    guests INT DEFAULT 1,
    status ENUM('pending','confirmed','cancelled') DEFAULT 'confirmed',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(190) NOT NULL UNIQUE,
    subscribed_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    hotel_id INT NOT NULL,
    UNIQUE KEY uniq_fav (user_id, hotel_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data matching the four hotels already in the HTML
INSERT INTO hotels (name, location, category, description, image_url, badge, price, rating, reviews_count) VALUES
('Grand Horizon Hotel', 'Dubai, UAE', 'luxury', 'Luxury rooms with stunning city views and premium facilities.', 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=900&q=85', 'Top Rated', 180.00, 4.9, 128),
('Ocean Breeze Resort', 'Bali, Indonesia', 'resort', 'Relax in a beautiful tropical resort surrounded by nature.', 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=900&q=85', 'Popular', 145.00, 4.8, 96),
('Urban Comfort Hotel', 'Paris, France', 'budget', 'Affordable rooms with modern amenities in the heart of the city.', 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?auto=format&fit=crop&w=900&q=85', 'Best Value', 95.00, 4.7, 74),
('Skyline Grand Hotel', 'Singapore', 'luxury', 'Enjoy elegant rooms and breathtaking skyline views.', 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=900&q=85', 'Luxury', 220.00, 4.9, 210);
