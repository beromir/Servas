<script>
    import {Link, page} from "@inertiajs/svelte";
    import clsx from "clsx";

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

<Link href={url}
      class={clsx('block py-1.5 px-3 text-white font-medium rounded-md hover:bg-white/10', isCurrentPage ? 'bg-white/10' : '', $$restProps.class)}>
    {title}
</Link>
