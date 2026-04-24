# AI-Powered Air Purification System

A comprehensive web-based system for monitoring air quality and controlling an intelligent robot car for air purification using advanced AI algorithms.

## Features

### Core Functionality
- **Real-time AQI Monitoring** - Track Air Quality Index with MQ135 sensor
- **Temperature & Humidity Tracking** - DHT22 sensor integration
- **GPS Location Tracking** - Real-time robot positioning
- **Smart Robot Control** - Autonomous navigation and manual control
- **Intelligent Spray System** - Automated purification mist deployment
- **AI Decision Making** - Predictive analytics and smart automation

### Dashboard Interfaces
- **Admin Dashboard** - Advanced control panel with analytics
- **User Dashboard** - Clean monitoring interface for general users

## Technology Stack

### Frontend
- HTML5 with semantic markup
- Tailwind CSS for modern styling
- Vanilla JavaScript for interactivity
- Chart.js for data visualization
- Font Awesome for icons
- Responsive design (Mobile + Desktop)

### Backend
- PHP (Core PHP, no heavy framework)
- RESTful API architecture
- MySQL database
- XAMPP development environment

### Hardware Integration (Simulated)
- MQ135 Air Quality Sensor
- DHT22 Temperature & Humidity Sensor
- GPS Module for location tracking
- Smart Robot Car mechanics

## Installation Guide

### Prerequisites
- XAMPP (or similar PHP/MySQL environment)
- Modern web browser
- Internet connection (for CDN resources)

