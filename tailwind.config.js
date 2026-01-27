/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./vendor/livewire/flux/**/*.php"
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#3b82f6', // Default blue color
          dark: '#2563eb',    // Darker blue for hover/active states
        }
      }
    },
  },
  plugins: [],
}
