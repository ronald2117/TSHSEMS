import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        cors: true,
        hmr: {
            host: 'ubiquitous-bassoon-5pj95qgq97ghpwq-5173.app.github.dev',
            protocol: 'wss',
            clientPort: 443,
        },
        origin: 'https://ubiquitous-bassoon-5pj95qgq97ghpwq-5173.app.github.dev',
    },
});
