# SecureWipe GUI - Production Setup

## Overview

This is the production-ready web GUI for SecureWipe with local Tailwind CSS and icon dependencies, eliminating CDN dependencies for production use.

## Setup Instructions

### Prerequisites

- Node.js 18+ 
- npm or yarn
- Python 3 (for local server)

### Installation

1. **Install Dependencies**
```bash
cd src/ui/gui
npm install
```

2. **Build Production CSS**
```bash
npm run build
```

This will:
- Compile Tailwind CSS from `src/input.css` to `dist/output.css`
- Minify the CSS for production
- Generate optimized styles

3. **Start Development Server**
```bash
npm run serve
```

Then open `http://localhost:8080/index-prod.html`

### Development Mode

For development with live CSS reloading:
```bash
npm run build-css
```

This will watch for changes and rebuild CSS automatically.

## File Structure

```
src/ui/gui/
|-- package.json              # Node.js dependencies
|-- tailwind.config.js        # Tailwind configuration
|-- src/
|   |-- input.css            # Tailwind input file
|-- dist/
|   |-- output.css           # Generated production CSS
|-- index-prod.html         # Production HTML (no CDN deps)
|-- index.html              # Development HTML (with CDN)
```

## Production Features

### Removed CDN Dependencies
- **Tailwind CSS**: Now compiled locally from `src/input.css`
- **Lucide Icons**: Replaced with CSS-based icon placeholders
- **External Fonts**: Uses system fonts for better performance

### Fallback CSS
The production HTML includes comprehensive fallback CSS styles in case Tailwind CSS fails to load, ensuring the interface remains functional.

### Optimized Performance
- Minified CSS production build
- No external network dependencies
- Self-contained deployment package

## Deployment

### Option 1: Static File Server
Copy the entire `src/ui/gui` directory to your web server and serve `index-prod.html`.

### Option 2: Docker Deployment
```dockerfile
FROM nginx:alpine
COPY src/ui/gui /usr/share/nginx/html
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
```

### Option 3: Integration with Bootable Media
The production files can be included in the bootable ISO for offline operation.

## Customization

### Adding New Icons
Replace the CSS icon placeholders in the `<style>` section:
```css
.icon-new-icon::before { content: "Icon"; }
```

### Modifying Colors
Update `tailwind.config.js` to add custom colors:
```javascript
theme: {
  extend: {
    colors: {
      'custom-color': '#your-color'
    }
  }
}
```

### Adding Components
Add new component classes to `src/input.css`:
```css
@layer components {
  .new-component {
    @apply bg-blue-500 text-white p-4 rounded;
  }
}
```

## Browser Compatibility

- Chrome 88+
- Firefox 85+
- Safari 14+
- Edge 88+

## Security Considerations

- No external CDN dependencies
- All resources served locally
- Content Security Policy ready
- HTTPS recommended for production

## Troubleshooting

### CSS Not Loading
1. Ensure `dist/output.css` exists
2. Run `npm run build` to regenerate CSS
3. Check file permissions

### Icons Not Displaying
1. Icons use CSS text placeholders
2. Customize icon styles in the `<style>` section
3. Consider replacing with SVG icons for better visual quality

### Build Errors
1. Clear node_modules: `rm -rf node_modules`
2. Reinstall: `npm install`
3. Check Node.js version compatibility

## Integration with Backend

The GUI can be integrated with the SecureWipe backend via:

1. **REST API**: Replace mock functions with actual API calls
2. **WebSocket**: Real-time progress updates
3. **File Upload**: Certificate generation and download

Example API integration:
```javascript
async function startWipe() {
  try {
    const response = await fetch('/api/wipe', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        device: selectedDevice.path,
        method: selectedMethod
      })
    });
    
    const result = await response.json();
    // Handle response
  } catch (error) {
    console.error('Wipe failed:', error);
  }
}
```
