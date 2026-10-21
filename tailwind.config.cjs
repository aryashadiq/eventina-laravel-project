module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        // Darker shade of the theme primary that keeps 4.5:1 contrast for small text
        'primary-ink': '#0055C4',
      },
      fontFamily: {
        // Gig-poster headline voice; pair with a condensed font-stretch
        poster: ['Archivo', 'Arial Narrow', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('daisyui'),
  ],
  daisyui: {
    themes: [
      "winter", "halloween", "cmyk"
    ],
    darkTheme: "class",
  }
}
