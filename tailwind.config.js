/** @type {import('tailwindcss').Config} */
export default {
  // TAMBAHKAN BARIS INI
  darkMode: 'class', 

  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./vendor/livewire/flux/**/*.php"
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#3b82f6', 
          dark: '#2563eb',    
        }
      }
    },
  },
  plugins: [],
}