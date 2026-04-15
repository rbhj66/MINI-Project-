/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{html,js}",
    "./*.html"
  ],
  theme: {
    extend: {
      colors: {
        'securewipe': {
          'blue': '#2563eb',
          'green': '#059669',
          'red': '#dc2626',
          'yellow': '#d97706'
        }
      },
      animation: {
        'pulse-border': 'pulse-border 2s ease-in-out infinite',
      },
      keyframes: {
        'pulse-border': {
          '0%, 100%': { borderColor: 'rgb(239 68 68)' },
          '50%': { borderColor: 'rgb(248 113 113)' },
        }
      }
    },
  },
  plugins: [],
}
