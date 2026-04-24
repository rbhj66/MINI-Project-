# AI Air Purification System - Complete Setup Guide

## Quick Start Guide (5 Minutes)

### Prerequisites
- XAMPP installed on Windows/Mac/Linux
- Modern web browser (Chrome, Firefox, Safari, Edge)
- Internet connection (for initial setup only)

### Step 1: Start XAMPP Services
1. Open XAMPP Control Panel
2. Start **Apache** service
3. Start **MySQL** service
4. Verify both services are running (green indicators)

### Step 2: Database Setup
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click **"New"** to create database
3. Enter database name: `air_purification_system`
4. Click **"Create"**
5. Select the database
6. Click **"Import"** tab
7. Choose file: `db_final.sql` (from project folder)
8. Click **"Go"** (bottom right)

### Step 3: Deploy Project
1. Copy entire `/air/` folder to XAMPP htdocs:
   - **Windows**: `C:\xampp\htdocs\air\`
   - **Mac**: `/Applications/XAMPP/htdocs/air/`
   - **Linux**: `/opt/lampp/htdocs/air/`

### Step 4: Access System
Open browser and navigate to:
- **Main Portal**: `http://localhost/air/`
- **Admin Portal**: `http://localhost/air/admin.html`
- **User Portal**: `http://localhost/air/user.html`
- **Simulation Control**: `http://localhost/air/simulation_control.php`

### Step 5: Test System
1. Visit **Simulation Control**: `http://localhost/air/simulation_control.php`
2. Click **"Start Simulation"** to generate live data
3. Open **Admin Dashboard** to see real-time updates
4. Open **User Dashboard** for monitoring view

---

## Detailed Setup Instructions

### Database Configuration

#### Default XAMPP Settings
- **Host**: localhost
- **Username**: root
- **Password**: (empty/blank)
- **Database**: air_purification_system

#### If Using Different Credentials
Edit `api/config.php`:
```php
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'air_purification_system';
```

#### Database Schema Verification
After importing `db_final.sql`, verify tables:
```sql
SHOW TABLES;
-- Should show: alerts, robot_status, sensor_data, system_logs
```

### File Structure Verification

Ensure your htdocs/air/ folder contains:
```
air/
|-- index.html                    # Main portal
|-- admin.html                    # Admin entry
|-- user.html                     # User entry
|-- simulation_control.php        # Simulation control
|-- db_final.sql                  # Database file
|-- admin/
|   |-- index.html               # Admin dashboard
|-- user/
|   |-- index.html               # User dashboard
|-- api/
|   |-- config.php               # Database config
|   |-- get_data.php             # Data API
|   |-- robot_control.php        # Control API
|   |-- data_simulator.php       # Simulation API
|   |-- (other API files...)
|-- assets/
|   |-- css/
|   |   |-- tailwind.css         # Styles
|   |-- js/
|   |   |-- admin_final.js       # Admin scripts
|   |   |-- user_final.js        # User scripts
```

### Testing All Components

#### 1. Test API Endpoints
Open these URLs in browser (should return JSON):
- `http://localhost/air/api/get_data.php`
- `http://localhost/air/api/get_history.php?period=24h`

#### 2. Test Data Simulation
1. Go to: `http://localhost/air/simulation_control.php`
2. Click **"Generate Data"** - should show success message
3. Click **"Start Simulation"** - should show "Active" status
4. Wait 5 seconds - should auto-refresh with new data

#### 3. Test Admin Dashboard
1. Open: `http://localhost/air/admin.html`
2. Click **"Launch Admin Dashboard"**
3. Verify:
   - Real-time data updates every 5 seconds
   - Robot control buttons work
   - Charts display data
   - No console errors

#### 4. Test User Dashboard
1. Open: `http://localhost/air/user.html`
2. Click **"View Air Quality"**
3. Verify:
   - AQI display updates
   - Health recommendations show
   - Charts render correctly
   - Mobile responsive

#### 5. Test Robot Controls
In Admin Dashboard:
1. Click **"Robot Power"** toggle
2. Verify status changes
3. Click **"Auto Mode"** button
4. Adjust **AQI Threshold** slider
5. Try **Manual Control** directional buttons
6. Click **"High AQI"** quick action

### Troubleshooting Guide

#### Common Issues

**Issue 1: Database Connection Error**
```
Error: Database connection failed
```
**Solution:**
1. Verify MySQL is running in XAMPP
2. Check database name: `air_purification_system`
3. Verify db_final.sql was imported successfully
4. Check credentials in api/config.php

**Issue 2: 404 Not Found Errors**
```
Not Found: The requested URL was not found
```
**Solution:**
1. Verify files are in correct htdocs/air/ folder
2. Check Apache is running
3. Try: `http://localhost/air/` (with trailing slash)

**Issue 3: API Not Responding**
```
Network error: Failed to fetch
```
**Solution:**
1. Check browser console for errors
2. Verify api/ folder exists
3. Test API directly in browser
4. Check PHP error logs

