<script>
    import {inertia} from "@inertiajs/svelte";

    export let link = '';
    export let title = '';
</script>

<li>
    <div class="flex items-center">
        <svg class="flex-shrink-0 size-5 text-gray-300" xmlns="http://www.w3.org/2000/svg"
             fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z"/>
        </svg>
        {#if link !== ''}
            <a href={link} use:inertia
               class="max-w-[3rem] text-xs font-medium truncate text-gray-500 hover:text-gray-700 md:ml-4 md:max-w-none md:text-sm">
                {title}
            </a>

        {:else}
            <span
                class="max-w-[3rem] text-xs font-medium truncate text-gray-500 hover:text-gray-700 md:ml-4 md:max-w-none md:text-sm">
                {title}
            </span>
        {/if}

    </div>
</li>
