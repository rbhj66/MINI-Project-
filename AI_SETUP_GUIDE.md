# AI-Powered Air Purification System - Complete Setup Guide

## Overview
Transform your existing Air Purification Robot System into a real AI-powered intelligent platform with machine learning predictions, smart decision making, and anomaly detection.

## System Architecture
```
ESP32 Sensors -> PHP Backend -> MySQL Database
                     -> Python Flask AI API -> ML Models
                     -> Frontend Dashboards -> AI Insights
```

## Prerequisites
- Python 3.8+ installed
- XAMPP with Apache and MySQL
- PHP 7.4+ with curl extension
- Git (optional)

## Step-by-Step Setup

### Step 1: Database Setup

1. **Import AI-Enhanced Database**
   ```bash
   # Open phpMyAdmin: http://localhost/phpmyadmin
   # Create database: air_purification_system
   # Import file: db_ai.sql
   ```

2. **Verify Tables Created**
   ```sql
   SHOW TABLES;
   -- Should show: sensor_data, robot_status, alerts, predictions, ai_logs, system_logs
   ```

### Step 2: Python AI API Setup

1. **Install Python Dependencies**
   ```bash
   cd ai
   pip install -r requirements.txt
   ```

2. **Start Python Flask API**
   ```bash
   python app.py
   # API will run on http://localhost:5000
   ```

3. **Test AI API Health**
   ```bash
   curl http://localhost:5000/health
   # Should return: {"status": "healthy", "model_trained": false}
   ```

### Step 3: Deploy Files to XAMPP

1. **Copy Project Files**
   ```
   Copy entire /air/ folder to C:\xampp\htdocs\air\
   ```

2. **Verify File Structure**
   ```
   C:\xampp\htdocs\air\
   |-- ai/                          # Python Flask API
   |-- admin/
   |   |-- ai_dashboard.html       # AI Dashboard
   |   |-- index.html              # Admin Dashboard
   |-- user/
   |   |-- index.html              # User Dashboard
   |-- api/
   |   |-- ai_integration.php      # PHP-AI Bridge
   |   |-- config.php              # Database Config
   |   |-- get_data.php            # Data API
   |   |-- robot_control.php       # Robot Control
   |-- assets/
   |-- ai_simulation_system.php    # AI Simulation
   |-- db_ai.sql                   # Database Schema
   ```

### Step 4: Start XAMPP Services

1. **Open XAMPP Control Panel**
2. **Start Apache** (Port 80)
3. **Start MySQL** (Port 3306)

### Step 5: Test System Integration

1. **Test PHP-AI Integration**
   ```bash
   curl http://localhost/air/api/ai_integration.php?action=health
   ```

2. **Test AI Simulation**
   ```bash
   curl http://localhost/air/ai_simulation_system.php?action=run
   ```

### Step 6: Access AI Dashboard

1. **Open AI Dashboard**
   ```
   http://localhost/air/admin/ai_dashboard.html
   ```

2. **Main Portal**
   ```
   http://localhost/air/
   ```

## AI Features Overview

### 1. AQI Prediction Model
- **Algorithm**: Linear Regression with feature engineering
- **Input Features**: AQI history, temperature, humidity, time of day
- **Predictions**: 15min, 30min, 1hour ahead
- **Confidence Scoring**: 70-95% based on data quality
- **Fallback**: Time-based prediction when model unavailable

### 2. Smart Decision Engine
- **Proactive Actions**: Move robot BEFORE pollution increases
- **Spray Intensity**: LOW/MEDIUM/HIGH based on AQI severity
- **Route Optimization**: Priority + distance algorithm
- **Confidence-Based**: Decisions include confidence scores

### 3. Anomaly Detection
- **Spike Detection**: Identifies unusual AQI increases
- **Pattern Analysis**: Detects deviations from expected patterns
- **Real-time Alerts**: Immediate notification of anomalies
- **Severity Classification**: Medium/High severity levels

### 4. Route Optimization
- **Priority Scoring**: AQI level + distance penalty
- **Multi-Location**: Handles multiple high AQI areas
- **Efficiency Metrics**: Route optimization scores
- **Time Estimation**: Predicted arrival times

