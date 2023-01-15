<script>
    import {Link, page} from '@inertiajs/svelte';
    import {createEventDispatcher} from "svelte";

    const dispatch = createEventDispatcher();

    export let title;
    export let url;
    export let exactMatch = true;
</script>

<Link href={url}
   on:click={() => dispatch('clicked')}
   class={['block px-3 py-2 rounded-md text-base font-medium',
   exactMatch ? ($page.url === url ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white') : '',
   !exactMatch ? ($page.url.startsWith(url) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white') : '',
   $$restProps.class].join(' ').trim()}
   aria-current="page">
    {title}
</Link>
