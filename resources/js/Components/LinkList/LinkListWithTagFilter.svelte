<script>
    import {dispatchCustomEvent, route} from "@/utils";
    import {router} from "@inertiajs/svelte";
    import {linkFilter} from "@/stores.js";
    import {beforeUpdate, onDestroy} from "svelte";
    import Button from "@/Components/Buttons/Button.svelte";
    import LinkList from "@/Components/LinkList/LinkList.svelte";

    export let links = [];
    export let searchString = '';
    export let filteredTags = [];
    export let showUntaggedOnly = false;
    export let showTagFilter = true;

    $: $linkFilter.isActive && filterLinks();

    function search(term) {
        searchString = term;

        filterLinks();
    }

    function filterLinks() {
        if (searchString === '') {
            searchString = null;
        }

        router.get(route(route().current(), {
            group: route().params.group,
            tags: $linkFilter.tags.length ? $linkFilter.tags.map(item => item.name).join(',') : null,
            search: searchString,
            untaggedOnly: $linkFilter.showUntaggedOnly ? true : null,
        }), {}, {
            only: ['links', 'searchString', 'filteredTags', 'showUntaggedOnly'],
            preserveState: true,
        });
    }

    function handleFilterButtonClick() {
        dispatchCustomEvent('tags.filter', $linkFilter);

        window.addEventListener('tags.filtered', (e) => {
            $linkFilter.tags = e.detail.tags;
            $linkFilter.showUntaggedOnly = e.detail.showUntaggedOnly;
            $linkFilter.isActive = true;

        }, {once: true});
    }

    function removeFilteredTag(tag) {
        $linkFilter.tags = $linkFilter.tags.filter(item => item !== tag);
    }

    beforeUpdate(() => {
        $linkFilter.tags = filteredTags;
        $linkFilter.showUntaggedOnly = showUntaggedOnly;
    });

    onDestroy(() => {
        linkFilter.reset();
    });
</script>

<LinkList on:searched={(e) => search(e.detail)} {links} {searchString}>
    <!-- Tag filter -->
    <div slot="toolbar" class="w-full sm:flex sm:items-center sm:w-auto">
        {#if showTagFilter}
            <Button on:clicked={handleFilterButtonClick} title="Filter by tags" color="white">
                <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M5.5 3A2.5 2.5 0 003 5.5v2.879a2.5 2.5 0 00.732 1.767l6.5 6.5a2.5 2.5 0 003.536 0l2.878-2.878a2.5 2.5 0 000-3.536l-6.5-6.5A2.5 2.5 0 008.38 3H5.5zM6 7a1 1 0 100-2 1 1 0 000 2z"
                          clip-rule="evenodd"/>
                </svg>
            </Button>
            {#if $linkFilter.showUntaggedOnly}
                <button on:click={() => {$linkFilter.showUntaggedOnly = false; $linkFilter.isActive = true}}
                        type="button"
                        class="block mt-2 mx-auto text-sm text-gray-700 sm:mt-0 sm:mr-0 sm:ml-2 dark:text-gray-200">
                    Show all
                </button>
            {/if}
        {/if}
    </div>

    <!-- Tag filter list -->
    {#if showTagFilter && $linkFilter.tags.length}
        <div class="mt-4">
            {#each $linkFilter.tags as tag (tag.id)}
                <button on:click={() => {removeFilteredTag(tag); $linkFilter.isActive = true}} type="button"
                        class="inline-flex items-center mr-2 mt-2 py-0.5 px-2.5 bg-primary-100 text-sm text-primary-800 font-medium rounded-full group dark:bg-primary-700/80 dark:text-primary-50">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                         class="mr-1 -ml-1 size-4 fill-primary-500 rounded-full group-hover:fill-primary-700 group-hover:bg-primary-200/50 dark:fill-primary-300 dark:group-hover:fill-primary-200 dark:group-hover:bg-primary-500/50">
                        <path
                            d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                    </svg>
                    {tag.name}
                </button>
            {/each}
        </div>
    {/if}
</LinkList>
