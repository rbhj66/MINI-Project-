# SecureWipe User Guide

## Table of Contents

1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Quick Start](#quick-start)
4. [Command Line Interface](#command-line-interface)
5. [Web GUI](#web-gui)
6. [Wiping Methods](#wiping-methods)
7. [Certificates](#certificates)
8. [Bootable Media](#bootable-media)
9. [Verification](#verification)
10. [Troubleshooting](#troubleshooting)

## Introduction

SecureWipe is a cross-platform, NIST SP 800-88 Rev 2 compliant data wiping application designed for trustworthy IT asset recycling. It provides secure erasure of storage devices with tamper-proof certificates and third-party verification.

### Key Features

- **Cross-Platform**: Windows, Linux, and Android support
- **Standards Compliant**: NIST SP 800-88 Rev 2 and IEEE 2883-2022
- **Complete Sanitization**: Erases HPA, DCO, and hidden areas
- **Tamper-Proof Certificates**: Digitally signed PDF/JSON certificates
- **One-Click Operation**: Intuitive interface for all users
- **Offline Capability**: Bootable ISO/USB creation
- **Third-Party Verification**: External validation and audit trails

## Installation

### System Requirements

- **Operating System**: Windows 10+, Ubuntu 18.04+, Android 8.0+
- **Memory**: Minimum 512MB RAM
- **Storage**: 100MB free space
- **Permissions**: Administrator/root privileges for device access

### Installation Steps

#### Option 1: Download Binary
1. Download the appropriate binary for your platform
2. Extract to a secure location
3. Run with administrator/root privileges

#### Option 2: Build from Source
```bash
git clone https://github.com/securewipe/securewipe.git
cd securewipe
npm install
npm run build
```

#### Option 3: Package Manager
```bash
# npm
npm install -g securewipe

# apt (Ubuntu/Debian)
sudo apt install securewipe

# yum (CentOS/RHEL)
sudo yum install securewipe
```

## Quick Start

### One-Click Wipe (Recommended)

For most users, the one-click wipe provides the perfect balance of security and simplicity:

```bash
securewipe wipe --auto --device /dev/sda
```

This command:
- Auto-detects device type
- Uses recommended NIST Purge method
- Generates certificates automatically
- Provides progress tracking

### Interactive Mode

For more control over the wiping process:

```bash
securewipe wipe
```

This will guide you through:
1. Device selection
2. Method selection
3. Confirmation
4. Wipe execution
5. Certificate generation

## Command Line Interface

### Basic Commands

#### List Available Devices
```bash
securewipe list
```

Output:
```
Available devices:
1. /dev/sda - Samsung SSD 860 EVO 500GB
   Type: SSD | Status: Ready
   Serial: S3Z8NB0K123456

2. /dev/sdb - WD Blue 1TB
   Type: HDD | Status: Ready
   Serial: WX1D1234567890
```

#### Start Wipe Process
```bash
# Interactive mode
securewipe wipe

# Auto mode with specific device
securewipe wipe --device /dev/sda --method nist-purge --output ./certificates

# Quick wipe with defaults
securewipe wipe --auto --device /dev/sda
```

#### Verify Certificate
```bash
securewipe verify certificate_2024-04-15.json
```

#### Create Bootable Media
```bash
securewipe create-iso --output ./securewipe.iso
```

### Advanced Options

#### Method Selection
```bash
--method nist-clear      # User-addressable areas only (faster)
--method nist-purge      # Complete sanitization (recommended)
--method ieee-purge      # Cryptographic erase (for SSDs)
--method gutmann         # 35-pass maximum security
--method dod-5220        # DoD 5220.22-M standard
```

#### Output Options
```bash
--output ./certificates    # Certificate output directory
--format pdf,json          # Certificate formats
--language en,hi,bn        # Certificate language
```

#### Verification Options
```bash
--verify-online            # Enable third-party verification
--blockchain-anchor        # Anchor to blockchain
--audit-log                # Create audit trail
```

## Web GUI

### Accessing the GUI

#### Method 1: Local Server
```bash
cd src/ui/gui
python3 -m http.server 8080
```
Then open `http://localhost:8080` in your browser.

#### Method 2: Bootable Media
Boot from SecureWipe ISO and access GUI at `http://localhost:8080/gui.html`

### GUI Features

#### Device Selection
- Automatic device discovery
- Device information display
- Real-time status updates

#### Method Selection
- Visual method comparison
- Security level indicators
- Compliance information

#### Progress Tracking
- Real-time progress bar
- Pass-by-pass updates
- Time remaining estimates

#### Certificate Management
- Download PDF certificates
- Export JSON certificates
- Verify certificates online

## Wiping Methods

### NIST Clear
- **Purpose**: User-addressable areas only
- **Speed**: Fast
- **Security**: Standard
- **Use Case**: Quick sanitization of non-sensitive data

### NIST Purge (Recommended)
- **Purpose**: Complete media sanitization
- **Speed**: Medium
- **Security**: High
- **Use Case**: Most enterprise and personal use

### IEEE Purge
- **Purpose**: Cryptographic/block erase
- **Speed**: Fast (for SSDs)
- **Security**: Very High
- **Use Case**: SSDs and self-encrypting drives

### Gutmann Method
- **Purpose**: Maximum security
- **Speed**: Very Slow
- **Security**: Maximum
- **Use Case**: Highly sensitive classified data

### DoD 5220.22-M
- **Purpose**: Department of Defense standard
- **Speed**: Slow
- **Security**: High
- **Use Case**: Military/government requirements

## Certificates

### Certificate Types

#### PDF Certificate
- Professional format
- Digital signature
- QR code for verification
- Multi-language support

#### JSON Certificate
- Machine-readable
- API integration
- Automated processing
- Backup format

### Certificate Contents

#### Device Information
- Device model and serial
- Storage capacity
- Device type (HDD/SSD/NVMe)

#### Wipe Details
- Wiping method used
- Date and time of wipe
- Duration of process
- Sectors erased

#### Verification
- Digital signature
- Verification hash
- Compliance standards
- Third-party verification status

### Certificate Security

#### Digital Signatures
- RSA-2048 encryption
- SHA-256 hashing
- PKI-based verification
- Tamper-evident design

#### Verification Methods
- Local verification
- Online verification
- QR code scanning
- Blockchain anchoring

## Bootable Media

### Creating Bootable Media

#### ISO Creation
```bash
securewipe create-iso --output ./securewipe.iso
```

#### USB Creation
```bash
# List USB devices
securewipe list-usb

# Create bootable USB
securewipe create-usb --device /dev/sdb --iso ./securewipe.iso
```

### Bootable Media Features

#### Live Environment
- Minimal Linux OS
- Pre-installed SecureWipe
- Web-based GUI
- Command-line tools

#### Offline Operation
- No internet required
- Complete functionality
- Certificate generation
- Local verification

#### Cross-Platform Support
- Boot on any system
- UEFI/Legacy support
- Automatic driver loading
- Hardware compatibility

## Verification

### Local Verification

#### Command Line
```bash
securewipe verify certificate.json
```

#### GUI Verification
1. Open verification dashboard
2. Upload certificate file
3. View verification results

### Online Verification

#### Web Verification
1. Scan QR code in certificate
2. Visit verification URL
3. View verification status

#### API Verification
```bash
curl -X POST https://verify.securewipe.in/api/verify \
  -H "Content-Type: application/json" \
  -d '{"certificateId": "cert-123", "hash": "abc123"}'
```

### Third-Party Verification

#### Verification Services
- SecureWipe Verification Service
- Independent auditors
- Regulatory compliance verification
- Blockchain verification

#### Audit Trails
- Complete operation logging
- Timestamp verification
- Actor identification
- Chain of custody

## Troubleshooting

### Common Issues

#### Device Not Found
```bash
# Check device permissions
sudo ls -la /dev/sd*

# Check device status
sudo hdparm -I /dev/sda
```

#### Permission Denied
```bash
# Run with sudo
sudo securewipe wipe --device /dev/sda

# Check user groups
groups $USER
```

#### Wipe Failed
```bash
# Check device health
sudo smartctl -a /dev/sda

# Try different method
securewipe wipe --device /dev/sda --method nist-clear
```

#### Certificate Verification Failed
```bash
# Check certificate integrity
securewipe verify certificate.json

# Verify signature manually
openssl dgst -sha256 -verify public.pem -signature signature.bin certificate.json
```

### Error Codes

| Code | Description | Solution |
|------|-------------|----------|
| 001 | Device not found | Check device path and permissions |
| 002 | Permission denied | Run with administrator privileges |
| 003 | Device busy | Unmount device and retry |
| 004 | Wipe method not supported | Use compatible method for device type |
| 005 | Certificate generation failed | Check disk space and permissions |
| 006 | Verification failed | Check certificate integrity and signature |

### Performance Issues

#### Slow Wipe Speed
- Use appropriate method for device type
- Check for system resource contention
- Verify device health status

#### Memory Issues
- Close unnecessary applications
- Increase system RAM if possible
- Use streaming mode for large devices

### Getting Help

#### Documentation
- Complete API documentation: `docs/API.md`
- Security guidelines: `docs/SECURITY.md`
- Compliance information: `docs/COMPLIANCE.md`

#### Support Channels
- Email: support@securewipe.in
- Website: https://securewipe.in
- GitHub: https://github.com/securewipe/securewipe

#### Community
- Forums: https://community.securewipe.in
- Discord: https://discord.gg/securewipe
- Stack Overflow: Tag with `securewipe`

## Best Practices

### Before Wiping
1. **Backup Important Data**: Ensure all needed data is backed up
2. **Verify Device**: Confirm correct device selection
3. **Check Permissions**: Ensure necessary privileges
4. **Plan Method**: Choose appropriate wiping method

### During Wiping
1. **Monitor Progress**: Watch for error messages
2. **Maintain Power**: Ensure stable power supply
3. **Avoid Interruption**: Don't cancel the process
4. **Log Results**: Save operation logs

### After Wiping
1. **Verify Certificate**: Check certificate validity
2. **Store Securely**: Save certificates in safe location
3. **Update Records**: Update asset management systems
4. **Dispose Properly**: Follow e-waste disposal guidelines

### Security Considerations
1. **Physical Security**: Secure devices during wiping
2. **Network Security**: Use isolated networks when possible
3. **Certificate Security**: Protect private keys and certificates
4. **Audit Trail**: Maintain complete audit records

## Compliance

### Standards Compliance
- **NIST SP 800-88 Rev 2**: Complete compliance
- **IEEE 2883-2022**: Modern sanitization standards
- **ISO 27001**: Information security management
- **GDPR**: Data protection compliance

### Regulatory Requirements
- **Data Protection Laws**: Local and international compliance
- **Industry Standards**: Sector-specific requirements
- **Audit Requirements**: Complete audit trails
- **Documentation**: Comprehensive record-keeping

### Certification
- **NIST Certification**: Official NIST compliance certification
- **Third-Party Audit**: Independent verification
- **Industry Recognition**: Wide industry acceptance
- **Legal Validity**: Legally defensible certificates
