import('./bootstrap');
import '../css/app.css';

import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createInertiaApp } from '@inertiajs/svelte';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: title => `${title} - ${appName}`,
    resolve: name => resolvePageComponent(`./Pages/${name}.svelte`, import.meta.glob('./Pages/**/*.svelte')),
    setup({ el, App, props }) {
        new App({ target: el, props });
    },
    progress: {
        color: '#4e64b7',
    },
});
