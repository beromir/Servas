<script>
    import Modal from "@/Components/Modals/Modal.svelte";
    import Button from "@/Components/Buttons/Button.svelte";
    import {router} from '@inertiajs/svelte';
    import {dispatchCustomEvent, route} from '@/utils';
    import {refreshTags} from "@/stores";

    let tag = $state({});
    let modal = $state();

    function prepareDeleteTag(e) {
        tag = e.detail;

        modal.show();
    }

    function deleteTag() {
        router.delete(route('tags.destroy', tag.id), {
            preserveScroll: true,
            onSuccess: () => {
                // End the edit mode
                dispatchCustomEvent('tagDeletionWasSuccessful');
                modal.close()
                $refreshTags = true;
            },
        });
    }
</script>

<svelte:window ondeleteTag={prepareDeleteTag}/>

<Modal bind:this={modal} title="Delete Tag">
    <p class="text-sm text-gray-600 dark:text-gray-300">
        Are you sure you want to delete this tag?
    </p>
    <div class="mt-1 text-sm text-gray-700 font-medium dark:text-gray-50">
        {tag.name}
    </div>

    {#snippet footer()}
        <Button clicked={deleteTag} title="Delete" color="red"
                class="sm:ml-3"/>
        <Button clicked={() => modal.close()} title="Cancel" color="white"
                class="hidden mt-3 sm:inline-flex sm:mt-0"/>
    {/snippet}
</Modal>
