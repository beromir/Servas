<script context="module">
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import {dispatchCustomEvent} from "@/utils";
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import LinkList from "@/Components/LinkList/LinkList.svelte";
    import Button from "@/Components/Buttons/Button.svelte";
    import {linkFilter} from "@/stores.js";

    export let links = [];

    $title = 'Links';
</script>

<Main title="Home">
    <LinkList {links}/>
</Main>
