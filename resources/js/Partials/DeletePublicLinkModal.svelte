<script>
    import Modal from "@/Components/Modals/Modal.svelte";
    import Button from "@/Components/Buttons/Button.svelte";
    import {router} from '@inertiajs/svelte';
    import {route} from '@/utils';

    let showModal = false;
    let publicLinkId = null;
    let publicLinkTitle = '';

    function prepareDeletePublicLink(e) {
        publicLinkId = e.detail.id;
        publicLinkTitle = e.detail.title;

        showModal = true;
    }

    function deletePublicLink() {
        router.delete(route('publicLinks.destroy', publicLinkId), {
            only: ['publicLink', 'publicLinks'],
            preserveScroll: true,
            onSuccess: () => {
                showModal = false;
            },
        });
    }
</script>

<svelte:window on:deletePublicLink={prepareDeletePublicLink}/>

<Modal title="Delete Public link" bind:showModal>
    <p class="text-sm text-gray-600 dark:text-gray-300">
        Are you sure you want to delete this public link and stop the sharing of the group?
    </p>
    <div class="mt-1 text-sm text-gray-700 font-medium dark:text-gray-50">
        {publicLinkTitle}
    </div>

    <svelte:fragment slot="footer">
        <Button on:clicked={() => showModal = false} title="Cancel" color="white"
                class="hidden focus:ring-offset-gray-50 sm:block"/>
        <Button on:clicked={deletePublicLink} title="Delete" color="red" focusButton={true}
                class="focus:ring-offset-gray-50"/>
    </svelte:fragment>
</Modal>
