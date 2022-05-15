<script>
    import {route} from "@/utils";
    import {inertia} from '@inertiajs/inertia-svelte';

    export let links = [];
</script>

<div class="mt-6 shadow overflow-hidden sm:rounded-md">
    <ul class="divide-y divide-gray-200">
        {#each links as link (link.id)}
            <li class="flex">
                <a href={route('links.show', link.id)} use:inertia class="flex-auto min-w-0 bg-white">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {link.title}
                            </p>
                            <div class="ml-2 flex-shrink-0 flex">
                                <div class="text-xs text-gray-700">
                                    {link.createdAt}
                                </div>
                            </div>
                        </div>
                        <div class="mt-0.5 text-xs text-gray-500 truncate">
                            {link.link}
                        </div>
                    </div>
                </a>
                <a href={link.link} target="_blank" rel="noreferrer noopener nofollow" title="Open the link"
                   class="flex-none flex items-center justify-center w-16 group bg-gray-50 sm:w-20">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6 text-gray-300 group-hover:text-gray-500"
                         fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </li>
        {/each}
    </ul>
</div>
