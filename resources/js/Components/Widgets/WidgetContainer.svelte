<script>
    import Container from "@/Components/Container.svelte";

    export let hasColumns = false;
</script>

<Container classNames={hasColumns ? 'grid grid-cols-1 gap-8 md:grid-cols-2' : ''}>
    <slot/>
</Container>
