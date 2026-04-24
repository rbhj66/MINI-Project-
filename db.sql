-- AI-Powered Air Purification System Database Schema
-- Create database if not exists
CREATE DATABASE IF NOT EXISTS air_purification_system;
USE air_purification_system;

-- Table for storing sensor data
CREATE TABLE sensor_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aqi DECIMAL(5,2) NOT NULL COMMENT 'Air Quality Index value',
    temperature DECIMAL(5,2) NOT NULL COMMENT 'Temperature in Celsius',
    humidity DECIMAL(5,2) NOT NULL COMMENT 'Humidity percentage',
    latitude DECIMAL(10,8) NOT NULL COMMENT 'GPS latitude',
    longitude DECIMAL(11,8) NOT NULL COMMENT 'GPS longitude',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_created_at (created_at),
    INDEX idx_aqi (aqi)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table for robot status and control
CREATE TABLE robot_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status ENUM('ON', 'OFF') NOT NULL DEFAULT 'OFF' COMMENT 'Robot power status',
    mode ENUM('AUTO', 'MANUAL') NOT NULL DEFAULT 'MANUAL' COMMENT 'Control mode',
    spray_status ENUM('ON', 'OFF') NOT NULL DEFAULT 'OFF' COMMENT 'Spray system status',
    aqi_threshold INT NOT NULL DEFAULT 100 COMMENT 'AQI threshold for auto activation',
    current_latitude DECIMAL(10,8) DEFAULT NULL COMMENT 'Current robot latitude',
    current_longitude DECIMAL(11,8) DEFAULT NULL COMMENT 'Current robot longitude',
    target_latitude DECIMAL(10,8) DEFAULT NULL COMMENT 'Target destination latitude',
    target_longitude DECIMAL(11,8) DEFAULT NULL COMMENT 'Target destination longitude',
    battery_level INT DEFAULT 100 COMMENT 'Battery percentage',
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_mode (mode)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table for system alerts and notifications
CREATE TABLE alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message VARCHAR(255) NOT NULL COMMENT 'Alert message',
    level ENUM('INFO', 'WARNING', 'ERROR', 'CRITICAL') NOT NULL DEFAULT 'INFO' COMMENT 'Alert severity level',
    aqi_value DECIMAL(5,2) DEFAULT NULL COMMENT 'AQI value that triggered alert',
    location VARCHAR(100) DEFAULT NULL COMMENT 'Location where alert was triggered',
    is_read BOOLEAN DEFAULT FALSE COMMENT 'Whether alert has been read',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_level (level),
    INDEX idx_created_at (created_at),
    INDEX idx_is_read (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table for system logs and activities
CREATE TABLE system_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action VARCHAR(100) NOT NULL COMMENT 'Action performed',
    details TEXT DEFAULT NULL COMMENT 'Additional details about the action',
    user_id VARCHAR(50) DEFAULT 'system' COMMENT 'User who performed the action',
    ip_address VARCHAR(45) DEFAULT NULL COMMENT 'IP address of the user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default robot status
INSERT INTO robot_status (status, mode, spray_status, aqi_threshold, current_latitude, current_longitude) 
VALUES ('OFF', 'MANUAL', 'OFF', 100, 28.6139, 77.2090);

-- Insert sample sensor data for testing
INSERT INTO sensor_data (aqi, temperature, humidity, latitude, longitude) VALUES
(45, 25.5, 60, 28.6139, 77.2090),
(52, 26.0, 58, 28.6140, 77.2091),
(38, 24.8, 62, 28.6138, 77.2089),
(125, 27.2, 55, 28.6150, 77.2100),
(89, 25.9, 59, 28.6135, 77.2085),
(156, 28.1, 52, 28.6160, 77.2110),
(67, 26.3, 57, 28.6137, 77.2088),
(43, 25.1, 61, 28.6141, 77.2092),
(201, 29.0, 48, 28.6170, 77.2120),
(78, 26.8, 56, 28.6136, 77.2087);

-- Insert sample alerts
INSERT INTO alerts (message, level, aqi_value, location) VALUES
('High AQI detected in area', 'WARNING', 156, 'Sector 15'),
('AQI levels critical - Immediate action required', 'CRITICAL', 201, 'Sector 18'),
('Air quality improving', 'INFO', 45, 'Sector 12'),
('Moderate air quality - Monitor closely', 'WARNING', 89, 'Sector 10');

-- Insert sample system logs
INSERT INTO system_logs (action, details, user_id) VALUES
('Robot started', 'Robot activated in AUTO mode', 'admin'),
('Spray system activated', 'Purification spray started due to high AQI', 'system'),
('Threshold updated', 'AQI threshold changed from 100 to 120', 'admin'),
('Robot moved', 'Robot moved to new location for purification', 'system');
