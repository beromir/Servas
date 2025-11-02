<script>
    import Container from "@/Components/Container.svelte";

    let { hasColumns = false, children } = $props();
</script>

<Container classNames={hasColumns ? 'grid grid-cols-1 gap-8 md:grid-cols-2' : ''}>
    {@render children?.()}
</Container>
