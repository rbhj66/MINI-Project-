<?php
/**
 * Comprehensive AI Simulation System
 * Generates realistic data for testing AI features
 */

require_once 'api/config.php';

class AISimulationSystem {
    private $conn;
    private $locations;
    private $timePatterns;
    
    public function __construct() {
        $this->conn = $GLOBALS['conn'];
        $this->initializeLocations();
        $this->initializeTimePatterns();
    }
    
    private function initializeLocations() {
        $this->locations = [
            ['lat' => 28.6139, 'lng' => 77.2090, 'name' => 'Connaught Place', 'base_aqi' => 75],
            ['lat' => 28.6170, 'lng' => 77.2120, 'name' => 'Sector 18', 'base_aqi' => 125],
            ['lat' => 28.6150, 'lng' => 77.2100, 'name' => 'Sector 15', 'base_aqi' => 110],
            ['lat' => 28.6140, 'lng' => 77.2091, 'name' => 'India Gate', 'base_aqi' => 85],
            ['lat' => 28.6135, 'lng' => 77.2085, 'name' => 'Sector 12', 'base_aqi' => 65],
            ['lat' => 28.6138, 'lng' => 77.2089, 'name' => 'Sector 10', 'base_aqi' => 70],
            ['lat' => 28.6137, 'lng' => 77.2088, 'name' => 'Sector 8', 'base_aqi' => 80],
            ['lat' => 28.6141, 'lng' => 77.2092, 'name' => 'Sector 6', 'base_aqi' => 90],
            ['lat' => 28.6136, 'lng' => 77.2087, 'name' => 'Sector 4', 'base_aqi' => 95],
            ['lat' => 28.6160, 'lng' => 77.2110, 'name' => 'Sector 2', 'base_aqi' => 105]
        ];
    }
    
    private function initializeTimePatterns() {
        $this->timePatterns = [
            'rush_hour_morning' => ['start' => 7, 'end' => 9, 'multiplier' => 1.4],
            'business_hours' => ['start' => 10, 'end' => 16, 'multiplier' => 1.1],
            'rush_hour_evening' => ['start' => 17, 'end' => 19, 'multiplier' => 1.5],
            'night' => ['start' => 20, 'end' => 6, 'multiplier' => 0.8],
            'early_morning' => ['start' => 6, 'end' => 7, 'multiplier' => 0.9]
        ];
    }
    
    public function generateRealisticSensorData() {
        $hour = (int)date('H');
        $location = $this->locations[array_rand($this->locations)];
        
        // Get time-based multiplier
        $timeMultiplier = $this->getTimeMultiplier($hour);
        
        // Add random variation
        $randomFactor = (rand(80, 120) / 100);
        $locationFactor = (rand(90, 110) / 100);
        
        // Calculate AQI with all factors
        $baseAqi = $location['base_aqi'];
        $aqi = $baseAqi * $timeMultiplier * $randomFactor * $locationFactor;
        
        // Ensure realistic bounds
        $aqi = max(20, min(400, $aqi));
        
        // Generate other environmental data
        $temperature = $this->generateTemperature($hour);
        $humidity = $this->generateHumidity($temperature);
        
        // Generate pollutant data
        $pollutants = $this->generatePollutants($aqi);
        
        // Generate weather data
        $weather = $this->generateWeatherData();
        
        // Detect anomalies
        $isAnomaly = $this->detectAnomaly($aqi, $hour);
        $anomalyScore = $isAnomaly ? rand(70, 95) : 0;
        
        return array_merge([
            'aqi' => round($aqi, 1),
            'temperature' => $temperature,
            'humidity' => $humidity,
            'latitude' => $location['lat'] + (rand(-50, 50) / 100000),
            'longitude' => $location['lng'] + (rand(-50, 50) / 100000),
            'location_name' => $location['name'],
            'is_anomaly' => $isAnomaly,
            'anomaly_score' => $anomalyScore,
            'created_at' => date('Y-m-d H:i:s')
        ], $pollutants, $weather);
    }
    
    private function getTimeMultiplier($hour) {
        foreach ($this->timePatterns as $pattern) {
            if ($pattern['start'] <= $pattern['end']) {
                if ($hour >= $pattern['start'] && $hour <= $pattern['end']) {
                    return $pattern['multiplier'];
                }
            } else {
                // Handle overnight pattern (e.g., 20:00 to 06:00)
                if ($hour >= $pattern['start'] || $hour <= $pattern['end']) {
                    return $pattern['multiplier'];
                }
            }
        }
        return 1.0; // Default multiplier
    }
    
    private function generateTemperature($hour) {
        $baseTemp = 25; // Base temperature in Delhi
        
        if ($hour >= 6 && $hour <= 14) {
            // Warming up from 6 AM to 2 PM
            return $baseTemp + (($hour - 6) * 1.2) + (rand(-10, 10) / 10);
        } elseif ($hour > 14 && $hour <= 20) {
            // Cooling down from 2 PM to 8 PM
            return $baseTemp + 10 - (($hour - 14) * 1.5) + (rand(-10, 10) / 10);
        } else {
            // Night time
            return $baseTemp - 2 + (rand(-5, 5) / 10);
        }
    }
    
