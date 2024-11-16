<script>
    import {Link, page} from "@inertiajs/svelte";
    import {route} from "@/utils/index.js";
    import {fade} from "svelte/transition";
    import clsx from "clsx";
    import {getOpenedGroups, toggleOpenedGroup} from "@/utils/local-settings.js";
    import {closeSidebar} from "@/utils/sidebar.js";

    export let group = null;
    export let groups = [];

    let openedGroups = getOpenedGroups();

    $: currentGroupId = $page.props.group && $page.props.group.id;
</script>

<div>
    <div class="flex items-center">
        <button on:click={() => openedGroups = toggleOpenedGroup(group.id)} type="button"
                class="transition duration-100 group"
                class:invisible={group.childGroupsCount === 0} class:rotate-90={openedGroups.includes(group.id)}>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                 class="size-6 fill-gray-300 group-hover:fill-gray-200">
                <path fill-rule="evenodd"
                      d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                      clip-rule="evenodd"/>
            </svg>
        </button>

        <Link href={route('groups.show', group.id)} on:click={closeSidebar}
              class={clsx('flex justify-between items-center ml-1 py-1.5 px-3 w-full text-sm text-white font-medium truncate rounded-md hover:bg-white/10 dark:hover:bg-gray-700/60', currentGroupId === group.id && 'bg-white/10 dark:bg-gray-700/60')}>
            {group.title}

            {#if group.linksCount}
            <span class="text-sm text-gray-200 font-normal">
                {group.linksCount}
            </span>
            {/if}
        </Link>
    </div>

    {#if group.childGroupsCount > 0 && openedGroups.includes(group.id)}
        <div class="flex flex-col gap-y-2 mt-1 ml-6" transition:fade|local={{ duration: 100 }}>
            {#each groups.filter((singleGroup) => singleGroup.parentGroupId === group.id) as childGroup}
                <svelte:self group={childGroup} groups={groups}/>
            {/each}
        </div>
    {/if}
</div>
