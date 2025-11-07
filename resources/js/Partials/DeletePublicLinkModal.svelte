<script>
    import Modal from "@/Components/Modals/Modal.svelte";
    import Button from "@/Components/Buttons/Button.svelte";
    import {router} from '@inertiajs/svelte';
    import {route} from '@/utils';

    let modal = $state();
    let publicLinkId = null;
    let publicLinkTitle = $state('');

    function prepareDeletePublicLink(e) {
        publicLinkId = e.detail.id;
        publicLinkTitle = e.detail.title;

        modal.show();
    }

    function deletePublicLink() {
        router.delete(route('publicLinks.destroy', publicLinkId), {
            only: ['publicLink', 'publicLinks'],
            preserveScroll: true,
            onSuccess: () => {
                modal.close();
            },
        });
    }
</script>

<svelte:window ondeletePublicLink={prepareDeletePublicLink}/>

<Modal bind:this={modal} title="Delete Public link">
    <p class="text-sm text-gray-600 dark:text-gray-300">
        Are you sure you want to delete this public link and stop the sharing of the group?
    </p>
    <div class="mt-1 text-sm text-gray-700 font-medium dark:text-gray-50">
        {publicLinkTitle}
    </div>

    {#snippet footer()}
        <Button clicked={() => modal.close()} title="Cancel" color="white" class="hidden sm:block"/>
        <Button clicked={deletePublicLink} title="Delete" color="red"/>
    {/snippet}
</Modal>
