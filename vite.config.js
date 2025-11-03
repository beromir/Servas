import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import {svelte} from '@sveltejs/vite-plugin-svelte';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        svelte({
            /* plugin options */
        }),
        tailwindcss(),
    ],
});
