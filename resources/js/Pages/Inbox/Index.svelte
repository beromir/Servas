<script module>
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import LinkList from "@/Components/LinkList/LinkList.svelte";
    import {router} from "@inertiajs/svelte";
    import {route} from "@/utils/index.js";
    import Toggle from "@/Components/Toggles/Toggle.svelte";

    let {
        links = [],
        searchString = $bindable(''),
        untagged = $bindable(true),
        ungrouped = $bindable(true)
    } = $props();

    $title = 'Inbox';

    function toggleUntagged(state) {
        untagged = state;

        if (!state && !ungrouped) {
            ungrouped = true;
        }

        filterLinks()
    }

    function toggleUngrouped(state) {
        ungrouped = state;

        if (!state && !untagged) {
            untagged = true;
        }

        filterLinks()
    }

    function filterLinks() {
        router.get(route(route().current(), {
            search: searchString ? searchString : null,
            untagged: untagged ? null : untagged,
            ungrouped: ungrouped ? null : ungrouped,
        }), {}, {
            only: ['links', 'searchString', 'untagged', 'ungrouped'],
            reset: ['links'],
            preserveState: true,
        });
    }
</script>

<Main title="Inbox">
    <LinkList bind:searchString {links} searched={filterLinks}>
        {#snippet toolbar()}
            <div class="flex gap-x-5">
                <Toggle toggledCallback={(value) => toggleUntagged(value)} toggled={untagged} title="Untagged"/>
                <Toggle toggledCallback={(value) => toggleUngrouped(value)} toggled={ungrouped} title="Ungrouped"/>
            </div>
        {/snippet}
    </LinkList>
</Main>
