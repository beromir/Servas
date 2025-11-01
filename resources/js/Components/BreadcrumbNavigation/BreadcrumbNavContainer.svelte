<script>
    import {inertia} from '@inertiajs/svelte';
    import BreadcrumbNavItem from "@/Components/BreadcrumbNavigation/BreadcrumbNavItem.svelte";

    /**
     * @typedef {Object} Props
     * @property {string} [navigationNameForSr]
     * @property {string} [homeLink]
     * @property {any} [items]
     * @property {string} [currentTitle]
     * @property {import('svelte').Snippet} [homeIcon]
     */

    /** @type {Props} */
    let {
        navigationNameForSr = '',
        homeLink = '',
        items = [],
        currentTitle = '',
        homeIcon
    } = $props();
</script>

<nav class="flex overflow-x-scroll md:overflow-x-hidden" aria-label="Breadcrumb">
    <ol class="flex items-center select-none md:space-x-4">
        <li>
            <div>
                <a href={homeLink} use:inertia class="text-gray-400 hover:text-gray-500">
                    <div class="flex-shrink-0 size-5">
                        {@render homeIcon?.()}
                    </div>
                    <span class="sr-only">{navigationNameForSr}</span>
                </a>
            </div>
        </li>

        {#each items as item}
            <BreadcrumbNavItem title={item.title} link={item.link}/>
        {/each}

        <BreadcrumbNavItem title={currentTitle}/>
    </ol>
</nav>
