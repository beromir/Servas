<script context="module">
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import {inertia} from '@inertiajs/inertia-svelte';
    import Pagination from "@/Components/Pagination.svelte";
    import {Inertia} from "@inertiajs/inertia";
    import {dispatchCustomEvent, route} from "@/utils";
    import Badge from "@/Components/Badge.svelte";
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import {debounce} from "lodash";

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

        Inertia.get(route(route().current(), {
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

    <!-- Link list -->
    <ul class="grid grid-cols-1 gap-3 mt-6 sm:grid-cols-2">
        {#each links.data as link (link.id)}
            <li class="flex shadow sm:rounded-lg">
                <a href={route('links.show', link.id)} use:inertia
                   class="flex-auto min-w-0 p-3 bg-white sm:px-6 sm:py-4 sm:rounded-l-lg">
                    <div class="flex justify-between">
                        <div
                            class="text-sm font-medium text-gray-900 truncate">{link.title}</div>
                        <div class="text-xs text-gray-700">
                            {link.created_at}
                        </div>
                    </div>
                    <div class="mt-0.5 text-xs text-gray-500 truncate">
                        {link.link}
                    </div>

                    <!-- Tags -->
                    {#if link.tags.length}
                        <div class="space-y-4">
                            {#each link.tags as tag (tag.id)}
                                <Badge title={tag.name} large={false} noHover={true}
                                       class="mr-1.5 first:mt-4 last:mr-0"/>
                            {/each}
                        </div>
                    {/if}

                    <!-- Groups -->
                    {#if link.groups.length}
                        <div class="space-y-4">
                            {#each link.groups as group}
                                <Badge title={group.title} large={false} noHover={true} rounded={true} color="gray"
                                       class="mr-1.5 first:mt-4 last:mr-0"/>
                            {/each}
                        </div>
                    {/if}
                </a>

                <!-- Open link in new tab -->
                <a href={link.link} target="_blank" rel="noreferrer noopener nofollow" title="Open the link"
                   class="flex-none flex items-center justify-center w-16 group bg-gray-50 hover:cursor-pointer sm:w-20 sm:rounded-r-lg">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6 text-gray-300 group-hover:text-gray-500"
                         fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </li>

        {:else}
            <div class="col-span-2 container mx-auto">
                <button on:click="{() => dispatchCustomEvent('prepareCreateNewLink')}" type="button"
                        class="relative block w-full border-2 border-gray-300 border-dashed rounded-lg p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 focus:ring-offset-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    <span class="mt-2 block text-sm font-medium text-gray-900">
                            Add a new link
                        </span>
                </button>
            </div>
        {/each}
    </ul>

    <Pagination links={links.links} class="mt-6"/>
</Main>
