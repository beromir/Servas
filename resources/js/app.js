import('./bootstrap');
import '../css/app.css';

import {createInertiaApp} from '@inertiajs/svelte';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: title => `${title} - ${appName}`,
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.svelte', {eager: true});
        return pages[`./Pages/${name}.svelte`];
    },
    setup({el, App, props}) {
        new App({target: el, props});
    },
    progress: {
        color: '#4e64b7',
    },
});
