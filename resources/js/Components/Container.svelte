<script>
    /**
     * @typedef {Object} Props
     * @property {string} [classNames]
     * @property {import('svelte').Snippet} [children]
     */

    /** @type {Props} */
    let { classNames = '', children } = $props();
</script>

<div class={[classNames, 'px-4 sm:px-0'].join(' ').trim()}>
    {@render children?.()}
</div>
