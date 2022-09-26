/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["**/*.twig", "../../../modules/**/*.twig"],
  theme: {
    extend: {
      colors: {
        'regal-blue': '#5964E0',
      },
    },
  },
  plugins: [],
}
