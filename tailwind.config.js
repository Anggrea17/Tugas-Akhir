/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      // kalau mau, animasi bisa didefinisikan di sini juga
      keyframes: {
        'gradient-dreamy': {
          '0%': { backgroundPosition: '0% 50%' },
          '50%': { backgroundPosition: '100% 50%' },
          '100%': { backgroundPosition: '0% 50%' },
        },
      },
      animation: {
        'gradient-dreamy': 'gradient-dreamy 8s ease infinite',
      },
    },
  },
  safelist: [
    'gradient-bg',
    'animate-gradient-dreamy',
  ],
  plugins: [],
}
