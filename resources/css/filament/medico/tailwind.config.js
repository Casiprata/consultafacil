import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Medico/**/*.php',
        './resources/views/filament/medico/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
