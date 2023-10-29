<script context="module">
    import AppLayout, {title, showHeader} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import {onDestroy} from 'svelte';
    import {dispatchCustomEvent, route} from "@/utils";
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import BreadcrumbNavContainer from "@/Components/BreadcrumbNavigation/BreadcrumbNavContainer.svelte";
    import NewGroup from "@/Pages/Groups/Partials/NewGroup.svelte";
    import LinkList from "@/Components/LinkList/LinkList.svelte";

    export let group = {};
    export let parentGroups = [];
    export let links = [];

    let newGroupElement;

    $: $title = group.title;
    $showHeader = false;

    onDestroy(() => {
        $showHeader = true;
    });

    function deleteGroup() {
        dispatchCustomEvent('deleteGroup', group)
    }
</script>

<Main>
    <svelte:fragment slot="toolbar">
        <h1 class="text-3xl text-gray-900 font-bold">{group.title}</h1>
    </svelte:fragment>

    <div class="mt-6">

        {#if links.length !== 0}
            <LinkList {links}/>
        {/if}
    </div>
</Main>
