/** @type {import('tailwindcss').Config} */
export default {
  content: ['./index.html', './src/**/*.{svelte,js,ts}'],
  darkMode: ['class', '[data-theme="dark"]'],
  theme: {
    extend: {
      colors: {
        brand: {
          main:      'rgb(var(--color-main) / <alpha-value>)',
          container: 'rgb(var(--color-container) / <alpha-value>)',
          accent:    'rgb(var(--color-accent) / <alpha-value>)',
          textMain:  'rgb(var(--color-text-main) / <alpha-value>)',
          textSec:   'rgb(var(--color-text-sec) / <alpha-value>)',
          border:    'rgb(var(--color-border) / <alpha-value>)',
        },
      },
      fontFamily: {
        sans:    ['Inter', 'sans-serif'],
        heading: ['"Plus Jakarta Sans"', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
};
