import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import {svelte} from '@sveltejs/vite-plugin-svelte';

export default defineConfig({
    optimizeDeps: {
        include: ['@inertiajs/inertia'],
    },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
        }),
        svelte({
            experimental: {
                prebundleSvelteLibraries: true,
            },
        }),
    ],
});
