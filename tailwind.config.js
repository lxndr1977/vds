module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './vendor/filament/**/*.blade.php',  // <- Importante para Filament
    './resource/filament/**/*.blade.php',  // <- Importante para Filament
    './app/Livewire/**/*.php',            // <- Importante para Livewire
  ],
  theme: {
    extend: {},
  },
  plugins: [require('daisyui')],
  daisyui: {
    themes: [
      {
        meuTema: {
          "primary": "#ffad22",
          "secondary": "#red",
          "accent": "#10b981",
          "neutral": "red",
          "base-100": "#ffffff",
          "info": "#3b82f6",
          "success": "#22c55e",
          "warning": "#f59e0b",
          "error": "#ef4444",
        },
      },
    ],
  },
}