### 5. Heatmap Data Preparation
- **Location Aggregation**: Grid-based AQI averaging
- **Real-time Updates**: Continuous data refresh
- **Visualization Ready**: JSON format for map rendering

## API Endpoints

### Python Flask AI API (Port 5000)
```
GET  /health                    # API health check
POST /predict                   # AQI prediction
POST /decision                  # AI decision making
POST /anomaly                   # Anomaly detection
POST /optimize_route           # Route optimization
POST /train_model              # Train ML model
GET  /heatmap_data             # Heatmap data
```

### PHP Integration API (Port 80)
```
GET  /api/ai_integration.php?action=health
POST /api/ai_integration.php?action=predict
POST /api/ai_integration.php?action=decision
POST /api/ai_integration.php?action=anomaly
POST /api/ai_integration.php?action=optimize_route
POST /api/ai_integration.php?action=train_model
GET  /api/ai_integration.php?action=heatmap
```

### Simulation System
```
GET  /ai_simulation_system.php?action=run        # Single simulation
GET  /ai_simulation_system.php?action=continuous  # Multiple simulations
GET  /ai_simulation_system.php?action=stats      # Simulation statistics
```

## Database Schema

### Enhanced Tables
- **sensor_data**: Added pollutants, weather, anomaly detection
- **robot_status**: Added AI mode, spray intensity, AI decisions
- **alerts**: Added AI source, confidence, action tracking
- **predictions**: New table for ML predictions
- **ai_logs**: New table for AI decision tracking
- **system_logs**: Enhanced with AI involvement tracking

### Key Features
- **Foreign Key Relationships**: Maintains data integrity
- **Indexes**: Optimized for AI queries
- **JSON Fields**: Flexible data storage for AI features
- **Timestamps**: Complete audit trail

## Frontend AI Features

### AI Dashboard
- **Real-time Predictions**: Live AQI forecasting
- **Decision Display**: AI reasoning and confidence
- **Performance Metrics**: Model accuracy and success rates
- **Anomaly Alerts**: Immediate notification system
- **Interactive Charts**: Actual vs Predicted comparisons

### Enhanced Admin Dashboard
- **AI Mode**: New robot control mode
- **Prediction Cards**: Future AQI display
- **Decision Panel**: AI recommendations
- **Confidence Indicators**: Visual confidence scores

### User Dashboard
- **AI Insights**: Health recommendations based on predictions
- **Trend Analysis**: ML-powered trend detection
- **Smart Alerts**: Proactive health warnings

## Simulation Mode

### Realistic Data Generation
- **Time-Based Patterns**: Rush hour, business hours, night
- **Location Variations**: Different base AQI levels
- **Weather Integration**: Temperature/humidity correlation
- **Anomaly Simulation**: Random spike generation

### AI Training Data
- **Automatic Generation**: Creates training datasets
- **Feature Engineering**: Time, temperature, humidity ratios
- **Model Persistence**: Saves trained models
- **Performance Tracking**: Accuracy monitoring

## Troubleshooting

### Common Issues

1. **Python API Not Responding**
   ```bash
   # Check if Flask is running
   curl http://localhost:5000/health
   
   # Restart Flask API
   cd ai && python app.py
   ```

2. **MySQL Connection Error**
   ```bash
   # Check MySQL service
   # Verify database exists
   # Check credentials in api/config.php
   ```

3. **AI Integration Fails**
   ```bash
   # Test Python API directly
   curl -X POST http://localhost:5000/predict
   
   # Check PHP logs for errors
   tail -f /var/log/apache2/error.log
   ```

4. **Model Training Fails**
   ```bash
   # Ensure sufficient data (50+ readings)
   curl http://localhost/air/ai_simulation_system.php?action=continuous
   ```

### Debug Mode

