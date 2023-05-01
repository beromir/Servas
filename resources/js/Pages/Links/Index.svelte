<script context="module">
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import {router} from '@inertiajs/svelte';
    import {route} from "@/utils";
    import Badge from "@/Components/Badge.svelte";
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import {debounce} from "lodash";
    import LinkList from "@/Components/LinkList/LinkList.svelte";

    export let links = [];
    export let tags = [];

    let filteredTags = [];
    let searchString = '';

    $title = 'Links';

    const search = debounce(() => {
        filterLinks();
    }, 400);

    const clearSearchInput = () => {
        searchString = '';
        search();
    }

    function addTagsToFilteredTags(tagId) {
        let index = filteredTags.indexOf(tagId);
        if (index !== -1) {
            filteredTags.splice(index, 1);
            filteredTags = filteredTags;
        } else {
            filteredTags = [...filteredTags, tagId];
        }

        filterLinks();
    }

    function filterLinks() {
        if (searchString === '') {
            searchString = null;
        }

        router.get(route(route().current(), {
            tags: filteredTags,
            search: searchString,
        }), {}, {
            only: ['links'],
            preserveState: true,
        });
    }
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
    </div>

    <!-- Tag filter list -->
    {#if tags.length !== 0}
        <div class="px-4 text-gray-800 text-sm font-medium sm:px-0">Filter by tags</div>
        <div class="px-4 space-y-2 -mt-1 sm:px-0 sm:space-y-3">
            {#each tags as tag (tag.id)}
                <Badge on:clicked={() => addTagsToFilteredTags(tag.name)} title={tag.name}
                       color={filteredTags.includes(tag.name) ? '' : 'gray'}
                       class="bg-gray-50 first:mt-2 last:mr-0 sm:mr-1.5 sm:first:mt-3"/>
            {/each}

            {#if filteredTags.length > 0}
                <button on:click={() => {filteredTags = []; filterLinks()}} type="button"
                        class="text-gray-700 text-sm">
                    Clear all
                </button>
            {/if}
        </div>
    {/if}

    <LinkList {links}/>
</Main>
