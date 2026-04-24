-- AI-Powered Air Purification System Database Schema
-- Enhanced with AI features and machine learning support

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS air_purification_system;
USE air_purification_system;

-- Drop existing tables if they exist (for clean setup)
DROP TABLE IF EXISTS ai_logs;
DROP TABLE IF EXISTS predictions;
DROP TABLE IF EXISTS system_logs;
DROP TABLE IF EXISTS alerts;
DROP TABLE IF EXISTS robot_status;
DROP TABLE IF EXISTS sensor_data;

-- Enhanced sensor_data table with AI features
CREATE TABLE sensor_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aqi DECIMAL(5,2) NOT NULL COMMENT 'Air Quality Index value',
    temperature DECIMAL(5,2) NOT NULL COMMENT 'Temperature in Celsius',
    humidity DECIMAL(5,2) NOT NULL COMMENT 'Humidity percentage',
    latitude DECIMAL(10,8) NOT NULL COMMENT 'GPS latitude',
    longitude DECIMAL(11,8) NOT NULL COMMENT 'GPS longitude',
    pm25 DECIMAL(6,2) DEFAULT NULL COMMENT 'PM2.5 concentration',
    pm10 DECIMAL(6,2) DEFAULT NULL COMMENT 'PM10 concentration',
    no2 DECIMAL(6,2) DEFAULT NULL COMMENT 'NO2 concentration',
    co DECIMAL(6,2) DEFAULT NULL COMMENT 'CO concentration',
    so2 DECIMAL(6,2) DEFAULT NULL COMMENT 'SO2 concentration',
    ozone DECIMAL(6,2) DEFAULT NULL COMMENT 'Ozone concentration',
    pressure DECIMAL(7,2) DEFAULT NULL COMMENT 'Atmospheric pressure',
    wind_speed DECIMAL(5,2) DEFAULT NULL COMMENT 'Wind speed in m/s',
    wind_direction INT DEFAULT NULL COMMENT 'Wind direction in degrees',
    is_anomaly BOOLEAN DEFAULT FALSE COMMENT 'Flagged as anomaly by AI',
    anomaly_score DECIMAL(5,2) DEFAULT NULL COMMENT 'Anomaly detection score',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_created_at (created_at),
    INDEX idx_aqi (aqi),
    INDEX idx_location (latitude, longitude),
    INDEX idx_anomaly (is_anomaly),
    INDEX idx_created_at_aqi (created_at, aqi)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Enhanced robot_status table with AI decision support
