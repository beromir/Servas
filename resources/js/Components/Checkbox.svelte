<script>
    let {checked = $bindable(false), ...props} = $props();
</script>

<input type="checkbox" bind:checked {...props}
       class="size-4 rounded-sm border-gray-300 ring-0! ring-offset-0! focus:outline-2 focus:outline-primary-500 checked:bg-primary-600 checked:border-primary-600 dark:bg-gray-900 dark:border-gray-500">
