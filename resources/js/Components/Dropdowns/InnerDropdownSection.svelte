<script>
    /**
     * @typedef {Object} Props
     * @property {string} [title]
     * @property {import('svelte').Snippet} [children]
     */

    /** @type {Props} */
    let { title = '', children } = $props();
</script>

<div class="py-1" role="none">
    {#if title}
        <div class="px-4 py-1 text-xs text-gray-600 font-medium dark:text-gray-300">
            {title}
        </div>
    {/if}
    {@render children?.()}
</div>
