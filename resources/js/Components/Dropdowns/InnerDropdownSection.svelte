<script>
    export let title = '';
</script>

<div class="py-1" role="none">
    {#if title}
        <div class="px-4 py-1 text-xs text-gray-600 font-medium dark:text-gray-300">
            {title}
        </div>
    {/if}
    <slot/>
</div>
