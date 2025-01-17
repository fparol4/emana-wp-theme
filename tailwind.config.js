const theme = { 
  colors: {
    'primary-900': '#ffff5a', 
    'primary-600': '#ffffaa', 
    'primary-300': '#ebe6dc', 
  }, 
  fontFamily: {
    sans: ['Archivo', 'system-ui']
  }
}

/** @type {import('tailwindcss').Config} */
module.exports = {
  purge: ['./public/**/*.html'], 
  theme: { extend: theme  } 
}

