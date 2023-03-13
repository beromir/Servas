<script context="module">
    import AppLayout, { title } from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import TreeItem from "./Partials/TreeItem.svelte";
    import LinkList from "./Partials/LinkList.svelte";

    $title = "Groups (Tree View)";

    export let current_group_id = null;
    export let groups = [];

    export let links = [];

    export function handleLinks({ detail }) {
        links = detail.links;
    }
</script>

<div class="min-h-screen max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex items-start">
        <div class="shrink-0 sticky top-3 mr-3">
            <ul class="w-96 bg-white rounded py-2">
                {#each groups as group (group.id)}
                    <li>
                        <TreeItem
                            {current_group_id}
                            {group}
                            on:link-retieved={handleLinks}
                        />
                    </li>
                {/each}
            </ul>
        </div>

        {#if links.length > 0}
            <div class="grow bg-white shadow overflow-hidden sm:rounded-md">
                <LinkList {links} />
            </div>
        {:else}
            <div class="col-span-2 container mx-auto">
                <div
                    class="relative block w-full border-2 border-gray-300 border-dashed rounded-lg p-12 text-center"
                >
                    <span class="mt-2 block text-sm font-medium text-gray-900">
                        Select group to view links
                    </span>
                </div>
            </div>
        {/if}
    </div>
</div>
