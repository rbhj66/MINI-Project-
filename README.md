# SecureWipe - Trustworthy IT Asset Data Sanitization

A cross-platform, standards-compliant data wiping application for secure IT asset recycling.

## Problem Statement

India faces a growing e-waste crisis with over 1.75 million tonnes generated annually. Fear of data breaches prevents millions of devices from being recycled, leading to hoarding of IT assets worth over **50,000 crore**. This solution provides user-friendly, verifiable data wiping to promote safe e-waste management.

## Features

- **Cross-Platform**: Windows, Linux, Android support
- **Standards Compliant**: NIST SP 800-88 Rev 2, IEEE 2883-2022
- **Complete Sanitization**: Erases HPA, DCO, DFA, and remapped sectors
- **Tamper-Proof Certificates**: Digitally signed PDF/JSON certificates
- **One-Click Interface**: Intuitive operation for general public
- **Offline Capability**: Bootable ISO/USB creation
- **Third-Party Verification**: Verifiable wipe status
- **Scalable**: Bulk operations for enterprise use

## Architecture

```
SecureWipe/
|-- core/                    # Platform-agnostic core logic
|   |-- algorithms/          # Data wiping algorithms
|   |-- crypto/              # Cryptographic operations
|   |-- verification/        # Verification systems
|   |-- certificates/        # Certificate generation
|-- platforms/               # Platform-specific implementations
|   |-- windows/            # Windows-specific code
|   |-- linux/              # Linux-specific code
|   |-- android/            # Android-specific code
|-- ui/                     # User interface
|   |-- cli/                # Command-line interface
|   |-- gui/                # Graphical interface
|-- tools/                  # Additional tools
|   |-- iso-creator/        # Bootable media creation
|   |-- verifier/           # Third-party verification
```

## Security Standards

- **NIST Clear**: User-addressable areas only
- **NIST Purge**: Complete media sanitization (recommended)
- **IEEE Purge**: Cryptographic erase, block erase, secure erase
- **Hidden Areas**: HPA, DCO, DFA handling
- **Verification**: Post-wipe verification required

## Quick Start

```bash
# Install dependencies
npm install

# Build for current platform
npm run build

# Create bootable media
npm run create-iso

# Run one-click wipe
npm run wipe -- --device /dev/sda --method nist-purge
```

## License

MIT License - See LICENSE file for details
