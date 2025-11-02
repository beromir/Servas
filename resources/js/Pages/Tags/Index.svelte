<script module>
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import Badge from "@/Components/Badge.svelte";
    import NewTag from "@/Pages/Tags/Partials/NewTag.svelte";
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";
    import EmptyStateWithAction from "@/Components/EmptyStates/EmptyStateWithAction.svelte";
    import Tag from "@/Heroicons/Outline/Tag.svelte";

    $title = 'Tags';

    let {tags = []} = $props();

    let modeIsEdit = $state(false);
    let newTagComponent = $state();
</script>

<Main title="Tags">
    <div class="px-4 sm:px-0">
        <NewTag bind:modeIsEdit bind:this={newTagComponent}/>

        <div class="pt-6 space-y-4 -mt-4">
            {#each tags as tag (tag.id)}
                <Badge clicked={() => newTagComponent.initEditMode(tag)} class="mr-2 first:mt-4 last:mr-0"
                       title={tag.name}/>
            {:else}
                <EmptyStateWithAction on:clicked={() => newTagComponent.focus()} title="Add a new tag"
                                      class="mt-2 sm:mt-4">
                    {#snippet icon()}
                        <Tag/>
                    {/snippet}
                </EmptyStateWithAction>
            {/each}
        </div>
    </div>
</Main>
