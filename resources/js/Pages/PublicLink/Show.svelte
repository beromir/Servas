<script context="module">
    import GuestLayout, {title as pageTitle} from "@/Layouts/GuestLayout.svelte";

    export const layout = GuestLayout;
</script>

<script>
    import LinkList from "@/Components/LinkList/LinkList.svelte";

    export let title = '';
    export let links = [];
    export let searchString = '';

    $: $pageTitle = title;
</script>

{#if links.length !== 0}
    <LinkList {links} {searchString}/>
{/if}
