<?php
/**
 * Web-based Simulation Control
 * This provides a simple web interface to control the data simulation
 */

require_once 'api/config.php';

$action = $_GET['action'] ?? 'status';
$message = '';
$simulation_active = false;

// Handle actions
switch ($action) {
    case 'start':
        // Create simulation flag
        $flag_file = __DIR__ . '/api/simulation_active.flag';
        file_put_contents($flag_file, 'active');
        
        // Generate initial data
        for ($i = 0; $i < 3; $i++) {
            generateSensorData();
            usleep(500000); // 0.5 second delay
        }
        
        $message = 'Simulation started! Data will be generated every 5 seconds.';
        $simulation_active = true;
        break;
        
    case 'stop':
        // Remove simulation flag
        $flag_file = __DIR__ . '/api/simulation_active.flag';
        if (file_exists($flag_file)) {
            unlink($flag_file);
        }
        $message = 'Simulation stopped.';
        $simulation_active = false;
        break;
        
    case 'generate':
        generateSensorData();
        $message = 'Single data point generated successfully!';
        break;
        
    case 'status':
    default:
        // Check current status
        $flag_file = __DIR__ . '/api/simulation_active.flag';
        $simulation_active = file_exists($flag_file);
        
        // Get last data generation time
        $last_query = "SELECT created_at FROM sensor_data ORDER BY created_at DESC LIMIT 1";
        $last_result = $conn->query($last_query);
        $last_generation = 'Never';
        $total_readings = 0;
        
        if ($last_result->num_rows > 0) {
            $last_data = $last_result->fetch_assoc();
            $last_generation = $last_data['created_at'];
        }
        
        // Get total readings count
        $count_query = "SELECT COUNT(*) as count FROM sensor_data";
        $count_result = $conn->query($count_query);
        if ($count_result->num_rows > 0) {
            $count_data = $count_result->fetch_assoc();
            $total_readings = $count_data['count'];
        }
        
        break;
}

function generateSensorData() {
    global $conn;
    
    // Get current robot status for threshold
    $robot_query = "SELECT aqi_threshold FROM robot_status ORDER BY id DESC LIMIT 1";
    $robot_result = $conn->query($robot_query);
    $threshold = 100; // Default threshold
    
    if ($robot_result->num_rows > 0) {
        $robot_data = $robot_result->fetch_assoc();
        $threshold = $robot_data['aqi_threshold'];
    }
    
    // Generate realistic sensor data
    $sensor_data = generateRealisticData($threshold);
    
    // Insert into database
    $insert_query = "INSERT INTO sensor_data (aqi, temperature, humidity, latitude, longitude) 
                     VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ddddd", 
        $sensor_data['aqi'], 
        $sensor_data['temperature'], 
        $sensor_data['humidity'], 
        $sensor_data['latitude'], 
        $sensor_data['longitude']
    );
    
    $success = $stmt->execute();
    $stmt->close();
    
    if ($success) {
        // Check if alert should be created
        checkAndCreateAlert($conn, $sensor_data);
        
        // Trigger AI simulation
        triggerAISimulation($conn);
    }
    
    return $success;
}

function generateRealisticData($threshold) {
    // Simulate different locations in New Delhi
    $locations = [
        ['lat' => 28.6139, 'lng' => 77.2090, 'name' => 'Connaught Place'],
        ['lat' => 28.6170, 'lng' => 77.2120, 'name' => 'Sector 18'],
        ['lat' => 28.6150, 'lng' => 77.2100, 'name' => 'Sector 15'],
        ['lat' => 28.6140, 'lng' => 77.2091, 'name' => 'India Gate'],
        ['lat' => 28.6135, 'lng' => 77.2085, 'name' => 'Sector 12'],
        ['lat' => 28.6138, 'lng' => 77.2089, 'name' => 'Sector 10'],
        ['lat' => 28.6137, 'lng' => 77.2088, 'name' => 'Sector 8'],
        ['lat' => 28.6141, 'lng' => 77.2092, 'name' => 'Sector 6'],
        ['lat' => 28.6136, 'lng' => 77.2087, 'name' => 'Sector 4'],
        ['lat' => 28.6160, 'lng' => 77.2110, 'name' => 'Sector 2']
    ];
    
    $location = $locations[array_rand($locations)];
    
    // Generate AQI with realistic patterns
    $hour = (int)date('H');
    $time_factor = getTimeBasedAQIFactor($hour);
    
    // Add some randomness and location-based variation
    $location_factor = rand(80, 120) / 100; // ±20% location variation
    $random_factor = rand(90, 110) / 100; // ±10% random variation
    
    // Base AQI with time-based variation
    $base_aqi = 75 + ($hour >= 7 && $hour <= 9 ? 30 : 0) + ($hour >= 17 && $hour <= 19 ? 40 : 0);
    $aqi = $base_aqi * $time_factor * $location_factor * $random_factor;
    
    // Ensure AQI stays within realistic bounds
    $aqi = max(20, min(400, $aqi));
    
    // Generate temperature (based on time of day)
    $temp_base = 25; // Base temperature in Delhi
    $temp_variation = getTemperatureVariation($hour);
    $temperature = $temp_base + $temp_variation + (rand(-20, 20) / 10); // ±2 degrees
    
    // Generate humidity (inversely related to temperature)
    $humidity_base = 60;
    $humidity_variation = -$temp_variation * 2; // Humidity drops when temperature rises
    $humidity = $humidity_base + $humidity_variation + (rand(-10, 10));
    $humidity = max(20, min(95, $humidity));
    
    return [
        'aqi' => round($aqi, 1),
        'temperature' => round($temperature, 1),
        'humidity' => round($humidity, 1),
        'latitude' => $location['lat'] + (rand(-50, 50) / 100000), // Small variation
        'longitude' => $location['lng'] + (rand(-50, 50) / 100000),
        'location_name' => $location['name']
    ];
}

