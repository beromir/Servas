<script context="module">
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import {router} from '@inertiajs/svelte';
    import {dispatchCustomEvent, route} from "@/utils";
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import {debounce} from "lodash";
    import LinkList from "@/Components/LinkList/LinkList.svelte";
    import Button from "@/Components/Buttons/Button.svelte";
    import {linkFilter} from "@/stores.js";
    import {onDestroy, onMount} from "svelte";

    export let links = [];
    export let searchString = '';
    export let filteredTags = [];
    export let showUntaggedOnly = false;

    $title = 'Links';

    $: $linkFilter.isActive && filterLinks();

    const search = debounce(() => {
        filterLinks();
    }, 400);

    const clearSearchInput = () => {
        searchString = '';
        search();
    }

    function filterLinks() {
        if (searchString === '') {
            searchString = null;
        }

        router.get(route(route().current(), {
            tags: $linkFilter.tags.length ? $linkFilter.tags.map(item => item.name).join(',') : null,
            search: searchString,
            untaggedOnly: $linkFilter.showUntaggedOnly ? true : null,
        }), {}, {
            only: ['links', 'searchString'],
            preserveState: true,
        });
    }

    function removeFilteredTag(tag) {
        $linkFilter.tags = $linkFilter.tags.filter(item => item !== tag);
    }

    onMount(() => {
        if (filteredTags || showUntaggedOnly) {
            $linkFilter.tags = filteredTags;
            $linkFilter.showUntaggedOnly = showUntaggedOnly;
        }
    });

    onDestroy(() => {
        linkFilter.reset();
    });
</script>

<Main>
    <div slot="toolbar" class="sm:flex sm:items-center">
        <div class="relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                     fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                          clip-rule="evenodd"/>
                </svg>
            </div>
            <input type="text" bind:value={searchString} on:input={search}
                   class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                   placeholder="Search links..." enterkeyhint="search">
        </div>
        {#if searchString}
            <button on:click={clearSearchInput} type="button"
                    class="ml-2 text-sm text-gray-700">
                Clear
            </button>
        {/if}

        <Button on:clicked={() => dispatchCustomEvent('filterTags')}
                title="Filter by tags" color="white" class="mt-4 sm:mt-0 sm:ml-6 sm:w-auto">
            <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-gray-600">
                <path fill-rule="evenodd"
                      d="M5.5 3A2.5 2.5 0 003 5.5v2.879a2.5 2.5 0 00.732 1.767l6.5 6.5a2.5 2.5 0 003.536 0l2.878-2.878a2.5 2.5 0 000-3.536l-6.5-6.5A2.5 2.5 0 008.38 3H5.5zM6 7a1 1 0 100-2 1 1 0 000 2z"
                      clip-rule="evenodd"/>
            </svg>
        </Button>
        {#if $linkFilter.showUntaggedOnly}
            <button on:click={() => {$linkFilter.showUntaggedOnly = false; $linkFilter.isActive = true}} type="button"
                    class="block mt-2 mx-auto text-sm text-gray-700 sm:mt-0 sm:mr-0 sm:ml-2">
                Show all
            </button>
        {/if}
    </div>

    <!-- Tag filter list -->
    {#if $linkFilter.tags.length}
        <div class="px-4 sm:px-0">
            {#each $linkFilter.tags as tag (tag.id)}
                <button on:click={() => {removeFilteredTag(tag); $linkFilter.isActive = true}} type="button"
                        class="inline-flex items-center mr-2 mt-2 py-0.5 px-2.5 bg-primary-100 text-sm text-primary-800 font-medium rounded-full group">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                         class="mr-1 -ml-1 w-4 h-4 fill-primary-500 rounded-full group-hover:fill-primary-700 group-hover:bg-primary-200/50">
                        <path
                            d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                    </svg>
                    {tag.name}
                </button>
            {/each}
        </div>
    {/if}

    <LinkList {links}/>
</Main>
