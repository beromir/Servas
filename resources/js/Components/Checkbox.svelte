<script>
    let {checked = $bindable(false), ...props} = $props();
</script>

<input type="checkbox" bind:checked {...props}
       class="size-4 rounded-sm border-gray-400 ring-0! ring-offset-0! focus-visible:outline-2 focus-visible:outline-primary-500 checked:bg-primary-600 checked:border-primary-600 dark:bg-gray-950 dark:border-gray-500">
