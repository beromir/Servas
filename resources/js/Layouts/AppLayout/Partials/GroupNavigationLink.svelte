<script>
    import {Link, page} from "@inertiajs/svelte";
    import {route} from "@/utils/index.js";
    import {fade} from "svelte/transition";
    import clsx from "clsx";

    export let group = null;
    export let groups = [];

    let showSubNav = false;

    $: currentGroupId = $page.props.group && $page.props.group.id;
</script>

<div>
    <div class="flex items-center">
        <button on:click={() => showSubNav = !showSubNav} type="button" class="transition group"
                class:invisible={group.childGroupsCount === 0} class:rotate-90={showSubNav}>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                 class="w-6 h-6 fill-gray-300 group-hover:fill-gray-200">
                <path fill-rule="evenodd"
                      d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                      clip-rule="evenodd"/>
            </svg>
        </button>

        <Link href={route('groups.show', group.id)}
              class={clsx('flex justify-between items-center ml-1 py-1.5 px-3 w-full text-sm text-white font-medium rounded-md hover:bg-white/10', currentGroupId === group.id && 'bg-white/10')}>
            {group.title}

            {#if group.linksCount}
            <span class="text-sm text-gray-200 font-normal">
                {group.linksCount}
            </span>
            {/if}
        </Link>
    </div>

    {#if group.childGroupsCount > 0 && showSubNav}
        <div class="flex flex-col gap-y-2 mt-1 ml-6" transition:fade={{ duration: 150 }}>
            {#each groups.filter((singleGroup) => singleGroup.parentGroupId === group.id) as childGroup}
                <svelte:self group={childGroup} groups={groups}/>
            {/each}
        </div>
    {/if}
</div>
