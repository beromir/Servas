<script>
    export let title = '';
    export let tag = 'div';
</script>

<svelte:element this={tag}
                class="flex flex-col items-center col-span-full gap-y-1 text-sm text-gray-700 font-medium md:text-base dark:text-gray-200">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
         stroke="currentColor" class="size-8 text-gray-500 md:size-10 dark:text-gray-300">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
    </svg>

    {title}
</svelte:element>
