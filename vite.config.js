import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    server: {
        proxy: {
            '/api': "192.168.1.9:8000"
        },
        host: "192.168.1.9",
        port: 4000,
        strictPort: true,
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/login.css', 'resources/js/login.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
