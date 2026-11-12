CREATE DATABASE IF NOT EXISTS health_consultation;
USE health_consultation;

CREATE TABLE IF NOT EXISTS consultations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(60) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  age INT NOT NULL,
  gender VARCHAR(10) NOT NULL,
  consultation_type VARCHAR(20) NOT NULL,
  department VARCHAR(50) NOT NULL,
  preferred_date DATE NOT NULL,
  symptoms TEXT NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);