    private function generateHumidity($temperature) {
        $baseHumidity = 60;
        
        // Inverse relationship with temperature
        $humidityAdjustment = -($temperature - 25) * 1.5;
        $humidity = $baseHumidity + $humidityAdjustment + (rand(-15, 15));
        
        return max(20, min(95, round($humidity, 1)));
    }
    
    private function generatePollutants($aqi) {
        return [
            'pm25' => round($aqi * 0.4 + rand(-5, 5), 1),
            'pm10' => round($aqi * 0.7 + rand(-8, 8), 1),
            'no2' => round($aqi * 0.15 + rand(-3, 3), 1),
            'co' => round($aqi * 0.008 + rand(-0.5, 0.5), 1),
            'so2' => round($aqi * 0.06 + rand(-2, 2), 1),
            'ozone' => round($aqi * 0.45 + rand(-6, 6), 1)
        ];
    }
    
    private function generateWeatherData() {
        return [
            'pressure' => round(1013 + rand(-10, 10), 1),
            'wind_speed' => round(rand(0, 15), 1),
            'wind_direction' => rand(0, 359)
        ];
    }
    
    private function detectAnomaly($aqi, $hour) {
        // Detect anomalies based on unusual patterns
        
        // 1. Sudden spikes (simulate sensor malfunction or real event)
        if (rand(1, 100) <= 5) { // 5% chance
            return true;
        }
        
        // 2. Unusual AQI for time of day
        $expectedAqi = $this->getExpectedAQI($hour);
        if (abs($aqi - $expectedAqi) > $expectedAqi * 0.5) {
            return true;
        }
        
        // 3. Extreme values
        if ($aqi > 300 || $aqi < 20) {
            return true;
        }
        
        return false;
    }
    
    private function getExpectedAQI($hour) {
        $baseAqi = 85;
        return $baseAqi * $this->getTimeMultiplier($hour);
    }
    
    public function saveSensorData($data) {
        $query = "INSERT INTO sensor_data (
            aqi, temperature, humidity, latitude, longitude,
            pm25, pm10, no2, co, so2, ozone,
            pressure, wind_speed, wind_direction,
            is_anomaly, anomaly_score, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("dddddddddddddddii", 
            $data['aqi'], $data['temperature'], $data['humidity'], 
            $data['latitude'], $data['longitude'],
            $data['pm25'], $data['pm10'], $data['no2'], 
            $data['co'], $data['so2'], $data['ozone'],
            $data['pressure'], $data['wind_speed'], $data['wind_direction'],
            $data['is_anomaly'], $data['anomaly_score'], $data['created_at']
        );
        
        return $stmt->execute();
    }
    
    public function generateAIPrediction() {
        // Simulate AI prediction
        $currentData = $this->generateRealisticSensorData();
        $currentAqi = $currentData['aqi'];
        
        // Generate predictions with confidence
        $confidence = rand(75, 95) / 100;
        $predictionVariance = (1 - $confidence) * 50;
        
        return [
            'predicted_aqi_15min' => round($currentAqi + rand(-$predictionVariance, $predictionVariance), 1),
            'predicted_aqi_30min' => round($currentAqi + rand(-$predictionVariance * 1.5, $predictionVariance * 1.5), 1),
            'predicted_aqi_1hour' => round($currentAqi + rand(-$predictionVariance * 2, $predictionVariance * 2), 1),
            'confidence' => $confidence,
            'model_used' => 'linear_regression',
            'features_used' => json_encode([
                'aqi' => $currentAqi,
                'temperature' => $currentData['temperature'],
                'humidity' => $currentData['humidity'],
                'hour' => (int)date('H')
            ])
        ];
    }
    
    public function generateAIDecision($currentAqi, $predictedAqi, $robotStatus) {
        $threshold = 100;
        
        $decision = [
            'action' => 'monitor',
            'reason' => 'Normal conditions',
            'spray_intensity' => 'OFF',
            'urgency' => 'low',
            'estimated_time' => 0,
            'confidence' => rand(70, 90) / 100
        ];
        
        // High current AQI
        if ($currentAqi > $threshold) {
            $decision = [
                'action' => 'activate_purification',
                'reason' => "High AQI detected: {$currentAqi}",
                'spray_intensity' => $this->getSprayIntensity($currentAqi),
                'urgency' => 'high',
                'estimated_time' => 5,
                'confidence' => rand(85, 95) / 100
            ];
        }
        // Predicted high AQI (proactive)
        elseif ($predictedAqi > $threshold) {
            $decision = [
                'action' => 'move_to_high_aqi',
                'reason' => "Predicted high AQI: {$predictedAqi}",
                'spray_intensity' => 'STANDBY',
                'urgency' => 'medium',
                'estimated_time' => 15,
                'confidence' => rand(75, 85) / 100
            ];
        }
        
        return $decision;
    }
    
