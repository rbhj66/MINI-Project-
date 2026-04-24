<?php
/**
 * Simple Simulation Control - Works without MySQL
 * For PHP development server testing
 */

require_once 'api/config_simple.php';

$action = $_GET['action'] ?? 'status';
$message = '';
$simulation_active = false;

// Handle actions
switch ($action) {
    case 'start':
        // Create simulation flag
        $flag_file = __DIR__ . '/api/simulation_active.flag';
        file_put_contents($flag_file, 'active');
        
        // Generate initial mock data
        for ($i = 0; $i < 3; $i++) {
            generateMockSensorData();
            usleep(500000); // 0.5 second delay
        }
        
        $message = 'Simulation started! Mock data will be generated every 5 seconds.';
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
        generateMockSensorData();
        $message = 'Mock data point generated successfully!';
        break;
        
    case 'status':
    default:
        // Check current status
        $flag_file = __DIR__ . '/api/simulation_active.flag';
        $simulation_active = file_exists($flag_file);
        
        // Get mock statistics
        $total_readings = 25; // Mock count
        $last_generation = date('Y-m-d H:i:s', strtotime('-5 minutes'));
        
        break;
}

function generateMockSensorData() {
    // Generate realistic mock data
    $sensor_data = getMockSensorData();
    
    // Log the generation (in real app, this would save to database)
    error_log("Generated mock data: AQI={$sensor_data['aqi']}, Temp={$sensor_data['temperature']}°C, Humidity={$sensor_data['humidity']}%");
    
    return true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulation Control - Air Purification System (Simple Mode)</title>
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
        .warning-banner {
            background: linear-gradient(135deg, #f59e0b, #ef4444);
            color: white;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.5rem;
            text-align: center;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Warning Banner -->
            <div class="warning-banner">
                <h3><i class="fas fa-exclamation-triangle mr-2"></i>Development Mode - Using Mock Data</h3>
                <p class="text-sm mt-1">MySQL not available. System is using simulated data for testing.</p>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">
                <i class="fas fa-cogs mr-2"></i>
                Data Simulation Control (Simple Mode)
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
                        Mock Readings: <?= $total_readings ?>
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
            
            <!-- Mock Data Preview -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Current Mock Data</h2>
                <?php
                $mockData = getMockSensorData();
                $mockRobot = getMockRobotStatus();
                ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-medium mb-2">Sensor Data</h3>
                        <div class="space-y-2 text-sm">
                            <p><strong>AQI:</strong> <?= $mockData['aqi'] ?> (<?= $mockData['aqi_status'] ?>)</p>
                            <p><strong>Temperature:</strong> <?= $mockData['temperature'] ?>°C</p>
                            <p><strong>Humidity:</strong> <?= $mockData['humidity'] ?>%</p>
                            <p><strong>Location:</strong> <?= $mockData['latitude'] ?>, <?= $mockData['longitude'] ?></p>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-medium mb-2">Robot Status</h3>
                        <div class="space-y-2 text-sm">
                            <p><strong>Status:</strong> <?= $mockRobot['status'] ?></p>
                            <p><strong>Mode:</strong> <?= $mockRobot['mode'] ?></p>
                            <p><strong>Spray:</strong> <?= $mockRobot['spray_status'] ?></p>
                            <p><strong>Battery:</strong> <?= $mockRobot['battery_level'] ?>%</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Instructions -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Instructions</h2>
                <div class="space-y-3 text-gray-700">
                    <p><i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <strong>Start Simulation:</strong> Begins automatic mock data generation every 5 seconds</p>
                    <p><i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <strong>Stop Simulation:</strong> Pauses automatic data generation</p>
                    <p><i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <strong>Generate Data:</strong> Creates a single mock data point immediately</p>
                    <p><i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                        <strong>Note:</strong> This is using mock data. To use real database, fix MySQLi extension.</p>
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
