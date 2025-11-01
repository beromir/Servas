<script>
    /**
     * @typedef {Object} Props
     * @property {import('svelte').Snippet} [children]
     */

    /** @type {Props & { [key: string]: any }} */
    let { children, ...rest } = $props();
</script>

<div {...rest} class={['space-y-6 sm:space-y-5', rest.class].join(' ').trim()}>
    {@render children?.()}
</div>
