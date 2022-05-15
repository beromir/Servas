<script>
    import {inertia} from "@inertiajs/inertia-svelte";
    import {page} from '@inertiajs/inertia-svelte';

    export let title;
    export let url;
    export let exactMatch = true;
</script>

<a href={url}
   use:inertia
   class={['px-3 py-2 rounded-md text-sm font-medium',
   exactMatch ? ($page.url === url ? 'bg-gray-900 text-white' : 'text-gray-300 hover:text-gray-100') : '',
   !exactMatch ? ($page.url.startsWith(url) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:text-gray-100') : '',
   $$restProps.class].join(' ').trim()}
   aria-current="page">
    {title}
</a>
