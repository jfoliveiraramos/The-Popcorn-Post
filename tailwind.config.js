/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    content: ["./src/**/*.{html,js}"],
    fontFamily: {
      'title': ['Newsreader', 'sans-serif'],
      'main': ['Roboto', 'sans-serif'],
    },
    colors: {
      transparent: 'transparent',
      current: 'currentColor',
      'background': '#f9e5cd',
      'clear': '#fcf1e3',
      'beige': '#eac392',
      'brown': '#a67437',
      'gold': '#daa520',
      'red': '#d27575',
      'light-red': '#f9e5e5',
      'blue': '#529b9c',
      'green': '#9cba8f',
      'white': '#FFFFFF', 
      'black': '#000000',
      'gray': '#808080',
      'light-gray': '#f3f4f6'
    },
    extend: {
      fontSize: {
        'xxxs': '.55rem',
        'xxs': '.65rem',
      },
      boxShadow: {
        'std': '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);'
      }
    }
  },
  plugins: [],
}

