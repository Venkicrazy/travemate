CREATE DATABASE IF NOT EXISTS travelmate CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE travelmate;

-- users table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  bio TEXT DEFAULT NULL,
  profile_pic VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- posts table (user posts about places)
CREATE TABLE IF NOT EXISTS posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(255),
  content TEXT,
  image VARCHAR(255) DEFAULT NULL,
  place_name VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- travels table (achievements/visited places)
CREATE TABLE IF NOT EXISTS travels (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  place_name VARCHAR(255) NOT NULL,
  visited_on DATE DEFAULT NULL,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- places table (for search)
CREATE TABLE IF NOT EXISTS places (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  location VARCHAR(255),
  info TEXT,
  nearby TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- insert a sample place
INSERT INTO places (name, location, info, nearby)
VALUES ('Manali', 'Himachal Pradesh, India', 'Popular hill station with treks and scenic views.', 'Guest houses, homestays, trekking agencies');