CREATE TABLE robot_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status ENUM('ON', 'OFF') NOT NULL DEFAULT 'OFF' COMMENT 'Robot power status',
    mode ENUM('AUTO', 'MANUAL', 'AI') NOT NULL DEFAULT 'MANUAL' COMMENT 'Control mode (AI mode added)',
    spray_status ENUM('ON', 'OFF') NOT NULL DEFAULT 'OFF' COMMENT 'Spray system status',
    spray_intensity ENUM('OFF', 'LOW', 'MEDIUM', 'HIGH') DEFAULT 'OFF' COMMENT 'AI-controlled spray intensity',
    aqi_threshold INT NOT NULL DEFAULT 100 COMMENT 'AQI threshold for activation',
    current_latitude DECIMAL(10,8) DEFAULT NULL COMMENT 'Current robot latitude',
    current_longitude DECIMAL(11,8) DEFAULT NULL COMMENT 'Current robot longitude',
    target_latitude DECIMAL(10,8) DEFAULT NULL COMMENT 'Target destination latitude',
    target_longitude DECIMAL(11,8) DEFAULT NULL COMMENT 'Target destination longitude',
    battery_level INT DEFAULT 100 COMMENT 'Battery percentage',
    last_ai_decision VARCHAR(100) DEFAULT NULL COMMENT 'Last AI decision made',
    ai_confidence DECIMAL(3,2) DEFAULT NULL COMMENT 'AI decision confidence score',
    predicted_arrival_time TIMESTAMP DEFAULT NULL COMMENT 'Predicted arrival at target',
    route_optimization_score DECIMAL(5,2) DEFAULT NULL COMMENT 'Route optimization efficiency score',
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_mode (mode),
    INDEX idx_ai_decision (last_ai_decision),
    INDEX idx_battery (battery_level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Enhanced alerts table with AI-generated alerts
CREATE TABLE alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message VARCHAR(255) NOT NULL COMMENT 'Alert message',
    level ENUM('INFO', 'WARNING', 'ERROR', 'CRITICAL') NOT NULL DEFAULT 'INFO' COMMENT 'Alert severity level',
    aqi_value DECIMAL(5,2) DEFAULT NULL COMMENT 'AQI value that triggered alert',
    location VARCHAR(100) DEFAULT NULL COMMENT 'Location where alert was triggered',
    is_read BOOLEAN DEFAULT FALSE COMMENT 'Whether alert has been read',
    source ENUM('SYSTEM', 'AI', 'SENSOR', 'USER') DEFAULT 'SYSTEM' COMMENT 'Alert source',
    ai_prediction_id INT DEFAULT NULL COMMENT 'Related AI prediction ID',
    confidence DECIMAL(3,2) DEFAULT NULL COMMENT 'AI confidence if AI-generated',
    action_required BOOLEAN DEFAULT FALSE COMMENT 'Whether action is required',
    action_taken VARCHAR(100) DEFAULT NULL COMMENT 'Action taken for this alert',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_level (level),
    INDEX idx_created_at (created_at),
    INDEX idx_is_read (is_read),
    INDEX idx_source (source),
    INDEX idx_ai_prediction (ai_prediction_id),
    FOREIGN KEY (ai_prediction_id) REFERENCES predictions(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- New predictions table for AI predictions
CREATE TABLE predictions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    predicted_aqi DECIMAL(5,2) NOT NULL COMMENT 'Predicted AQI value',
    prediction_type ENUM('15min', '30min', '1hour', '24hour') DEFAULT '15min' COMMENT 'Prediction time horizon',
    confidence DECIMAL(3,2) DEFAULT 0.0 COMMENT 'Prediction confidence score',
    model_used VARCHAR(50) DEFAULT 'linear_regression' COMMENT 'ML model used for prediction',
    features_used JSON DEFAULT NULL COMMENT 'Features used in prediction',
    actual_aqi DECIMAL(5,2) DEFAULT NULL COMMENT 'Actual AQI when prediction time passes',
    prediction_accuracy DECIMAL(5,2) DEFAULT NULL COMMENT 'Accuracy of prediction (calculated later)',
    is_correct BOOLEAN DEFAULT NULL COMMENT 'Whether prediction was correct',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP DEFAULT NULL COMMENT 'When prediction expires',
    INDEX idx_created_at (created_at),
    INDEX idx_prediction_type (prediction_type),
    INDEX idx_confidence (confidence),
    INDEX idx_expires_at (expires_at),
    INDEX idx_accuracy (prediction_accuracy)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- New AI logs table for AI decision tracking
CREATE TABLE ai_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    decision VARCHAR(100) NOT NULL COMMENT 'AI decision made',
    reason TEXT DEFAULT NULL COMMENT 'Reasoning behind decision',
    input_data JSON DEFAULT NULL COMMENT 'Input data used for decision',
    confidence DECIMAL(3,2) DEFAULT 0.0 COMMENT 'Decision confidence score',
    algorithm_used VARCHAR(50) DEFAULT 'decision_engine' COMMENT 'AI algorithm used',
    execution_time_ms INT DEFAULT NULL COMMENT 'Time taken to make decision in milliseconds',
    outcome ENUM('SUCCESS', 'FAILED', 'PENDING') DEFAULT 'PENDING' COMMENT 'Decision outcome',
    user_feedback ENUM('POSITIVE', 'NEGATIVE', 'NEUTRAL') DEFAULT NULL COMMENT 'User feedback on decision',
    sensor_data_id INT DEFAULT NULL COMMENT 'Related sensor reading',
    prediction_id INT DEFAULT NULL COMMENT 'Related prediction',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_decision (decision),
    INDEX idx_created_at (created_at),
    INDEX idx_algorithm (algorithm_used),
    INDEX idx_confidence (confidence),
    INDEX idx_outcome (outcome),
    INDEX idx_sensor_data (sensor_data_id),
    INDEX idx_prediction (prediction_id),
    FOREIGN KEY (sensor_data_id) REFERENCES sensor_data(id) ON DELETE SET NULL,
    FOREIGN KEY (prediction_id) REFERENCES predictions(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Enhanced system_logs table with AI features
CREATE TABLE system_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action VARCHAR(100) NOT NULL COMMENT 'Action performed',
    details TEXT DEFAULT NULL COMMENT 'Additional details about the action',
    user_id VARCHAR(50) DEFAULT 'system' COMMENT 'User who performed the action',
    ip_address VARCHAR(45) DEFAULT NULL COMMENT 'IP address of the user',
    session_id VARCHAR(100) DEFAULT NULL COMMENT 'Session identifier',
    user_agent TEXT DEFAULT NULL COMMENT 'User agent string',
    response_time_ms INT DEFAULT NULL COMMENT 'Response time in milliseconds',
    ai_involved BOOLEAN DEFAULT FALSE COMMENT 'Whether AI was involved in this action',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_action (action),
    INDEX idx_created_at (created_at),
    INDEX idx_user_id (user_id),
    INDEX idx_ai_involved (ai_involved),
    INDEX idx_response_time (response_time_ms)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default robot status with AI mode support
INSERT INTO robot_status (status, mode, spray_status, aqi_threshold, current_latitude, current_longitude, spray_intensity) 
VALUES ('OFF', 'AI', 'OFF', 100, 28.6139, 77.2090, 'OFF');

-- Insert comprehensive sample sensor data for ML training
INSERT INTO sensor_data (aqi, temperature, humidity, latitude, longitude, pm25, pm10, no2, co, so2, ozone, pressure, wind_speed, wind_direction, created_at) VALUES
-- Recent data (last 2 hours) with detailed pollutants
(45, 25.5, 60, 28.6139, 77.2090, 12.5, 25.3, 15.2, 0.8, 8.1, 45.2, 1013.2, 3.2, 270, DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(52, 26.0, 58, 28.6140, 77.2091, 18.3, 32.1, 22.5, 1.2, 12.4, 52.1, 1012.8, 4.1, 285, DATE_SUB(NOW(), INTERVAL 1 HOUR 50 MINUTE)),
(38, 24.8, 62, 28.6138, 77.2089, 8.7, 15.9, 11.3, 0.5, 5.2, 38.7, 1013.8, 2.8, 255, DATE_SUB(NOW(), INTERVAL 1 HOUR 40 MINUTE)),
(125, 27.2, 55, 28.6150, 77.2100, 45.8, 78.2, 48.5, 2.8, 28.7, 125.4, 1011.5, 5.8, 310, DATE_SUB(NOW(), INTERVAL 1 HOUR 30 MINUTE)),
(89, 25.9, 59, 28.6135, 77.2085, 32.4, 56.7, 35.8, 1.9, 18.3, 89.2, 1012.4, 4.3, 295, DATE_SUB(NOW(), INTERVAL 1 HOUR 20 MINUTE)),
(156, 28.1, 52, 28.6160, 77.2110, 58.9, 95.4, 62.3, 3.5, 38.1, 156.8, 1010.9, 6.2, 320, DATE_SUB(NOW(), INTERVAL 1 HOUR 10 MINUTE)),
(67, 26.3, 57, 28.6137, 77.2088, 24.1, 42.8, 28.9, 1.4, 14.7, 67.3, 1012.7, 3.8, 280, DATE_SUB(NOW(), INTERVAL 1 HOUR)),
(43, 25.1, 61, 28.6141, 77.2092, 9.8, 17.6, 12.8, 0.6, 6.4, 43.1, 1013.5, 3.1, 265, DATE_SUB(NOW(), INTERVAL 50 MINUTE)),
(201, 29.0, 48, 28.6170, 77.2120, 78.5, 125.8, 85.2, 4.8, 52.3, 201.7, 1009.8, 7.1, 335, DATE_SUB(NOW(), INTERVAL 40 MINUTE)),
(78, 26.8, 56, 28.6136, 77.2087, 28.7, 49.8, 31.2, 1.7, 16.8, 78.4, 1012.2, 4.0, 290, DATE_SUB(NOW(), INTERVAL 30 MINUTE)),
(92, 27.1, 54, 28.6145, 77.2095, 35.2, 58.9, 38.7, 2.1, 21.4, 92.6, 1011.8, 4.5, 305, DATE_SUB(NOW(), INTERVAL 20 MINUTE)),
(58, 25.7, 59, 28.6134, 77.2084, 19.8, 34.2, 22.1, 1.1, 11.3, 58.9, 1013.1, 3.5, 275, DATE_SUB(NOW(), INTERVAL 10 MINUTE)),
(41, 24.9, 63, 28.6142, 77.2093, 11.2, 19.8, 13.5, 0.7, 7.1, 41.3, 1013.7, 3.0, 260, DATE_SUB(NOW(), INTERVAL 5 MINUTE)),
(73, 26.5, 56, 28.6138, 77.2090, 26.4, 45.7, 29.8, 1.5, 15.2, 73.8, 1012.5, 3.9, 285, DATE_SUB(NOW(), INTERVAL 2 MINUTE)),
(65, 25.8, 58, 28.6140, 77.2091, 22.1, 38.9, 25.3, 1.3, 12.8, 65.7, 1012.9, 3.6, 280, NOW()),

-- Historical data for analytics (last 24 hours)
(55, 26.2, 57, 28.6143, 77.2094, 17.8, 31.2, 20.1, 1.0, 10.5, 55.9, 1012.6, 3.7, 290, DATE_SUB(NOW(), INTERVAL 3 HOUR)),
(48, 25.4, 61, 28.6136, 77.2086, 13.5, 23.8, 15.7, 0.8, 7.9, 48.4, 1013.4, 3.3, 270, DATE_SUB(NOW(), INTERVAL 4 HOUR)),
(82, 27.0, 55, 28.6152, 77.2102, 31.2, 54.7, 34.8, 1.8, 18.6, 82.5, 1011.9, 4.8, 315, DATE_SUB(NOW(), INTERVAL 5 HOUR)),
(71, 26.8, 56, 28.6139, 77.2090, 26.8, 46.9, 29.7, 1.4, 15.3, 71.8, 1012.3, 4.2, 300, DATE_SUB(NOW(), INTERVAL 6 HOUR)),
(39, 24.7, 64, 28.6135, 77.2083, 8.9, 15.6, 10.8, 0.5, 5.4, 39.2, 1013.9, 2.9, 255, DATE_SUB(NOW(), INTERVAL 7 HOUR)),
(95, 27.5, 53, 28.6165, 77.2115, 38.7, 67.3, 42.5, 2.3, 23.1, 95.8, 1011.2, 5.3, 325, DATE_SUB(NOW(), INTERVAL 8 HOUR)),
(63, 26.0, 58, 28.6141, 77.2092, 20.3, 35.8, 23.2, 1.1, 11.8, 63.5, 1012.8, 3.8, 285, DATE_SUB(NOW(), INTERVAL 9 HOUR)),
(76, 27.2, 54, 28.6138, 77.2089, 29.4, 51.2, 32.1, 1.6, 16.7, 76.9, 1012.0, 4.4, 310, DATE_SUB(NOW(), INTERVAL 10 HOUR)),
(51, 25.9, 60, 28.6144, 77.2096, 14.7, 26.1, 16.9, 0.9, 8.7, 51.7, 1013.2, 3.4, 275, DATE_SUB(NOW(), INTERVAL 11 HOUR)),
(68, 26.4, 57, 28.6139, 77.2090, 23.6, 41.3, 26.8, 1.2, 13.4, 68.6, 1012.4, 4.0, 295, DATE_SUB(NOW(), INTERVAL 12 HOUR)),
(44, 25.2, 62, 28.6137, 77.2088, 10.1, 17.9, 11.9, 0.6, 6.2, 44.3, 1013.6, 3.2, 265, DATE_SUB(NOW(), INTERVAL 13 HOUR)),
(87, 27.1, 55, 28.6153, 77.2103, 33.8, 58.9, 37.2, 1.9, 19.8, 87.4, 1011.7, 4.9, 320, DATE_SUB(NOW(), INTERVAL 14 HOUR)),
(59, 26.1, 59, 28.6140, 77.2091, 18.9, 33.1, 21.4, 1.0, 10.9, 59.3, 1013.0, 3.6, 280, DATE_SUB(NOW(), INTERVAL 15 HOUR)),
(72, 26.7, 56, 28.6138, 77.2089, 27.1, 47.5, 30.3, 1.5, 15.6, 72.7, 1012.2, 4.3, 305, DATE_SUB(NOW(), INTERVAL 16 HOUR)),
(46, 25.3, 61, 28.6142, 77.2093, 11.8, 20.8, 13.7, 0.7, 7.3, 46.1, 1013.5, 3.3, 270, DATE_SUB(NOW(), INTERVAL 17 HOUR)),
(79, 26.9, 55, 28.6136, 77.2085, 30.5, 53.2, 33.9, 1.7, 17.4, 79.8, 1011.8, 4.6, 315, DATE_SUB(NOW(), INTERVAL 18 HOUR)),
(53, 25.8, 60, 28.6141, 77.2092, 15.7, 27.6, 17.9, 0.9, 9.1, 53.4, 1013.1, 3.5, 285, DATE_SUB(NOW(), INTERVAL 19 HOUR)),
(66, 26.5, 57, 28.6139, 77.2090, 24.9, 43.6, 28.1, 1.3, 14.2, 66.8, 1012.3, 4.1, 300, DATE_SUB(NOW(), INTERVAL 20 HOUR)),
(41, 24.9, 63, 28.6137, 77.2088, 9.3, 16.4, 11.1, 0.5, 5.8, 41.5, 1013.8, 3.0, 260, DATE_SUB(NOW(), INTERVAL 21 HOUR)),
(74, 26.8, 56, 28.6143, 77.2094, 28.2, 49.4, 31.6, 1.6, 16.1, 74.9, 1012.0, 4.4, 310, DATE_SUB(NOW(), INTERVAL 22 HOUR)),
(57, 26.0, 59, 28.6138, 77.2089, 17.2, 30.3, 19.8, 1.0, 10.2, 57.6, 1013.0, 3.6, 280, DATE_SUB(NOW(), INTERVAL 23 HOUR)),
(69, 26.6, 57, 28.6140, 77.2091, 23.7, 41.8, 26.9, 1.2, 13.7, 69.5, 1012.2, 4.0, 295, DATE_SUB(NOW(), INTERVAL 24 HOUR));

-- Insert sample AI predictions
INSERT INTO predictions (predicted_aqi, prediction_type, confidence, model_used, features_used, created_at, expires_at) VALUES
(78.5, '15min', 0.85, 'linear_regression', '{"aqi": 65, "temperature": 25.8, "humidity": 58, "hour": 11}', NOW(), DATE_ADD(NOW(), INTERVAL 15 MINUTE)),
(82.3, '30min', 0.82, 'linear_regression', '{"aqi": 65, "temperature": 25.8, "humidity": 58, "hour": 11}', NOW(), DATE_ADD(NOW(), INTERVAL 30 MINUTE)),
(91.7, '1hour', 0.78, 'linear_regression', '{"aqi": 65, "temperature": 25.8, "humidity": 58, "hour": 11}', NOW(), DATE_ADD(NOW(), INTERVAL 1 HOUR)),
(145.2, '15min', 0.91, 'linear_regression', '{"aqi": 125, "temperature": 27.2, "humidity": 55, "hour": 14}', NOW(), DATE_ADD(NOW(), INTERVAL 15 MINUTE)),
(168.9, '30min', 0.88, 'linear_regression', '{"aqi": 125, "temperature": 27.2, "humidity": 55, "hour": 14}', NOW(), DATE_ADD(NOW(), INTERVAL 30 MINUTE)),
(195.4, '1hour', 0.84, 'linear_regression', '{"aqi": 125, "temperature": 27.2, "humidity": 55, "hour": 14}', NOW(), DATE_ADD(NOW(), INTERVAL 1 HOUR));

-- Insert sample AI decisions
INSERT INTO ai_logs (decision, reason, input_data, confidence, algorithm_used, execution_time_ms, outcome, sensor_data_id, prediction_id) VALUES
('activate_purification', 'High AQI detected: 125', '{"current_aqi": 125, "predicted_aqi": 145.2, "threshold": 100}', 0.91, 'decision_engine', 45, 'SUCCESS', 4, 4),
('move_to_high_aqi', 'Predicted high AQI: 91.7', '{"current_aqi": 65, "predicted_aqi": 91.7, "threshold": 100}', 0.78, 'decision_engine', 38, 'SUCCESS', 15, 2),
('investigate_anomaly', 'Unusual AQI pattern detected', '{"current_aqi": 201, "predicted_aqi": 168.9, "difference": 32.1}', 0.95, 'anomaly_detection', 52, 'SUCCESS', 9, 5),
('monitor', 'Normal conditions', '{"current_aqi": 45, "predicted_aqi": 48.5, "threshold": 100}', 0.85, 'decision_engine', 25, 'SUCCESS', 1, 1),
('standby', 'Low AQI with stable trend', '{"current_aqi": 38, "predicted_aqi": 42.1, "threshold": 100}', 0.82, 'decision_engine', 30, 'SUCCESS', 3, 1);

-- Insert enhanced alerts with AI source
INSERT INTO alerts (message, level, aqi_value, location, source, ai_prediction_id, confidence, action_required, created_at) VALUES
('AI Prediction: High AQI expected in 15 minutes', 'WARNING', 78.5, 'Sector 15', 'AI', 1, 0.85, TRUE, DATE_SUB(NOW(), INTERVAL 10 MINUTE)),
('Anomaly Detected: Unusual AQI spike', 'ERROR', 201, 'Sector 18', 'AI', 4, 0.95, TRUE, DATE_SUB(NOW(), INTERVAL 40 MINUTE)),
('AI Decision: Robot moving to high AQI area', 'INFO', NULL, 'AI System', 'AI', 2, 0.78, FALSE, DATE_SUB(NOW(), INTERVAL 30 MINUTE)),
('High AQI detected - AI activated purification', 'WARNING', 125, 'Sector 15', 'AI', 4, 0.91, TRUE, DATE_SUB(NOW(), INTERVAL 1 HOUR 30 MINUTE)),
('System started successfully', 'INFO', NULL, 'System', 'SYSTEM', NULL, NULL, FALSE, DATE_SUB(NOW(), INTERVAL 2 HOUR)),
('AI Model trained with 50 data points', 'INFO', NULL, 'AI System', 'AI', NULL, NULL, FALSE, DATE_SUB(NOW(), INTERVAL 3 HOUR)),
('Spray system activated by AI', 'INFO', NULL, 'Robot Location', 'AI', 1, 0.91, FALSE, DATE_SUB(NOW(), INTERVAL 1 HOUR)),
('Temperature spike detected by AI', 'WARNING', 29.0, 'Sector 18', 'AI', NULL, 0.88, TRUE, DATE_SUB(NOW(), INTERVAL 40 MINUTE));

-- Insert enhanced system logs with AI tracking
INSERT INTO system_logs (action, details, user_id, ip_address, response_time_ms, ai_involved) VALUES
('AI Prediction Generated', '15min AQI prediction: 78.5 with 85% confidence', 'ai_system', '127.0.0.1', 120, TRUE),
('AI Decision Made', 'Robot activated purification due to high AQI', 'ai_system', '127.0.0.1', 85, TRUE),
('Data received', 'New sensor data received from device', 'sensor_device', '192.168.1.100', 45, FALSE),
('Alert generated', 'AI-generated alert for predicted high AQI', 'ai_system', '127.0.0.1', 65, TRUE),
('Model training completed', 'Linear regression model trained successfully', 'ai_system', '127.0.0.1', 2500, TRUE),
('Robot moved', 'Robot moved to high AQI location per AI decision', 'ai_system', '127.0.0.1', 150, TRUE),
('Anomaly detected', 'AI detected unusual AQI pattern', 'ai_system', '127.0.0.1', 95, TRUE),
('System initialized', 'Database initialized with AI features', 'system', '127.0.0.1', 800, FALSE);

-- Create AI-specific stored procedures
DELIMITER //

-- Procedure to get AI dashboard summary
CREATE PROCEDURE GetAIDashboardSummary()
BEGIN
    SELECT 
        COUNT(*) as total_predictions,
        AVG(confidence) as avg_confidence,
        COUNT(CASE WHEN actual_aqi IS NOT NULL THEN 1 END) as verified_predictions,
        AVG(CASE WHEN actual_aqi IS NOT NULL THEN 
            100 - (ABS(predicted_aqi - actual_aqi) / actual_aqi * 100) 
            ELSE NULL END) as avg_accuracy
    FROM predictions 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR);
    
    SELECT 
        COUNT(*) as total_decisions,
        AVG(confidence) as avg_decision_confidence,
        COUNT(CASE WHEN outcome = 'SUCCESS' THEN 1 END) as successful_decisions,
        AVG(execution_time_ms) as avg_execution_time
    FROM ai_logs 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR);
    
    SELECT 
        COUNT(*) as total_anomalies,
        AVG(anomaly_score) as avg_anomaly_score
    FROM sensor_data 
    WHERE is_anomaly = TRUE 
    AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR);
END//

-- Procedure to get AI performance metrics
CREATE PROCEDURE GetAIPerformanceMetrics()
BEGIN
    -- Prediction accuracy by type
    SELECT 
        prediction_type,
        AVG(CASE WHEN actual_aqi IS NOT NULL THEN 
            100 - (ABS(predicted_aqi - actual_aqi) / actual_aqi * 100) 
            ELSE NULL END) as accuracy,
        COUNT(*) as total_predictions
    FROM predictions 
    WHERE actual_aqi IS NOT NULL
    GROUP BY prediction_type;
    
    -- Decision success rate
    SELECT 
        decision,
        COUNT(CASE WHEN outcome = 'SUCCESS' THEN 1 END) as successful,
        COUNT(*) as total,
        (COUNT(CASE WHEN outcome = 'SUCCESS' THEN 1 END) / COUNT(*) * 100) as success_rate
    FROM ai_logs 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
    GROUP BY decision;
    
    -- Model performance
    SELECT 
        model_used,
        AVG(confidence) as avg_confidence,
        COUNT(*) as usage_count
    FROM predictions 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
    GROUP BY model_used;
END//

DELIMITER ;

-- Show final database structure
SHOW TABLES;

-- Display sample data counts
SELECT 
    'sensor_data' as table_name, COUNT(*) as record_count FROM sensor_data
UNION ALL
SELECT 
    'robot_status' as table_name, COUNT(*) as record_count FROM robot_status
UNION ALL
SELECT 
    'alerts' as table_name, COUNT(*) as record_count FROM alerts
UNION ALL
SELECT 
    'predictions' as table_name, COUNT(*) as record_count FROM predictions
UNION ALL
SELECT 
    'ai_logs' as table_name, COUNT(*) as record_count FROM ai_logs
UNION ALL
SELECT 
    'system_logs' as table_name, COUNT(*) as record_count FROM system_logs;
