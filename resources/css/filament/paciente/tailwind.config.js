import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Paciente/**/*.php',
        './resources/views/filament/paciente/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
