<script>
    import {route} from "@/utils";
    import {inertia} from "@inertiajs/inertia-svelte";

    export let link = {};
    export let withShadow = true;
    export let showDateOnMobile = true;
</script>

<li class={['flex', withShadow ? 'shadow' : ''].join(' ').trim()}>
    <a href={route('links.show', link.id)} use:inertia
       class="flex flex-col flex-auto min-w-0 px-4 py-4 bg-white sm:block sm:px-6">
        {#if showDateOnMobile}
            <div class="flex justify-end mb-1 text-xs text-gray-700 sm:hidden sm:mt-0">
                {link.createdAt}
            </div>
        {/if}
        <div>
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-900 truncate">
                    {link.title}
                </p>
                <div class="hidden ml-2 flex-shrink-0 sm:flex">
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
    <a href={link.link} target="_blank" rel="noreferrer noopener nofollow"
       title="Open the link"
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
