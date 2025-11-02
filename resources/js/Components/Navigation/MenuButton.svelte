<script>
    import clsx from "clsx";
    import {closeSidebar} from "@/utils/sidebar.js";

    let { title, children, clicked, ...rest } = $props();
</script>

<button onclick={() => {clicked(); closeSidebar()}} type="button"
        class={clsx('flex items-center gap-x-3 py-1.5 px-3 w-full text-white font-medium rounded-md hover:bg-white/10 dark:hover:bg-gray-700/60', rest.class)}>
    <span class="flex-none size-5 text-gray-300">
        {@render children?.()}
    </span>

    {title}
</button>
