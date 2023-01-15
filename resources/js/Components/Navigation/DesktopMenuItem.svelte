<script>
    import {Link, page} from "@inertiajs/svelte";

    export let title;
    export let url;
    export let exactMatch = true;
</script>

<Link href={url}
   class={['px-3 py-2 rounded-md text-sm font-medium',
   exactMatch ? ($page.url === url ? 'bg-gray-900 text-white' : 'text-gray-300 hover:text-gray-100') : '',
   !exactMatch ? ($page.url.startsWith(url) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:text-gray-100') : '',
   $$restProps.class].join(' ').trim()}
   aria-current="page">
    {title}
</Link>
