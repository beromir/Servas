<script>
    let { children, ...rest } = $props();
</script>

<div {...rest} class={['space-y-6 sm:space-y-5', rest.class].join(' ').trim()}>
    {@render children?.()}
</div>
