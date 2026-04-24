# AI Air Purification System - Final Project Structure

## Complete File Organization

```
/air/                                    # Main project folder
|
|--- index.html                          # Main portal landing page
|--- admin.html                          # Admin portal entry page
|--- user.html                           # User portal entry page
|--- simulation_control.php              # Web-based simulation control
|--- simulation_runner.php               # CLI simulation runner (optional)
|--- db_final.sql                        # Complete database schema and data
|--- db.sql                              # Original database (legacy)
|--- README.md                           # Documentation
|--- PROJECT_STRUCTURE.md                # This file
|--- test_system.php                     # System testing suite
|
|--- admin/                              # Admin dashboard folder
|    |--- index.html                     # Admin dashboard main page
|
|--- user/                               # User dashboard folder
|    |--- index.html                     # User dashboard main page
|
|--- api/                                # Backend API folder
|    |--- config.php                     # Database configuration
|    |--- get_data.php                   # Fetch sensor data API
|    |--- save_data.php                  # Save sensor data API
|    |--- robot_control.php              # Robot control API
|    |--- get_history.php                # Historical data API
|    |--- ai_simulation.php              # AI simulation API
|    |--- simulate_data.php              # Data simulation API
|    |--- data_simulator.php             # Advanced simulation API
|
|--- assets/                             # Static assets folder
|    |--- css/
|    |    |--- tailwind.css             # Production CSS (no CDN)
|    |
|    |--- js/
|    |    |--- admin_final.js            # Admin dashboard JavaScript
|    |    |--- user_final.js             # User dashboard JavaScript
|    |    |--- admin.js                  # Original admin JS (legacy)
|    |    |--- user.js                   # Original user JS (legacy)
|    |
|    |--- images/                        # Image assets (empty - ready for use)
|
|--- simulation_active.flag              # Simulation flag file (created when active)
```

## File Descriptions

### Main Entry Points
- **index.html**: Central hub with portal selection
- **admin.html**: Admin portal landing page
- **user.html**: User portal landing page

### Dashboards
- **admin/index.html**: Advanced admin dashboard with full controls
- **user/index.html**: Simple user dashboard for monitoring

### Backend APIs
- **config.php**: Database connection and helper functions
- **get_data.php**: Fetches latest sensor data and system status
- **robot_control.php**: Handles all robot control commands
- **ai_simulation.php**: AI decision-making logic
- **data_simulator.php**: Realistic data simulation

### Frontend JavaScript
- **admin_final.js**: Complete admin dashboard functionality
- **user_final.js**: Complete user dashboard functionality

### Database
- **db_final.sql**: Complete database with sample data
- Includes: sensor_data, robot_status, alerts, system_logs tables

### Simulation Control
- **simulation_control.php**: Web interface for data simulation
- **simulation_runner.php**: CLI script for continuous simulation

### Static Assets
- **tailwind.css**: Production-ready CSS (no CDN dependencies)
- **images/**: Ready for custom images and icons

## Key Features Implemented

### 1. Complete Backend Integration
- All APIs connected with proper error handling
- Real-time data fetching every 5 seconds
- Comprehensive database schema with sample data

### 2. Advanced Frontend Features
- Modern UI with glassmorphism effects
- Real-time charts and data visualization
- Responsive design for all screen sizes
- Smooth animations and transitions

### 3. Robot Control System
- Power ON/OFF control
- Auto/Manual mode switching
- AQI threshold adjustment
- Manual directional controls
- Spray system control
- Quick action buttons

### 4. Data Simulation
- Realistic sensor data generation
- Time-based AQI patterns
- Location-based variations
- Automatic alert generation
- AI simulation triggers

### 5. Error Handling & Logging
- Comprehensive error handling
- Toast notifications
- Console logging
- System activity tracking

### 6. Production Ready
- No CDN dependencies
- Local CSS optimization
- XAMPP compatible paths
- Clean file structure

## Database Tables

### sensor_data
- Stores AQI, temperature, humidity, GPS coordinates
- Timestamped for historical analysis
- Indexed for performance

### robot_status
- Current robot state and settings
- Power status, mode, spray status
- GPS coordinates and battery level

### alerts
- System notifications and warnings
- Different severity levels
- Read/unread status tracking

### system_logs
- Activity and action logging
- User tracking
- Audit trail functionality

## API Endpoints

### GET /api/get_data.php
Returns: Latest sensor data, robot status, alerts, statistics

### POST /api/robot_control.php
Actions: start_robot, stop_robot, toggle_spray, set_mode, set_threshold, manual_control

### GET /api/get_history.php?period=X
Returns: Historical data for charts and analytics

### POST /api/data_simulator.php
Actions: generate, start_continuous, stop_continuous, status

## Security Considerations

### Database Security
- Uses prepared statements to prevent SQL injection
- Input validation on all endpoints
- Proper error handling without exposing sensitive data

### API Security
- CORS headers properly configured
- Request validation
- Rate limiting considerations

### Frontend Security
- No sensitive data in JavaScript
- Proper error handling
- Input sanitization

## Performance Optimizations

### Database
- Proper indexing on frequently queried columns
- Efficient queries with limits
- Connection pooling ready

### Frontend
- Local CSS (no CDN dependencies)
- Optimized JavaScript with proper error handling
- Efficient DOM updates
- Smooth animations with CSS transforms

### API
- Efficient data structures
- Minimal data transfer
- Proper caching headers
- Async processing for heavy operations

## Deployment Requirements

### Server Requirements
- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx web server
- XAMPP compatible

### Browser Requirements
- Modern browser with ES6 support
- Chart.js 3.9.1+
- Responsive design support

### File Permissions
- Write access for simulation flag file
- Database access credentials
- Proper file ownership

## Testing Instructions

### 1. Database Setup
1. Import db_final.sql into MySQL
2. Verify all tables created successfully
3. Check sample data insertion

### 2. API Testing
1. Test get_data.php endpoint
2. Test robot_control.php actions
3. Verify data simulation works

### 3. Frontend Testing
1. Load main portal page
2. Test admin dashboard functionality
3. Test user dashboard functionality
4. Verify real-time updates

### 4. Integration Testing
1. Test robot control buttons
2. Verify data simulation
3. Check error handling
4. Test responsive design

## Maintenance Notes

### Regular Tasks
- Database backup and cleanup
- Log file rotation
- Performance monitoring
- Security updates

### Troubleshooting
- Check database connection
- Verify API endpoints
- Monitor console errors
- Check file permissions

### Scaling Considerations
- Database indexing optimization
- API caching strategies
- Load balancing preparation
- CDN integration possibility
