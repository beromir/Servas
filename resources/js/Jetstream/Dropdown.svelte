<script>
    /**
     * @typedef {Object} Props
     * @property {string} [align]
     * @property {string} [width]
     * @property {any} [contentClasses]
     * @property {import('svelte').Snippet} [trigger]
     * @property {import('svelte').Snippet} [content]
     */

    /** @type {Props} */
    let {
        align = 'right',
        width = '48',
        contentClasses = ['py-1', 'bg-white'],
        trigger,
        content
    } = $props();
    let open = $state(false);

    function widthClass() {
        return {
            '48': 'w-48',
        }[width.toString()]
    }

    function alignmentClasses() {
        if (align === 'left') {
            return 'origin-top-left left-0'
        } else if (align === 'right') {
            return 'origin-top-right right-0'
        } else {
            return 'origin-top'
        }
    }
</script>

<div class="relative">
    <div onclick={() => open = !open}>
        {@render trigger?.()}
    </div>

    <!-- Full Screen Dropdown Overlay -->
    {#if open}
        <div class="fixed inset-0 z-40" onclick={() => open = false}>
        </div>
    {/if}

    {#if open}
        <div class={[widthClass(), alignmentClasses(), 'absolute z-50 mt-2 rounded-md shadow-lg'].join(' ')}
             onclick={() => open = false}>
            <div class="rounded-md ring-1 ring-black ring-opacity-5">
                {@render content?.()}
            </div>
        </div>
    {/if}
</div>
