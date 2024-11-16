<script>
    export let id;
    export let label;

    $: props = {
        ...$$restProps,
        class: `block font-medium text-sm text-gray-700 dark:text-gray-200 ${$$restProps.class || ''}`,
    }
</script>

{#if label}
    <label {...props} for={id}>{label}</label>
{/if}
