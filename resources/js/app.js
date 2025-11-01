import {mount} from "svelte";
import {createInertiaApp} from '@inertiajs/svelte';

import('./bootstrap');
import '../css/app.css';


const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: title => `${title} - ${appName}`,
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.svelte', {eager: true});
        return pages[`./Pages/${name}.svelte`];
    },
    setup({el, App, props}) {
        mount(App, {target: el, props})
    },
    defaults: {
        visitOptions: (href, options) => {
            return {viewTransition: true}
        },
    },
    progress: {
        color: '#4e64b7',
    },
});
