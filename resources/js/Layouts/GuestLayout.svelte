<script context="module">
    import {writable} from 'svelte/store';

    export const title = writable(null);
</script>

<script>
    import {page} from '@inertiajs/svelte';
    import {initTheme} from "@/utils/theme.js";
    import {onMount} from "svelte";

    let appName = $page.props.appName;

    onMount(() => {
        initTheme();
    });
</script>

<svelte:head>
    <title>{$title ? `${$title} | ${appName}` : appName}</title>
</svelte:head>

<slot/>
