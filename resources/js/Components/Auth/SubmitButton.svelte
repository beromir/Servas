<script>
    import clsx from "clsx";

    let { title = '', ...rest } = $props();
</script>

<button type="submit" {...rest}
        class={clsx(rest.class, 'flex w-full justify-center rounded-md border border-transparent bg-primary-600 py-2 px-4 text-sm font-medium text-white shadow-xs hover:bg-primary-700 focus:outline-hidden focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900')}>
    {title}
</button>
