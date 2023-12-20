<script>
    import {inertia} from '@inertiajs/svelte';
    import BreadcrumbNavItem from "@/Components/BreadcrumbNavigation/BreadcrumbNavItem.svelte";

    export let navigationNameForSr = '';
    export let homeLink = '';
    export let items = [];
    export let currentTitle = '';
</script>

<nav class="flex overflow-x-scroll md:overflow-x-hidden" aria-label="Breadcrumb">
    <ol class="flex items-center select-none md:space-x-4">
        <li>
            <div>
                <a href={homeLink} use:inertia class="text-gray-400 hover:text-gray-500">
                    <div class="flex-shrink-0 size-5">
                        <slot name="homeIcon"/>
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
