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
  important: true, 
  content: ['./**/*.(html|php)'], 
  theme: { extend: theme  } 
}

