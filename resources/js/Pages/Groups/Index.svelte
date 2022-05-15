<script context="module">
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import NewGroup from "@/Pages/Groups/Partials/NewGroup.svelte";
    import GroupsGrid from "@/Pages/Groups/Partials/GroupsGrid.svelte";
    import GroupSelectMenu from "@/Partials/GroupSelectMenu.svelte";

    $title = 'Groups';

    export let groups = [];
</script>

<Main>
    <NewGroup slot="toolbar"/>
    <GroupSelectMenu/>

    <div class="py-6 px-4 sm:px-0">
        <GroupsGrid {groups}/>
    </div>
</Main>
