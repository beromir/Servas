<script context="module">
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import LinkList from "@/Components/LinkList/LinkList.svelte";
    import {router} from "@inertiajs/svelte";
    import {route} from "@/utils/index.js";
    import {linkFilter} from "@/stores.js";

    export let links = [];
    export let searchString = '';

    let untagged = false;
    let ungrouped = false;

    $title = 'Inbox';

    function filterLinks() {
        router.post(route(route().current(), {}), {
            untagged: untagged,
            ungrouped: ungrouped,
        }, {
            only: ['links'],
            preserveState: true,
        });
    }
</script>

<Main title="Inbox">
    <button type="button" on:click={() => {untagged = !untagged; filterLinks()}}>Show untagged</button>
    <button type="button" on:click={() => {untagged = !untagged; filterLinks()}}>Show ungrouped</button>

    <LinkList {links} {searchString} showTagFilter={false}/>
</Main>
