<script>
    import Modal from "@/Components/Modals/Modal.svelte";
    import Button from "@/Components/Buttons/Button.svelte";
    import {router} from '@inertiajs/svelte';
    import {route} from '@/utils';
    import {refreshLinks} from "@/stores";

    let showModal = false;
    let link = null;

    function prepareDeleteLink(e) {
        link = e.detail;

        showModal = true;
    }

    function deleteLink() {
        router.delete(route('links.destroy', link.id), {
            preserveScroll: true,
            onSuccess: () => {
                showModal = false;
                $refreshLinks = true;
            },
        });
    }
</script>

<svelte:window on:deleteLink={prepareDeleteLink}/>

<Modal title="Delete Link" bind:showModal>
    <p class="text-sm text-gray-500">
        Are you sure you want to delete this link?
    </p>
    <div class="mt-1 text-sm text-gray-700 font-medium">
        {link.title}
    </div>

    <svelte:fragment slot="footer">
        <Button on:clicked={deleteLink} title="Delete" color="red" focusButton={true}
                class="focus:ring-offset-gray-50 sm:ml-3"/>
        <Button on:clicked={() => showModal = false} title="Cancel" color="white"
                class="hidden mt-3 focus:ring-offset-gray-50 sm:inline-flex sm:mt-0"/>
    </svelte:fragment>
</Modal>
