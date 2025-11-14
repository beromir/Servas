<script>
    import clsx from "clsx";

    let {
        type = 'text',
        value = $bindable(null),
        ...props
    } = $props();
</script>

<input {type} bind:value {...props}
       class={clsx('block w-full rounded-md border-gray-400 shadow-xs focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white', props.class)}>
