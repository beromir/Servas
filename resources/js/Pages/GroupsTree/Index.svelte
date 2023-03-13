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
        <div class="sticky top-0 mr-3">
            <ul class="w-64 bg-white rounded py-2">
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

        <LinkList {links} />
    </div>
</div>
