<script context="module">
    import {writable} from 'svelte/store';

    export const title = writable(null);
</script>

<script>
    import {page} from '@inertiajs/svelte';

    let appName = $page.props.appName;
</script>

<svelte:head>
    <title>{$title ? `${$title} | ${appName}` : appName}</title>
</svelte:head>

<slot/>
