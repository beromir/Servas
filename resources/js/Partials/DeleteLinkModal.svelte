<script>
    import Modal from "@/Components/Modals/Modal.svelte";
    import Button from "@/Components/Buttons/Button.svelte";
    import {router} from '@inertiajs/svelte';
    import {route} from '@/utils';
    import {refreshLinks} from "@/stores";

    let showModal = $state(false);
    let link = $state(null);

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

<svelte:window ondeleteLink={prepareDeleteLink}/>

<Modal title="Delete Link" bind:showModal>
    <p class="text-sm text-gray-600 dark:text-gray-300">
        Are you sure you want to delete this link?
    </p>
    <div class="mt-1 text-sm text-gray-700 font-medium dark:text-gray-50">
        {link.title}
    </div>

    {#snippet footer()}
    
            <Button on:clicked={() => showModal = false} title="Cancel" color="white"
                    class="hidden focus:ring-offset-gray-50 sm:block"/>
            <Button on:clicked={deleteLink} title="Delete" color="red" focusButton={true}
                    class="focus:ring-offset-gray-50"/>
        
    {/snippet}
</Modal>
