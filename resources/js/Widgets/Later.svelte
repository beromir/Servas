<script>
    import Widget from "@/Components/Widgets/Widget.svelte";
    import ListContainer from "@/Components/LinkList/ListContainer.svelte";

    export let later = {};
</script>

<Widget title="Later" fixedHeight={true}>
    <ListContainer links={later.links} modalTitle="Later"/>
</Widget>
