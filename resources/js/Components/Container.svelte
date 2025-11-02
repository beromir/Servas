<script>
    let { classNames = '', children } = $props();
</script>

<div class={[classNames, 'px-4 sm:px-0'].join(' ').trim()}>
    {@render children?.()}
</div>
