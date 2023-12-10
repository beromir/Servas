<script context="module">
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import LinkList from "@/Components/LinkList/LinkList.svelte";

    export let links = [];
    export let searchString = '';
    export let filteredTags = [];
    export let showUntaggedOnly = false;

    $title = 'Links';
</script>

<Main title="Home">
    <LinkList {links} {searchString} {filteredTags} {showUntaggedOnly}/>
</Main>
