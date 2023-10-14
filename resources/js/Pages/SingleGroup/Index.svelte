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
    <NewGroup slot="toolbar" bind:this={newGroupElement}/>

    <div class="px-4 sm:px-0">
        <BreadcrumbNavContainer items={parentGroups} navigationNameForSr="Groups" currentTitle={group.title}
                                homeLink={route('groups.index')}>
            <svg slot="homeIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                 fill="currentColor">
                <path
                    d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
            </svg>
        </BreadcrumbNavContainer>
    </div>

    <div class="mt-6">
        <div class="px-4 sm:px-0">
            <h2 class="text-lg leading-6 font-medium text-gray-900 sm:text-xl">{group.title}</h2>
            <div class="mt-1">
                <button on:click={() => newGroupElement.initEditMode(group)} type="button"
                        class="inline-flex text-primary-600 hover:text-primary-700 text-sm focus:outline-none focus:underline focus:decoration-primary-600/70 focus:decoration-2">
                    Edit group
                </button>
                <button on:click={deleteGroup} type="button"
                        class="ml-2.5 inline-flex text-red-600 hover:text-red-700 text-sm focus:outline-none focus:underline focus:decoration-red-600/70 focus:decoration-2">
                    Delete
                </button>
            </div>
        </div>

        {#if links.length !== 0}
            <LinkList {links}/>
        {/if}
    </div>
</Main>
