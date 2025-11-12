CREATE DATABASE IF NOT EXISTS travelmate;
USE travelmate;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100),
  password VARCHAR(100),
  bio TEXT,
  profile_pic VARCHAR(255)
);

CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  content VARCHAR(255),
  image VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE places (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  location VARCHAR(100),
  info TEXT,
  nearby_hotels TEXT,
  attractions TEXT
);

INSERT INTO posts (user_id, content, image) VALUES
(1, 'Trip to Goa – Sunset on the beach!', 'goa.jpg'),
(1, 'Completed Kedarkantha Trek 🏔️', 'kedarkantha.jpg');

INSERT INTO places (name, location, info, nearby_hotels, attractions) VALUES
('Goa', 'India', 'Beautiful beaches and nightlife', 'Goa Beach Resort, Ocean View Hotel', 'Baga Beach, Fort Aguada'),
('Kedarkantha', 'Uttarakhand', 'Famous snow trek destination', 'Himalaya Inn, Trek Base Camp', 'Summit Point, Juda ka Talab');
