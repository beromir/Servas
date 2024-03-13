<script context="module">
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import {Link, router} from "@inertiajs/svelte";
    import {route} from "@/utils/index.js";

    export let publicLinks = [];

    $title = 'Shared Groups';

    function deletePublicLink(id) {
        router.delete(route('publicLinks.destroy', id), {
            only: ['publicLinks'],
        });
    }
</script>

<Main title="Shared Groups">
    <div class="flow-root">
        <div class="-mx-4 -my-2 pt-10 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow sm:rounded-md">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                Group
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                Public link
                            </th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Delete</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">

                        {#each publicLinks as publicLink (publicLink.id)}
                            <tr>
                                <td class="py-4 pl-4 pr-3 sm:pl-6">
                                    <Link
                                        class="text-sm text-gray-900 font-medium  whitespace-nowrap hover:text-gray-700"
                                        href={route('groups.show', publicLink.group.id)}>{publicLink.group.title}</Link>
                                </td>
                                <td class="px-3 py-4">
                                    <button type="button" class="flex items-center gap-x-2 w-fit group">
                                        <div class="text-sm text-gray-500 whitespace-nowrap">{publicLink.shareId}</div>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                                             class="size-4 fill-gray-400 group-hover:fill-gray-700">
                                            <path fill-rule="evenodd"
                                                  d="M10.986 3H12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h1.014A2.25 2.25 0 0 1 7.25 1h1.5a2.25 2.25 0 0 1 2.236 2ZM9.5 4v-.75a.75.75 0 0 0-.75-.75h-1.5a.75.75 0 0 0-.75.75V4h3Z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <button on:click={() => deletePublicLink(publicLink.id)} type="button"
                                            class="text-red-600 hover:text-red-700">
                                        Delete<span class="sr-only">, {publicLink.group.title}</span>
                                    </button>
                                </td>
                            </tr>
                        {/each}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</Main>
