#!/bin/bash

# SecureWipe GUI Production Build Script
# This script builds the production-ready GUI with local dependencies

set -e

echo "=== SecureWipe GUI Production Build ==="

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    echo "Error: Node.js is not installed. Please install Node.js 18+ first."
    exit 1
fi

# Check Node.js version
NODE_VERSION=$(node -v | cut -d'v' -f2 | cut -d'.' -f1)
if [ "$NODE_VERSION" -lt 18 ]; then
    echo "Error: Node.js version 18+ is required. Current version: $(node -v)"
    exit 1
fi

echo "Node.js version: $(node -v) - OK"

# Install dependencies if not present
if [ ! -d "node_modules" ]; then
    echo "Installing dependencies..."
    npm install
fi

# Create dist directory if it doesn't exist
mkdir -p dist

# Build production CSS
echo "Building production CSS..."
npm run build-css-prod

# Verify CSS was built
if [ ! -f "dist/output.css" ]; then
    echo "Error: CSS build failed. dist/output.css not found."
    exit 1
fi

# Get CSS file size
CSS_SIZE=$(stat -f%z dist/output.css 2>/dev/null || stat -c%s dist/output.css 2>/dev/null)
echo "CSS built successfully: $CSS_SIZE bytes"

# Validate HTML file
if [ ! -f "index-prod.html" ]; then
    echo "Error: index-prod.html not found."
    exit 1
fi

# Create a simple test server script
cat > serve.sh << 'EOF'
#!/bin/bash
echo "Starting SecureWipe GUI server..."
echo "Open http://localhost:8080/index-prod.html"
python3 -m http.server 8080
EOF
chmod +x serve.sh

echo ""
echo "=== Build Complete ==="
echo "Production files ready:"
echo "  - index-prod.html (Production HTML)"
echo "  - dist/output.css (Optimized CSS)"
echo ""
echo "To start the server:"
echo "  ./serve.sh"
echo ""
echo "Or open directly in browser:"
echo "  file://$(pwd)/index-prod.html"
echo ""
echo "=== Production Deployment Ready ==="
