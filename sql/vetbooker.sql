-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS vetbooker;

-- Use the database
USE vetbooker;

-- Drop existing tables to avoid duplicate primary key errors (optional, remove if you want to keep data)
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS users;

-- Create the appointments table
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service VARCHAR(100) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    client_name VARCHAR(100) NOT NULL,
    client_email VARCHAR(100) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the users table for admin authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert a default admin user (username: admin, password: Admin123)
INSERT INTO users (username, password) VALUES ('admin', '$2y$10$QfL3X9z7Z8z1y2x3y4z5A.3z2y1x0w9v8u7t6r5q4p3o2n1m0l9k');
