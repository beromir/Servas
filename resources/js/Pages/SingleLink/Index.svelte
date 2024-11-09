<script context="module">
    import AppLayout, {title, showHeader} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import {onDestroy} from 'svelte';
    import {dispatchCustomEvent, route} from "@/utils";
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import Badge from "@/Components/Badge.svelte";
    import {inertia, Link} from "@inertiajs/svelte";
    import Button from "@/Components/Buttons/Button.svelte";

    export let link = {};

    $title = link.title;
    $showHeader = false;

    let copied = false;

    onDestroy(() => {
        $showHeader = true;
    });

    function copyLink(link) {
        navigator.clipboard.writeText(link);
        copied = true;

        setTimeout(() => copied = false, 500);
    }

    function shareLink() {
        if (navigator.share) {
            navigator.share({
                title: link.title,
                url: link.link,
            })
                .catch((error) => console.log('Error sharing', error));
        }
    }

    function editLink() {
        dispatchCustomEvent('editLink', link)
    }

    function deleteLink() {
        dispatchCustomEvent('deleteLink', link)
    }
</script>

<Main showHeader={false}>
    <div class="max-w-3xl mx-auto px-4 py-5 bg-white shadow ring-contrast sm:p-6 sm:rounded-lg dark:bg-gray-800">
        <div class="md:flex md:flex-row-reverse md:justify-between md:gap-8">
            <div class="flex justify-end md:-mt-1.5 md:h-fit">
                <button on:click={copyLink(link.link)} title="Copy link" type="button"
                        class="w-max inline-flex items-center p-2 border border-gray-400 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-800">

                    {#if copied}
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="size-5 text-green-500 dark:text-green-400">
                            <path fill-rule="evenodd"
                                  d="M18 5.25a2.25 2.25 0 00-2.012-2.238A2.25 2.25 0 0013.75 1h-1.5a2.25 2.25 0 00-2.238 2.012c-.875.092-1.6.686-1.884 1.488H11A2.5 2.5 0 0113.5 7v7h2.25A2.25 2.25 0 0018 11.75v-6.5zM12.25 2.5a.75.75 0 00-.75.75v.25h3v-.25a.75.75 0 00-.75-.75h-1.5z"
                                  clip-rule="evenodd"/>
                            <path fill-rule="evenodd"
                                  d="M3 6a1 1 0 00-1 1v10a1 1 0 001 1h8a1 1 0 001-1V7a1 1 0 00-1-1H3zm6.874 4.166a.75.75 0 10-1.248-.832l-2.493 3.739-.853-.853a.75.75 0 00-1.06 1.06l1.5 1.5a.75.75 0 001.154-.114l3-4.5z"
                                  clip-rule="evenodd"/>
                        </svg>

                    {:else}
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-500 dark:text-gray-300"
                             viewBox="0 0 20 20"
                             fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M13.887 3.182c.396.037.79.08 1.183.128C16.194 3.45 17 4.414 17 5.517V16.75A2.25 2.25 0 0114.75 19h-9.5A2.25 2.25 0 013 16.75V5.517c0-1.103.806-2.068 1.93-2.207.393-.048.787-.09 1.183-.128A3.001 3.001 0 019 1h2c1.373 0 2.531.923 2.887 2.182zM7.5 4A1.5 1.5 0 019 2.5h2A1.5 1.5 0 0112.5 4v.5h-5V4z"
                                  clip-rule="evenodd"/>
                        </svg>
                    {/if}
                </button>

                <a href={link.link} target="_blank" rel="noreferrer noopener nofollow"
                   class="w-max inline-flex items-center ml-3 px-4 py-2 border border-gray-400 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-800">
                    Open link
                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 -mr-1 size-5" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M4.25 5.5a.75.75 0 00-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 00.75-.75v-4a.75.75 0 011.5 0v4A2.25 2.25 0 0112.75 17h-8.5A2.25 2.25 0 012 14.75v-8.5A2.25 2.25 0 014.25 4h5a.75.75 0 010 1.5h-5z"
                              clip-rule="evenodd"/>
                        <path fill-rule="evenodd"
                              d="M6.194 12.753a.75.75 0 001.06.053L16.5 4.44v2.81a.75.75 0 001.5 0v-4.5a.75.75 0 00-.75-.75h-4.5a.75.75 0 000 1.5h2.553l-9.056 8.194a.75.75 0 00-.053 1.06z"
                              clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
            <div class="mt-4 md:mt-0">
                <h2 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{link.title}</h2>
                <p class="mt-1 text-sm text-gray-600 leading-snug break-all dark:text-gray-300">
                    <a href={link.link} target="_blank" rel="noreferrer noopener nofollow">{link.link}</a>
                </p>
            </div>
        </div>

        <!-- Tags -->
        {#if link.tags.length}
            <div class="mt-2 space-y-2 sm:space-y-3">
                {#each link.tags as tag (tag.id)}
                    <Link href={route('links.index', {tags: tag.name})}
                          class="inline-block mr-1.5 first:mt-2 last:mr-0 sm:first:mt-3">
                        <Badge title={tag.name} noHover={true}/>
                    </Link>
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

        <div class="flex flex-col mt-8 text-sm text-gray-800 dark:text-gray-200">
            <div>
                <span class="font-medium">Created at:</span> {link.created_at_with_time}
            </div>
            {#if link.updated_at_with_time !== link.created_at_with_time}
                <div class="mt-1.5">
                    <span class="font-medium">Updated at:</span> {link.updated_at_with_time}
                </div>
            {/if}
        </div>

        {#if navigator.share}
            <button type="button" on:click={shareLink}
                    class="w-max inline-flex items-center mt-6 px-4 py-2  border border-gray-400 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-800">
                Share link

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                     class="ml-2 -mr-1 size-5">
                    <path fill-rule="evenodd"
                          d="M13.75 7h-3V3.66l1.95 2.1a.75.75 0 101.1-1.02l-3.25-3.5a.75.75 0 00-1.1 0L6.2 4.74a.75.75 0 001.1 1.02l1.95-2.1V7h-3A2.25 2.25 0 004 9.25v7.5A2.25 2.25 0 006.25 19h7.5A2.25 2.25 0 0016 16.75v-7.5A2.25 2.25 0 0013.75 7zm-3 0h-1.5v5.25a.75.75 0 001.5 0V7z"
                          clip-rule="evenodd"/>
                </svg>
            </button>
        {/if}

        <div class="flex justify-end mt-6 w-full">
            <div>
                <button on:click={deleteLink} type="button"
                        class="text-red-600 hover:text-red-700 focus:outline-none focus:underline focus:decoration-red-600/70 focus:decoration-2 dark:text-red-300 dark:hover:text-red-400 dark:focus:decoration-red-400/70">
                    Delete
                </button>
                <button on:click={editLink} type="button"
                        class="ml-3 text-primary-600 hover:text-primary-700 focus:outline-none focus:underline focus:decoration-primary-600/70 focus:decoration-2 dark:text-primary-300 dark:hover:text-primary-400 dark:focus:decoration-primary-400/70">
                    Edit link
                </button>
            </div>
        </div>
    </div>
</Main>
