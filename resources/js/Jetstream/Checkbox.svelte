<script>
    export let checked = false;

    $: props = {
        ...$$restProps,
    };
</script>

<input type="checkbox" {...props} bind:checked
       class="{props.class ? props.class + ' ' : ''}rounded border-gray-300 text-primary-500 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
