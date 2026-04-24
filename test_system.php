<?php
/**
 * System Test Script for AI Air Purification System
 * This script tests all major components and generates sample data
 */

// Enable error reporting for testing
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include configuration
require_once 'api/config.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>System Test - AI Air Purification System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; text-align: center; margin-bottom: 30px; }
        h2 { color: #667eea; border-bottom: 2px solid #667eea; padding-bottom: 10px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background: #d4edda; border-color: #c3e6cb; color: #155724; }
        .error { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .info { background: #d1ecf1; border-color: #bee5eb; color: #0c5460; }
        .warning { background: #fff3cd; border-color: #ffeaa7; color: #856404; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
        .btn { background: #667eea; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        .btn:hover { background: #5a6fd8; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0; }
        .stat-card { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 20px; border-radius: 8px; text-align: center; }
        .stat-number { font-size: 2em; font-weight: bold; }
        .stat-label { font-size: 0.9em; opacity: 0.9; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>AI Air Purification System - Test Suite</h1>
        
        <div class='test-section info'>
            <h2>System Overview</h2>
            <p>This test script verifies all system components and generates sample data for testing purposes.</p>
            <p><strong>Database:</strong> " . $database . "</p>
            <p><strong>Host:</strong> " . $host . "</p>
            <p><strong>Test Time:</strong> " . date('Y-m-d H:i:s') . "</p>
        </div>";

// Test 1: Database Connection
echo "<div class='test-section'>";
echo "<h2>Test 1: Database Connection</h2>";

if ($conn->connect_error) {
    echo "<div class='error'>Database connection failed: " . $conn->connect_error . "</div>";
} else {
    echo "<div class='success'>Database connection successful!</div>";
    
    // Test table existence
    $tables = ['sensor_data', 'robot_status', 'alerts', 'system_logs'];
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            echo "<div class='success'>Table '$table' exists</div>";
        } else {
            echo "<div class='error'>Table '$table' missing</div>";
        }
    }
}
echo "</div>";

// Test 2: API Endpoints
echo "<div class='test-section'>";
echo "<h2>Test 2: API Endpoints</h2>";

$api_files = [
    'api/config.php' => 'Configuration',
    'api/get_data.php' => 'Get Data API',
    'api/save_data.php' => 'Save Data API',
    'api/robot_control.php' => 'Robot Control API',
    'api/get_history.php' => 'History API',
    'api/ai_simulation.php' => 'AI Simulation API',
    'api/simulate_data.php' => 'Data Simulation API'
];

foreach ($api_files as $file => $description) {
    if (file_exists($file)) {
        echo "<div class='success'>$description - File exists</div>";
    } else {
        echo "<div class='error'>$description - File missing: $file</div>";
    }
}
echo "</div>";

// Test 3: Generate Sample Data
echo "<div class='test-section'>";
echo "<h2>Test 3: Sample Data Generation</h2>";

if (isset($_POST['generate_data'])) {
    $count = intval($_POST['data_count'] ?? 20);
    
    echo "<h3>Generating $count sample sensor readings...</h3>";
    
    $generated = 0;
    $errors = 0;
    
    for ($i = 0; $i < $count; $i++) {
        // Generate realistic data
        $aqi = rand(30, 250);
        $temperature = 20 + rand(-5, 15);
        $humidity = 40 + rand(0, 40);
        $latitude = 28.6139 + (rand(-100, 100) / 10000);
        $longitude = 77.2090 + (rand(-100, 100) / 10000);
        
        $insert_query = "INSERT INTO sensor_data (aqi, temperature, humidity, latitude, longitude) 
                         VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ddddd", $aqi, $temperature, $humidity, $latitude, $longitude);
        
        if ($stmt->execute()) {
            $generated++;
            
            // Create alerts for high AQI
            if ($aqi > 150) {
                $alert_level = $aqi > 200 ? 'CRITICAL' : 'ERROR';
                $alert_message = "High AQI detected: $aqi at location ($latitude, $longitude)";
                
                $alert_query = "INSERT INTO alerts (message, level, aqi_value, location) 
                               VALUES (?, ?, ?, ?)";
                
                $stmt_alert = $conn->prepare($alert_query);
                $location = "$latitude, $longitude";
                $stmt_alert->bind_param("ssds", $alert_message, $alert_level, $aqi, $location);
                $stmt_alert->execute();
                $stmt_alert->close();
            }
        } else {
            $errors++;
        }
        
        $stmt->close();
        
        // Add small delay to simulate real-time data
        usleep(10000); // 10ms
    }
    
    echo "<div class='success'>Generated $generated sensor readings successfully</div>";
    if ($errors > 0) {
        echo "<div class='error'>$errors errors occurred during generation</div>";
    }
} else {
    echo "<form method='post'>";
    echo "<p>Generate sample sensor data for testing:</p>";
    echo "<input type='number' name='data_count' value='20' min='1' max='100' style='padding: 5px; margin: 5px;'>";
    echo "<button type='submit' name='generate_data' class='btn'>Generate Data</button>";
    echo "</form>";
}
echo "</div>";

// Test 4: Current Database Statistics
echo "<div class='test-section'>";
echo "<h2>Test 4: Database Statistics</h2>";

echo "<div class='stats'>";

// Sensor data count
$result = $conn->query("SELECT COUNT(*) as count FROM sensor_data");
$row = $result->fetch_assoc();
echo "<div class='stat-card'>
    <div class='stat-number'>" . $row['count'] . "</div>
    <div class='stat-label'>Sensor Readings</div>
</div>";

// Alerts count
$result = $conn->query("SELECT COUNT(*) as count FROM alerts");
$row = $result->fetch_assoc();
echo "<div class='stat-card'>
    <div class='stat-number'>" . $row['count'] . "</div>
    <div class='stat-label'>System Alerts</div>
</div>";

// System logs count
$result = $conn->query("SELECT COUNT(*) as count FROM system_logs");
$row = $result->fetch_assoc();
echo "<div class='stat-card'>
    <div class='stat-number'>" . $row['count'] . "</div>
    <div class='stat-label'>System Logs</div>
</div>";

// Robot status
$result = $conn->query("SELECT status, mode, spray_status FROM robot_status ORDER BY id DESC LIMIT 1");
$row = $result->fetch_assoc();
echo "<div class='stat-card'>
    <div class='stat-number'>" . strtoupper($row['status']) . "</div>
    <div class='stat-label'>Robot Status</div>
</div>";

echo "</div>";

// Latest sensor reading
$result = $conn->query("SELECT * FROM sensor_data ORDER BY created_at DESC LIMIT 1");
if ($row = $result->fetch_assoc()) {
    echo "<h3>Latest Sensor Reading:</h3>";
    echo "<pre>";
    echo "AQI: " . $row['aqi'] . "\n";
    echo "Temperature: " . $row['temperature'] . "°C\n";
    echo "Humidity: " . $row['humidity'] . "%\n";
    echo "Location: (" . $row['latitude'] . ", " . $row['longitude'] . ")\n";
    echo "Timestamp: " . $row['created_at'] . "\n";
    echo "</pre>";
}
echo "</div>";

// Test 5: API Response Test
echo "<div class='test-section'>";
echo "<h2>Test 5: API Response Test</h2>";

if (isset($_POST['test_api'])) {
    echo "<h3>Testing API Endpoints...</h3>";
    
    // Test get_data.php
    echo "<h4>Get Data API:</h4>";
    $api_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/api/get_data.php';
    $response = file_get_contents($api_url);
    if ($response) {
        echo "<div class='success'>API responded successfully</div>";
        echo "<pre>" . htmlspecialchars(substr($response, 0, 500)) . "...</pre>";
    } else {
        echo "<div class='error'>API call failed</div>";
    }
    
    // Test robot_control.php
    echo "<h4>Robot Control API:</h4>";
    $control_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/api/robot_control.php';
    $control_data = json_encode(['action' => 'test']);
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => $control_data
        ]
    ]);
    
    $control_response = file_get_contents($control_url, false, $context);
    if ($control_response) {
        echo "<div class='success'>Robot Control API responded</div>";
        echo "<pre>" . htmlspecialchars(substr($control_response, 0, 300)) . "...</pre>";
    } else {
        echo "<div class='error'>Robot Control API call failed</div>";
    }
} else {
    echo "<form method='post'>";
    echo "<button type='submit' name='test_api' class='btn'>Test API Endpoints</button>";
    echo "</form>";
}
echo "</div>";

// Test 6: File Structure Check
echo "<div class='test-section'>";
echo "<h2>Test 6: File Structure Verification</h2>";

$required_files = [
    'index.html' => 'Main Landing Page',
    'admin/index.html' => 'Admin Dashboard',
    'user/index.html' => 'User Dashboard',
    'assets/js/admin.js' => 'Admin JavaScript',
    'assets/js/user.js' => 'User JavaScript',
    'db.sql' => 'Database Schema',
    'README.md' => 'Documentation'
];

foreach ($required_files as $file => $description) {
    if (file_exists($file)) {
        echo "<div class='success'>$description - Found</div>";
    } else {
        echo "<div class='error'>$description - Missing: $file</div>";
    }
}
echo "</div>";

// Test 7: Dashboard Links
echo "<div class='test-section'>";
echo "<h2>Test 7: Dashboard Access</h2>";
echo "<div class='info'>";
echo "<p><strong>Main Site:</strong> <a href='index.html' target='_blank'>index.html</a></p>";
echo "<p><strong>Admin Dashboard:</strong> <a href='admin/index.html' target='_blank'>admin/index.html</a></p>";
echo "<p><strong>User Dashboard:</strong> <a href='user/index.html' target='_blank'>user/index.html</a></p>";
echo "</div>";

// Quick system status
$robot_query = "SELECT * FROM robot_status ORDER BY id DESC LIMIT 1";
$robot_result = $conn->query($robot_query);
$robot_status = $robot_result->fetch_assoc();

echo "<h3>Quick System Status:</h3>";
echo "<ul>";
echo "<li>Robot Power: <strong>" . $robot_status['status'] . "</strong></li>";
echo "<li>Control Mode: <strong>" . $robot_status['mode'] . "</strong></li>";
echo "<li>Spray System: <strong>" . $robot_status['spray_status'] . "</strong></li>";
echo "<li>AQI Threshold: <strong>" . $robot_status['aqi_threshold'] . "</strong></li>";
echo "</ul>";

echo "</div>";

// Final Summary
echo "<div class='test-section info'>";
echo "<h2>System Test Summary</h2>";
echo "<p>The AI Air Purification System has been tested. Key components verified:</p>";
echo "<ul>";
echo "<li>Database connection and tables</li>";
echo "<li>API endpoint availability</li>";
echo "<li>File structure integrity</li>";
echo "<li>Sample data generation</li>";
echo "<li>Dashboard accessibility</li>";
echo "</ul>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Access the dashboards using the links above</li>";
echo "<li>Generate sample data if needed for testing</li>";
echo "<li>Test robot control features</li>";
echo "<li>Verify real-time updates and charts</li>";
echo "</ol>";
echo "</div>";

echo "</div></body></html>";

$conn->close();
?>
