<script module>
    import GuestLayout, {title as pageTitle} from "@/Layouts/GuestLayout.svelte";

    export const layout = GuestLayout;
</script>

<script>
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import {debounce} from "lodash";
    import {InfiniteScroll, router} from "@inertiajs/svelte";
    import {route} from "@/utils/index.js";
    import EmptyState from "@/Components/EmptyStates/EmptyState.svelte";
    import Input from "@/Components/Input.svelte";

    let {
        title = '',
        links = [],
        shareId = '',
        searchString = $bindable(''),
        toolbar
    } = $props();

    $effect(() => {
        $pageTitle = title;
    });

    const search = debounce(() => {
        router.get(route(route().current(), {
            shareId: shareId,
            search: searchString ? searchString : null,
        }), {}, {
            only: ['links', 'searchString'],
            reset: ['links'],
            preserveState: true,
        });
    }, 400);

    const clearSearchInput = () => {
        searchString = '';
        search();
    }
</script>

<div class="hidden sm:block sm:pt-16">
</div>

<Main title={title} showNewLinkButton={false}>
    <div class="px-4 sm:px-0">
        <!-- Search input -->
        <div class="flex flex-col gap-y-4 sm:flex-row sm:gap-x-6">
            <div class="w-full sm:flex sm:items-center sm:w-auto">
                <Input bind:value={searchString} oninput={search} placeholder="Search links..." enterkeyhint="search"/>

                {#if searchString}
                    <button onclick={clearSearchInput} type="button"
                            class="ml-2 text-sm text-gray-700 dark:text-gray-200">
                        Clear
                    </button>
                {/if}
            </div>

            {@render toolbar?.()}
        </div>
    </div>

    <!-- Link list -->
    <InfiniteScroll as="ul" data="links"
                    class="grid grid-cols-1 mt-6 divide-y divide-gray-200 sm:grid-cols-2 sm:gap-3 sm:mt-8 sm:divide-none dark:divide-gray-800">
        {#each links.data as link (link.id)}
            <li class="bg-white shadow-sm ring-contrast group sm:overflow-hidden sm:rounded-lg dark:bg-gray-800">
                <a href={link.link} target="_blank" rel="noreferrer noopener nofollow" class="flex">
                    <div class="flex-auto min-w-0 p-3 sm:px-6 sm:py-4">
                        <div class="flex justify-between">
                            <div title={link.title}
                                 class="text-sm font-medium text-gray-900 truncate dark:text-white">{link.title}</div>
                        </div>
                        <div class="mt-0.5 text-xs text-gray-500 truncate dark:text-gray-400">
                            {new URL(link.link).host}
                        </div>
                    </div>

                    <div class="py-2 pr-2">
                        <!-- Open link icon -->
                        <div class="flex-none flex items-center justify-center" title="Open the link">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="size-6 text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </div>
                    </div>
                </a>
            </li>

        {:else}
            <EmptyState tag="li" title="No links found"/>
        {/each}
    </InfiniteScroll>
</Main>