    private function getSprayIntensity($aqi) {
        if ($aqi > 200) return 'HIGH';
        if ($aqi > 150) return 'MEDIUM';
        if ($aqi > 100) return 'LOW';
        return 'OFF';
    }
    
    public function saveAIPrediction($prediction) {
        $query = "INSERT INTO predictions (
            predicted_aqi, prediction_type, confidence, model_used, 
            features_used, created_at, expires_at
        ) VALUES (?, '15min', ?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 15 MINUTE))";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ddss", 
            $prediction['predicted_aqi_15min'], 
            $prediction['confidence'], 
            $prediction['model_used'], 
            $prediction['features_used']
        );
        
        return $stmt->execute();
    }
    
    public function saveAIDecision($decision) {
        $query = "INSERT INTO ai_logs (
            decision, reason, confidence, algorithm_used, 
            execution_time_ms, outcome, created_at
        ) VALUES (?, ?, ?, 'decision_engine', ?, 'SUCCESS', NOW())";
        
        $stmt = $this->conn->prepare($query);
        $executionTime = rand(30, 100);
        $stmt->bind_param("ssdi", 
            $decision['action'], 
            $decision['reason'], 
            $decision['confidence'], 
            $executionTime
        );
        
        return $stmt->execute();
    }
    
    public function generateAnomalies() {
        $anomalies = [];
        
        // Generate random anomalies
        if (rand(1, 100) <= 20) { // 20% chance
            $anomalies[] = [
                'timestamp' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 60) . ' minutes')),
                'aqi' => rand(200, 400),
                'difference' => rand(50, 150),
                'severity' => rand(1, 100) > 50 ? 'high' : 'medium'
            ];
        }
        
        return $anomalies;
    }
    
    public function runCompleteSimulation() {
        // Generate sensor data
        $sensorData = $this->generateRealisticSensorData();
        $this->saveSensorData($sensorData);
        
        // Generate AI prediction
        $prediction = $this->generateAIPrediction();
        $this->saveAIPrediction($prediction);
        
        // Get current robot status
        $robotQuery = "SELECT * FROM robot_status ORDER BY id DESC LIMIT 1";
        $robotResult = $this->conn->query($robotQuery);
        $robotStatus = $robotResult->num_rows > 0 ? $robotResult->fetch_assoc() : [];
        
        // Generate AI decision
        $decision = $this->generateAIDecision($sensorData['aqi'], $prediction['predicted_aqi_15min'], $robotStatus);
        $this->saveAIDecision($decision);
        
        // Generate anomalies if any
        $anomalies = $this->generateAnomalies();
        
        // Log the simulation
        $this->logSimulation($sensorData, $prediction, $decision);
        
        return [
            'sensor_data' => $sensorData,
            'prediction' => $prediction,
            'decision' => $decision,
            'anomalies' => $anomalies,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    private function logSimulation($sensorData, $prediction, $decision) {
        $query = "INSERT INTO system_logs (action, details, user_id, ai_involved, response_time_ms) 
                 VALUES (?, ?, 'ai_simulation', TRUE, ?)";
        
        $stmt = $this->conn->prepare($query);
        $details = json_encode([
            'sensor_aqi' => $sensorData['aqi'],
            'predicted_aqi' => $prediction['predicted_aqi_15min'],
            'decision' => $decision['action'],
            'confidence' => $prediction['confidence']
        ]);
        $responseTime = rand(50, 150);
        
        $stmt->bind_param("ssi", 'ai_simulation_complete', $details, $responseTime);
        $stmt->execute();
    }
    
    public function getSimulationStats() {
        $stats = [];
        
        // Get sensor data count
        $result = $this->conn->query("SELECT COUNT(*) as count FROM sensor_data");
        $stats['sensor_readings'] = $result->fetch_assoc()['count'];
        
        // Get prediction count
        $result = $this->conn->query("SELECT COUNT(*) as count FROM predictions");
        $stats['predictions'] = $result->fetch_assoc()['count'];
        
        // Get decision count
        $result = $this->conn->query("SELECT COUNT(*) as count FROM ai_logs");
        $stats['decisions'] = $result->fetch_assoc()['count'];
        
        // Get anomaly count
        $result = $this->conn->query("SELECT COUNT(*) as count FROM sensor_data WHERE is_anomaly = TRUE");
        $stats['anomalies'] = $result->fetch_assoc()['count'];
        
        return $stats;
    }
}

// Handle simulation requests
$action = $_GET['action'] ?? 'run';

$simulation = new AISimulationSystem();

switch ($action) {
    case 'run':
        $result = $simulation->runCompleteSimulation();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $result,
            'message' => 'AI simulation completed successfully'
        ]);
        break;
        
    case 'stats':
        $stats = $simulation->getSimulationStats();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'stats' => $stats
        ]);
        break;
        
    case 'continuous':
        // Run simulation continuously
        for ($i = 0; $i < 5; $i++) {
            $simulation->runCompleteSimulation();
            usleep(500000); // 0.5 second delay
        }
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Ran 5 simulation cycles'
        ]);
        break;
        
    default:
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => 'Invalid action'
        ]);
}
?>
