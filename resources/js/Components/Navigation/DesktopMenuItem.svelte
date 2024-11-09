<script>
    import {Link, page} from "@inertiajs/svelte";
    import clsx from "clsx";
    import {closeSidebar} from "@/utils/sidebar.js";

    export let title;
    export let url;
    export let exactMatch = true;

    $: isCurrentPage = checkCurrentPage($page.url);

    function checkCurrentPage() {
        if (url === undefined) {
            return false;
        }

        url = new URL(url);
        const pathname = url.pathname;

        return exactMatch ? $page.url === pathname : $page.url.startsWith(pathname);
    }
</script>

<Link href={url} on:click={closeSidebar}
      class={clsx('flex items-center gap-x-3 py-1.5 px-3 text-white font-medium rounded-md hover:bg-white/10 dark:hover:bg-gray-700/60', isCurrentPage ? 'bg-white/10 dark:bg-gray-700/60' : '', $$restProps.class)}>
    <div class={clsx('flex-none size-5', isCurrentPage ? 'text-gray-100' : 'text-gray-300')}>
        <slot/>
    </div>

    {title}
</Link>
