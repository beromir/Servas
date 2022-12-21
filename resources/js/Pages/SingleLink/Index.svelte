<script context="module">
    import AppLayout, {title, showHeader} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import {onDestroy} from 'svelte';
    import {dispatchCustomEvent, route} from "@/utils";
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import Badge from "@/Components/Badge.svelte";
    import {Inertia} from "@inertiajs/inertia";
    import {inertia} from "@inertiajs/inertia-svelte";

    export let link = {};

    $title = link.title;
    $showHeader = false;

    onDestroy(() => {
        $showHeader = true;
    });

    function toggleLinkOnLaterList() {
        Inertia.post(route('later.toggle-link'), {
            linkId: link.id,
        })
    }

    function editLink() {
        dispatchCustomEvent('editLink', link)
    }

    function deleteLink() {
        dispatchCustomEvent('deleteLink', link)
    }
</script>

<Main>
    <div class="max-w-3xl mx-auto px-4 py-5 bg-white shadow sm:p-6 sm:rounded-lg">
        <div class="md:flex md:flex-row-reverse md:justify-between md:gap-8">
            <div class="flex justify-end md:-mt-1.5 md:h-fit">
                <button on:click={navigator.clipboard.writeText(link.link)} type="button"
                        class="w-max inline-flex items-center p-2  border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M13.887 3.182c.396.037.79.08 1.183.128C16.194 3.45 17 4.414 17 5.517V16.75A2.25 2.25 0 0114.75 19h-9.5A2.25 2.25 0 013 16.75V5.517c0-1.103.806-2.068 1.93-2.207.393-.048.787-.09 1.183-.128A3.001 3.001 0 019 1h2c1.373 0 2.531.923 2.887 2.182zM7.5 4A1.5 1.5 0 019 2.5h2A1.5 1.5 0 0112.5 4v.5h-5V4z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>

                <a href={link.link} target="_blank" rel="noreferrer noopener nofollow"
                   class="w-max inline-flex items-center ml-3 px-4 py-2  border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Open link
                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 -mr-1 h-5 w-5" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path
                            d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                        <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
                    </svg>
                </a>
            </div>
            <div class="mt-4 md:mt-0">
                <h2 class="text-lg leading-6 font-medium text-gray-900">{link.title}</h2>
                <p class="mt-1 text-sm text-gray-600 leading-snug break-all">
                    <a href={link.link} target="_blank" rel="noreferrer noopener nofollow">{link.link}</a>
                </p>
            </div>
        </div>

        <!-- Tags -->
        {#if link.tags.length}
            <div class="mt-2 space-y-2 sm:space-y-3">
                {#each link.tags as tag (tag.id)}
                    <Badge title={tag.name} noHover={true}
                           class="mr-1.5 first:mt-2 last:mr-0 sm:first:mt-3"/>
                {/each}
            </div>
        {/if}

        <!-- Groups -->
        {#if link.linkGroups.length}
            <div class="mt-2 space-y-2 sm:space-y-3">
                {#each link.linkGroups as group}
                    <a href={route('groups.show', group.id)} use:inertia>
                        <Badge title={group.title} noHover={true} color="gray" rounded={true}
                               class="mr-1.5 first:mt-2 last:mr-0 sm:first:mt-3"/>
                    </a>
                {/each}
            </div>
        {/if}

        <div class="flex flex-col mt-8 text-sm text-gray-800">
            <div>
                <span class="font-medium">Created at:</span> {link.created_at_with_time}
            </div>
            <div class="mt-1.5">
                <span class="font-medium">Updated at:</span> {link.updated_at_with_time}
            </div>
        </div>

        <div class="flex justify-between mt-6 w-full">
            <button type="button" on:click={toggleLinkOnLaterList}
                    class="text-gray-600 hover:text-gray-700 focus:outline-none focus:underline focus:decoration-gray-600/70 focus:decoration-2">
                {link.isInLaterList ? 'Remove link from later list' : 'Add link to later list'}
            </button>

            <div>
                <button on:click={deleteLink} type="button"
                        class="text-red-600 hover:text-red-700 focus:outline-none focus:underline focus:decoration-red-600/70 focus:decoration-2">
                    Delete
                </button>
                <button on:click={editLink} type="button"
                        class="ml-3 text-primary-600 hover:text-primary-700 focus:outline-none focus:underline focus:decoration-primary-600/70 focus:decoration-2">
                    Edit link
                </button>
            </div>
        </div>
    </div>
</Main>