1. **Enable PHP Error Reporting**
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```

2. **Python Debug Mode**
   ```bash
   export FLASK_ENV=development
   python app.py
   ```

3. **Check Logs**
   ```bash
   # Apache logs
   tail -f /var/log/apache2/error.log
   
   # Python logs
   tail -f ai/flask.log
   ```

## Performance Optimization

### Database Optimization
```sql
-- Add indexes for AI queries
CREATE INDEX idx_sensor_aqi_time ON sensor_data(aqi, created_at);
CREATE INDEX idx_predictions_confidence ON predictions(confidence);
CREATE INDEX idx_ai_logs_decision ON ai_logs(decision, created_at);
```

### Caching Strategy
- **Prediction Cache**: Cache predictions for 5 minutes
- **Model Cache**: Keep ML model in memory
- **Database Cache**: Use Redis for frequent queries

### API Response Times
- **Python API**: < 100ms response time
- **PHP Integration**: < 200ms total response
- **Frontend Updates**: Real-time with 30s refresh

## Security Considerations

### API Security
- **CORS Configuration**: Proper cross-origin setup
- **Input Validation**: Sanitize all inputs
- **Rate Limiting**: Prevent API abuse
- **Authentication**: Add API keys for production

### Data Privacy
- **Anonymization**: Remove sensitive location data
- **Data Retention**: Automatic cleanup of old data
- **Access Control**: Role-based dashboard access

## Production Deployment

### Docker Setup
```dockerfile
# Python Flask API
FROM python:3.9-slim
WORKDIR /app
COPY requirements.txt .
RUN pip install -r requirements.txt
COPY . .
EXPOSE 5000
CMD ["python", "app.py"]
```

### Environment Variables
```bash
# Production configuration
export FLASK_ENV=production
export DB_HOST=localhost
export DB_USER=air_user
export DB_PASSWORD=secure_password
export DB_NAME=air_purification_system
```

### Monitoring
- **Health Checks**: /health endpoint monitoring
- **Performance Metrics**: Response time tracking
- **Error Tracking**: Automated error reporting
- **Model Performance**: Accuracy monitoring

## Advanced Features

### Custom Models
- **Random Forest**: For non-linear patterns
- **LSTM Networks**: For time-series prediction
- **Ensemble Methods**: Combine multiple models

### Real-time Processing
- **WebSocket Integration**: Real-time data streaming
- **Message Queues**: Asynchronous processing
- **Edge Computing**: Local AI processing

### Integration Options
- **IoT Platforms**: AWS IoT, Azure IoT
- **Cloud Services**: AWS SageMaker, Google AI Platform
- **Third-party APIs**: Weather data, traffic data

## Testing

### Unit Tests
```bash
# Python tests
cd ai && python -m pytest tests/

# PHP tests
phpunit tests/
```

### Integration Tests
```bash
# Test complete pipeline
curl -X POST http://localhost/air/api/ai_integration.php
```

### Load Testing
```bash
# Simulate multiple requests
ab -n 1000 -c 10 http://localhost/air/api/ai_integration.php
```

## Support and Maintenance

### Regular Tasks
- **Model Retraining**: Weekly model updates
- **Data Cleanup**: Monthly old data removal
- **Performance Monitoring**: Continuous performance tracking
- **Security Updates**: Regular dependency updates

### Backup Strategy
```bash
# Database backup
mysqldump -u root -p air_purification_system > backup.sql

# Model backup
cp ai/*.pkl models/
```

### Scaling Considerations
- **Horizontal Scaling**: Multiple API instances
- **Database Sharding**: Split data by location
- **CDN Integration**: Static asset delivery
- **Load Balancing**: Distribute API requests

---

## Quick Start Summary

1. **Database**: Import `db_ai.sql` to MySQL
2. **Python API**: `cd ai && pip install -r requirements.txt && python app.py`
3. **XAMPP**: Start Apache + MySQL
4. **Deploy**: Copy files to `htdocs/air/`
5. **Test**: Visit `http://localhost/air/admin/ai_dashboard.html`
6. **Simulate**: Run `ai_simulation_system.php` for data

## Success Metrics

Your AI system is working when:
- [ ] Python Flask API responds to health checks
- [ ] AQI predictions appear in dashboard
- [ ] AI decisions show reasoning and confidence
- [ ] Anomalies trigger alerts
- [ ] Route optimization provides efficient paths
- [ ] Model accuracy improves over time
- [ ] Real-time updates work smoothly

**Your AI-Powered Air Purification System is now ready!** 

The system provides intelligent predictions, proactive decision making, and comprehensive monitoring with a beautiful, futuristic interface. Enjoy exploring your intelligent air quality management system!