**Issue 4: Charts Not Loading**
```
Chart.js: Canvas is already in use
```
**Solution:**
1. Refresh the page
2. Check Chart.js CDN is loading
3. Verify data is being fetched
4. Clear browser cache

**Issue 5: Real-time Updates Not Working**
**Solution:**
1. Check simulation is active
2. Verify data is being generated
3. Check browser console for JavaScript errors
4. Test API endpoints directly

#### Debug Mode
Add to any PHP file for debugging:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

#### Browser Console
Check for JavaScript errors:
- Press **F12** (or Ctrl+Shift+I)
- Go to **Console** tab
- Look for red error messages

#### Network Tab
Monitor API calls:
- Press **F12**
- Go to **Network** tab
- Look for failed requests (red)

### Performance Optimization

#### Database Optimization
```sql
-- Check table sizes
SELECT 
    table_name,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size (MB)'
FROM information_schema.tables 
WHERE table_schema = 'air_purification_system';

-- Optimize tables
OPTIMIZE TABLE sensor_data;
OPTIMIZE TABLE alerts;
```

#### Clear Old Data
```sql
-- Delete data older than 30 days
DELETE FROM sensor_data WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);
DELETE FROM alerts WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);
DELETE FROM system_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);
```

### Security Considerations

#### Production Deployment
1. Change database credentials
2. Add authentication to admin dashboard
3. Use HTTPS (SSL certificate)
4. Implement rate limiting
5. Regular database backups

#### File Permissions
Ensure proper permissions:
- PHP files: 644 (readable, writable by owner)
- Folders: 755 (readable, executable)
- Database config: 600 (only owner readable)

### Advanced Features

#### Continuous Simulation
For automatic data generation:
1. Use `simulation_runner.php` via cron job
2. Or keep `simulation_control.php` open in browser
3. Simulation generates data every 5 seconds

#### Custom Locations
Edit `api/data_simulator.php` to add new locations:
```php
$locations = [
    ['lat' => 28.6139, 'lng' => 77.2090, 'name' => 'Your Location'],
    // Add more locations here
];
```

#### Custom AQI Patterns
Modify time-based factors in `getTimeBasedAQIFactor()`:
```php
function getTimeBasedAQIFactor($hour) {
    // Customize for your location's patterns
    if ($hour >= 7 && $hour <= 9) return 1.3; // Morning rush
    // Add your custom patterns
}
```

### API Documentation

#### Get Sensor Data
```http
GET /api/get_data.php
Response: {
    "success": true,
    "data": {
        "sensor_data": {...},
        "robot_status": {...},
        "alerts": [...],
        "statistics": {...}
    }
}
```

#### Control Robot
```http
POST /api/robot_control.php
Body: {
    "action": "start_robot|stop_robot|toggle_spray|set_mode|set_threshold|manual_control",
    "mode": "AUTO|MANUAL",
    "threshold": 100,
    "direction": "forward|backward|left|right|stop"
}
```

#### Get Historical Data
```http
GET /api/get_history.php?period=1h|6h|24h|7d|30d
Response: {
    "success": true,
    "data": {
        "chart_data": {...},
        "aqi_distribution": {...},
        "system_logs": [...]
    }
}
```

### Support & Maintenance

#### Regular Tasks
- **Daily**: Monitor system performance
- **Weekly**: Check database size, clean old data
- **Monthly**: Update PHP/Apache versions
- **Quarterly**: Review security settings

#### Backup Strategy
```bash
# Database backup
mysqldump -u root -p air_purification_system > backup.sql

# Files backup
cp -r /path/to/air/ /path/to/backup/
```

#### Monitoring
Check system health:
- Database connection status
- API response times
- Error log monitoring
- User activity tracking

---

## Quick Reference URLs

| Feature | URL | Description |
|---------|-----|-------------|
| Main Portal | `http://localhost/air/` | Central hub with portal selection |
| Admin Portal | `http://localhost/air/admin.html` | Admin entry page |
| User Portal | `http://localhost/air/user.html` | User entry page |
| Admin Dashboard | `http://localhost/air/admin/` | Full admin control panel |
| User Dashboard | `http://localhost/air/user/` | Monitoring dashboard |
| Simulation Control | `http://localhost/air/simulation_control.php` | Data simulation control |
| API Test | `http://localhost/air/api/get_data.php` | Test API endpoint |
| Database Admin | `http://localhost/phpmyadmin` | Database management |

## Success Checklist

Before finishing, verify:
- [ ] XAMPP services running (Apache + MySQL)
- [ ] Database imported successfully
- [ ] Main portal loads without errors
- [ ] Admin dashboard functional
- [ ] User dashboard functional
- [ ] Robot controls working
- [ ] Data simulation active
- [ ] Real-time updates working
- [ ] No console errors
- [ ] Mobile responsive design
- [ ] Charts displaying correctly

---

**Your AI Air Purification System is now ready!** 

The system provides:
- Real-time air quality monitoring
- Advanced robot control
- AI-powered decision making
- Beautiful user interfaces
- Complete data simulation
- Production-ready deployment

Enjoy exploring your intelligent air purification system!
