<script>
    import {Link} from "@inertiajs/svelte";
    import {route} from "@/utils/index.js";

    export let group = null;
    export let groups = [];
</script>

<div>
    <Link href={route('groups.show', group.id)} class="flex justify-between items-center w-full text-white font-medium">
        {group.title}

        {#if group.linksCount}
            <span class="text-sm text-gray-200 font-medium">
                {group.linksCount}
            </span>
        {/if}
    </Link>

    {#if group.childGroupsCount > 0}
        <div class="flex flex-col gap-y-2 mt-2 ml-4">
            {#each groups.filter((singleGroup) => singleGroup.parentGroupId === group.id) as childGroup}
                <svelte:self group={childGroup} groups={groups}/>
            {/each}
        </div>
    {/if}
</div>
