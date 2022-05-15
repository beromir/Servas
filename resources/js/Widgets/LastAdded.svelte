<script>
    import Widget from "@/Components/Widgets/Widget.svelte";
    import ListContainer from "@/Components/LinkList/ListContainer.svelte";

    export let lastAdded = {};
</script>

<Widget title="Last added" fixedHeight={true}>
    <ListContainer links={lastAdded.links} modalTitle="Last added" class="rounded-lg"/>
</Widget>