function getTimeBasedAQIFactor($hour) {
    if ($hour >= 6 && $hour <= 8) return 1.3; // Morning rush hour
    elseif ($hour >= 9 && $hour <= 11) return 1.1; // Mid morning
    elseif ($hour >= 12 && $hour <= 16) return 0.9; // Afternoon dispersion
    elseif ($hour >= 17 && $hour <= 19) return 1.4; // Evening rush hour
    else return 0.8; // Night time
}

function getTemperatureVariation($hour) {
    if ($hour >= 6 && $hour <= 14) {
        return ($hour - 6) * 1.5; // Up to +12 degrees by 2 PM
    } elseif ($hour > 14 && $hour <= 20) {
        return 12 - (($hour - 14) * 2); // Down to +2 degrees by 8 PM
    } else {
        return -2; // Below base temperature
    }
}

function checkAndCreateAlert($conn, $sensor_data) {
    // Get current threshold
    $robot_query = "SELECT aqi_threshold FROM robot_status ORDER BY id DESC LIMIT 1";
    $robot_result = $conn->query($robot_query);
    $threshold = 100; // Default threshold
    
    if ($robot_result->num_rows > 0) {
        $robot_data = $robot_result->fetch_assoc();
        $threshold = $robot_data['aqi_threshold'];
    }
    
    if ($sensor_data['aqi'] > $threshold) {
        $alert_level = 'WARNING';
        if ($sensor_data['aqi'] > 200) $alert_level = 'ERROR';
        if ($sensor_data['aqi'] > 300) $alert_level = 'CRITICAL';
        
        $alert_message = sprintf('High AQI detected: %.1f at %s', 
            $sensor_data['aqi'], 
            $sensor_data['location_name'] ?? 'Unknown location'
        );
        
        $alert_query = "INSERT INTO alerts (message, level, aqi_value, location) 
                       VALUES (?, ?, ?, ?)";
        
        $stmt = $conn->prepare($alert_query);
        $location = $sensor_data['location_name'] ?? sprintf('%.6f, %.6f', 
            $sensor_data['latitude'], $sensor_data['longitude']);
        $stmt->bind_param("ssds", $alert_message, $alert_level, $sensor_data['aqi'], $location);
        $stmt->execute();
        $stmt->close();
    }
}

function triggerAISimulation($conn) {
    // Call AI simulation to make decisions based on new data
    $ai_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/api/ai_simulation.php';
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode(['trigger' => 'data_simulation'])
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false
        ]
    ]);
    
    // Make async call (non-blocking)
    $result = file_get_contents($ai_url, false, $context);
    
    // Log the trigger
    $log_query = "INSERT INTO system_logs (action, details, user_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($log_query);
    $action = 'Data Simulation Triggered';
    $details = 'AI simulation triggered after data generation';
    $user_id = 'simulation_system';
    $stmt->bind_param("sss", $action, $details, $user_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulation Control - Air Purification System</title>
    <link rel="stylesheet" href="assets/css/tailwind.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }
        .status-active {
            background-color: #10b981;
            animation: pulse 2s infinite;
        }
        .status-inactive {
            background-color: #ef4444;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">
                <i class="fas fa-cogs mr-2"></i>
                Data Simulation Control
            </h1>
            
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Simulation Status</h2>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="status-indicator <?= $simulation_active ? 'status-active' : 'status-inactive' ?>"></span>
                        <span class="text-lg font-medium">
                            <?= $simulation_active ? 'Active' : 'Inactive' ?>
                        </span>
                    </div>
                    <div class="text-sm text-gray-600">
                        Total Readings: <?= $total_readings ?>
                    </div>
                </div>
                <?php if ($message): ?>
                    <div class="mt-4 p-3 bg-blue-100 border border-blue-300 rounded-lg">
                        <p class="text-blue-800"><?= $message ?></p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Control Buttons -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Controls</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="?action=start" class="block text-center bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition-colors">
                        <i class="fas fa-play mr-2"></i>
                        Start Simulation
                    </a>
                    <a href="?action=stop" class="block text-center bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg transition-colors">
                        <i class="fas fa-stop mr-2"></i>
                        Stop Simulation
                    </a>
                    <a href="?action=generate" class="block text-center bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Generate Data
                    </a>
                </div>
            </div>
            
            <!-- Instructions -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Instructions</h2>
                <div class="space-y-3 text-gray-700">
                    <p><i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <strong>Start Simulation:</strong> Begins automatic data generation every 5 seconds</p>
                    <p><i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <strong>Stop Simulation:</strong> Pauses automatic data generation</p>
                    <p><i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <strong>Generate Data:</strong> Creates a single data point immediately</p>
                    <p><i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <strong>Data includes:</strong> AQI, temperature, humidity, GPS coordinates</p>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Quick Links</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="admin.html" class="block text-center bg-purple-500 hover:bg-purple-600 text-white px-6 py-3 rounded-lg transition-colors">
                        <i class="fas fa-cog mr-2"></i>
                        Admin Dashboard
                    </a>
                    <a href="user.html" class="block text-center bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        User Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Auto-refresh every 5 seconds when simulation is active
        <?php if ($simulation_active): ?>
        setTimeout(() => {
            window.location.reload();
        }, 5000);
        <?php endif; ?>
    </script>
</body>
</html>
