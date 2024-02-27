<script context="module">
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import LinkList from "@/Components/LinkList/LinkList.svelte";

    export let links = [];
    export let searchString = '';

    $title = 'Inbox';
</script>

<Main title="Inbox">
    <LinkList {links} {searchString} showTagFilter={false}/>
</Main>
