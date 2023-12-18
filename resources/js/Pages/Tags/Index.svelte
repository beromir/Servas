<script context="module">
    import AppLayout, {title} from "@/Layouts/AppLayout/AppLayout.svelte";

    export const layout = AppLayout;
</script>

<script>
    import Badge from "@/Components/Badge.svelte";
    import NewTag from "@/Pages/Tags/Partials/NewTag.svelte";
    import Main from "@/Layouts/AppLayout/Partials/Main.svelte";

    $title = 'Tags';

    export let tags = [];

    let modeIsEdit;
    let newTagComponent;
</script>

<Main title="Tags">
    <div class="px-4 sm:px-0">
        <NewTag bind:modeIsEdit bind:this={newTagComponent}/>

        <div class="pt-6 space-y-4 -mt-4">
            {#each tags as tag (tag.id)}
                <Badge on:clicked={() => newTagComponent.initEditMode(tag)} class="mr-2 first:mt-4 last:mr-0"
                       title={tag.name}/>
            {:else}
                <div class="col-span-2 container mx-auto">
                    <button on:click={() => newTagComponent.focus()} type="button"
                            class="relative block w-full border-2 border-gray-300 border-dashed rounded-lg p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 focus:ring-offset-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span class="mt-2 block text-sm font-medium text-gray-900">
                    Add a new tag
                </span>
                    </button>
                </div>
            {/each}
        </div>
    </div>
</Main>
