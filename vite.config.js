import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/filament/admin/theme.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',   // expone el dev server fuera del contenedor
        port: 5173,
        hmr: {
        host: 'localhost', // el host que abre tu navegador
        port: 5173,
        },
        watch: {
        usePolling: true, // WSL2/Docker: evita que se pierdan cambios
        },
    },
});