### Step 1: Database Setup
1. Start XAMPP and ensure Apache and MySQL are running
2. Open phpMyAdmin (http://localhost/phpmyadmin)
3. Create a new database named `air_purification_system`
4. Import the `db.sql` file:
   - Click on the database
   - Click "Import" tab
   - Choose the `db.sql` file from the project
   - Click "Go"

### Step 2: Project Setup
1. Copy the entire project folder to your XAMPP htdocs directory
   - Windows: `C:/xampp/htdocs/air/`
   - Mac/Linux: `/opt/lampp/htdocs/air/`

2. Ensure the file structure is correct:
   ```
   /air/
   |-- admin/
   |   |-- index.html
   |-- user/
   |   |-- index.html
   |-- api/
   |   |-- config.php
   |   |-- get_data.php
   |   |-- save_data.php
   |   |-- robot_control.php
   |   |-- get_history.php
   |   |-- ai_simulation.php
   |   |-- simulate_data.php
   |-- assets/
   |   |-- js/
   |   |   |-- admin.js
   |   |   |-- user.js
   |   |-- css/
   |   |-- images/
   |-- index.html
   |-- db.sql
   |-- README.md
   ```

### Step 3: Database Configuration
The system uses default XAMPP credentials:
- Host: localhost
- Username: root
- Password: (empty)
- Database: air_purification_system

If you use different credentials, update `api/config.php`:
```php
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'air_purification_system';
```

### Step 4: Access the System
1. Open your web browser
2. Navigate to: `http://localhost/air/`
3. Choose your dashboard:
   - **Main Landing Page**: `http://localhost/air/`
   - **Admin Dashboard**: `http://localhost/air/admin/`
   - **User Dashboard**: `http://localhost/air/user/`

## Usage Guide

### Admin Dashboard
The admin panel provides full system control:

1. **Overview** - Real-time sensor data and system status
2. **Live Map** - GPS tracking and robot positioning
3. **Robot Control** - Manual and automated control options
4. **Analytics** - Historical data and trend analysis
5. **Alerts** - System notifications and warnings
6. **System Logs** - Activity monitoring

### User Dashboard
Clean, simple interface for monitoring:

1. **AQI Display** - Large, color-coded air quality indicator
2. **Health Recommendations** - Personalized advice based on current conditions
3. **Trend Analysis** - 24-hour air quality patterns
4. **Environmental Statistics** - Temperature, humidity, and system status

### Robot Control Features

#### Manual Control
- Directional movement (forward, backward, left, right)
- Spray system activation/deactivation
- Power control

#### Automated Mode
- AI-driven navigation to high AQI areas
- Automatic spray activation based on thresholds
- Energy management and return-to-base functionality

#### AI Simulation
- Predictive analytics for air quality trends
- Intelligent decision making
- Energy optimization
- Alert generation

## API Endpoints

### Data Management
- `GET /api/get_data.php` - Fetch latest sensor data and system status
- `POST /api/save_data.php` - Save new sensor readings
- `GET /api/get_history.php?period=24h` - Get historical data

### Robot Control
- `POST /api/robot_control.php` - Control robot actions
  - Actions: `start_robot`, `stop_robot`, `toggle_spray`, `set_mode`, `set_threshold`, `move_robot`, `manual_control`

### AI Features
- `POST /api/ai_simulation.php` - Trigger AI decision making
- `POST /api/simulate_data.php` - Generate test data

## Data Simulation

The system includes a data simulation feature for testing:

```javascript
// Generate sample data
fetch('/api/simulate_data.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        count: 10,           // Number of readings to generate
        interval: 5,         // Minutes between readings
        base_aqi: 75,        // Base AQI value
        variation: 25        // AQI variation range
    })
})
```

## Database Schema

### Tables
1. **sensor_data** - Stores sensor readings
2. **robot_status** - Current robot state and settings
3. **alerts** - System notifications
4. **system_logs** - Activity tracking

### Key Fields
- AQI values with timestamp
- GPS coordinates
- Temperature and humidity
- Robot operational status
- Alert levels and messages

## Customization

### AQI Thresholds
Update AQI thresholds in the admin panel or directly in the database:
- Good: 0-50
- Moderate: 51-100
- Unhealthy for Sensitive: 101-150
- Unhealthy: 151-200
- Very Unhealthy: 201-300
- Hazardous: 301+

### Location Settings
Modify GPS coordinates in the simulation to match your location:
- Base coordinates in `api/simulate_data.php`
- Robot home position in database

### Styling
The system uses Tailwind CSS. Customize colors and styles by:
1. Modifying Tailwind classes in HTML files
2. Adding custom CSS in `<style>` sections
3. Updating color schemes for AQI indicators

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Ensure XAMPP MySQL is running
   - Check database credentials in `api/config.php`
   - Verify database exists and tables are created

2. **404 Errors**
   - Check file permissions
   - Ensure .htaccess allows directory access
   - Verify Apache mod_rewrite is enabled

3. **Charts Not Loading**
   - Check internet connection for Chart.js CDN
   - Verify API endpoints are responding
   - Check browser console for JavaScript errors

4. **Real-time Updates Not Working**
   - Check API endpoint responses
   - Verify JavaScript console for errors
   - Ensure CORS headers are properly set

### Debug Mode
Add error reporting to PHP files for debugging:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## Security Considerations

- Change default database credentials in production
- Implement authentication for admin dashboard
- Add input validation and sanitization
- Use HTTPS in production environment
- Regular database backups

## Performance Optimization

- Implement database indexing for large datasets
- Add caching for frequently accessed data
- Optimize API response times
- Use CDN for static resources

## Future Enhancements

- Mobile app development
- Machine learning model improvements
- Additional sensor integrations
- Multi-robot coordination
- Cloud deployment options
- Advanced reporting features

## Support

For issues and questions:
1. Check the troubleshooting section
2. Review browser console errors
3. Verify database connectivity
4. Test API endpoints individually

## License

This project is for educational and demonstration purposes. Feel free to modify and enhance according to your needs.

---

**System Requirements:**
- PHP 7.4+
- MySQL 5.7+
- Modern web browser (Chrome, Firefox, Safari, Edge)
- Internet connection for CDN resources

**Recommended Setup:**
- XAMPP for local development
- 2GB+ RAM
- 1GB+ disk space
- Stable internet connection
