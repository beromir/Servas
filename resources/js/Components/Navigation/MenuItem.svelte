<script>
    import {Link, page} from "@inertiajs/svelte";
    import clsx from "clsx";
    import {closeSidebar} from "@/utils/sidebar.js";

    let {
        title,
        url = $bindable(),
        exactMatch = true,
        children,
        ...rest
    } = $props();

    let isCurrentPage = $derived.by(() => {
        if (url === undefined) {
            return false;
        }

        const pathname = new URL(url).pathname;

        return exactMatch ? $page.url === pathname : $page.url.startsWith(pathname);
    });
</script>

<Link href={url} on:click={closeSidebar}
      class={clsx('flex items-center gap-x-3 py-1.5 px-3 text-white font-medium rounded-md hover:bg-white/10 dark:hover:bg-gray-700/60', isCurrentPage ? 'bg-white/10 dark:bg-gray-700/60' : '', rest.class)}>
    <div class={clsx('flex-none size-5', isCurrentPage ? 'text-gray-100' : 'text-gray-300')}>
        {@render children?.()}
    </div>

    {title}
</Link>
