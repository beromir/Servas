<script context="module">
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import {Link} from "@inertiajs/svelte";
    import {dispatchCustomEvent} from "@/utils";
    import EmptyState from "@/Components/EmptyStates/EmptyState.svelte";

    export let publicLinks = [];

    let copied = null;

    $title = 'Shared Groups';

    function copyLink(link, id) {
        navigator.clipboard.writeText(link);
        copied = id;

        setTimeout(() => copied = null, 500);
    }

    function showPublicLinkDeleteModal(publicLink) {
        dispatchCustomEvent('deletePublicLink', {id: publicLink.id, title: publicLink.group.title});
    }
</script>

<Main title="Shared Groups">
    <div class="max-w-[100dvw] overflow-x-auto lg:pt-10">

        {#if publicLinks.length}
            <div class="inline-block min-w-full">
                <div class="overflow-hidden shadow sm:rounded-md dark:border dark:border-white/15">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-950">
                        <tr>
                            <th scope="col"
                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 dark:text-white">
                                Group
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                Public link
                            </th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Delete</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white dark:bg-gray-800 dark:divide-gray-700">

                        {#each publicLinks as publicLink (publicLink.id)}
                            <tr>
                                <td class="py-4 pl-4 pr-3 sm:pl-6">
                                    <Link
                                        class="text-sm text-gray-900 font-medium whitespace-nowrap hover:text-primary-700 dark:text-white dark:hover:text-gray-300"
                                        href={route('groups.show', publicLink.group.id)}>{publicLink.group.title}</Link>
                                </td>
                                <td class="px-3 py-4">
                                    <button on:click={() => copyLink(publicLink.link, publicLink.id)} type="button"
                                            class="flex items-center gap-x-2 w-fit group">
                                        <span
                                            class="text-sm text-gray-600 whitespace-nowrap dark:text-gray-200">{publicLink.shareId}</span>

                                        {#if copied === publicLink.id}
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                                                 fill="currentColor"
                                                 class="size-4 fill-green-500 dark:fill-green-400">
                                                <path fill-rule="evenodd"
                                                      d="M11.986 3H12a2 2 0 0 1 2 2v6a2 2 0 0 1-1.5 1.937V7A2.5 2.5 0 0 0 10 4.5H4.063A2 2 0 0 1 6 3h.014A2.25 2.25 0 0 1 8.25 1h1.5a2.25 2.25 0 0 1 2.236 2ZM10.5 4v-.75a.75.75 0 0 0-.75-.75h-1.5a.75.75 0 0 0-.75.75V4h3Z"
                                                      clip-rule="evenodd"/>
                                                <path fill-rule="evenodd"
                                                      d="M2 7a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7Zm6.585 1.08a.75.75 0 0 1 .336 1.005l-1.75 3.5a.75.75 0 0 1-1.16.234l-1.75-1.5a.75.75 0 0 1 .977-1.139l1.02.875 1.321-2.64a.75.75 0 0 1 1.006-.336Z"
                                                      clip-rule="evenodd"/>
                                            </svg>

                                        {:else}
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                                                 class="size-4 fill-gray-500 group-hover:fill-primary-600 dark:fill-gray-300 dark:group-hover:fill-white">
                                                <path fill-rule="evenodd"
                                                      d="M10.986 3H12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h1.014A2.25 2.25 0 0 1 7.25 1h1.5a2.25 2.25 0 0 1 2.236 2ZM9.5 4v-.75a.75.75 0 0 0-.75-.75h-1.5a.75.75 0 0 0-.75.75V4h3Z"
                                                      clip-rule="evenodd"/>
                                            </svg>
                                        {/if}
                                    </button>
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm sm:pr-6">
                                    <button on:click={() => showPublicLinkDeleteModal(publicLink)} type="button"
                                            class="text-red-600 hover:text-red-700 dark:text-red-300 dark:hover:text-red-400">
                                        Delete<span class="sr-only">, {publicLink.group.title}</span>
                                    </button>
                                </td>
                            </tr>
                        {/each}
                        </tbody>
                    </table>
                </div>
            </div>

        {:else}
            <EmptyState title="No shared groups found"/>
        {/if}
    </div>
</Main>
